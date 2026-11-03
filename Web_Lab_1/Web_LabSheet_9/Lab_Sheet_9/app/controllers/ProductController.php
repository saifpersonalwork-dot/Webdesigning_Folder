<?php
declare(strict_types=1);

final class ProductController {
    private Product $products;
    public function __construct(private PDO $db, private array $config) { $this->products=new Product($db); }

    public function index(): void {
        require_login();
        $page=max(1,(int)($_GET['page'] ?? 1)); $perPage=5; $search=trim($_GET['q'] ?? '');
        $category=trim($_GET['category'] ?? '');
        $min=($_GET['min'] ?? '')!=='' ? filter_var($_GET['min'],FILTER_VALIDATE_FLOAT) : null;
        $max=($_GET['max'] ?? '')!=='' ? filter_var($_GET['max'],FILTER_VALIDATE_FLOAT) : null;
        $sort=(string)($_GET['sort'] ?? 'id'); $dir=(string)($_GET['dir'] ?? 'DESC');
        $rows=$this->products->all($search,$category,$min===false?null:$min,$max===false?null:$max,$sort,$dir,$perPage,($page-1)*$perPage);
        $pages=max(1,(int)ceil($this->products->count($search)/$perPage));
        require __DIR__.'/../views/products/index.php';
    }

    public function form(): void {
        require_login();
        $id=(int)($_GET['id'] ?? 0); $product=$id?$this->products->find($id):null;
        if ($_SERVER['REQUEST_METHOD']==='POST') {
            verify_csrf();
            $name=trim($_POST['name'] ?? ''); $category=trim($_POST['category'] ?? '');
            $price=filter_var($_POST['price'] ?? '',FILTER_VALIDATE_FLOAT);
            if ($name==='' || $category==='' || $price===false || $price<0) $error='Enter valid product details.';
            else {
                $image=null;
                if (!empty($_FILES['image']['name'])) $image=$this->upload($_FILES['image']);
                $this->products->save(['name'=>$name,'category'=>$category,'price'=>$price,
                    'description'=>trim($_POST['description'] ?? ''),'image'=>$image],$id?:null);
                redirect('products');
            }
        }
        require __DIR__.'/../views/products/form.php';
    }

    private function upload(array $f): ?string {
        if ($f['error']!==UPLOAD_ERR_OK || $f['size']>$this->config['upload_max_bytes']) throw new RuntimeException('Upload failed or file too large.');
        $mime=(new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']);
        $types=['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'];
        if (!isset($types[$mime])) throw new RuntimeException('Only JPG, PNG and WEBP images are allowed.');
        $name=bin2hex(random_bytes(16)).'.'.$types[$mime];
        $dir=__DIR__.'/../../storage/uploads/';
        if (!is_dir($dir)) mkdir($dir,0750,true);
        if (!move_uploaded_file($f['tmp_name'],$dir.$name)) throw new RuntimeException('Could not save uploaded file.');
        return $name;
    }

    public function delete(): void {
        require_login(); verify_csrf();
        $this->products->delete((int)($_POST['id'] ?? 0)); redirect('products');
    }
    public function suggest(): never {
        require_login();
        $q=trim($_GET['q'] ?? '');
        $s=$this->db->prepare('SELECT id,name,price FROM products WHERE name LIKE ? ORDER BY name LIMIT 8');
        $s->execute(['%'.$q.'%']); json_response($s->fetchAll());
    }
}

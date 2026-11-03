<?php
declare(strict_types=1);

final class ApiController {
    public function __construct(private PDO $db) {}
    private function authenticate(): void {
        $header=$_SERVER['HTTP_AUTHORIZATION'] ?? '';
        if (!preg_match('/Bearer\s+(.+)/i',$header,$m)) json_response(['error'=>'Bearer token required'],401);
        $token=trim($m[1]); $users=$this->db->query('SELECT id,api_token_hash FROM users WHERE api_token_hash IS NOT NULL')->fetchAll();
        foreach ($users as $u) if (password_verify($token,$u['api_token_hash'])) { $_SESSION['api_user_id']=$u['id']; return; }
        json_response(['error'=>'Invalid token'],401);
    }
    public function products(): never {
        $this->authenticate(); $method=$_SERVER['REQUEST_METHOD'];
        if ($method==='GET') {
            $id=(int)($_GET['id'] ?? 0);
            if ($id) { $s=$this->db->prepare('SELECT * FROM products WHERE id=?'); $s->execute([$id]); $row=$s->fetch(); json_response($row ?: ['error'=>'Not found'], $row?200:404); }
            json_response($this->db->query('SELECT * FROM products ORDER BY id DESC')->fetchAll());
        }
        $data=json_decode(file_get_contents('php://input'),true) ?: [];
        if ($method==='POST') {
            $s=$this->db->prepare('INSERT INTO products(name,category,price,description) VALUES(?,?,?,?)');
            $s->execute([$data['name']??'',$data['category']??'',$data['price']??0,$data['description']??'']);
            json_response(['id'=>(int)$this->db->lastInsertId(),'message'=>'Created'],201);
        }
        if ($method==='PUT') {
            $s=$this->db->prepare('UPDATE products SET name=?,category=?,price=?,description=? WHERE id=?');
            $s->execute([$data['name']??'',$data['category']??'',$data['price']??0,$data['description']??'',(int)($data['id']??0)]);
            json_response(['updated'=>$s->rowCount()]);
        }
        if ($method==='DELETE') {
            $s=$this->db->prepare('DELETE FROM products WHERE id=?'); $s->execute([(int)($_GET['id']??$data['id']??0)]);
            json_response(['deleted'=>$s->rowCount()]);
        }
        json_response(['error'=>'Method not allowed'],405);
    }
}

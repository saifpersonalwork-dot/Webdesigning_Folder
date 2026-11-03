<?php
declare(strict_types=1);

final class Product {
    public function __construct(private PDO $db) {}
    public function all(string $search = '', string $category = '', ?float $min = null,
                        ?float $max = null, string $sort = 'id', string $direction = 'DESC',
                        int $limit = 10, int $offset = 0): array {
        $allowed = ['id','name','category','price','created_at'];
        if (!in_array($sort, $allowed, true)) $sort = 'id';
        $direction = strtoupper($direction) === 'ASC' ? 'ASC' : 'DESC';
        $sql = 'SELECT * FROM products WHERE name LIKE :search';
        $params = [':search' => '%' . $search . '%'];
        if ($category !== '') { $sql .= ' AND category LIKE :category'; $params[':category'] = '%' . $category . '%'; }
        if ($min !== null) { $sql .= ' AND price >= :min'; $params[':min'] = $min; }
        if ($max !== null) { $sql .= ' AND price <= :max'; $params[':max'] = $max; }
        $sql .= " ORDER BY $sort $direction LIMIT :lim OFFSET :off";
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) $stmt->bindValue($key, $value);
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':off', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function count(string $search = ''): int {
        $s = $this->db->prepare('SELECT COUNT(*) FROM products WHERE name LIKE ?');
        $s->execute(['%' . $search . '%']);
        return (int)$s->fetchColumn();
    }
    public function find(int $id): ?array {
        $s = $this->db->prepare('SELECT * FROM products WHERE id=?');
        $s->execute([$id]); return $s->fetch() ?: null;
    }
    public function save(array $data, ?int $id = null): void {
        if ($id) {
            $s = $this->db->prepare('UPDATE products SET name=?, category=?, price=?, description=?, image=COALESCE(?,image) WHERE id=?');
            $s->execute([$data['name'],$data['category'],$data['price'],$data['description'],$data['image'],$id]);
        } else {
            $s = $this->db->prepare('INSERT INTO products(name,category,price,description,image) VALUES(?,?,?,?,?)');
            $s->execute([$data['name'],$data['category'],$data['price'],$data['description'],$data['image']]);
        }
    }
    public function delete(int $id): void {
        $s = $this->db->prepare('DELETE FROM products WHERE id=?'); $s->execute([$id]);
    }
}

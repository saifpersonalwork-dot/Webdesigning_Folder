<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class Product extends Model
{
    public function all(): array
    {
        return $this->db->query('SELECT id, name, price FROM products ORDER BY id DESC')->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT id, name, price FROM products WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(string $name, float $price): int
    {
        $stmt = $this->db->prepare('INSERT INTO products (name, price) VALUES (?, ?)');
        $stmt->execute([$name, $price]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, string $name, float $price): bool
    {
        $stmt = $this->db->prepare('UPDATE products SET name = ?, price = ? WHERE id = ?');
        return $stmt->execute([$name, $price, $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM products WHERE id = ?');
        return $stmt->execute([$id]);
    }
}

<?php

declare(strict_types=1);

class ProductModel
{
    public function __construct(private mysqli $db) {}

    public function getAll(): array
    {
        $result = $this->db->query('SELECT * FROM products');
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM products WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc() ?: null;
    }
    public function create(string $name, float $price): int
    {
        $stmt = $this->db->prepare('INSERT INTO products (name, price) VALUES(?, ?)');
        $stmt->bind_param('sd', $name, $price);
        $stmt->execute();

        return $stmt->insert_id;
    }
    public function update(int $id, string $name, float $price): int
    {
        $stmt = $this->db->prepare('UPDATE products SET NAME= ?, PRICE = ? WHERE id = ?');
        $stmt->bind_param('sdi', $name, $price, $id);
        $stmt->execute();

        return $stmt->affected_rows;
    }
    public function delete(int $id): int
    {
        $stmt = $this->db->prepare('DELETE FROM products WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();

        return $stmt->affected_rows;
    }
}

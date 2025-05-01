<?php

declare(strict_types=1);

class ProductController
{
    private ProductModel $model;

    public function __construct(mysqli $db)
    {
        $this->model = new ProductModel($db);
    }

    public function index(): void
    {
        Response::json($this->model->getAll(), 200);
    }
    public function show(string $id): void
    {
        if (!ctype_digit($id)) {
            Response::json(['error' => 'Invalid ID'], 400);
        }
        $product = $this->model->getById((int) $id);
        $product
            ? Response::json($product, 200)
            : Response::json(['error' => 'Product not found'], 404);
    }
    public function store(): void
    {
        $data = $this->getJson(['name', 'price']);
        $id = $this->model->create($data['name'], (float)$data['price']);
        Response::json(['id' => $id], 201);
    }
    public function update(string $id): void
    {
        if(!ctype_digit($id)){
            Response::json(['error' => 'Invalid ID'], 400);
        }

        $data = $this->getJson(['name', 'price']);
        $rows = $this->model->update((int)$id, $data['name'], (float)$data['price']);
        Response::json(['updated' => $rows], 200);
    }
    public function destroy(string $id): void
    {
        if (!ctype_digit($id)) {
            Response::json(['error' => 'Invalid ID'], 400);
        }
        $rows = $this->model->delete((int)$id);
        Response::json(['deleted' => $rows], 200);
    }
    public function getJson(array $required): array
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!is_array($data)) {
            Response::json(['error' => 'Malformed Json'], 400);
        }

        foreach ($required as $field) {
            if (!isset($data[$field]) || $data[$field] === '') {
                Response::json(['error' => 'Missiong Filed: ' . $field], 422);
            }
        }

        return $data;
    }
}

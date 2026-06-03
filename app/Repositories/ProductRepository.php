<?php

namespace App\Repositories;

use App\DTO\CreateProductDTO;
use App\Models\Products;

class ProductRepository
{
    public function findId($id)
    {
        return Products::findOrFail($id);
    }

    public function getAll()
    {
        return Products::all();
    }

    public function create(array $data)
    {
        return Products::create($data);
    }

    public function createByDTO(CreateProductDTO $dto): Products
    {
        return Products::create([
            'category_id' => $dto->category_id,
            'name'        => $dto->name,
            'code'        => $dto->code,
            'create_uid'  => $dto->create_uid,
            'update_uid'  => $dto->update_uid,
            'qty'         => $dto->qty,
        ]);
    }

    public function update(Products $products, array $data)
    {
        return $products->update($data);
    }

    public function delete(Products $products)
    {
        return $products->delete();
    }
}

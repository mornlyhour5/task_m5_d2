<?php

namespace App\Repositories;

use App\Models\Products;

class ProductRepository
{
    public function findId($id)
    {
        return Products::findOrFail($id);
    }

    public function create(array $data)
    {
        return Products::create($data);
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

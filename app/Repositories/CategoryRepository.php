<?php

namespace App\Repositories;

use App\Models\Categories;

class CategoryRepository
{

    public function findId($id)
    {
        return Categories::findOrFail($id);
    }

    public function create(array $data)
    {
        return Categories::create($data);
    }

    public function update(Categories $categories, array $data)
    {
        return $categories->update($data);
    }

    public function delete(Categories $categories)
    {
        return $categories->delete();
    }
}

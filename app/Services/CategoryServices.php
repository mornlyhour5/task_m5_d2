<?php

namespace App\Services;

// use App\Models\Categories;

use App\Exceptions\NotFoundExcept;
use App\Repositories\CategoryRepository;

class CategoryServices
{

    public function __construct(protected CategoryRepository $categoryrepository)
    {
        $this->categoryrepository = $categoryrepository;
    }

    public function create(array $data){
        $data['name'] = $data['name'];

        return $this->categoryrepository->create($data);
    }

    public function getById($id){
        $category = $this->categoryrepository->findId($id);

        if(!$category)
        {
            throw new NotFoundExcept("Category not found");
        }

        return $category;
    }

    public function update($id, array $data){
        $category = $this->categoryrepository->findId($id);

        if(!$category)
        {
            throw new NotFoundExcept("Category not found");
        }

        $data['name'] = $data['name'];

        return $this->categoryrepository->update($category, $data);

    }

    public function delete($id){
        // return Categories::FindOrFail($id)->delete();
        $category = $this->categoryrepository->findId($id);

        if(!$category)
        {
            throw new NotFoundExcept("Category not found");
        }

        return $this->categoryrepository->delete($category);

    }
}

<?php

namespace App\Services;

use App\DTO\CreateProductDTO;
use App\Models\Products;
use App\Repositories\ProductRepository;

class ProductServices
{

    public function __construct(protected ProductRepository $productrepository)
    {
        $this->productrepository = $productrepository;
    }

    public function create(array $data)
    {
        $data = [
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'code' => $data['code'],
            'qty' => $data['qty'],
            'create_uid' => $data['userId'],
        ] ;

        return $this->productrepository->create($data);
    }

    public function createByDTO(CreateProductDTO $dto)
    {
        return $this->productrepository->createByDTO($dto);
    }

    public function update($id, array $data)
    {
        $product = $this->productrepository->findId($id);

        $data = [
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'code' => $data['code'],
            'qty' => $data['qty'],
            'update_uid' => $data['update_uid'],
        ];

        return $this->productrepository->update($product, $data);
    }

    public function delete($id)
    {
        $product = $this->productrepository->findId($id);

        return $this->productrepository->delete($product);
    }
}


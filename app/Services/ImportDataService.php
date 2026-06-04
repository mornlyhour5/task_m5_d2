<?php

namespace App\Services;
use App\Models\Products;

class ImportDataService
{
    public function importVendor(array $data): void
{
    foreach ($data as $item) {
        Products::create([
            'category_id' => $item['category_id'],
            'name'        => $item['name'],
            'code'        => $item['code'],
            'create_uid'  => $item['create_uid'],
            'update_uid'  => $item['update_uid'],
            'qty'         => $item['qty'],
        ]);
    }
}
}

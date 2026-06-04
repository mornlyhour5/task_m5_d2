<?php

namespace App\Imports;

// use App\Models\Products;
use App\Services\ImportDataService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ProductImport implements ToCollection
{
    protected ImportDataService $importDataService;

    public function __construct(ImportDataService $importDataService)
    {
        $this->importDataService = $importDataService;
    }

    public function collection(Collection $rows)
{
    $data = [];

    foreach ($rows as $index => $row) {
        if ($index === 0) continue; // skip header

        $data[] = [
            'category_id' => $row[0] ?? 1,
            'name'        => $row[1] ?? null,
            'code'        => $row[2] ?? null,
            'create_uid'  => $row[3] ?? null,
            'update_uid'  => $row[4] ?? null,
            'qty'         => $row[5] ?? 0,
        ];
    }

    $this->importDataService->importVendor($data);
}
}

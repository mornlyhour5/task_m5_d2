<?php

namespace App\Imports;

use App\Services\ImportDataService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class CategoryImport implements ToCollection
{
    public function __construct(private ImportDataService $importDataService)
    {
        $this->importDataService = $importDataService;
    }
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        $data = [];

        foreach ($rows as $index => $row) {
            if ($index === 0) continue;

            $data[] = [
                'name'  => $row[0] ?? 1,
            ];
        }
        $this->importDataService->importCategory($data);
    }
}

<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CategoryExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(private $category) {}
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->category;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Created At',
        ];
    }

    public function map($category): array
    {
        return [
            $category->id,
            $category->name,
            $category->created_at,

        ];
    }
}

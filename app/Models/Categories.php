<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
    ];

    public function product()
    {
        return $this->hasMany(Products::class, 'category_id');
    }
    public function importCategory(array $data): void
    {
        dd($data);
    }
}

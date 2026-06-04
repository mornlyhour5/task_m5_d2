<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $primaryKey = 'id';

    protected $fillable = [
        'category_id',
        'name',
        'code',
        'create_uid',
        'update_uid',
        'qty',
    ];

    public function category()
{
    return $this->belongsTo(Categories::class, 'category_id');
}
public function importVendor(array $data): void
{
    dd($data);
}

}

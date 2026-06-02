<?php

namespace App\DTO;

use Illuminate\Http\Request;
// use Ramsey\Uuid\Type\Integer;

class CreateProductDTO
{
    public function __construct(
        public readonly int $category_id,
        public readonly string $name,
        public readonly string $code,
        public readonly int $create_uid,
        public readonly int $update_uid,
        public readonly int $qty,
    ) {}

    public static function fromRequest(Request $request): self // ✅ fromRequest not formRequest
    {
        return new self(
            category_id: $request->category_id,
            name:        $request->name,
            code:        $request->code,
            create_uid:  $request->create_uid, // ✅ comes from merged request
            update_uid:  $request->update_uid, // ✅ comes from merged request
            qty:         $request->qty,
        );
    }
}

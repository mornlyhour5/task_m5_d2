<?php

namespace App\Enums;

enum OrderStatus:int
{
    case PENDING = 1;
    case PAID = 2;
    case SHIPPED = 3;
    case DELIVERED = 4;
    case CANCELLED = 5;

    public function label(): string
    {
        return match ($this) {
            self::PENDING   => 'pending',
            self::PAID      => 'pain',
            self::SHIPPED   => 'shippen',
            self::DELIVERED => 'delivered',
            self::CANCELLED => 'cancelled'
        };
    }

    public static function options(): array
    {
        return array_map(
            fn($case) => [
                'value' => $case->value,
                'label' => $case->label(),
            ],
            self::cases()
        );
    }

    public static function optionsReturn(): array
    {
        $allowed = [
            self::CANCELLED,
        ];

        return array_map(
            fn(self $case) => [
                'value' => $case->value,
                'label' => $case->label(),
            ],
            $allowed
        );
    }
}

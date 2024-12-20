<?php

namespace App\Enums\Order;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case SHIPPED = 'shipped';
    case DELIVERED = 'delivered';
    case CANCELED = 'canceled';

    /**
     * Check if the status is valid
     * @param mixed $status
     * @return bool
     */
    public static function isValid(string $status): bool
    {
        return in_array($status, self::toArray());
    }

    /**
     * @return array|string[]
     */
    private static function toArray(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}

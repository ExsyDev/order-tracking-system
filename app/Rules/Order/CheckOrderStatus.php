<?php

namespace App\Rules\Order;

use App\Enums\Order\OrderStatus;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckOrderStatus implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!OrderStatus::isValid($value)) {
            $fail("The $attribute is invalid.");
        }
    }
}

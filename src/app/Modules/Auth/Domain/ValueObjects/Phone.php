<?php
namespace App\Modules\Auth\Domain\ValueObjects;

class Phone
{
    public function __construct(public string $value) {}

    public function __toString(): string
    {
        return $this->value;
    }
}

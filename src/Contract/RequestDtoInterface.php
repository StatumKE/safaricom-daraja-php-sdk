<?php

declare(strict_types=1);

namespace Statum\Safaricom\Daraja\Contract;

interface RequestDtoInterface
{
    /**
     * @return array<array-key, mixed>
     */
    public function toArray(): array;
}

<?php

declare(strict_types=1);

namespace Statum\Safaricom\Daraja\Contract;

interface RequestDtoInterface
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;
}

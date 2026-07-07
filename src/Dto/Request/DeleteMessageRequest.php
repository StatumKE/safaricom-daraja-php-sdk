<?php

declare(strict_types=1);

namespace Statum\Safaricom\Daraja\Dto\Request;

use Statum\Safaricom\Daraja\Contract\RequestDtoInterface;

final class DeleteMessageRequest extends AbstractRequestDto implements RequestDtoInterface
{
    public function __construct(
        public readonly int $id,
        public readonly string $vpnGroup,
        public readonly string $username
    ) {
        self::requireNonNegativeInt($this->id, 'id');
        self::requireNonEmptyString($this->vpnGroup, 'vpnGroup');
        self::requireNonEmptyString($this->username, 'username');
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'vpnGroup' => $this->vpnGroup,
            'username' => $this->username,
        ];
    }
}

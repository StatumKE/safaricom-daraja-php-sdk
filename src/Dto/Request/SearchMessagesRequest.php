<?php

declare(strict_types=1);

namespace Statum\Safaricom\Daraja\Dto\Request;

use Statum\Safaricom\Daraja\Contract\RequestDtoInterface;

final class SearchMessagesRequest extends AbstractRequestDto implements RequestDtoInterface
{
    public function __construct(
        public readonly string $searchValue,
        public readonly string $vpnGroup,
        public readonly string $username
    ) {
        self::requireNonEmptyString($this->searchValue, 'searchValue');
        self::requireNonEmptyString($this->vpnGroup, 'vpnGroup');
        self::requireNonEmptyString($this->username, 'username');
    }

    public function toArray(): array
    {
        return [
            'searchValue' => $this->searchValue,
            'vpnGroup' => $this->vpnGroup,
            'username' => $this->username,
        ];
    }
}

<?php

declare(strict_types=1);

namespace Statum\Safaricom\Daraja\Dto\Request;

use Statum\Safaricom\Daraja\Contract\RequestDtoInterface;

/**
 * Typed request DTO for Safaricom Daraja.
 *
 * @property-read string $searchValue
 * @property-read string $vpnGroup
 * @property-read string $username
 */
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

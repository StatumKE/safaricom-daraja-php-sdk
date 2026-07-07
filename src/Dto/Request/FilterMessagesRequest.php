<?php

declare(strict_types=1);

namespace Statum\Safaricom\Daraja\Dto\Request;

use Statum\Safaricom\Daraja\Contract\RequestDtoInterface;

final class FilterMessagesRequest extends AbstractRequestDto implements RequestDtoInterface
{
    public function __construct(
        public readonly string $startDate,
        public readonly string $endDate,
        public readonly string $status,
        public readonly string $vpnGroup,
        public readonly string $username
    ) {
        self::requireNonEmptyString($this->startDate, 'startDate');
        self::requireNonEmptyString($this->endDate, 'endDate');
        self::requireNonEmptyString($this->status, 'status');
        self::requireNonEmptyString($this->vpnGroup, 'vpnGroup');
        self::requireNonEmptyString($this->username, 'username');
    }

    public function toArray(): array
    {
        return [
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'status' => $this->status,
            'vpnGroup' => $this->vpnGroup,
            'username' => $this->username,
        ];
    }
}

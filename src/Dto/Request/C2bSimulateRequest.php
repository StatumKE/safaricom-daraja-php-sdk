<?php

declare(strict_types=1);

namespace Statum\Safaricom\Daraja\Dto\Request;

use Statum\Safaricom\Daraja\Contract\RequestDtoInterface;

final class C2bSimulateRequest extends AbstractRequestDto implements RequestDtoInterface
{
    public function __construct(
        public readonly string $shortCode,
        public readonly string $commandID,
        public readonly int|string $amount,
        public readonly int|string $msisdn,
        public readonly string $billRefNumber
    ) {
        self::requireNonEmptyString($this->shortCode, 'shortCode');
        self::requireNonEmptyString($this->commandID, 'commandID');
        self::requireNonEmptyString($this->billRefNumber, 'billRefNumber');
    }

    public function toArray(): array
    {
        return [
            'ShortCode' => $this->shortCode,
            'CommandID' => $this->commandID,
            'Amount' => $this->amount,
            'Msisdn' => $this->msisdn,
            'BillRefNumber' => $this->billRefNumber,
        ];
    }
}

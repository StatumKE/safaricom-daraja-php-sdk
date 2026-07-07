<?php

declare(strict_types=1);

namespace Statum\Safaricom\Daraja\Dto\Request;

use Statum\Safaricom\Daraja\Contract\RequestDtoInterface;

final class B2cPaymentRequest extends AbstractRequestDto implements RequestDtoInterface
{
    public function __construct(
        public readonly string $initiatorName,
        public readonly string $securityCredential,
        public readonly string $commandID,
        public readonly int|string $amount,
        public readonly int|string $partyA,
        public readonly int|string $partyB,
        public readonly string $remarks,
        public readonly string $queueTimeOutURL,
        public readonly string $resultURL,
        public readonly ?string $occasion = null
    ) {
        self::requireNonEmptyString($this->initiatorName, 'initiatorName');
        self::requireNonEmptyString($this->securityCredential, 'securityCredential');
        self::requireNonEmptyString($this->commandID, 'commandID');
        self::requireNonEmptyString($this->remarks, 'remarks');
        self::requireNonEmptyString($this->queueTimeOutURL, 'queueTimeOutURL');
        self::requireNonEmptyString($this->resultURL, 'resultURL');
    }

    public function toArray(): array
    {
        return self::withoutNulls([
            'InitiatorName' => $this->initiatorName,
            'SecurityCredential' => $this->securityCredential,
            'CommandID' => $this->commandID,
            'Amount' => $this->amount,
            'PartyA' => $this->partyA,
            'PartyB' => $this->partyB,
            'Remarks' => $this->remarks,
            'QueueTimeOutURL' => $this->queueTimeOutURL,
            'ResultURL' => $this->resultURL,
            'Occasion' => $this->occasion,
        ]);
    }
}

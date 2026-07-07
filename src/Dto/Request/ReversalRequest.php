<?php

declare(strict_types=1);

namespace Statum\Safaricom\Daraja\Dto\Request;

use Statum\Safaricom\Daraja\Contract\RequestDtoInterface;

final class ReversalRequest extends AbstractRequestDto implements RequestDtoInterface
{
    public function __construct(
        public readonly string $initiator,
        public readonly string $securityCredential,
        public readonly string $commandID,
        public readonly string $transactionID,
        public readonly int|string $amount,
        public readonly int|string $receiverParty,
        public readonly int|string $recieverIdentifierType,
        public readonly string $resultURL,
        public readonly string $queueTimeOutURL,
        public readonly string $remarks,
        public readonly ?string $occasion = null
    ) {
        self::requireNonEmptyString($this->initiator, 'initiator');
        self::requireNonEmptyString($this->securityCredential, 'securityCredential');
        self::requireNonEmptyString($this->commandID, 'commandID');
        self::requireNonEmptyString($this->transactionID, 'transactionID');
        self::requireNonEmptyString($this->resultURL, 'resultURL');
        self::requireNonEmptyString($this->queueTimeOutURL, 'queueTimeOutURL');
        self::requireNonEmptyString($this->remarks, 'remarks');
    }

    public function toArray(): array
    {
        return self::withoutNulls([
            'Initiator' => $this->initiator,
            'SecurityCredential' => $this->securityCredential,
            'CommandID' => $this->commandID,
            'TransactionID' => $this->transactionID,
            'Amount' => $this->amount,
            'ReceiverParty' => $this->receiverParty,
            'RecieverIdentifierType' => $this->recieverIdentifierType,
            'ResultURL' => $this->resultURL,
            'QueueTimeOutURL' => $this->queueTimeOutURL,
            'Remarks' => $this->remarks,
            'Occasion' => $this->occasion,
        ]);
    }
}

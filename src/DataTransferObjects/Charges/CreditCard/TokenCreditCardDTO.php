<?php

namespace TioJobs\AsaasPhp\DataTransferObjects\Charges\CreditCard;

class TokenCreditCardDTO
{
    public function __construct(
        public readonly string $customerId,
        public readonly CreditCardDTO $creditCardDTO,
        public readonly CreditCardHolderInfoDTO $creditCardHolderInfoDTO,
        public string $remoteIP = '',
    ) {
        if (blank($this->remoteIP)) {
            $this->remoteIP = get_remote_ip();
        }
    }
}

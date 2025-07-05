<?php

namespace TioJobs\AsaasPhp\Endpoints\Charges\CreditCard;

use TioJobs\AsaasPhp\Contracts\Core\AsaasInterface;
use TioJobs\AsaasPhp\DataTransferObjects\Charges\CreditCard\TokenCreditCardDTO;

class TokenChargeByCreditCard implements AsaasInterface
{
    public function __construct(
        protected TokenCreditCardDTO $tokenCreditCardDTO,
    ) {}

    public function getPath(): string
    {
        return 'creditCard/tokenizeCreditCard';
    }

    /** @return array<string, array<string, string|null>|float|int|string> */
    public function getData(): array
    {
        return [
            'customer' => $this->tokenCreditCardDTO->customerId,
            'creditCard' => [
                'holderName' => $this->tokenCreditCardDTO->creditCardDTO->holderName,
                'number' => sanitize($this->tokenCreditCardDTO->creditCardDTO->number),
                'expiryMonth' => $this->tokenCreditCardDTO->creditCardDTO->expiryMonth,
                'expiryYear' => $this->tokenCreditCardDTO->creditCardDTO->expiryYear,
                'ccv' => $this->tokenCreditCardDTO->creditCardDTO->ccv,
            ],
            'creditCardHolderInfo' => [
                'name' => $this->tokenCreditCardDTO->creditCardHolderInfoDTO->name,
                'email' => $this->tokenCreditCardDTO->creditCardHolderInfoDTO->email,
                'cpfCnpj' => sanitize($this->tokenCreditCardDTO->creditCardHolderInfoDTO->document),
                'postalCode' => $this->tokenCreditCardDTO->creditCardHolderInfoDTO->postalCode,
                'addressNumber' => $this->tokenCreditCardDTO->creditCardHolderInfoDTO->addressNumber,
                'addressComplement' => $this->tokenCreditCardDTO->creditCardHolderInfoDTO->addressComplement,
                'phone' => sanitize($this->tokenCreditCardDTO->creditCardHolderInfoDTO->phone),
                'mobilePhone' => sanitize($this->tokenCreditCardDTO->creditCardHolderInfoDTO->mobilePhone),
            ],
            'remoteIP' => $this->tokenCreditCardDTO->remoteIP,
        ];
    }
}

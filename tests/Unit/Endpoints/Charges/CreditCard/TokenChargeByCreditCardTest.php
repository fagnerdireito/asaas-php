<?php

use TioJobs\AsaasPhp\DataTransferObjects\Charges\CreditCard\TokenCreditCardDTO;
use TioJobs\AsaasPhp\DataTransferObjects\Charges\CreditCard\CreditCardDTO;
use TioJobs\AsaasPhp\DataTransferObjects\Charges\CreditCard\CreditCardHolderInfoDTO;
use TioJobs\AsaasPhp\Endpoints\Charges\CreditCard\TokenChargeByCreditCard;

test('TokenChargeByCreditCard endpoint should return correct path', function () {
    $creditCardDTO = new CreditCardDTO(
        holderName: 'João Silva',
        number: '5162306219378829',
        expiryMonth: '05',
        expiryYear: '2024',
        ccv: '318',
    );

    $creditCardHolderInfoDTO = new CreditCardHolderInfoDTO(
        name: 'João Silva',
        email: 'joao@test.com',
        document: '24971563792',
        postalCode: '89223-005',
        addressNumber: '277',
        phone: '4738010919',
        mobilePhone: '47998781877',
        addressComplement: 'Apt 101',
    );

    $tokenCreditCardDTO = new TokenCreditCardDTO(
        customerId: 'cus_123456',
        creditCardDTO: $creditCardDTO,
        creditCardHolderInfoDTO: $creditCardHolderInfoDTO,
    );

    $endpoint = new TokenChargeByCreditCard($tokenCreditCardDTO);

    expect($endpoint->getPath())->toBe('creditCard/tokenizeCreditCard');
});

test('TokenChargeByCreditCard endpoint should return correct data structure', function () {
    $creditCardDTO = new CreditCardDTO(
        holderName: 'João Silva',
        number: '5162306219378829',
        expiryMonth: '05',
        expiryYear: '2024',
        ccv: '318',
    );

    $creditCardHolderInfoDTO = new CreditCardHolderInfoDTO(
        name: 'João Silva',
        email: 'joao@test.com',
        document: '24971563792',
        postalCode: '89223-005',
        addressNumber: '277',
        phone: '4738010919',
        mobilePhone: '47998781877',
        addressComplement: 'Apt 101',
    );

    $tokenCreditCardDTO = new TokenCreditCardDTO(
        customerId: 'cus_123456',
        creditCardDTO: $creditCardDTO,
        creditCardHolderInfoDTO: $creditCardHolderInfoDTO,
    );

    $endpoint = new TokenChargeByCreditCard($tokenCreditCardDTO);
    $data = $endpoint->getData();

    expect($data)
        ->toHaveKey('customer')
        ->toHaveKey('creditCard')
        ->toHaveKey('creditCardHolderInfo')
        ->toHaveKey('remoteIP')
        ->and($data['customer'])
        ->toBe('cus_123456')
        ->and($data['creditCard'])
        ->toHaveKey('holderName')
        ->toHaveKey('number')
        ->toHaveKey('expiryMonth')
        ->toHaveKey('expiryYear')
        ->toHaveKey('ccv')
        ->and($data['creditCardHolderInfo'])
        ->toHaveKey('name')
        ->toHaveKey('email')
        ->toHaveKey('cpfCnpj')
        ->toHaveKey('postalCode')
        ->toHaveKey('addressNumber')
        ->toHaveKey('phone')
        ->toHaveKey('mobilePhone');
});

test('TokenChargeByCreditCard endpoint should sanitize sensitive data', function () {
    $creditCardDTO = new CreditCardDTO(
        holderName: 'João Silva',
        number: '5162-3062-1937-8829',
        expiryMonth: '05',
        expiryYear: '2024',
        ccv: '318',
    );

    $creditCardHolderInfoDTO = new CreditCardHolderInfoDTO(
        name: 'João Silva',
        email: 'joao@test.com',
        document: '249.715.637-92',
        postalCode: '89223-005',
        addressNumber: '277',
        phone: '(47) 3801-0919',
        mobilePhone: '(47) 99878-1877',
        addressComplement: 'Apt 101',
    );

    $tokenCreditCardDTO = new TokenCreditCardDTO(
        customerId: 'cus_123456',
        creditCardDTO: $creditCardDTO,
        creditCardHolderInfoDTO: $creditCardHolderInfoDTO,
    );

    $endpoint = new TokenChargeByCreditCard($tokenCreditCardDTO);
    $data = $endpoint->getData();

    expect($data['creditCard']['number'])
        ->toBe('5162306219378829')
        ->and($data['creditCardHolderInfo']['cpfCnpj'])
        ->toBe('24971563792')
        ->and($data['creditCardHolderInfo']['phone'])
        ->toBe('4738010919')
        ->and($data['creditCardHolderInfo']['mobilePhone'])
        ->toBe('47998781877');
});
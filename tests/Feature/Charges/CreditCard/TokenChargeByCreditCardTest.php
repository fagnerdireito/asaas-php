<?php

use TioJobs\AsaasPhp\DataTransferObjects\Charges\CreditCard\TokenCreditCardDTO;
use TioJobs\AsaasPhp\DataTransferObjects\Charges\CreditCard\CreditCardDTO;
use TioJobs\AsaasPhp\DataTransferObjects\Charges\CreditCard\CreditCardHolderInfoDTO;
use TioJobs\AsaasPhp\Facades\AsaasPhp;

test('check if customer can tokenize credit card', function () {
    // Create a new customer
    $customer = generateCustomer();

    $responseCustomer = AsaasPhp::withKey('$aact_hmlg_000MzkwODA2MWY2OGM3MWRlMDU2NWM3MzJlNzZmNGZhZGY6OjE1NjcyZGZjLWFkZWEtNDA5My04YzU0LTg5OWNlMmFhNzU0Mjo6JGFhY2hfNzBjOTkxZmEtMGRmYi00Y2EwLWFkNjUtZmJmOWRiZWYyYzFk', 'sandbox')->customer()->create(...$customer);

    // Create credit card data
    $creditCardDTO = new CreditCardDTO(
        holderName: 'João Silva',
        number: '5162306219378829',
        expiryMonth: '05',
        expiryYear: '2028',
        ccv: '318',
    );

    // Create credit card holder info
    $creditCardHolderInfoDTO = new CreditCardHolderInfoDTO(
        name: 'João Silva',
        email: 'joao@test.com',
        document: '69534101249',
        postalCode: '89223-005',
        addressNumber: '277',
        phone: '4738010919',
        mobilePhone: '47998781877',
        addressComplement: 'Apt 101',
    );

    // Create token credit card DTO
    $data = new TokenCreditCardDTO(
        customerId: $responseCustomer['id'],
        creditCardDTO: $creditCardDTO,
        creditCardHolderInfoDTO: $creditCardHolderInfoDTO,
    );

    $response = AsaasPhp::withKey('$aact_hmlg_000MzkwODA2MWY2OGM3MWRlMDU2NWM3MzJlNzZmNGZhZGY6OjE1NjcyZGZjLWFkZWEtNDA5My04YzU0LTg5OWNlMmFhNzU0Mjo6JGFhY2hfNzBjOTkxZmEtMGRmYi00Y2EwLWFkNjUtZmJmOWRiZWYyYzFk', 'sandbox')->charge()->tokenCreditCard($data);

    expect(json_encode($response))
        ->json()
        ->creditCardToken->toBeString()
        ->and($response)
        ->toHaveKey('creditCardToken')
        ->toHaveKey('creditCardNumber')
        ->toHaveKey('creditCardBrand')
        ->and($response['creditCardToken'])
        ->toBeString()
        ->toMatch('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/'); // UUID format
});
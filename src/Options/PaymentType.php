<?php

namespace Mnabialek\LaravelDotpay\Options;

class PaymentType
{
    const PAYMENT = 'payment';
    const PAYMENT_MULTIMERCHANT_CHILD = 'payment_multimerchant_child';
    const PAYMENT_MULTIMERCHANT_PARENT = 'payment_multimerchant_parent';
    const REFUND = 'refund';
    const PAYOUT = 'payout';
    const RELEASE_ROLLBACK = 'release_rollback';
    const UNIDENTIFIED_PAYMENT = 'unidentified_payment';
    const COMPLAINT = 'complaint';
}

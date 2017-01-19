<?php

namespace Mnabialek\LaravelDotpay\Options;

class PaymentStatus
{
    const NEW = 'new';
    const PROCESSING = 'processing';
    const COMPLETED = 'completed';
    const REJECTED = 'rejected';
    const PROCESSING_REALIZATION_WAITING = 'processing_realization_waiting';
    const PROCESSING_REALIZATION = 'processing_realization';
}

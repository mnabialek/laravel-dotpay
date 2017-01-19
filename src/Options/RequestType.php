<?php

namespace Mnabialek\LaravelDotpay\Options;

class RequestType
{
    const BACK_BUTTON = 0;
    const CALLBACK_URL = 1;
    const NONE = 2;
    const BACK_BUTTON_AND_CALLBACK_URL = 3;
    const DIRECT = 4;
}

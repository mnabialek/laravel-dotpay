<?php

namespace Mnabialek\LaravelDotpay;

use Illuminate\Contracts\Foundation\Application;
use Mnabialek\LaravelDotpay\Options\Currency;
use Mnabialek\LaravelDotpay\Options\PaymentStatus;
use Mnabialek\LaravelDotpay\Options\PaymentType;

class Response
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * Configuration options.
     *
     * @var array
     */
    protected $config;

    /**
     * Response constructor.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->config = $app['config']->get('dotpay');
    }

    /**
     * Verify whether incoming request data come from Dotpay.
     *
     * @return bool
     */
    public function canBeTrusted()
    {
        return $this->isValidOrigin() && $this->validSignature();
    }

    /**
     * Verify whether IP of sender is one of Dotpay ips.
     *
     * @return bool
     */
    public function isValidOrigin()
    {
        return in_array($this->app['request']->ip(), $this->config['allowed_ip']);
    }

    /**
     * Verify whether signature for response is valid.
     *
     * @return bool
     */
    public function validSignature()
    {
        return $this->app->make(Signer::class)->verify($this->getData(), $this->field('signature'));
    }

    /**
     * Verify whether paid amount and currency was same as given and if it's for same store.
     *
     * @param float $amount
     * @param string|null $currency
     *
     * @return bool
     */
    public function verify($amount, $currency = null)
    {
        $currency = $currency ?: $this->config['currency'];

        return $this->getStoreId() == $this->config['store_id'] &&
            round($this->getAmount(), 2) == round($amount, 2)
            && $this->getCurrency() == $currency;
    }

    /**
     * Verify whether incoming response is completed payment.
     *
     * @return bool
     */
    public function isCompletedPayment()
    {
        return $this->getType() == PaymentType::PAYMENT &&
            $this->getStatus() == PaymentStatus::COMPLETED;
    }

    /**
     * Get all incoming data.
     *
     * @return array
     */
    public function getData()
    {
        return $this->app['request']->all();
    }

    /**
     * Get IP address.
     *
     * @return string
     */
    public function getIp()
    {
        return $this->app['request']->ip();
    }

    /**
     * Get unique identifier of transaction.
     *
     * @return string
     */
    public function getUniqueIdentifier()
    {
        return $this->field('control');
    }

    /**
     * Get status of transaction.
     *
     * @see PaymentStatus For possible values
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->field('operation_status');
    }

    /**
     * Get type of transaction.
     *
     * @see PaymentType For possible values
     *
     * @return string
     */
    public function getType()
    {
        return $this->field('operation_type');
    }

    /**
     * Get amount of transaction.
     *
     * @return float
     */
    public function getAmount()
    {
        return (float) $this->field('operation_amount');
    }

    /**
     * Get currency of transaction.
     *
     * @see Currency For possible values
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->field('operation_currency');
    }

    /**
     * Get store id.
     *
     * @return string
     */
    public function getStoreId()
    {
        return $this->field('id');
    }

    /**
     * Get value from input with given name.
     *
     * @param string $name
     *
     * @return string
     */
    protected function field($name)
    {
        return $this->app['request']->input($name);
    }
}

<?php

namespace Mnabialek\LaravelDotpay;

use Illuminate\Contracts\Foundation\Application;

class Request
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @var array
     */
    protected $config;

    /**
     * Url for production environment.
     */
    const PRODUCTION_URL = 'https://ssl.dotpay.pl/t2/';

    /**
     * Url for develop environment.
     */
    const DEVELOP_URL = 'https://ssl.dotpay.pl/test_payment/';

    /**
     * Request constructor.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->config = $app['config']->get('dotpay');

        return $this;
    }

    /**
     * Set amount and currency.
     *
     * @param float $amount
     * @param string|null $currency
     *
     * @return $this
     */
    public function setAmount($amount, $currency = null)
    {
        return $this->setParameter('amount', $amount)
            ->setParameter('currency', $currency ?: $this->config['currency']);
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        return $this->setParameter('description', $description);
    }

    /**
     * Set language for interface.
     *
     * @param string $lang
     *
     * @return $this
     */
    public function setLang($lang)
    {
        return $this->setParameter('lang', $lang);
    }

    /**
     * Set customer email.
     *
     * @param string $email
     *
     * @return $this
     */
    public function setCustomerEmail($email)
    {
        return $this->setParameter('email', $email);
    }

    /**
     * Set return url.
     *
     * @param string $returnUrl
     *
     * @return $this
     */
    public function setReturnUrl($returnUrl)
    {
        return $this->setParameter('URL', $returnUrl);
    }

    /**
     * Set callback url.
     *
     * @param string $callbackUrl
     *
     * @return $this
     */
    public function setCallbackUrl($callbackUrl)
    {
        return $this->setParameter('URLC', $callbackUrl);
    }

    /**
     * Set unique identifier for transaction.
     *
     * @param string $uniqueId
     *
     * @return $this
     */
    public function setUniqueIdentifier($uniqueId)
    {
        return $this->setParameter('control', $uniqueId);
    }

    /**
     * Set seller's email.
     *
     * @param string $sellerEmail
     *
     * @return Request
     */
    public function setSellerEmail($sellerEmail)
    {
        return $this->setParameter('p_email', $sellerEmail);
    }

    /**
     * Set seller's display name.
     *
     * @param string $sellerName
     *
     * @return Request
     */
    public function setSellerName($sellerName)
    {
        return $this->setParameter('p_info', $sellerName);
    }

    /**
     * Set return button text.
     *
     * @param string $buttonText
     *
     * @return Request
     */
    public function setReturnButtonText($buttonText)
    {
        return $this->setParameter('buttontext', $buttonText);
    }

    /**
     * Set parameter.
     *
     * @param string $name
     * @param string $value
     *
     * @return $this
     */
    protected function setParameter($name, $value)
    {
        $this->parameters[$name] = $value;

        return $this;
    }

    /**
     * Verify whether test environment should be used.
     *
     * @return bool
     */
    protected function isTest()
    {
        return (bool) $this->config['test'];
    }

    /**
     * Get basic url (for POST or GET request).
     *
     * @return string
     */
    public function basicUrl()
    {
        return $this->isTest() ? self::DEVELOP_URL : self::PRODUCTION_URL;
    }

    /**
     * Get full url with parameters (for GET request).
     *
     * @param array $parameters
     *
     * @return string
     */
    public function url(array $parameters = [])
    {
        $parameters = empty($parameters) ? $this->parameters() : $parameters;

        return $this->basicUrl() . '?' . http_build_query($parameters);
    }

    /**
     * Redirect to payment gateway url.
     *
     * @param array $parameters
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirect(array $parameters = [])
    {
        return $this->app['redirect']->away($this->url($parameters));
    }

    /**
     * Get parameters.
     *
     * @return array
     * @throws \Mnabialek\LaravelDotpay\Exceptions\MissingParameter
     */
    public function parameters()
    {
        // set lang if it's not set yet
        $this->setLang(array_get($this->parameters, 'lang', $this->config['lang']));

        // set api version
        $this->setParameter('api_version', $this->isTest() ? 'dev' : $this->config['api_version']);

        // set shop id and type
        $this->setParameter('id', $this->config['store_id']);
        $this->setParameter('type', $this->config['type']);

        // verify all parameters
        $this->app->make(Verifier::class)->check($this->parameters);

        // add checksum
        $this->parameters['chk'] = $this->app->make(Signer::class)->create($this->parameters);

        return $this->parameters;
    }
}

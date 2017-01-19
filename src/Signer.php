<?php

namespace Mnabialek\LaravelDotpay;

class Signer
{
    /**
     * Pin that should be used.
     *
     * @var string
     */
    protected $pin;

    /**
     * Signer constructor.
     *
     * @param $pin
     */
    public function __construct($pin)
    {
        $this->pin = $pin;
    }

    /**
     * Create signature for given parameters.
     *
     * @param array $parameters
     *
     * @return string
     */
    public function create(array $parameters)
    {
        return hash('sha256', implode('', $this->createValues($parameters)));
    }

    /**
     * Verify if signature for given parameters is correct.
     *
     * @param array $parameters
     * @param string $signature
     *
     * @return bool
     */
    public function verify(array $parameters, $signature)
    {
        return $signature === hash('sha256', implode('', $this->verifyValues($parameters)));
    }

    /**
     * Get values for creating new signature.
     *
     * @param array $parameters
     *
     * @return array
     */
    protected function createValues(array $parameters)
    {
        return $this->values($parameters, $this->createFields());
    }

    /**
     * Get values to verify given signature.
     *
     * @param array $parameters
     *
     * @return array
     */
    protected function verifyValues(array $parameters)
    {
        return $this->values($parameters, $this->verifyFields());
    }

    /**
     * Get values to be signed.
     *
     * @param array $parameters
     * @param array $fields
     *
     * @return array
     */
    protected function values(array $parameters, array $fields)
    {
        $values = [$this->pin];

        foreach ($fields as $field) {
            if (array_key_exists($field, $parameters)) {
                $values[] = $parameters[$field];
            }
        }

        return $values;
    }

    /**
     * Get fields that should be used to sign. Order of those elements if important.
     *
     * @return array
     */
    protected function createFields()
    {
        return [
            'api_version',
            'charset',
            'lang',
            'id',
            'amount',
            'currency',
            'description',
            'control',
            'channel',
            'credit_card_brand',
            'ch_lock',
            'channel_groups',
            'onlinetransfer',
            'URL',
            'type',
            'buttontext',
            'URLC',
            'firstname',
            'lastname',
            'email',
            'street',
            'street_n1',
            'street_n2',
            'state',
            'addr3',
            'city',
            'postcode',
            'phone',
            'country',
            'code',
            'p_info',
            'p_email',
            'n_email',
            'expiration_date',
            'deladdr',
            'recipient_account_number',
            'recipient_company',
            'recipient_first_name',
            'recipient_last_name',
            'recipient_address_street',
            'recipient_address_building',
            'recipient_address_apartment',
            'recipient_address_postcode',
            'recipient_address_city',
            'warranty',
            'bylaw',
            'personal_data',
            'credit_card_number',
            'credit_card_expiration_date_year',
            'credit_card_expiration_date_month',
            'credit_card_security_code',
            'credit_card_store',
            'credit_card_store_security_code',
            'credit_card_customer_id',
            'credit_card_id',
            'blik_code',
            'credit_card_registration',
            'recurring_frequency',
            'recurring_interval',
            'recurring_start',
            'recurring_count',
        ];
    }

    /**
     * Get fields that should be used for verification.
     *
     * @return array
     */
    protected function verifyFields()
    {
        return [
            'id',
            'operation_number',
            'operation_type',
            'operation_status',
            'operation_amount',
            'operation_currency',
            'operation_withdrawal_amount',
            'operation_commission_amount',
            'operation_original_amount',
            'operation_original_currency',
            'operation_datetime',
            'operation_related_number',
            'control',
            'description',
            'email',
            'p_info',
            'p_email',
            'credit_card_issuer_identification_number',
            'credit_card_masked_number',
            'credit_card_brand_codename',
            'credit_card_brand_code',
            'credit_card_id',
            'channel',
            'channel_country',
            'geoip_country',
        ];
    }
}

<?php

namespace App\Contracts;

use Authy\AuthyApi;

class Authy
{
    /**
     * Authy instance.
     *
     * @var \Authy\AuthyApi
     */
    protected $authy;

    /**
     * Country code for phone numbers.
     *
     * @var int
     */
    protected $country_code;

    /**
     * Language value for messages sms.
     *
     * @var string
     */
    protected $language;

    /**
     * Create instance class.
     *
     * @param \Authy\AuthyApi $authy
     */
    public function __construct(AuthyApi $authy)
    {
        $this->authy        = $authy;
        $this->country_code = 503;
        $this->language     = 'es';
    }

    /**
     * Send code confirmation via sms or call.
     *
     * @param string $number_phone
     * @param string $type
     */
    public function send($number_phone, $type = 'sms')
    {
        return $this->authy
                    ->phoneVerificationStart(
                        $number_phone,
                        $this->country_code,
                        $type,
                        $this->language
                    );
    }

    /**
     * Verfy code confirmation.
     *
     * @param string $number_phone
     * @param string $code
     */
    public function verify($number_phone, $code)
    {
        return $this->authy
                    ->phoneVerificationCheck(
                        $number_phone,
                        $this->country_code,
                        $code
                    );
    }
}

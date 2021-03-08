<?php

/**
 * Smsotp.php
 *
 * @category Class
 * @package  Ryuamy\RyRajaSMS
 *
 * @author   Ryu Amy <ryuamy.mail@gmail.com>
 */

namespace Ryuamy\RyRajaSMS;

/**
 * Class Smsotp
 *
 * @category Class
 * @package  Ryuamy\RyRajaSMS
 *
 * @author   Ryu Amy <ryuamy.mail@gmail.com>
 */
class Smsotp
{
    /**
     * Sending non bulk SMS OTP
     *
     * @param   string  $baseUrl
     * @param   string  $apikey
     * @param   array   $phoneNumber
     * @param   array   $smsMessage
     * @param   string  $callbackurl
     *
     * @return object
     *
     * @throws \TypeError
     * @throws \ArgumentCountError
     */
    public static function nonBulkSms( string $baseUrl, string $apikey, array $phoneNumber, array $smsMessage, string $callbackurl ) : object
    {        
        return Api::sendRequest( $baseUrl, 'SMS_OTP', $apikey, $phoneNumber, $smsMessage, '', $callbackurl );
    }
    
    /**
     * Sending bulk SMS OTP
     *
     * @param   string  $baseUrl
     * @param   string  $apikey
     * @param   array   $phoneNumber
     * @param   array   $smsMessage
     * @param   string  $callbackurl
     *
     * @return object
     *
     * @throws \TypeError
     * @throws \ArgumentCountError
     */
    public static function bulkSms( string $baseUrl, string $apikey, array $phoneNumber, array $smsMessage, string $callbackurl ) : object
    {        
        return Api::sendRequest( $baseUrl, 'BULK_SMS_OTP', $apikey, $phoneNumber, $smsMessage, '', $callbackurl );
    }
}
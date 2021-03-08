<?php

/**
 * Smsmasking.php
 *
 * @category Class
 * @package  Ryuamy\RyRajaSMS
 *
 * @author   Ryu Amy <ryuamy.mail@gmail.com>
 */

namespace Ryuamy\RyRajaSMS;

/**
 * Class Smsmasking
 *
 * @category Class
 * @package  Ryuamy\RyRajaSMS
 *
 * @author   Ryu Amy <ryuamy.mail@gmail.com>
 */
class Smsmasking
{
    /**
     * Sending non bulk Masking OTP
     *
     * @param   string  $baseUrl
     * @param   string  $apikey
     * @param   array   $phoneNumber
     * @param   array   $smsMessage
     * @param   string  $apiUsername
     *
     * @return object
     *
     * @throws \TypeError
     * @throws \ArgumentCountError
     */
    public static function nonBulkSmsMasking( string $baseUrl, string $apikey, array $phoneNumber, array $smsMessage, string $apiUsername ) : object
    {
        return Api::sendRequest( $baseUrl, 'SMS_MASKING', $apikey, $phoneNumber, $smsMessage, $apiUsername );
    }
    
    /**
     * Sending non bulk Masking OTP
     *
     * @param   string  $baseUrl
     * @param   string  $apikey
     * @param   array   $phoneNumber
     * @param   array   $smsMessage
     * @param   string  $apiUsername
     *
     * @return object
     *
     * @throws \TypeError
     * @throws \ArgumentCountError
     */
    public static function nonBulkSmsMaskingOtp( string $baseUrl, string $apikey, array $phoneNumber, array $smsMessage, string $apiUsername ) : object
    {
        return Api::sendRequest( $baseUrl, 'SMS_MASKING_OTP', $apikey, $phoneNumber, $smsMessage, $apiUsername );
    }
    
    /**
     * Sending bulk SMS Masking
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
    public static function bulkSmsMasking( string $baseUrl, string $apikey, array $phoneNumber, array $smsMessage, string $callbackurl ) : object
    {
        return Api::sendRequest( $baseUrl, 'BULK_SMS_MASKING', $apikey, $phoneNumber, $smsMessage, '', $callbackurl );
    }
}
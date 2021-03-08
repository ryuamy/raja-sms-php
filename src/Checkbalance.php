<?php

/**
 * Checkbalance.php
 *
 * @category Class
 * @package  Ryuamy\RyRajaSMS
 *
 * @author   Ryu Amy <ryuamy.mail@gmail.com>
 */

namespace Ryuamy\RyRajaSMS;

/**
 * Class Checkbalance
 *
 * @category Class
 * @package  Ryuamy\RyRajaSMS
 *
 * @author   Ryu Amy <ryuamy.mail@gmail.com>
 */
class Checkbalance
{
    /**
     * Check saldo balance
     *
     * @param   string  $baseUrl
     * @param   string  $apikey
     *
     * @return object
     *
     * @throws \TypeError
     * @throws \ArgumentCountError
     */
    public static function smsBalance( string $baseUrl, string $apikey ) : object
    {
        return Api::sendRequest( $baseUrl, 'CHECK_BALANCE', $apikey );
    }

    /**
     * Check saldo balance
     *
     * @param   string  $baseUrl
     * @param   string  $apikey
     *
     * @return object
     *
     * @throws \TypeError
     * @throws \ArgumentCountError
     */
    public static function waBalance( string $baseUrl, string $apikey ) : object
    {
        return Api::sendRequest( $baseUrl, 'CHECK_WA_BALANCE', $apikey );
    }
}
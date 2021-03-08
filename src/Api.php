<?php

/**
 * Api.php
 *
 * @category Class
 * @package  Ryuamy\RyRajaSMS
 *
 * @author   Ryu Amy <ryuamy.mail@gmail.com>
 */

namespace Ryuamy\RyRajaSMS;

/**
 * Class Api
 *
 * @category Class
 * @package  Ryuamy\RyRajaSMS
 *
 * @author   Ryu Amy <ryuamy.mail@gmail.com>
 */
class Api
{
    /**
     * Send HTTP Request to Raja SMS API.
     */
    public static function sendRequest( string $baseUrl, string $serviceType, string $apikey, string $apiUsername, array $phoneNumber = [], array $smsMessage = [], string $callbackurl = '') : object
    {
        $baseUrl = $baseUrl.'/sms/';

        $senddata = array(
            'apikey' => $apikey
        );

        $requestHeaders = array(
            'Content-Type: application/json'
        );
        
        if( !empty($phoneNumber) ) {
            foreach($phoneNumber as $kPhone => $phone) {
                $checkPhone = preg_match('/^(^\+62\s?|^0)(\d{2,4}-?){2}\d{3,5}$/', $phone);

                if(!$checkPhone) {
                    $return = array(
                        'status' => 400,
                        'message' => 'Invalid Indonesia phone number'
                    );

                    return json_decode($return);
                }
                
                $phoneNumber[$kPhone] = preg_replace( '/[^0-9]/i', '', $phone );
            }
        }

        if( !empty($smsMessage) ) {
            foreach($smsMessage as $kSMS => $sms) {
                $smsMessage[$kSMS] = preg_replace( '/[\W]/', '', $sms );

                $count_message = strlen($smsMessage[$kSMS]);

                if($count_message > 145) {
                    $smsMessage[$kSMS] = substr($smsMessage[$kSMS], 0, 145).' (cropped)';
                }
            }
        }

        switch ($serviceType) {
            case 'SMS_OTP':
            {
                // non bulk SMS
                $baseUrl = $baseUrl.'api_sms_otp_send_json.php';

                $senddata['callbackurl'] = $callbackurl; 
                $senddata['datapacket'] = array();

                array_push($senddata['datapacket'], array(
                    'number' => $phoneNumber[0],
                    'message' => $smsMessage[0]
                ));
            }
            break;
            case 'BULK_SMS_OTP':
            {
                $baseUrl = $baseUrl.'api_sms_otp_send_json.php';

                $senddata['callbackurl'] = $callbackurl; 
                $senddata['datapacket'] = array();
                
                foreach($smsMessage as $key => $sms) {
                    array_push($senddata['datapacket'], array(
                        'number' => $phoneNumber[$key],
                        'message' => $sms
                    ));
                }
            }
            break;
            case 'SMS_MASKING':
            {
                // non bulk SMS
                $baseUrl = $baseUrl.'smsmasking.php?username='.urlencode($apiUsername).'&key='.urlencode($apikey).'&number='.urlencode($phoneNumber[0]).'&message='.urlencode($smsMessage[0]);
            }
            break;
            case 'SMS_MASKING_OTP':
            {
                // non bulk SMS
                $baseUrl = $baseUrl.'smsmasking_otp.php?username='.urlencode($apiUsername).'&key='.urlencode($apikey).'&number='.urlencode($phoneNumber[0]).'&message='.urlencode($smsMessage[0]);
            }
            break;
            case 'BULK_SMS_MASKING':
            {
                $baseUrl = $baseUrl.'api_sms_masking_send_json.php';

                $sendingTime = date('Y-m-d H:i:s');

                $senddata['callbackurl'] = $callbackurl; 
                $senddata['datapacket'] = array();
                
                foreach($smsMessage as $key => $sms) {
                    $sendingTime = ($key != 0) ? date('Y-m-d H:i:s', strtotime($sendingTime.'+5 minute')) : $sendingTime;

                    array_push($senddata['datapacket'], array(
                        'number' => $phoneNumber[$key],
                        'message' => $sms,
                        'sendingdatetime' => $sendingTime
                    ));
                }
            }
            break;
            // case 'MISSCALL_OTP':
            // {
            //     $baseUrl = $baseUrl.'api_misscall_otp_send_json.php';
            // }
            // break;
            case 'WA':
            {
                $baseUrl = $baseUrl.'api_whatsapp_send_json.php';
            }
            break;
            case 'CHECK_BALANCE':
            {
                $baseUrl = $baseUrl.'api_sms_otp_balance_json.php';
            }
            break;
        }

        $data = json_encode($senddata);

        $requestHeaders[] = 'Content-Length: ' . strlen($data);

        if($serviceType !== 'SMS_MASKING' && $serviceType !== 'SMS_MASKING_OTP') {        
            $curlHandle = curl_init($baseUrl);

            curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curlHandle, CURLOPT_HTTPHEADER, $requestHeaders);
            curl_setopt($curlHandle, CURLOPT_TIMEOUT, 30);
            curl_setopt($curlHandle, CURLOPT_CONNECTTIMEOUT, 30);
        } else {
            $curlHandle = curl_init();
            curl_setopt($curlHandle, CURLOPT_URL, $baseUrl);
            curl_setopt($curlHandle, CURLOPT_HEADER, 0);
            curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curlHandle, CURLOPT_TIMEOUT,10);
        }

        $execute = curl_exec($curlHandle);
        curl_close($curlHandle);

        $response = json_decode($execute);

        $sendingstatus = $response->sending_respon[0]->sendingstatus;
        unset($response->sending_respon[0]->sendingstatus);
        
        $return = [];

        switch ($sendingstatus) {
            case 10:
            {
                unset($response->sending_respon[0]->sendingstatustext);
                $return['status'] = 200;
                $return['message'] = 'Success.';
                $return['datas'] = $response->sending_respon[0];
            }
            break;
            case 20:
            {
                $return['status'] = 401;
                $return['message'] = 'Invalid parameter of service type ('.str_replace('_', ' ', strtolower($serviceType)).'). Please check again.'; 
            }
            break;
            case 30:
            {
                unset($response->sending_respon[0]->sendingstatustext);
                $return['status'] = 400;
                $return['message'] = 'API key not registered.';
            }
            break;
            default: 
                $return['status'] = 200;
                $return['message'] = 'There are some errors, please check again.';
                $return['datas'] = $response->sending_respon[0];
        }

        return json_decode($return);
    }
}
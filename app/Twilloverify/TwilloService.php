<?php


namespace App\Twilloverify;


interface TwilloService
{
    /**
     * Start a phone verification process using an external service
     *
     * @param $phone_number
     * @param $channel
     * @return Result
     */
    public function startVerification($phone_number, $channel);


    /**
     * Check verification code using an external service
     *
     * @param $phone_number
     * @param $code
     * @return Result
     */
    public function checkVerification($phone_number, $code);

     /**
     * Check verification code using an external service
     *
     * @param $phone_number
     * @param $code
     * @return Result
     */
    public function lookupPhonenumber($phone_number);
    /**
     * Send all project sms using an external service
     *
     * @param $phone_number
     * @param $code
     * @return Result
     */
    public function sendMsg($firstname,$lastname,$msgType,$countryCode, $phone_number,$data=null);

}
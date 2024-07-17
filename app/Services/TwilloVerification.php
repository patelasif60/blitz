<?php


namespace App\Services;


use App\Twilloverify\TwilloResult;
use App\Twilloverify\TwilloService;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;
use Twilio;
use Auth;

class TwilloVerification implements TwilloService
{

    /**
     * @var Client
     */
    private $client;


    /**
     * @var string
     */
    private $verification_sid;


    /**
     * Verification constructor.
     * @param $client
     * @param string|null $verification_sid
     * @throws \Twilio\Exceptions\ConfigurationException
     */
    public function __construct($client = null, string $verification_sid = null)
    {
        if ($client === null) {
            $sid = config('services.twilio.sid');
            $token = config('services.twilio.token');
            $client = new Client($sid, $token);
        }
        $this->client = $client;
        $this->verification_sid = $verification_sid ?: config('services.twilio.verifiedServiceId');
    }


    /**
     * Start a phone verification process using Twilio Verify V2 API
     *
     * @param $phone_number
     * @param $channel
     * @return Result
     */
    public function startVerification($phone_number, $channel)
    {
        try {
            $verification = $this->client->verify->v2->services($this->verification_sid)
                ->verifications
                ->create($phone_number, $channel);
            return new TwilloResult((string)count($verification->sendCodeAttempts));
        } catch (TwilioException $exception) {
            if($exception->getStatusCode() == 429){
                return new TwilloResult([ __('validation.max_attempt')]);
            }else{
                return new TwilloResult(["Verification failed to start: {$exception->getMessage()}"]);
            }
        }

    }

    /**
     * Check verification code using Twilio Verify V2 API
     *
     * @param $phone_number
     * @param $code
     * @return Result
    */
    public function checkVerification($phone_number, $code)
    {
        try {
            $verification_check = $this->client->verify->v2->services($this->verification_sid)
                ->verificationChecks
                ->create(['to' => $phone_number,'code' => $code]);
            if($verification_check->status === 'approved') {
                return new TwilloResult($verification_check->sid);
            }
            return new TwilloResult([ __('validation.invalidotp')]);
        } catch (TwilioException $exception) {
            if($exception->getStatusCode() == 404){
                return new TwilloResult([ __('validation.otpexpired')]);
            }
            if($exception->getStatusCode() == 400){
                return new TwilloResult([ __('validation.invalidotp')]);
            }
            return new TwilloResult(["Verification check failed:{$exception->getMessage()}"]);
        }
    }
    /**
     * Check phone number using Twilio Verify V2 API
     *
     * @param $phone_number
     * @return Result
    */
    public function lookupPhonenumber($phone_number)
    {
        try{
            $lookupResponse = $this->client->lookups->v1->phoneNumbers($phone_number)->fetch(["type" => ["carrier"]]);
            if($lookupResponse->carrier['error_code'] != null){
                return new TwilloResult([ __('validation.mobvalida')]);
            }
            if($lookupResponse->countryCode != 'IN' && $lookupResponse->countryCode != 'ID'){
                return new TwilloResult([ __('validation.preferctry')]);
            }
            return new TwilloResult($lookupResponse->carrier['type']);
        }catch (TwilioException $exception) {
            return new TwilloResult([ __('validation.mobvalida')]);
        }
    }
     /**
     * send sms using Twilio API
     *
     * @param $phone_number
     * @return Result
     */
    public function sendMsg($firstname,$lastname,$msgType,$countryCode, $phone_number,$data=null)
    {
        try{
                $countryCode = $countryCode ? $countryCode : '+61';
                $phoneNumber = $countryCode.$phone_number;
                $header = "Hello " .$firstname." ".$lastname." \r\n";
                $msg =config('message.'.$msgType);
                switch ($msgType) {
                    case "buyer_welcome_msg":
                         $finalmsg = $header.$msg.config('message.buyer_login_link');
                         break;
                    case "quote_received":
                         $msg = str_replace('@RFQNumber',$data['rfq_number'],$msg);
                         $msg = str_replace('@QuoteNumber',$data['quote_number'],$msg);
                         $finalmsg = $header.$msg.config('message.buyer_login_link');
                         break;
                    case "quote_ending":
                         $msg = str_replace('@RFQNumber',$data['rfq_number'],$msg);
                         $msg = str_replace('@QuoteNumber',$data['quote_number'],$msg);
                         $finalmsg = $header.$msg.config('message.buyer_login_link');
                         break;
                    case "order_placed":
                         $msg = str_replace('@OrderNumber',$data['order_number'],$msg);
                         $finalmsg = $header.$msg.config('message.buyer_login_link');
                         break;
                    case "order_payment_pending":
                         $msg = str_replace('@ordernumber',$data['order_number'],$msg);
                         $msg = str_replace('@amount',$data['final_amount'],$msg);
                         $finalmsg = $header.$msg.config('message.buyer_login_link');
                         break;
                    case "order_status_updated":
                         $msg = str_replace('@OrderNumber',$data['order_number'],$msg);
                         $msg = str_replace('@OrderStatus',$data['status'],$msg);
                         $finalmsg = $header.$msg.config('message.buyer_login_link');
                         break;
                    case "order_credit_approved":
                         $msg = str_replace('@OrderNumber',$data['order_number'],$msg);
                         $finalmsg = $header.$msg.config('message.buyer_login_link');
                         break;
                    case "order_credit_rejected":
                         $msg = str_replace('@OrderNumber',$data['order_number'],$msg);
                         $finalmsg = $header.$msg.config('message.buyer_login_link');
                         break;
                    case "quote_received_for_approval":
                         $msg = str_replace('@RFQNumber',$data['rfq_number'],$msg);
                         $msg = str_replace('@QuoteNumber',$data['quote_number'],$msg);
                         $finalmsg = $header.$msg.config('message.buyer_login_link');
                         break;
                    case "quote_rejected":
                         $msg = str_replace('@RFQNumber',$data['rfq_number'],$msg);
                         $msg = str_replace('@QuoteNumber',$data['quote_number'],$msg);
                         $msg = str_replace('@PersonName',$data['order_number'],$msg);
                         $finalmsg = $header.$msg.config('message.buyer_login_link');
                         break; 
                    case "quote_approved":
                         $msg = str_replace('@RFQNumber',$data['rfq_number'],$msg);
                         $msg = str_replace('@QuoteNumber',$data['quote_number'],$msg);
                         $msg = str_replace('@PersonName',$data['order_number'],$msg);
                         $finalmsg = $header.$msg.config('message.buyer_login_link');
                         break; 
                    case "supplier_welcome_msg":
                         $finalmsg = $header.$msg.config('message.supplier_login_link');
                         break; 
                    case "rfq_received":
                         $countryCode = $countryCode ? $countryCode : '+61';
                         $phoneNumber = $countryCode.$phone_number;
                         $finalmsg = $header.$msg.config('message.supplier_login_link');
                         break;
                    case "order_received":
                         $msg = str_replace('@OrderNumber',$data['order_number'],$msg);
                         $finalmsg = $header.$msg.config('message.supplier_login_link');
                         break;  
                    default:
                         echo "";
                }
                Twilio::message($phoneNumber,$finalmsg);
                return true;
        }catch (TwilioException $exception) {
            return $exception;
        }
    }
}

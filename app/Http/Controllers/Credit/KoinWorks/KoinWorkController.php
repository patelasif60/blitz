<?php

namespace App\Http\Controllers\Credit\KoinWorks;

use App\Http\Controllers\Controller;
use App\Models\LoanProvider;
use App\Models\LoanProviderApiList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class KoinWorkController extends Controller
{
    public $privateKey;
    public $publicToken;
    public $timestamp;
    public $payloadBody;
    public $path;
    public $method;
    public $bodyEncrypt;
    public $formatEncrypt;
    public $signature;
    public $loanProvider;
    public $basePath;
    public $accessToken;

    public function __construct()
    {
        $this->loanProvider = LoanProvider::find(LoanProvider::KOINWORKS);
        if (config('app.env')=='live') {
            $this->basePath = $this->loanProvider->production_base_path;
            $this->privateKey = 'X86QSLGPF66BLD8RF9EQ45X9W6PKFQ62U39MJCAXP63WPSMD1TXT8YZCTF4UELYQCGWV88Q9ECU6W59K8F24NGAE817GUK3T6A8CGM9YCK2G6XQGDRUT681E66I2A968MYDSPURPIWX33N6P0IWJYJ0PWI62XWC5KM51MCIHBCNL6DBQEX4FPWGLHQEKMNTBE54I6OALY87UHWY2VL3W82OR1H50HUGP4QOTXSAN1SFNFURDPWP2VYT2JGJB9QL0';
            $this->publicToken = '6HBVJHJFCU1MA40WIA2ND59W5M42O6ICGRNI2D5VAW0O4HQG59RNZLLNOP5WPAI0';
        }else{
            $this->basePath = $this->loanProvider->staging_base_path;
            $this->privateKey = '4O5QKD9ZGKRC48QSMTT9GF52GO1ZI87WUNU4K6X8G9QHEJIME2LL3CKTE8LR6UV02COJT9SA26B0RDOEYCEAL97URXI2ATWSINLUL7V4QXO3WFK0EXJZVD5PP7UJPSTFS2BCOQAVNLJIS30M82FLW8TJD4ASD22AQHVI1MY5LE9EJNHBHJ7NAIAKNYGJJ92Q4Q1RQWLK64M9IXYV21GX0GRLF6T75MTTLDJHL8XX0P7EN200T2PE7G5BYVG80673';
            $this->publicToken = 'FWUETN35AP4QP3KNK5OGCD05LAVDLVDBJSH1N5N1ZAQNZKXUU25Q7M3GF8DQDACS';
        }
        date_default_timezone_set('Asia/Kolkata');
        $this->timestamp = str_replace('+05:30','+0530',date('c',time()));
        $this->accessToken = 'X';
    }

    /**
     * Koinworks Set Access Token
     *
     * @param  string  $accessToken
     */
    public function setAccessToken($token)
    {
        $this->accessToken = $token;
        return $this;
    }

    /**
     * Koinworks Set Signature
     *
     * @param $path
     * @param $method
     * @param string $payload
     * @return $this
     */
    public function setSignature($path,$method,$payload='')
    {
        $this->method = strtoupper($method);
        $this->path = $path;
        $this->payloadBody = $payload;
        $this->bodyEncrypt = hash('sha256', $this->payloadBody);
        $this->formatEncrypt = "{$this->method};{$this->path};{$this->accessToken};{$this->timestamp};{$this->bodyEncrypt}";
        $this->signature = hash_hmac('sha256',$this->formatEncrypt,"KW:{$this->privateKey}");
        return $this;
    }

    /**
     *
     * Koinworks Authenticate for Create Limit
     *
     * @param $data
     * @return mixed
     */
    public function send($data)
    {
        $method = strtolower($data['method']);
        $payload = $data['payload']??'';
        $this->setSignature($data['path'],$data['method'],$payload);
        $response = Http::withBody($payload, 'application/json')
                    ->withHeaders([
                        'x-koinworks-token' => $this->publicToken,
                        'x-koinworks-signature' => $this->signature,
                        'x-koinworks-timestamp' => $this->timestamp,
                        'User-Agent' => 'blitznet',
                    ])->$method($this->basePath.str_replace('/apis','',$data['path']));

        return $response->json();
    }

    /**
     *
     * Koinworks Authenticate for Request OTP and Verify OTP
     *
     * @param $data
     * @return mixed
     */
    public function sendAuth($data)
    {
        $method = strtolower($data['method']);
        $payload = $data['payload']??'';
        $this->setSignature($data['path'],$data['method'],$payload);
        $response = Http::withBody($payload, 'application/json')
                    ->withHeaders([
                        'x-koinworks-token'     => $this->publicToken,
                        'x-koinworks-signature' => $this->signature,
                        'x-koinworks-timestamp' => $this->timestamp,
                        'Authorization' => 'Bearer '.$this->accessToken,
                        'User-Agent' => 'blitznet',
                    ])->$method($this->basePath.str_replace('/apis','',$data['path']));

        return $response->json();
    }

    /**
     * Koinworks Create Limit API Call.
     *
     * @return \Illuminate\Http\Response
     */
    public function createLimit($payload)
    {
        $loanProviderApi = LoanProviderApiList::where('id',LoanProviderApiList::REQUEST_NEW_LIMIT)->first(['method','path']);
        return $this->send(['path'=>$loanProviderApi->path,'method'=>$loanProviderApi->method,'payload'=>json_encode($payload)]);
    }

    /**
     * Koinworks Get Limit API Call.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLimit($parameters='')
    {

        $loanProviderApi = LoanProviderApiList::where('id',LoanProviderApiList::GET_LIMIT)->first(['method','path']);
        return $this->send(['path'=>$loanProviderApi->path.'?id='.$parameters,'method'=>$loanProviderApi->method]);
    }

    /**
     * Koinworks Generate Token API Call.
     *
     * @return \Illuminate\Http\Response
     */
    public function generateToken($userId)
    {
        $loanProviderApi = LoanProviderApiList::where('id',LoanProviderApiList::GENERATE_TOKEN)->first(['method','path']);
        $scopes = '[
                    "asgard-bivrost.auth-ctx-user-read",
                    "asgard-bivrost.auth-ctx-user-write",
                    "asgard-activity.welcome-read",
                    "asgard-bivrost.auth-refresh-read",
                    "asgard-bivrost.auth-refresh-write",
                    "asgard-user.sme-register-business-write",
                    "asgard-user.sme-business-profile-write",
                    "asgard-user.sme-register-personal-write",
                    "asgard-user.referral-check-read",
                    "asgard-user.sme-business-profile-write",
                    "asgard-activity.welcome-read",
                    "asgard-koinbnpl.user-read",
                    "asgard-koinbnpl.user-write",
                    "asgard-koinbnpl.bnpl-limit-read",
                    "asgard-koinbnpl.bnpl-limit-write",
                    "asgard-koinbnpl.loan-read",
                    "asgard-koinbnpl.loan-write",
                    "asgard-koinbnpl.asgard-user.user-read",
                    "asgard-koinbnpl.asgard-user.user-write",
                    "asgard-koinbnpl.asgard-koinbnpl.limit-read",
                    "asgard-koinbnpl.asgard-koinbnpl.limit-write",
                    "asgard-koinbnpl.asgard-loan.loan-read",
                    "asgard-koinbnpl.asgard-loan.loan-write",
                    "asgard-koinbnpl.asgard-koinp2p.marketplace-read",
                    "asgard-koinbnpl.asgard-koinp2p.marketplace-write",
                    "asgard-koinbnpl.limit-read",
                    "asgard-koinbnpl.limit-write",
                    "asgard-user.user-read",
                    "asgard-user.user-write",
                    "asgard-loan.loan-read",
                    "asgard-loan.loan-write",
                    "asgard-koinp2p.marketplace-read",
                    "asgard-koinp2p.marketplace-write",
                    "asgard-user.user-personal-registration-write",
                    "asgard-user.user-personal-registration-write",
                    "asgard-user.user-vendor-read",
                    "asgard-user.user-vendor-write",
                    "asgard-activity.balance-read",
                    "asgard-activity.balance-write",
                    "asgard-activity.cash-in-out-aps-read",
                    "asgard-activity.cash-in-out-aps-write",
                    "asgard-activity.mutation-read",
                    "asgard-activity.mutation-write",
                    "asgard-activity.asgard-activity.balance-read",
                    "asgard-activity.asgard-activity.balance-write",
                    "asgard-activity.asgard-activity.cash-in-out-aps-read",
                    "asgard-activity.asgard-activity.cash-in-out-aps-write",
                    "asgard-activity.asgard-activity.mutation-read",
                    "asgard-activity.asgard-activity.mutation-write",
                    "asgard-user.user-document-read",
                    "asgard-user.user-document-write",
                    "asgard-koinbnpl.asgard-user.user-document-read",
                    "asgard-koinbnpl.asgard-user.user-document-write"
                ]';
        if (config('app.env')=='live') {
            $scopes = '[
                "asgard-user.user-read",
                "asgard-koinbnpl.limit-read",
                "asgard-koinbnpl.limit-write",
                "asgard-loan.loan-read",
                "asgard-loan.loan-write",
                "asgard-koinp2p.marketplace-read",
                "asgard-koinp2p.marketplace-write",
                "asgard-user.user-document-read",
                "asgard-user.user-document-write"
            ]';
        }
        $result = $this->send([
            'path'=>$loanProviderApi->path,
            'method'=>$loanProviderApi->method,
            'payload'=>'{
                "type": "user",
                "key": '.$userId.',
                "scopes": '.$scopes.'
            }'
        ]);
        if ($result['status']==200) {
            $this->setAccessToken($result['data']['accessToken']);
        }else{
            $this->setAccessToken('X');
        }
    }

    /**
     * Koinworks Request MY Limit API Call.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function userLimit($userId)
    {
        $this->generateToken($userId);
        $loanProviderApi = LoanProviderApiList::where('id',LoanProviderApiList::MY_LIMIT)->first(['method','path']);
        return $this->sendAuth(['path'=>$loanProviderApi->path,'method'=>$loanProviderApi->method]);
    }

    /**
     * Koinworks Request OTP API Call.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function requestLimitOTP($userId)
    {
        $this->generateToken($userId);
        $loanProviderApi = LoanProviderApiList::where('id',LoanProviderApiList::REQUEST_LIMIT_OTP)->first(['method','path']);
        return $this->sendAuth(['path'=>$loanProviderApi->path,'method'=>$loanProviderApi->method]);
    }

    /**
     * Koinworks Verfiy OTP API Call.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function verifyLimitOTP($userId,$code)
    {
        $this->generateToken($userId);
        $loanProviderApi = LoanProviderApiList::where('id',LoanProviderApiList::VERIFY_LIMIT_OTP)->first(['method','path']);
        return $this->sendAuth(['path'=>$loanProviderApi->path,'method'=>$loanProviderApi->method,'payload'=>json_encode(['code'=>$code])]);
    }

    /**
     * Koinworks Reupload Document API Call.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reuploadDocument($userId,$payload)
    {
        $this->generateToken($userId);
        $loanProviderApi = LoanProviderApiList::where('id',LoanProviderApiList::REUPLOAD_DOCUMENT)->first(['method','path']);
        return $this->sendAuth(['path'=>$loanProviderApi->path,'method'=>$loanProviderApi->method,'payload'=>json_encode($payload)]);
    }

    /**
     * Koinworks upload contract API Call.
     *
     * @param  id and file
     * @return \Illuminate\Http\Response
     */
    public function limitContractUpload($userId,$contractURL){
        $this->generateToken($userId);
        $loanProviderApi = LoanProviderApiList::where('id',LoanProviderApiList::UPLOAD_SIGNED_CONTRACT)->first(['method','path']);
        return $this->sendAuth(['path'=>$loanProviderApi->path,'method'=>$loanProviderApi->method,'payload'=>json_encode(['contractURL'=>$contractURL])]);

    }

     /**
     * Koinworks create Loan API Call.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function createLoan($userId,$getArrCreateLOan)
    {
        //dd($getArrCreateLOan);
        $this->generateToken($userId);
        $loanProviderApi = LoanProviderApiList::where('id',LoanProviderApiList::CREATE_LOAN)->first(['method','path']);
        return $this->sendAuth(['path'=>$loanProviderApi->path,'method'=>$loanProviderApi->method,'payload'=>json_encode($getArrCreateLOan)]);
    }

     /**
     * Koinworks loan Verfiy OTP API Call.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function verifyOTP($userId,$code,$loanId)
    {
        $this->generateToken($userId);
        $loanProviderApi = LoanProviderApiList::where('id',LoanProviderApiList::VERIFY_LOAN_OTP)->first(['method','path']);
        $path = str_replace(":loanId",$loanId,$loanProviderApi->path);
        return $this->sendAuth(['path'=>$path,'method'=>$loanProviderApi->method,'payload'=>json_encode(['code'=>$code])]);
    }

     /**
     * Koinworks Request OTP API Call.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function requestLoanOTP($userId,$loanId)
    {

        $this->generateToken($userId);
        $loanProviderApi = LoanProviderApiList::where('id',LoanProviderApiList::REQUEST_LOAN_OTP)->first(['method','path']);
        $path = str_replace(":loanId",$loanId,$loanProviderApi->path);
        return $this->sendAuth(['path'=> $path,'method'=>$loanProviderApi->method]);
    }
    /**
     * Koinworks Loan Disbursement API call.
     *
     * @param  start date and end date
     * @return \Illuminate\Http\Response
     */
    public function loanDisbursementReport($parameters){
        $loanProviderApi = LoanProviderApiList::where('id',LoanProviderApiList::LOAN_DISBURSEMENT_REPORT)->first(['method','path']);
        return $this->send(['path'=>$loanProviderApi->path.'?'.$parameters,'method'=>$loanProviderApi->method]);

    }

    /**
     * Koinworks Loan Disbursement API call.
     *
     * @param  start date and end date
     * @return \Illuminate\Http\Response
     */
    public function loanPartnerCancel($userId, $loanId){
        $this->generateToken($userId);
        $loanProviderApi = LoanProviderApiList::where('id',LoanProviderApiList::LOAN_PARTNER_CANCELLED)->first(['method','path']);
        $path = str_replace(":loanId",$loanId,$loanProviderApi->path);
        $emptyObj = json_encode( (object)[] );
        return $this->sendAuth(['path'=>$path,'method'=>$loanProviderApi->method, 'payload'=>$emptyObj]);
    }

    /**
     * Koinworks repay API call.
     *
     * @param  loan id pasas
     * @return \Illuminate\Http\Response
     *
     * @ekta
     */
    public function repayment($loanId){
        $loanProviderApi = LoanProviderApiList::where('id',LoanProviderApiList::REPAYMENT)->first(['method','path']);
        $path = str_replace(":loanId",$loanId,$loanProviderApi->path);
        return $this->send(['path'=>$path,'method'=>$loanProviderApi->method]);

    }

    /**
     * Koinworks loan Delivery Confirmation API Call.
     *
     * @param  int  $userId, $loanId
     * @param array $payload
     * @return \Illuminate\Http\Response
     */
    public function loanConfirmation($userId,$loanId,$payload)
    {
        $this->generateToken($userId);
        $loanProviderApi = LoanProviderApiList::where('id',LoanProviderApiList::LOAN_CONFIRMATION)->first(['method','path']);
        $path = str_replace(":loanId",$loanId,$loanProviderApi->path);
        return $this->sendAuth(['path'=>$path,'method'=>$loanProviderApi->method,'payload'=>json_encode($payload)]);
    }

}

<?php

namespace App\Http\Controllers\API\Xendit;

use App\Http\Controllers\Controller;
use Xendit\Disbursements;
use Xendit\Platform;
use Xendit\Xendit;
use Xendit\Invoice;
use Xendit\Balance;

class XenditApiController extends Controller
{

    private $params = [];

    public function __construct()
    {
        if (config('app.env')=='live') {
            Xendit::setApiKey(getSettingValueByKey('xendit_live_token'));
        }elseif(config('app.env')=='staging'){
            Xendit::setApiKey(getSettingValueByKey('xendit_test_token'));
        }else{
            Xendit::setApiKey(getSettingValueByKey('xendit_test_token'));
        }
    }

    /**
     * Xendit Get Invoice resource
     *
     * @param $params
     * @param $invoice
     * @return array
     */
    public function getInvoice($params,$invoice)
    {
        return $this->generateInvoice($params,$invoice);
    }

    /**
     * Request disbursement
     *
     * @param $params
     * @return mixed
     */
    public function requestDisbursement($params)
    {
        return $this->getDisbursementDetails($params);
    }

    /**
     * Request Internal Transfer
     *
     * @param $params
     * @return array
     */
    public function requestInternalTransfer($params)
    {
        return $this->getInternalTransferDetails($params);
    }

    /**
     * Xendit Generate Invoice
     *
     * @param $params
     * @param $invoice
     * @return array
     */
    private function generateInvoice($params,$invoice)
    {
        if ($invoice == 'single') {
            return $this->singleInvoiceGenerate($params);
        }
    }

    /**
     * Xendit Payment Link Re-Generate
     *
     */
    private function repaymentLinkGenerate()
    {

    }

    /**
     * Set single invoice generate data
     *
     * @param $params
     */
    private function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * Xendit Invoice Generate for single payment
     *
     * @param $params
     * @return array
     */
    private function singleInvoiceGenerate($params)
    {
        $this->setParams($params);

        return $this->getSingleInvoiceGenerate();
    }

    /**
     * Get single invoice generate data
     *
     * @return array
     */
    private function getSingleInvoiceGenerate()
    {
        return Invoice::create($this->params);
    }

    /**
     * Get disbursement details
     *
     * @param $params
     */
    private function getDisbursementDetails($params)
    {
        $this->setParams($params);

        return $this->createDisbursementRequest();
    }

    /**
     * Create disbursement request
     *
     * @return array
     */
    private function createDisbursementRequest()
    {
        return Disbursements::create($this->params);
    }

    /**
     * Get internal transfer details
     *
     * @param $params
     */
    private function getInternalTransferDetails($params)
    {
        $this->setParams($params);

        return $this->createInternalTransferRequest();
    }

    /**
     * Create an Internal Transfer request
     *
     * @return array
     * @throws \Xendit\Exceptions\ApiException
     */
    private function createInternalTransferRequest()
    {
        return Platform::createTransfer($this->params);
    }
}

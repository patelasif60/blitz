<?php

namespace App\Http\Controllers\API\Xendit;

use App\Models\LoanApply;
use App\Models\PaymentProviderTransaction;
use App\Http\Controllers\Controller;
use App\Models\User;
use View;
use Illuminate\Support\Facades\Log;

class XenditTemplateController extends Controller
{

    private $templateAttribute = [];

    /**
     * Render payment fail template
     *
     * @param $model
     * @param $model_id
     */
    public function renderPaymentSuccess($model, $model_id)
    {
        return $this->renderTemplate($model, $model_id,'success_template');
    }

    /**
     * Render payment success template
     *
     * @param $model
     * @param $model_id
     */
    public function renderPaymentFail($model, $model_id)
    {
        return $this->renderTemplate($model, $model_id,'fail_template');

    }

    /**
     *
     * Render Xendit templates
     *
     * @param $model
     * @param $model_id
     * @param $template_name
     */
    private function renderTemplate($model, $model_id,$template_name)
    {
        $attribute = $this->getTemplateAttribute($model, $model_id);

        return View::make('buyer.layouts.xendit.'.$template_name)->with(compact('attribute'));
    }

    private function getTemplateAttribute($model, $model_id)
    {
        try {

            if ($model == LoanApply::class) {

                $modelObj = PaymentProviderTransaction::whereHasMorph('related', [$model])->whereHasMorph('users', [User::class])->where('payment_provider_id', PAYMENT_PROVIDERS['XENDIT'])
                    ->where('transaction_type_id', TRANSACTION_TYPES['BUYER_REPAYMENT'])
                    ->where('related_id', $model_id)
                    ->first();

                $response = !empty($modelObj) ? $modelObj->response_by_provider : '';

                $this->templateAttribute['payer_email'] = !empty($response) ? json_decode($response)->payer_email : '-';
                $this->templateAttribute['paid_amount'] = !empty($response) ? json_decode($response)->amount : 0.00;
                $this->templateAttribute['payment_date'] = !empty($modelObj) ? $modelObj->created_at : '';
                $this->templateAttribute['payment_channel'] = !empty($response) ? json_decode($response)->payment_channel : '';

                return $this->templateAttribute;

            }

        } catch (\Exception $exception) {

            Log::critical('Code - 204 | ErrorCode:B009 Xendit Email Template');

            return [];
        }

        return [];
    }

}

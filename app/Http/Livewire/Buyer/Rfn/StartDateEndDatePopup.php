<?php

namespace App\Http\Livewire\Buyer\Rfn;

use App\Http\Controllers\Buyer\RFN\GlobalRfnController;
use Livewire\Component;

class StartDateEndDatePopup extends Component
{
    public $convertStartDate="",$convertEndDate = "",$rfqId= "";
    protected $listeners = ['addDateOnGlobalRfnConvert' => 'addDateOnGlobalRfnConvert','dateRfnIdConfigure' => 'configure'];
    public function rules()
    {

        $rules =  [
            'convertStartDate'      =>  'required|before:convertEndDate',
            'convertEndDate'        =>  'required|after:convertStartDate'
        ];
        return $rules;

    }
    public function messages()
    {
        return [
            'convertStartDate.required'     =>  __('validation.custom.startDate.required'),
            'convertEndDate.required'       =>  __('validation.custom.endDate.required'),
            'convertStartDate.before'       =>  __('validation.custom.startDate.before'),
            'convertEndDate.after'          =>  __('validation.custom.endDate.after'),
        ];
    }
    public function render()
    {
        return view('livewire.buyer.rfn.start-date-end-date-popup');
    }
    /**
     * Configure the component
     *
     * @param $formType
     */
    public function configure($rfqId = null)
    {
        if (!empty($rfqId)) {
            $this->rfnId = $rfqId;
        }
        $this->resetFields();
        $this->resetError();
    }
    public function addDateOnGlobalRfnConvert()
    {

        $validateData = $this->validate();
        $data = collect();
        $data->rfn_id = isset($this->rfnId) ? $this->rfnId : '';
        $data->startDate = $validateData['convertStartDate'];
        $data->endDate = $validateData['convertEndDate'];
        if (!empty($data)) {
            $response =  (new GlobalRfnController())->createGlobalRfn($data);
        }
        $message = $response->getOriginalContent();

        if ($message['success']) {
            $this->dispatchBrowserEvent('pnotify', [
                'text' => $message['message'],
                'type' => 'success',
                'styling' => 'bootstrap3',
                'animateSpeed' => 'fast',
                'delay' => 3000,
                'component' => 'convertRfnStartEndDatePopup',
                'modal' => true,
                'success' => true
            ]);
            $this->resetFields();
        } else {
            $this->dispatchBrowserEvent('pnotify', [
                'text' => $message['message'],
                'type' => 'alert',
                'styling' => 'bootstrap3',
                'animateSpeed' => 'fast',
                'delay' => 3000,
                'component' => 'convertRfnStartEndDatePopup',
                'modal' => false,
                'success' => false
            ]);
        }

    }
    /**
     * Reset Form fields
     */
    public function resetFields()
    {
      $this->convertStartDate = $this->convertEndDate = "";
    }
    /**
     * Reset error bags
     * @param $input
     * @param bool $isSingle
     */
    public function resetError($input = null,$isSingle = false)
    {
        if ($isSingle && (!empty($input))) {
            $this->resetValidation($input);
            $this->resetErrorBag($input);
        } else {
            $this->resetErrorBag();
            $this->resetValidation();
        }
    }
}

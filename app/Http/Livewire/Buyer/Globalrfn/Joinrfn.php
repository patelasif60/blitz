<?php

namespace App\Http\Livewire\Buyer\Globalrfn;

use App\Http\Controllers\Buyer\RFN\RfnController;
use App\Models\Category;
use App\Models\Product;
use App\Models\Rfn;
use App\Models\RfnItems;
use App\Models\RfnResponse;
use App\Models\Unit;
use Livewire\Component;

class Joinrfn extends Component
{
    public $rfnId = "",$rfnjoinquantity = "", $unit_id = "",$expected_date ="",$rfnjoincomment = "",$units = "",$rfn = "",$rfnResponse = "",$startDate = "",$endDate = "",$productDestription = "",$joinRfnexpectedDate = "",$itemId="",
        $is_joinRfnEdit = "",$joinRfnResponse_id ="";
    protected $listeners = ['rfnJoinConfigure' => 'configure','updateJoinRfn' => 'updateJoinRfn','resetError'=>'resetError','rfnJoinEditConfigure'=>'rfnJoinEditConfigure'];

    public function rules()
    {

        $rules =  [
            'rfnjoinquantity'      =>  'required|max:255',
            'joinRfnexpectedDate'        =>  'required',
            'rfnjoincomment'              =>  'max:5000',
        ];
        return $rules;

    }
    public function messages()
    {
        return [
            'rfnjoinquantity.required'      =>  __('validation.custom.buyerProductQuantity.required'),
            'rfnjoinquantity.max'           =>  __('validation.custom.buyerProductQuantity.max'),
            'rfnjoincomment.max'            =>  __('validation.custom.buyerComment.max'),
            'joinRfnexpectedDate.required'  =>  __('validation.custom.expectedDate.required')
        ];
    }
    public function mount($rfnId = '')
    {
        $this->rfnId = $rfnId;
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
        $this->setData();
    }
    public function rfnJoinEditConfigure($itemId = null,$rfqId = null,$joinRfnResponse_id = null,$is_joinRfnEdit = null)
    {
        if (!empty($itemId)) {
            $this->itemId = $itemId;
        }
        if (!empty($rfqId)) {
            $this->rfnId = $rfqId;
        }
        if (!empty($joinRfnResponse_id)){
            $this->joinRfnResponse_id = $joinRfnResponse_id;
        }
        $this->is_joinRfnEdit = !empty($is_joinRfnEdit) ? $is_joinRfnEdit : null;
        $this->resetError();
        $this->resetFields();
        $this->editJoinRfnFields();
    }
    public function render()
    {
        return view('livewire.buyer.globalrfn.joinrfn');
    }
    /**
     * Set Join RFN edit fields
     */
    public function editJoinRfnFields()
    {
        $rfnItem = RfnItems::where('id', $this->itemId)->first();
        $rfn = Rfn::with('rfnItem')->where('id', $this->rfnId)->first();
        $rfnResponse = RfnResponse::where('id',$this->joinRfnResponse_id)->first();
        $units = Unit::query()->where('is_deleted', 0)->get();
        $this->startDate = isset($rfn->start_date) ? \Carbon\Carbon::parse($rfn->start_date)->format('d-m-Y') : '-';
        $this->endDate = isset($rfn->end_date) ?\Carbon\Carbon::parse($rfn->end_date)->format('d-m-Y') : '-';
        $this->unit_id = isset($rfnItem) ? $rfnItem->unit_id : 0;
        $this->units = $units;
        $this->rfnjoinquantity = isset($rfnItem->quantity) ? $rfnItem->quantity : '';
        $this->rfnjoincomment = isset($rfnItem->item_description) ? $rfnItem->item_description : '';
        $this->joinRfnexpectedDate = isset($rfnResponse->expected_date) ? \Carbon\Carbon::parse($rfnResponse->expected_date)->format('d-m-Y') : '';
        $this->productDestription = isset($rfn->rfnItem->item_description) ? $rfn->rfnItem->item_description : '-';
    }
    /**
     * Set Join Rfn fields
     */
    public function setData()
    {
        $rfn = Rfn::with('rfnItem')->where('id', $this->rfnId)->first();
        $units = Unit::query()->where('is_deleted', 0)->get();
        $this->startDate = isset($rfn->start_date) ? \Carbon\Carbon::parse($rfn->start_date)->format('d-m-Y') : '-';
        $this->endDate = isset($rfn->end_date) ?\Carbon\Carbon::parse($rfn->end_date)->format('d-m-Y') : '-';
        $this->unit_id = isset($rfn->rfnItem) ? $rfn->rfnItem->unit_id : 0;
        $this->units = $units;
        $this->productDestription = isset($rfn->rfnItem->item_description) ? $rfn->rfnItem->item_description : '-';
    }
    /**
     * Reset Form fields
     */
    public function resetFields()
    {
        $this->rfnjoinquantity = $this->unit_id = $this->expected_date = $this->rfnjoincomment = $this->startDate = $this->endDate = $this->productDestription = $this->units = $this->joinRfnexpectedDate  = "";
    }
    /**
     * Update join RFN
     */
    public function updateJoinRfn()
    {
        $validateData = $this->validate();
        $data = collect();
        $data->rfn_id = isset($this->rfnId) ? $this->rfnId : '';
        $data->itemId = isset($this->itemId) ? $this->itemId : '';
        $data->joinRfnResponse_id = isset($this->joinRfnResponse_id) ? $this->joinRfnResponse_id : '';
        $data->quantity = $validateData['rfnjoinquantity'];
        $data->expected_date = $validateData['joinRfnexpectedDate'];
        $data->product_description = $validateData['rfnjoincomment'];
         if ($this->is_joinRfnEdit == 1){
             $response = (new RfnController())->editjoinRfnRequest($data);
         }else{

             $response = (new RfnController())->joinRfnRequest($data);
         }

        $message = $response->getOriginalContent();

        if ($message['success']) {
            $this->dispatchBrowserEvent('pnotify', [
                'text' => $message['message'],
                'type' => 'success',
                'styling' => 'bootstrap3',
                'animateSpeed' => 'fast',
                'delay' => 3000,
                'component' => 'joinRfnModal',
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
                'component' => 'joinRfnModal',
                'modal' => false,
                'success' => false
            ]);
        }
        return redirect(request()->header('Referer'));
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

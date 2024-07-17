<?php

namespace App\Http\Livewire\Buyer\Rfn;

use App\Http\Controllers\Buyer\RFN\GlobalRfnController;
use App\Http\Controllers\Buyer\RFN\RfnController;
use App\Models\Category;
use App\Models\Product;
use App\Models\Rfn;
use App\Models\SubCategory;
use App\Models\Unit;
use Livewire\Component;

class EditRfn extends Component
{
    public $editBuyerCategory="", $editBuyerSubcategory="", $editBuyerProduct, $editBuyerProductDescription,
        $editBuyerProductUnit="", $editBuyerProductQuantity, $editProductDescription, $editBuyerComment, $editExpectedDate, $editStartDate, $editEndDate,
        $editProductId = "", $rfnId = "", $editproductSearch = ""; // Form Variables

    public $subCategories, $products; // Select List variables

    public $productResultShow = false;

    public $formType; // 1 RFN, 2 Global RFN

    protected $listeners = ['editFadeProductResult' => 'hideProductResult', 'editResetError' => 'resetError', 'editConfigure' => 'configure', 'updateRfn' => 'updateRfn',
        'editSetData' => 'setOtherSource'];

    protected function rules()
    {

        $rules =  [
            'editBuyerCategory'             =>  'required',
            'editBuyerSubcategory'          =>  'required',
            'editBuyerProduct'              =>  'required|max:5000',
            'editProductDescription'        =>  'required|max:5000',
            'editBuyerComment'              =>  'max:5000',
            'editBuyerProductUnit'          =>  'required',
        ];

        // Dynamic rules
        if ($this->formType == Rfn::RFN) {
            $rules['editExpectedDate'] = 'required';
            $rules['editBuyerProductQuantity'] =  'required|max:255|min:0|not_in:0';

        } else if ($this->formType == Rfn::GLOBALRFN) {
            $rules['editBuyerProductQuantity'] =  'max:255';
            $rules['editStartDate'] = 'required|before:editEndDate';
            $rules['editEndDate'] = 'required|after:editStartDate';
        }

        return $rules;

    }

    protected function messages()
    {
        return [
            'editBuyerCategory.required'            =>  __('validation.custom.buyerCategory.required'),
            'editBuyerProduct.required'             =>  __('validation.custom.buyerProduct.required'),
            'editBuyerSubcategory.required'         =>  __('validation.custom.buyerSubcategory.required'),
            'editBuyerProductQuantity.required'     =>  __('validation.custom.buyerProductQuantity.required'),
            'editProductDescription.required'       =>  __('validation.custom.productDescription.required'),
            'editBuyerComment.max'                  =>  __('validation.custom.buyerComment.max'),
            'editExpectedDate.required'             =>  __('validation.custom.expectedDate.required'),
            'editStartDate.required'                =>  __('validation.custom.startDate.required'),
            'editEndDate.required'                  =>  __('validation.custom.endDate.required'),
            'editStartDate.before'                  =>  __('validation.custom.startDate.before'),
            'editEndDate.after'                     =>  __('validation.custom.endDate.after'),
            'editBuyerProductUnit.required'         =>  __('validation.custom.buyerProductUnit.required'),
            'editBuyerProduct.max'                  =>  __('validation.custom.buyerProduct.max'),
            'editBuyerProductQuantity.max'          =>  __('validation.custom.buyerProductQuantity.max'),
            'editProductDescription.max'            =>  __('validation.custom.productDescription.max'),
            'editBuyerProductQuantity.not_in'       =>  __('validation.custom.buyerProductQuantity.not_zero')

        ];
    }

    public function mount($formType, $rfnId = '')
    {
        $this->rfnId = $rfnId;
        $this->configure($formType);
        $this->editFields();
    }

    /**
     * Configure the component
     *
     * @param $formType
     */
    public function configure($formType, $rfqId = null)
    {
        $this->formType = $formType;

        if (!empty($rfqId)) {
            $this->rfnId = $rfqId;

        }
        $this->resetError();
        $this->resetFields();
        $this->editFields();

    }

    public function render()
    {
        $data = $this->getData();

        return view('livewire.buyer.rfn.edit-rfn')->with($data);
    }

    /**
     * Update Rfn
     */
    public function updateRfn()
    {
        $validateData = $this->validate();
        $data = collect();
        $data->rfn_id = $this->rfnId;
        $data->type = $this->formType;
        $data->category_id = $validateData['editBuyerCategory'];
        $data->subcategory_id = $validateData['editBuyerSubcategory'];
        $data->product_name = $validateData['editBuyerProduct'];
        $data->quantity = $validateData['editBuyerProductQuantity'];
        $data->unit_id = $validateData['editBuyerProductUnit'];
        $data->item_description = $validateData['editProductDescription'];
        $data->comment = $validateData['editBuyerComment'];
        $data->product_id = $this->editProductId;
        if ($this->formType == Rfn::RFN) {
            $data->expected_date = $validateData['editExpectedDate'];
            $response = (new RfnController())->updateRfn($data);

        } else if ($this->formType == Rfn::GLOBALRFN) {

            $data->start_date = $validateData['editStartDate'];
            $data->end_date = $validateData['editEndDate'];
            $response = (new GlobalRfnController())->updateGlobalRfn($data);
        }

        $message = $response->getOriginalContent();

        if ($message['success']) {
            $this->dispatchBrowserEvent('pnotify', [
                'text' => $message['message'],
                'type' => 'success',
                'styling' => 'bootstrap3',
                'animateSpeed' => 'fast',
                'delay' => 3000,
                'component' => 'editRfnModal',
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
                'component' => 'editRfnModal',
                'modal' => true,
                'success' => true

            ]);
        }

    }

    /**
     * Get form Data
     *
     * @return array
     */
    public function getData()
    {
        return [
            'categories' => Category::query()->where('is_deleted',0)->where('status',1)->get(),
            'units' => Unit::query()->where('is_deleted', 0)->get(),
            'subCategories' => $this->subCategories,
            'products' => Product::where('subcategory_id', $this->editBuyerSubcategory)->where('is_deleted', 0)->where('status', 1)->get(),
            'rfn' => Rfn::with('rfnItem')->where('id',$this->rfnId)->first()

        ];
    }

    /**
     * Set edit fields
     */
    public function editFields()
    {
        $rfn = Rfn::with('rfnItem')->where('id', $this->rfnId)->first();
        $this->editBuyerCategory = isset($rfn->rfnItem->category_id) ? $rfn->rfnItem->category_id : '';
        $this->categoryChange();
        $this->editBuyerComment = isset($rfn->comment) ? $rfn->comment : '';
        $this->editEndDate = isset($rfn->end_date) ?\Carbon\Carbon::parse($rfn->end_date)->format('d-m-Y') : '';
        $this->editStartDate = isset($rfn->start_date) ? \Carbon\Carbon::parse($rfn->start_date)->format('d-m-Y') : '';
        $this->editExpectedDate = isset($rfn->expected_date) ? \Carbon\Carbon::parse($rfn->expected_date)->format('d-m-Y') : '';
        $this->editBuyerSubcategory = isset($rfn->rfnItem->subcategory_id) ? $rfn->rfnItem->subcategory_id : '';
        $this->editProductId = isset($rfn->rfnItem->product_id) ? $rfn->rfnItem->product_id : '';
        $this->editBuyerProduct = isset($rfn->rfnItem->product_name) ? $rfn->rfnItem->product_name : '';
        $this->editBuyerProductUnit = isset($rfn->rfnItem->unit_id) ? $rfn->rfnItem->unit_id : '';
        $this->editBuyerProductQuantity = isset($rfn->rfnItem->quantity) ? $rfn->rfnItem->quantity : '';
        $this->editProductDescription = isset($rfn->rfnItem->item_description) ? $rfn->rfnItem->item_description : '';
    }

    /**
     * Event for when category change
     */
    public function categoryChange()
    {
        if (!empty($this->editBuyerCategory)) {
            $this->subCategories = SubCategory::where('category_id', $this->editBuyerCategory)->where('is_deleted', 0)->where('status', 1)->get();
        }
        $this->products = $this->editBuyerSubcategory = $this->editBuyerProduct = ''; //Reset depended fields after change
    }

    /**
     * Event for when sub-category change
     */
    public function subcategoryChange()
    {
        if (!empty($this->editBuyerSubcategory)) {
            $this->products = Product::where('subcategory_id', $this->editBuyerSubcategory)->where('is_deleted', 0)->where('status', 1)->get();
            $this->products = $this->products->count() != 0 ? $this->products : '';
        }
        $this->editBuyerProduct = ''; //Reset depended fields after change

    }

    /**
     * Product search
     */
    public function productSearch()
    {
        if (!empty($this->editBuyerSubcategory) && !empty($this->editBuyerProduct)) {
            $this->products = Product::where('subcategory_id', $this->editBuyerSubcategory)->where('is_deleted', 0)->where('status', 1)
                ->where('name','like','%'.$this->editBuyerProduct.'%')->get();
        } else if (!empty($this->editBuyerSubcategory) && empty($this->editBuyerProduct)){
            $this->subcategoryChange();
        }
        $this->productResultShow = true;

    }

    /**
     * Set product on click of results
     * @param null $term
     */
    public function setProduct($term=null)
    {
        $this->editBuyerProduct = $term;
        $this->productResultShow = false;
        $this->resetValidation('editBuyerProduct');
        $this->resetErrorBag('editBuyerProduct');
    }

    /**
     * Hide product results
     */
    public function hideProductResult()
    {
        $this->productResultShow = false;
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

    /**
     * Reset Form fields
     */
    public function resetFields()
    {
        $this->editBuyerCategory =  $this->editBuyerSubcategory = $this->editBuyerProduct = $this->editBuyerProductDescription = $this->editBuyerProductUnit = "";
        $this->editBuyerProductQuantity = $this->editProductDescription = $this->editBuyerComment = $this->editExpectedDate = $this->editStartDate = $this->editEndDate = $this->editproductSearch = "";
    }

    /**
     * Set the data from other sources
     * @param $data
     */
    public function setOtherSource()
    {
        $this->subCategories = !empty($this->editBuyerCategory) ? (SubCategory::where('category_id', $this->editBuyerCategory)->where('is_deleted', 0)->where('status', 1)->get()) : '';
        $this->products = !empty($this->editBuyerSubcategory) ? (Product::where('subcategory_id', $this->editBuyerSubcategory)->where('is_deleted', 0)->where('status', 1)
            ->where('name','like','%'.$this->editBuyerProduct.'%')->get()) : '';
    }

}

<?php

namespace App\Http\Livewire\Buyer\Rfn;

use App\Http\Controllers\Buyer\RFN\GlobalRfnController;
use App\Http\Controllers\Buyer\RFN\RfnController;
use App\Http\Controllers\ProductController;
use App\Models\Category;
use App\Models\Product;
use App\Models\Rfn;
use App\Models\SubCategory;
use App\Models\Unit;
use Carbon\Carbon;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Str;
use Livewire\Component;

class CreateRfn extends Component
{
    public $buyerCategory="", $buyerSubcategory="", $buyerProduct, $buyerProductDescription,
        $buyerProductUnit="", $buyerProductQuantity, $productDescription, $buyerComment, $expectedDate, $startDate, $endDate,
        $productId = "", $rfnId = "", $productSearch = ""; // Form Variables

    protected $searchLength = 3, $enableSearch = false;

    public $subCategories, $products, $searchProductResult; // Select List variables

    public $productResultShow = false;

    public $formType; // 1 RFN, 2 Global RFN

    protected $listeners = ['fadeProductResult' => 'hideProductResult',
        'resetError' => 'resetError', 'configure' => 'configure', 'storeRfn' => 'storeRfn', 'setData' => 'setOtherSource'];

    protected function rules()
    {

        $rules =  [
            'buyerCategory'             =>  'required',
            'buyerSubcategory'          =>  'required',
            'buyerProduct'              =>  'required|max:5000',
            'productDescription'        =>  'required|max:5000',
            'buyerComment'              =>  'max:5000',
            'buyerProductUnit'          =>  'required'
        ];

        // Dynamic rules
        if ($this->formType == Rfn::RFN) {
            $rules['expectedDate'] = 'required';
            $rules['buyerProductQuantity'] =  'required|max:255|min:0|not_in:0';

        } else if ($this->formType == Rfn::GLOBALRFN) {
            $rules['buyerProductQuantity'] =  'max:255';
            $rules['startDate'] = 'required|before:endDate';
            $rules['endDate'] = 'required|after:startDate';
        }

        return $rules;

    }

    protected function messages()
    {
        return [
            'buyerCategory.required'            =>  __('validation.custom.buyerCategory.required'),
            'buyerProduct.required'             =>  __('validation.custom.buyerProduct.required'),
            'buyerSubcategory.required'         =>  __('validation.custom.buyerSubcategory.required'),
            'buyerProductQuantity.required'     =>  __('validation.custom.buyerProductQuantity.required'),
            'buyerProductQuantity.not_in'       =>  __('validation.custom.buyerProductQuantity.not_zero'),
            'productDescription.required'       =>  __('validation.custom.productDescription.required'),
            'buyerComment.max'                  =>  __('validation.custom.buyerComment.max'),
            'expectedDate.required'             =>  __('validation.custom.expectedDate.required'),
            'startDate.required'                =>  __('validation.custom.startDate.required'),
            'endDate.required'                  =>  __('validation.custom.endDate.required'),
            'startDate.before'                  =>  __('validation.custom.startDate.before'),
            'endDate.after'                     =>  __('validation.custom.endDate.after'),
            'buyerProductUnit.required'         =>  __('validation.custom.buyerProductUnit.required'),
            'buyerProduct.max'                  =>  __('validation.custom.buyerProduct.max'),
            'buyerProductQuantity.max'          =>  __('validation.custom.buyerProductQuantity.max'),
            'productDescription.max'            =>  __('validation.custom.productDescription.max'),

        ];
    }

    public function mount($formType, $rfnId = '')
    {
        $this->rfnId = $rfnId;
        $this->configure($formType);
    }

    /**
     * Configure the component
     *
     * @param $formType
     */
    public function configure($formType)
    {
        $this->formType = $formType;
        $this->resetError();
        $this->resetFields();
    }

    public function render()
    {
        $data = $this->getData();

        return view('livewire.buyer.rfn.create-rfn')->with($data);
    }

    /**
     * Store RFN
     */
    public function storeRfn()
    {
        $validateData = $this->validate();
        $data = collect();
        $data->type = $this->formType;
        $data->category_id = $validateData['buyerCategory'];
        $data->subcategory_id = $validateData['buyerSubcategory'];
        $data->product_name = $validateData['buyerProduct'];
        $data->quantity = $validateData['buyerProductQuantity'];
        $data->unit_id = $validateData['buyerProductUnit'];
        $data->item_description = $validateData['productDescription'];
        $data->comment = $validateData['buyerComment'];

        if ($this->formType == Rfn::RFN) {

            $data->expected_date = $validateData['expectedDate'];
            $response = (new RfnController())->storeRfn($data);

        } else if ($this->formType == Rfn::GLOBALRFN) {

            $data->start_date = $validateData['startDate'];
            $data->end_date = $validateData['endDate'];
            $response = (new GlobalRfnController())->storeGlobalRfn($data);
        }

        $message = $response->getOriginalContent();

        if ($message['success']) {
            $this->dispatchBrowserEvent('pnotify', [
                'text' => $message['message'],
                'type' => 'success',
                'styling' => 'bootstrap3',
                'animateSpeed' => 'fast',
                'delay' => 3000,
                'modal' => false,
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
                'modal' => false,
                'success' => false
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
            'products' => $this->products
        ];

    }

    /**
     * Event for when category change
     */
    public function categoryChange()
    {
        if (!empty($this->buyerCategory)) {
            $this->subCategories = SubCategory::where('category_id', $this->buyerCategory)->where('is_deleted', 0)->where('status', 1)->get();
        }
        $this->products = $this->buyerSubcategory = $this->buyerProduct = ''; //Reset depended fields after change
    }

    /**
     * Event for when sub-category change
     */
    public function subcategoryChange()
    {
        if (!empty($this->buyerSubcategory)) {
            $this->products = Product::where('subcategory_id', $this->buyerSubcategory)->where('is_deleted', 0)->where('status', 1)->get();
            $this->products = $this->products->count() != 0 ? $this->products : '';
        }
        $this->buyerProduct = ''; //Reset depended fields after change

    }

    /**
     * Product search
     */
    public function productSearch()
    {
        if (!empty($this->buyerSubcategory) && !empty($this->buyerProduct)) {
            $this->products = Product::where('subcategory_id', $this->buyerSubcategory)->where('is_deleted', 0)->where('status', 1)
                ->where('name','like','%'.$this->buyerProduct.'%')->get();
        } else if (!empty($this->buyerSubcategory) && empty($this->buyerProduct)){
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
        $this->buyerProduct = $term;
        $this->productResultShow = false;
        $this->resetValidation('buyerProduct');
        $this->resetErrorBag('buyerProduct');
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
        $this->buyerCategory =  $this->buyerSubcategory = $this->buyerProduct = $this->buyerProductDescription = $this->buyerProductUnit = "";
        $this->buyerProductQuantity = $this->productDescription = $this->buyerComment = $this->expectedDate = $this->startDate = $this->endDate = $this->productSearch = "";
    }


    /**
     * Set the data from other sources
     * @param $data
     */
    public function setOtherSource()
    {
        $this->subCategories = !empty($this->buyerCategory) ? (SubCategory::where('category_id', $this->buyerCategory)->where('is_deleted', 0)->where('status', 1)->get()) : '';
        $this->products = !empty($this->buyerSubcategory) ? (Product::where('subcategory_id', $this->buyerSubcategory)->where('is_deleted', 0)->where('status', 1)
            ->where('name','like','%'.$this->buyerProduct.'%')->get()) : '';
    }

}

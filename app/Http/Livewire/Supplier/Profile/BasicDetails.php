<?php

namespace App\Http\Livewire\Supplier\Profile;

use App\Models\CompanyDetails;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Livewire\Component;

class BasicDetails extends Component
{
    public $companyDetails;
    public $user;
    public $supplierId;
    public $supplier;
    public $updateMode = false;

    protected $listeners = [
        'get-basic' => 'getBasic'
    ];
    public function mount($supplierId = null)
    {
        $this->supplierId = $supplierId;
        $this->setSupplier();
        $this->setCompanyDetails();
        $this->setMode();
    }



    protected function setSupplier()
    {
        if (!empty($this->supplierId)) {
            try {
                $this->supplier = Supplier::with(['user','user.company'])->where('id', $this->supplierId)->first();
            } catch (\Exception $e) {
                $this->addError('error', __('profile.something_went_wrong'));
            }
        }
    }

    protected function setCompanyDetails()
    {
        $this->companyDetails = isset($this->supplierId) ? CompanyDetails::where('model_id',$this->supplierId)->first() : '';
    }

    protected function setMode()
    {
        $this->updateMode = !empty($this->companyDetails) ? true : false;
    }

    public function render()
    {
        $supplier = $this->supplier;
        return view('livewire.supplier.profile.basic-details',compact(['supplier']));
    }
    public function getBasic($supplierId)
    {
        $this->mount($supplierId);
    }
}

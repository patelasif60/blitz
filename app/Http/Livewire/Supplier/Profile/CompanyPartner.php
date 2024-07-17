<?php

namespace App\Http\Livewire\Supplier\Profile;

use Livewire\Component;

class CompanyPartner extends Component
{
    public $supplierId;
    protected $listeners = [
        'get-CompanyPartner' => 'getCompanyPartner'
    ];
    public function mount($supplierId = null)
    {
        $this->supplierId = $supplierId;
    }

    public function render()
    {
        return view('livewire.supplier.profile.company-partner');
    }
    public function getCompanyPartner($supplierId)
    {
        $this->mount($supplierId);
    }
}

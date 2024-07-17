<?php

namespace App\Http\Livewire\Supplier\Profile;

use Livewire\Component;

class ClientSupplierPortfolio extends Component
{
    public $supplier_id;
    public $companyDetails;

    public function render()
    {
        return view('livewire.supplier.profile.client-supplier-portfolio');
    }
}

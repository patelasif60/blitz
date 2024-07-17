<?php

namespace App\Http\Livewire\Supplier\Profile;

use Livewire\Component;

class Portfolio extends Component
{
    public $company_user_type;
    public $slug;
    public $supplierId;
    public $typeName;
    protected $listeners = [
        'get-portfolio' => 'getportfolio'
    ];
    public function mount($supplierId = null)
    {
        $this->supplierId = $supplierId;
    }

    public function render()
    {
        return view('livewire.supplier.profile.portfolio');
    }
    public function getportfolio($supplierId)
    {
        $this->mount($supplierId);
    }
}

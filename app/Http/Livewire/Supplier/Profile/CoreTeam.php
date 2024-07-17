<?php

namespace App\Http\Livewire\Supplier\Profile;

use Livewire\Component;

class CoreTeam extends Component
{
    public $company_user_type;
    public $slug;
    public $typeName;
    public $supplierId;
    public $supplier;
    protected $listeners = [
        'get-coreTeam' => 'getcoreTeam'
    ];

    public function mount($supplierId = null)
    {
        $this->supplierId = $supplierId;
    }

    public function render()
    {
        return view('livewire.supplier.profile.core-team');
    }
    public function getcoreTeam($supplierId)
    {
        $this->mount($supplierId);
    }
}

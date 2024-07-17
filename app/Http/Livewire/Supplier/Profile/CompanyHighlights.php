<?php

namespace App\Http\Livewire\Supplier\Profile;

use Livewire\Component;

class CompanyHighlights extends Component
{

    public $supplierId;
    public $supplier;
    protected $listeners = [
        'get-highlights' => 'gethighlights'
    ];

    public function mount($supplierId = null)
    {
        $this->supplierId = $supplierId;
    }
    public function render()
    {
        return view('livewire.supplier.profile.company-highlights');
    }
    public function gethighlights($supplierId)
    {
        $this->mount($supplierId);
    }
}

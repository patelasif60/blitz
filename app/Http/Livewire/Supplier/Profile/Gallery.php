<?php

namespace App\Http\Livewire\Supplier\Profile;

use Livewire\Component;

class Gallery extends Component
{
    public $supplierId;
    protected $listeners = [
        'get-gallery' => 'getgallery'
    ];
    public function mount($supplierId = null)
    {
        $this->supplierId = $supplierId;
    }

    public function render()
    {
        return view('livewire.supplier.profile.gallery');
    }
    public function getgallery($supplierId)
    {
        $this->mount($supplierId);
    }
}

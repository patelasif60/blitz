<?php

namespace App\Http\Livewire\Supplier\Profile;

use Livewire\Component;

class Testimonial extends Component
{
    public $supplierId;
    protected $listeners = [
        'get-Testimonial' => 'getTestimonial'
    ];
    public function mount($supplierId = null)
    {
        $this->supplierId = $supplierId;
    }

    public function render()
    {
        return view('livewire.supplier.profile.testimonial');
    }
    public function getTestimonial($supplierId)
    {
        $this->mount($supplierId);
    }
}

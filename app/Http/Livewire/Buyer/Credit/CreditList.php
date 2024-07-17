<?php

namespace App\Http\Livewire\Buyer\Credit;

use App\Http\Controllers\Buyer\Credit\CreditController;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Livewire\Component;

class CreditList extends Component
{
    public $perPage = 10;

    public $activeAccordion = '';

    public $hasMore = true;

    protected $listeners = [
        'load-list' => 'load'
    ];

    /**
     * Credit Order List resource
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render()
    {
        $query = (new CreditController())->creditList();

        $allOrders = clone$query;

        $this->hasMore = $this->perPage > $allOrders->count() ? false : true;

        $orders = $query->take($this->perPage)->get();

        // Make loan apply id encrypted
        $orders->map(function($order){
            $order->enctypeLoanId = Crypt::encrypt($order->loanApply->id);
            return $order;
        });

        $this->emit('loanListStore');

        return view('livewire.buyer.credit.credit-list')->with(compact('orders'));
    }

    /**
     *  Per page loading
     */
    public function load()
    {
        $this->perPage+= 5;
    }
}

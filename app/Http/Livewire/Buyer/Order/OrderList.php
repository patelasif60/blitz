<?php

namespace App\Http\Livewire\Buyer\Order;

use Livewire\Component;
use App\Services\OrderService;

class OrderList extends Component
{
    public $page;
    public $perPage;
    protected $services;
    private $favrfq =2;
    protected $listeners = ['search'];
    public $searchedData = '';
    public $orderStatus = '';

    public function mount($page, $perPage,$favrfq) 
    {
        $this->page = $page ?? 1;
        $this->perPage = $perPage ?? 1;
        $this->services = new OrderService();
        $this->favrfq = $favrfq;
    }

    public function search($searchedText, $status = null)
    {
        $this->searchedData = $searchedText;
        $this->orderStatus = $status;
        $this->page = 1;
        $this->services = new OrderService();
        $this->favrfq= 2;
    }

    public function render()
    {
        $orders = $this->services->getRfqList($this->perPage,$this->page,$this->favrfq,$this->searchedData,$this->orderStatus);
        $favrfq = $this->favrfq;
        $searchedText = $this->searchedData;
        $status = $this->orderStatus;
        return view('livewire.buyer.order.order-list',compact('orders','favrfq','searchedText','status'));
    }

}
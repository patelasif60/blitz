<?php

namespace App\Http\Livewire\Buyer\Loadmore;

use Livewire\Component;
use App\Services\RfqService;
use App\Services\OrderService;

class LoadMoreItems extends Component
{
    public $page;
    public $perPage;
    private $loadMore = false;
    private $favrfq;
    protected $listeners = ['loadMoreItems','favrfq','search'];
    protected $services;
    protected $orderServices;
    public $searchedData = '';
    public $rfqstatus = '';

    //intilize function
    public function mount($page, $perPage,$favrfq,$searchedText,$status,$total,$currentRecord) 
    {
        $this->page = $page ?? 1;
        $this->perPage = $perPage ?? 10;
        $this->favrfq = $favrfq;
        $this->searchedData = $searchedText;
        $this->rfqstatus = $status;
        $this->falg = 1;
        $this->total = $total;
        $this->currentRecord = $currentRecord;
    }
    // load more items
    public function loadMoreItems($favrfq,$searchedText,$status) 
    {
        $this->loadMore = true;
        $this->page += 1;
        $this->favrfq = $favrfq;
        $this->searchedData = $searchedText;
        $this->rfqstatus = $status;
        $this->falg = 1;

    }
    // intilize on favourite rfq
    public function favrfq($favrflag,$searchedText, $status = null)
    {
        $this->favrfq = $favrflag;
        $this->page = 1;
        $this->searchedData = $searchedText;
        $this->rfqstatus = $status;
        $this->falg = 2;
    }
    // search rfq
    public function search($searchedText, $status = null,$favrflag=0)
    {
        $this->searchedData = $searchedText;
        $this->rfqstatus = $status;
        $this->page = 1;
        $this->favrfq= $favrflag;
        $this->falg = 2;   
    }
    // render htm
    public function render()
    { 
        $this->services = new RfqService();
        $this->orderServices = new OrderService();
        $favrfq = $this->favrfq;
        $searchedText = $this->searchedData;
        $status = $this->rfqstatus;
        $flag = $this->falg; 
       

        if( $this->loadMore) {
            if($this->favrfq == 2)
            {
                $orders = $this->orderServices->getRfqList($this->perPage,$this->page,$this->favrfq,$this->searchedData,$status);
                return view('livewire.buyer.order.order-list',compact('orders','favrfq','searchedText','status'));
            }
            else{

                $rfqs = $this->services->getRfqList($this->perPage,$this->page,$this->favrfq,$this->searchedData,$this->rfqstatus);
                return view('livewire.buyer.rfq.rfq-list',compact('rfqs','favrfq','searchedText','status','flag'));
            }
        } else {
            if($this->favrfq == 2)
            {
                $orders = $this->orderServices->getRfqList($this->perPage,$this->page,$this->favrfq,$this->searchedData,$status);
                $this->total = $orders->totalRecord;
                $this->currentRecord = $orders->currentRecord;
            }
            else{
                $rfqs = $this->services->getRfqList($this->perPage,$this->page,$this->favrfq,$this->searchedData,$this->rfqstatus);
                $this->total = $rfqs->totalRecord;
                $this->currentRecord = $rfqs->currentRecord;
            } 
            return view('livewire.buyer.loadmore.load-more-items',compact('favrfq','searchedText','status'));
        }
    }
}
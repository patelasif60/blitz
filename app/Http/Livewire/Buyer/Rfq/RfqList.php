<?php

namespace App\Http\Livewire\Buyer\Rfq;

use Livewire\Component;
use App\Services\RfqService;

class RfqList extends Component
{
    public $page;
    public $perPage;
    protected $services;
    private $favrfq =0;
    protected $listeners = ['favrfq','search'];
    public $searchedData = '';
    public $rfqstatus = '';

     //intilize function
    public function mount($page, $perPage,$favrfq) 
    {
        $this->page = $page ?? 1;
        $this->perPage = $perPage ?? 1;
        $this->services = new RfqService();
        $this->favrfq = $favrfq;
        $this->falg = 1;
    }
    // intilize on favourite rfq
    public function favrfq($favrflag,$searchedText, $status = null)
    {
        $this->favrfq = $favrflag;
        $this->services = new RfqService();
        $this->searchedData = $searchedText;
        $this->rfqstatus = $status;
        $this->falg = 2;
    }
     // search rfq
    public function search($searchedText, $status = null,$favrflag)
    {
        $this->searchedData = $searchedText;
        $this->rfqstatus = $status;
        $this->page = 1;
        $this->services = new RfqService();
        $this->favrfq= $favrflag;
        $this->falg = 2;
    }
    // render html
    public function render()
    {
        $rfqs = $this->services->getRfqList($this->perPage,$this->page,$this->favrfq,$this->searchedData,$this->rfqstatus);
        $favrfq = $this->favrfq;
        $searchedText = $this->searchedData;
        $status = $this->rfqstatus;
        $flag = $this->falg; 
        return view('livewire.buyer.rfq.rfq-list',compact('rfqs','favrfq','searchedText','status','flag'));
    }
}
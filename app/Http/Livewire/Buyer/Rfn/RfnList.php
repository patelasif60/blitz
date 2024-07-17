<?php

namespace App\Http\Livewire\Buyer\Rfn;

use App\Http\Controllers\Buyer\RFN\GlobalRfnController;
use App\Http\Controllers\Buyer\RFN\RfnController;
use App\Models\Rfn;
use App\Models\RfnItems;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class RfnList extends Component
{

    protected $listeners = ['createGlobalRfn' => 'createGlobalRfn','rfnlisting' => 'render', 'generateRfq' => 'generateRfq','cancelRfn' => 'cancelRfn','refreshData'=> 'refreshData'];
    public $rfnList;
    public function render()
    {
        $rfnListall  = (new RfnController())->getAllRfnList();
        $this->rfnList = $rfnListall;
        return view('livewire.buyer.rfn.rfn-list')->with(compact(['rfnListall']));
    }

    /**
     * Convert RFN to Global RFN
     * @param $rfnId - RFN id for convert to Global RFN
     */
    public function createGlobalRfn($rfnId)
    {
        if (!empty($rfnId)) {
            (new GlobalRfnController())->createGlobalRfn($rfnId);
        }
    }

    /**
     * RFN List Single Generate RFQ Button
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function generateRfq($id)
    {
        $response = (new RfnController())->singleRfnToRfq($id);

        $message = $response->getOriginalContent();

        if ($message['success']) {

            return redirect()->to('/dashboard')->with('isRfnEnable');

        } else {
            $this->dispatchBrowserEvent('pnotify', [
                'text' => $message['message'],
                'type' => 'warning',
                'styling' => 'bootstrap3',
                'animateSpeed' => 'fast',
                'delay' => 3000,
                'component' => 'generateRfn',
                'modal' => false,
                'success' => false
            ]);
        }
    }
    /**
     * Cancel Global RFN
     *
     * @param $rfnId
     */
    public function cancelRfn($rfnId)
    {
        if (!empty($rfnId)) {
            $response = (new GlobalRfnController())->cancelGlobalRfn($rfnId);
            $message = $response->getOriginalContent();
            if ($message['success']) {
                $this->dispatchBrowserEvent('pnotify', [
                    'text' => $message['message'],
                    'type' => 'success',
                    'styling' => 'bootstrap3',
                    'animateSpeed' => 'fast',
                    'delay' => 3000
                ]);
            } else {
                $this->dispatchBrowserEvent('pnotify', [
                    'text' => $message['message'],
                    'type' => 'alert',
                    'styling' => 'bootstrap3',
                    'animateSpeed' => 'fast',
                    'delay' => 3000
                ]);
            }
        }
    }

    /**
     * Rfresh RFN Data
     *
     */
    public function refreshData(){
        $rfnListall = $this->rfnList;
        return view('livewire.buyer.rfn.rfn-list')->with(compact(['rfnListall']));
    }
}

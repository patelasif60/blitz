<?php

namespace App\Http\Livewire\Buyer\Globalrfn;

use App\Http\Controllers\Buyer\RFN\GlobalRfnController;
use App\Models\Rfn;
use App\Models\RfnResponse;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class GlobalrfnList extends Component
{
    protected $listeners = ['globalRfnlisting' => 'render','cancelGlobalRfn' => 'cancelGlobalRfn','deleteRfnRequest' => 'deleteRfnRequest','generateMultiRfq'=>'generateMultiRfq','globalRefreshData'=> 'globalRefreshData'];
    public $globalRfnList;

    public function render()
    {
        $rfnList  = (new GlobalRfnController())->getAllGlobalRfnList();
        $this->globalRfnList = $rfnList;
        return view('livewire.buyer.globalrfn.globalrfn-list')->with(compact('rfnList'));
    }
    /**
     * RFN List Single Generate RFQ Button
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function generateMultiRfq($rfnId,$rfnResponseId)
    {
        $response = (new GlobalRfnController())->globalRfnToRfq($rfnId,$rfnResponseId);

        $message = $response->getOriginalContent();

        if ($message['success']) {

            return redirect()->to('/dashboard');

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
    public function cancelGlobalRfn($rfnId)
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

    public function deleteRfnRequest($rfnId,$rfnReaponseId)
    {
            if (!empty($rfnReaponseId)) {
                $response = (new GlobalRfnController())->deleteRfnRequest($rfnId,$rfnReaponseId);
                $message = $response->getOriginalContent();
                if ($message['success']) {
                    $this->dispatchBrowserEvent('pnotify', [
                        'text' => $message['message'],
                        'type' => 'warning',
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
     * Global Rfn refresh data
     */
    public function globalRefreshData()
    {
        $rfnList = $this->globalRfnList ;
        return view('livewire.buyer.globalrfn.globalrfn-list')->with(compact('rfnList'));
    }
}

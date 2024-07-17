<?php

namespace App\Http\Livewire\Admin\Limit;

use App\Http\Controllers\Credit\Common\LimitController;
use Illuminate\Support\Facades\Crypt;
use Livewire\Component;

class LimitApplyActivity extends Component
{
    public $limit;

    public function mount($limit)
    {
        $this->limit = $limit;
    }

    public function render()
    {
        $response = json_decode((new LimitController())->limitActivity($this->limit)->getContent());

        $activity = (isset($response->data) && !empty($response->data)) ? $response->data->activity : '';

        $limitApplication = (isset($response->data) && !empty($response->data)) ? $response->data->limit : '';

        return view('livewire.admin.limit.limit-apply-activity')->with(compact(['activity','limitApplication']));
    }
}

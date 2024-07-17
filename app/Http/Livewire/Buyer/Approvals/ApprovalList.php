<?php

namespace App\Http\Livewire\Buyer\Approvals;

use App\Models\Company;
use App\Models\UserQuoteFeedback;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ApprovalList extends Component
{
    public $perPage = 1;

    public $activeAccordion = '';

    public $hasMore = true;

    public $searchedData = '';

    public $quoteStatus = '';

    protected $listeners = [
        'load-list' => 'load',
        'search-approval-data' => 'setSearch',
    ];

    public function render()
    {
        $query = UserQuoteFeedback::getAllRfqQuoteApproval();

        $allApproval = clone$query;

        $this->hasMore = $this->perPage > $allApproval->count() ? false : true;

        $query = $this->getSearch($query);

        $rfqWithQuoteApproval = $query->orderBy('rfq_id','DESC')->get()->groupBy('rfq_id');

        $auth = Auth::user();
        return view('livewire.buyer.approvals.approval-list')->with(compact(['rfqWithQuoteApproval','auth']));;
    }

    /**
    *  Per page loading
    **/
    public function load()
    {
        $this->perPage = $this->perPage + 1;
    }

    /**
     * set search parameter
     * @param $searchedText
     * @param null $quoteStatus
     */
    public function setSearch($searchedText, $quoteStatus = null) {
        $this->searchedData = $searchedText;
        $this->quoteStatus = $quoteStatus;
    }

    /**
     * Search Approval data for rfq,quote and approved/reject status
     * @param $query - prepare query
     * @return mixed
     */

    public function getSearch($query)
    {
        $SearchText = $this->searchedData;
        $quoteStatus = trim($this->quoteStatus);

        if (!empty($SearchText) || !empty($quoteStatus)) {
            if (!empty($SearchText) && (!empty($quoteStatus) && $quoteStatus == "all")) {  // search text box only
                $query = $query->where(function ($query) use ($SearchText) {
                    $query->where('rfq_id', 'LIKE', '%' . $SearchText . '%')
                        ->orWhere('quote_id', 'LIKE', '%' . $SearchText . '%')
                        ->orWhereHas('rfqs.rfqProducts', function ($q) use ($SearchText) {
                            $q->where('category', 'LIKE', '%' . $SearchText . '%');
                        })
                        ->orWhereHas('rfqs.rfqProducts', function ($q) use ($SearchText) {
                            $q->where('product', 'LIKE', '%' . $SearchText . '%');
                        })
                        ->orWhereHas('rfqs.rfqProducts', function ($q) use ($SearchText) {
                            $q->where('sub_category', 'LIKE', '%' . $SearchText . '%');
                        });
                });
            }
            if (empty($SearchText) && (!empty($quoteStatus) && $quoteStatus != "all")) { //search filter dropdown only
                $quoteStatusVal = ($quoteStatus == '3') ? $quoteStatus = 0 : $quoteStatus; // 1- Approved, 2- rejected and 3 -pending
                $query = $query->where('feedback', '=', $quoteStatusVal);
            }
            if (!empty($SearchText) && (!empty($quoteStatus) && $quoteStatus != "all")) { //search filter dropdown and text box  both
                $quoteStatusVal = ($quoteStatus == '3') ? $quoteStatus = 0 : $quoteStatus; // 1- Approved, 2- rejected and 3 -pending
                $query->where('feedback', '=', $quoteStatusVal);
                $query = $query->where(function ($query) use ($SearchText) {
                    $query->where('rfq_id', 'LIKE', '%' . $SearchText . '%')
                        ->orWhere('quote_id', 'LIKE', '%' . $SearchText . '%')
                        ->orWhereHas('rfqs.rfqProducts', function ($q) use ($SearchText) {
                            $q->where('category', 'LIKE', '%' . $SearchText . '%');
                        })
                        ->orWhereHas('rfqs.rfqProducts', function ($q) use ($SearchText) {
                            $q->where('product', 'LIKE', '%' . $SearchText . '%');
                        })
                        ->orWhereHas('rfqs.rfqProducts', function ($q) use ($SearchText) {
                            $q->where('sub_category', 'LIKE', '%' . $SearchText . '%');
                        });
                });
            }

        }
        return $query;
    }

}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupTransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:create group transaction list|edit group transaction list|delete group transaction list|publish group transaction list|unpublish group transaction list', ['only'=> ['index']]);
        $this->middleware('permission:create group transaction list', ['only' => ['create']]);
        $this->middleware('permission:edit group transaction list', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete group transaction list', ['only' => ['delete']]);
    }

    public function index(){

        $transactions = DB::select('SELECT (SELECT COUNT(id) FROM group_transactions where quote_id=t1.quote_id) as generated_link_count, t1.*,suppliers.name as supplier_company,orders.order_number,
                                            orders.order_status,orders.is_credit,disbursements.status as disb_status FROM group_transactions t1
                                            LEFT JOIN quotes ON t1.quote_id = quotes.id
                                            LEFT JOIN orders ON t1.order_id = orders.id
                                            LEFT JOIN group_transactions t2 ON t1.id < t2.id AND t1.quote_id = t2.quote_id
                                            LEFT JOIN suppliers ON t1.user_id = suppliers.xen_platform_id
                                            LEFT JOIN disbursements ON t1.order_id = disbursements.order_id AND (disbursements.status!="FAILED" || disbursements.status is NULL)
                                            WHERE t2.id is NULL;');

        return view('admin/group_transaction/index', ['transactions' => $transactions]);
    }

}

<?php

namespace App\Export;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class QuoteExport implements FromCollection
{
    public function collection()
    {   
        dd('stop');
        $orders = DB::table('orders')
                ->leftJoin('order_credit_days as ocd', 'orders.id', '=', 'ocd.order_id')
                ->join('order_status', 'orders.order_status', '=', 'order_status.id')
                ->join('quotes', 'orders.quote_id', '=', 'quotes.id')
                ->join('suppliers', 'quotes.supplier_id', '=', 'suppliers.id')
                ->join('rfqs', 'quotes.rfq_id', '=', 'rfqs.id')
                ->join('rfq_products', 'rfq_products.rfq_id', '=', 'rfqs.id')
                ->join('users', 'orders.user_id', '=', 'users.id')
                ->join('user_companies', 'users.id', '=', 'user_companies.user_id')
                ->join('companies', 'user_companies.company_id', '=', 'companies.id')
                ->join('products', 'quotes.product_id', '=', 'products.id')
                ->join('sub_categories', 'products.subcategory_id', '=', 'sub_categories.id')
                ->join('categories', 'sub_categories.category_id', '=', 'categories.id')
                ->orderBy('id', 'desc')
                ->get(['orders.*', 'ocd.request_days', 'rfqs.id as rfq_id', 'ocd.approved_days', 'ocd.status as request_days_status', 'order_status.name as order_status_name', 'quotes.quote_number', 'quotes.final_amount', 'rfqs.reference_number as rfq_reference_number', 'users.firstname', 'users.lastname', 'companies.name as company_name', 'products.name as product_name', 'rfq_products.product_description as product_description', 'sub_categories.name as sub_category_name', 'categories.name as category_name', 'suppliers.contact_person_name as supplier_name', 'suppliers.name as supplier_company_name']);
        //return Order::all();
        return $orders;
    }
}

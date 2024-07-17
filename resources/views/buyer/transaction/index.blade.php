@extends('buyer.layouts.frontend.frontend_layout')

@section('css')
    <link href="{{ URL::asset('front-assets/js/datatables-bs4/dataTables.bootstrap4.css') }}" rel='stylesheet' />
@endsection

@section('content')
    <div class="col-lg-8 col-xl-9 py-2" id="mainContentSection">
        <div class="header_top d-flex align-items-center">
            <h1 class="mb-0">{{ __('dashboard.transaction') }}</h1>
            <a href="{{url()->previous()}}" class="btn btn-warning ms-auto btn-sm" style="padding-top: .1rem; padding-bottom: .1rem;">
                <img src="{{asset('front-assets/images/icons/angle-left.png')}}" alt=""> {{__('admin.back')}}
            </a>
        </div>
        <div class="row gy-3 gx-2">
            <div class="col-md-12">
                <div class="col-md-12">
                    <div class="tablepermission p-1">
                        <table class="table table-responsive" style="width:100%"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- view loan modal -->
    <div class="modal fade" id="CalcModal" tabindex="-1" role="dialog"
         aria-labelledby="CalcModal" aria-hidden="true">
        <div class="modal-dialog modal-lg  modal-dialog-centered" role="document">
            <div class="modal-content">
            </div>
        </div>
    </div>

    <!-- view loan modal -->
@endsection

@section('script')
    <script type="text/javascript" src="{{ URL::asset('front-assets/js/datatables/jquery.dataTables.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('front-assets/js/datatables-bs4/dataTables.bootstrap4.js') }}"></script>

@endsection

@section('custom-script')
    <script type="text/javascript">
        /****************************************begin: Buyer Transactions***************************************/
        var SnippetTransactionsTable = function(){

            var rolesDatatable = function () {
                    $('.table').DataTable({
                        serverSide: !0,
                        paginate: !0,
                        lengthMenu: [
                            [5, 10, 25, 50],
                            [5, 10, 25, 50],
                        ],
                        scrollX:!0,
                        footer:!1,
                        ajax: {
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            url : "{{  route('credit.wallet.transactions.json')  }}",
                            method : "POST"
                        },
                        columns: [
                            {data: "loan_id", title: "{{ __('admin.loan_id') }}", sortable:!0},

                            {data: "order_number", title: "{{ __('admin.order_number') }}", sortable:!0},

                            {data: "paid_amount", title: "{{ __('admin.amount_paid') }}", sortable:!1},

                            {data: "paid_date", title: "{{ __('admin.paid_date') }}", sortable:!1},
                            {data: "action", title: "{{ __('admin.action') }}" , class: "text-nowrap text-end",sortable:!1}
                        ]
                    });
                };

            return {
                init: function () {
                    rolesDatatable()

                }
            }


        }(1);

        jQuery(document).ready(function(){
            SnippetTransactionsTable.init();

        });
        /****************************************end:Buyer Buyer Transactions***************************************/

        $(document).on('click', '.loanCalculation', function (e) {
            e.preventDefault();
            viewLoanDetails($(this).attr('data-id'));
        });
        function viewLoanDetails(quote_id){

            if (quote_id) {
                $("#CalcModal").find(".modal-content").html('');
                $.ajax({
                    url: "{{ route('settings.credit.loan-view-calculation','') }}" + "/" +
                        quote_id ,
                    type: 'GET',
                    success: function (successData) {
                        console.log(successData.loanView);
                        $("#CalcModal").find(".modal-content").html(successData.loanView);
                        $('#CalcModal').modal('show');
                    },
                    error: function () {
                        console.log('error');
                    }
                });
            }
        }

        SnippetLoanTab.init();
    </script>

@endsection



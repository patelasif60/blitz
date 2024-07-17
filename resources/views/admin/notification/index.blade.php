@extends('admin/adminLayout')
@section('content')

    <div class="card h-100 bg-transparent newtable_v2">
        <div class="card-body p-0">
            <div class="row">
                <div class="col-md-12 d-flex align-items-center">
                    <h4 class="card-title mb-0">{{ __('admin.notification')}}</h4>
                    <div class="ms-auto notification_select">
                        <select class="form-select form-control-sm" style="width: 120px;" onchange="getNotificationFilterData()" id="notification_category" name="notification_category">
                            <option value="">{{ __('admin.view_all') }}</option>
                            <option value="rfq">{{ __('admin.rfqs') }}</option>
                            <option value="quote">{{ __('admin.quotes') }}</option>
                            <option value="order">{{ __('admin.orders') }}</option>
                            <option value="loan">{{ __('admin.loans') }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-12"><hr></div>
                <div id="notificationLists">

                </div>
            </div>

        </div>
    </div>

    <div class="modal version2 fade" id="viewRfqModalnew" tabindex="-1" role="dialog"
         aria-labelledby="viewRfqModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            </div>
        </div>
    </div>
    <div class="modal version2 fade" id="viewRfqModal" tabindex="-1" role="dialog"
         aria-labelledby="viewRfqModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            </div>
        </div>
    </div>
    <!-- <div class="modal version2 fade" id="viewLoanModal" tabindex="-1" role="dialog"
         aria-labelledby="viewLoanModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            </div>
        </div>
    </div> -->
@stop

@push('bottom_scripts')
    <script>

        $(document).ready(function() {
            getNotificationFilterData();
            $(document).on('click', '.vieQuoteDetail', function(e) {
                e.preventDefault();
                var id = $(this).attr('data-id');
                if (id) {
                    $.ajax({
                        url: "{{ route('quote-detail', '') }}" + "/" + id,
                        type: 'GET',
                        success: function(successData) {
                            $('#viewRfqModalnew').find('.modal-content').html(successData.quoteHTML);
                            $('#viewRfqModalnew').modal('show');
                        },
                        error: function() {
                            console.log('error');
                        }
                    });
                }
            });

            $(document).on('click', '.viewRfqDetail', function(e) {

                $('#callRFQHistory').html('');
                $('#message').html('');
                e.preventDefault();
                var id = $(this).attr('data-id');
                if (id) {
                    $.ajax({
                        url: "{{ route('rfq-detail', '') }}" + "/" + id,
                        type: 'GET',
                        success: function(successData) {
                            $("#viewRfqModal").find(".modal-content").html(successData.rfqview);
                            $('#viewRfqModal').modal('show');
                        },
                        error: function() {
                            console.log('error');
                        }
                    });
                }
            });

            $(document).on('click', '.viewLoanDetail', function(e) {
                e.preventDefault();
                viewLoanDetails($(this).attr('data-id'));
            });

        });

        function getNotificationFilterData(){
                var notificationCategory = $('#notification_category').val();
                $('#notificationLists').html('');
                var data = {
                    "_token": "{{ csrf_token() }}",
                    'notificationCategory': notificationCategory
                }
                //console.log(data);
                $.ajax({
                url: '{{ route('get-notification-filter-data-ajax') }}',
                data: data,
                type: 'POST',
                success: function(successData) {
                    $('#notificationLists').html(successData.notificationFilterDataView);
                },
            });
        }
        
    </script>
@endpush

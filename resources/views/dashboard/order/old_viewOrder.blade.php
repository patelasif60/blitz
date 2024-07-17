<div class="header_top d-flex align-items-center">
    <h1 class="mb-0">My Orders</h1>
</div>

<div class="accordion" id="Order_accordian">
    @if (count($orders))
        @foreach ($orders as $order)
            <div class="accordion-item radius_1 mb-2">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button justify-content-between collapsed" type="button"
                        data-bs-toggle="collapse" data-bs-target="#collapse{{ $order->id }}" aria-expanded="true"
                        aria-controls="collapse{{ $order->id }}">
                        <div class="flex-grow-1 ">
                            {{ $order->category_name . ' - ' . $order->sub_category_name . ' - ' . $order->product_name }}
                            <span class="badge rounded-pill bg-primary mx-2"
                                id="orderStatusNameBlock{{ $order->id }}"> {{ $order->order_status_name }}</span>
                        </div>
                        <div class="font_sub px-3">Date: {{ date('d-m-Y H:i', strtotime($order->created_at)) }}</div>
                    </button>
                </h2>
                <div id="collapse{{ $order->id }}" class="accordion-collapse collapse" aria-labelledby="headingOne"
                    data-bs-parent="#Order_accordian">
                    <div class="accordion-body">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="row rfqform_view g-3">
                                    <div class="col-md-12">
                                        <h5 class="dark_blue mb-0">Order Details</h5>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Customer Name:</label>
                                        <div> {{ $order->firstname . ' ' . $order->lastname }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Order Number:</label>
                                        <div>{{ $order->order_number }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Company Name:</label>
                                        <div>{{ $order->company_name }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Mobile Number:</label>
                                        <div>{{ $order->rfq_mobile }}</div>
                                    </div>

                                </div>
                                <div class="card radius_1 my-3">
                                    <table class="table mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Description</th>

                                                <th width="20%" class="text-center text-nowrap">QTY</th>
                                                <th width="20%" class="text-end text-nowrap">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ $order->category_name . ' ' . $order->sub_category_name . ' ' . $order->product_name . ' ' . $order->product_description }}
                                                </td>

                                                <td class="text-center text-nowrap">
                                                    {{ $order->product_quantity . ' ' . $order->product_unit }}</td>
                                                <td class="text-end text-nowrap">
                                                    {{ $order->product_amount . ' Rp' }}</td>
                                            </tr>
                                            <tr class="bg-light">
                                                <td colspan="2" class="fw-bold">Total</td>
                                                <td class="text-end text-nowrap fw-bold">
                                                    {{ $order->product_final_amount . ' Rp' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row rfqform_view g-3">
                                            <div class="col-md-12">
                                                <h5 class="dark_blue mb-0">Address Details</h5>
                                            </div>
                                            <div class="col-md-12 font_sub">
                                                <address>
                                                    <p>{{ $order->address_line_1 }}<br>
                                                        {{ $order->address_line_2 }}<br>
                                                        {{ $order->city }}<br>
                                                        {{ $order->state }}<br>
                                                        {{ $order->pincode }}
                                                    </p>
                                                </address>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row rfqform_view g-3">
                                            <div class="col-md-12">
                                                <h5 class="dark_blue mb-0">Bank Details</h5>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Bank Name:</label>
                                                <div>BCA (PT. Bank Central Asia, Tbk)</div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Name:</label>
                                                <div>BCA (PT. Bank Central Asia, Tbk)</div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>A/C No:</label>
                                                <div>145-0099577</div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Bank Code: </label>
                                                <div>014</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 text-danger">
                                        <p><small>* All transaction cost will be paid by buyer.<br>
                                                * Order will be confirmed on receipt of the
                                                payment.</small></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="card radius_1">
                                    <div class="card-header bg-white">
                                        <h5 class="dark_blue d-flex align-items-center mb-0 justify-content-between">
                                            Status <div class="refreshicon"><a href="javascript:void(0);"
                                                    data-id="{{ $order->id }}" class="refreshOrderStautsData"><img
                                                        src="{{ URL::asset('front-assets/images/icons/icon_refresh.png') }}"
                                                        alt="refresh"></a>
                                            </div>
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <ul class="bullet-line-list" id="orderStatusViewBlock{{ $order->id }}">
                                            @php
                                                $hideStatus = ['QC Failed', 'QC Passed', 'Order Returned', 'Order Cancelled'];
                                                
                                            @endphp
                                            @foreach ($order->orderAllStatus->unique('status_name') as $orderStatus)
                                                @php
                                                    $flexClass = '';
                                                    $paymentDone = 1;
                                                    $qcPassed = 1;
                                                    $qcDisabled = 1;
                                                    $extraClass = '';
                                                    if (trim($orderStatus->status_name) == 'Payment Done' || trim($orderStatus->status_name) == 'Under QC') {
                                                        $flexClass = 'd-flex';
                                                    }
                                                    
                                                    if ($order->order_status >= $orderStatus->show_order_id && $orderStatus->order_track_id) {
                                                        $class = 'active';
                                                        if (trim($orderStatus->status_name) == 'Under QC') {
                                                            $extraClass = ' UndeQCBlock';
                                                            $qcPassed = 0;
                                                            if ($order->order_status == 9) {
                                                                $qcDisabled = 0;
                                                            }
                                                        }
                                                    } else {
                                                        if (trim($orderStatus->status_name) == 'Payment Done') {
                                                            $class = 'paymentDoneBlock';
                                                            $paymentDone = 0;
                                                        } else {
                                                            $class = '';
                                                        }
                                                    }
                                                    
                                                    if (trim($orderStatus->status_name) == 'Order Completed' || trim($orderStatus->status_name) == 'Order Returned') {
                                                        $extraClass = ' finalOrderStatusBlock';
                                                    }
                                                    if (trim($orderStatus->status_name) == 'Under QC') {
                                                        $extraClass = ' qcStatusBlock';
                                                    }
                                                @endphp
                                                @if (!in_array($orderStatus->status_name, $hideStatus))
                                                    @if (trim($orderStatus->status_name) == 'Order Completed')
                                                        @php
                                                            if ($order->order_status == 13 || $order->order_status == 12) {
                                                                $class = 'active';
                                                            } else {
                                                                $class = '';
                                                            }
                                                        @endphp

                                                        <li class="{{ $class . ' ' . $extraClass }}">
                                                            <h6 class="mb-0 {{ $flexClass }}">
                                                                <a
                                                                    href="javascript:void(0)">{{ $order->order_status == 13 ? 'Order Returned' : 'Order Completed' }}</a>
                                                            </h6>
                                                            <p>

                                                                <small>
                                                                    {{ $orderStatus->created_at && $class == 'active' ? date('d-m-Y H:i:s', strtotime($orderStatus->created_at)) : " " }}
                                                                </small>

                                                            </p>
                                                        </li>
                                                    @elseif (trim($orderStatus->status_name)
                                                        == "Order Troubleshooting")
                                                        @if ($order->order_status == 15 || $order->order_status == 13)
                                                            <li class="{{ $class }}">
                                                                <h6 class="mb-0">
                                                                    <a
                                                                        href="javascript:void(0)">{{ $orderStatus->status_name }}</a>
                                                                </h6>
                                                                <p>
                                                                    <small>
                                                                        {{ $orderStatus->created_at && $class == 'active' ? date('d-m-Y H:i:s', strtotime($orderStatus->created_at)) : " " }}
                                                                    </small>
                                                                </p>
                                                            </li>
                                                        @endif
                                                    @else
                                                        <li class="{{ $class . ' ' . $extraClass }}">
                                                            <h6 class="mb-0 {{ $flexClass }}">
                                                                <a
                                                                    href="javascript:void(0)">{{ $orderStatus->status_name }}</a>
                                                                @if (trim($orderStatus->status_name) == 'Payment Done')
                                                                    <div class="p-1"
                                                                        style="margin-top: -5px;">
                                                                        <div class="form-check form-switch">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                data-order-id={{ $order->id }}
                                                                                id="paymentDone" name="paymentDone"
                                                                                {{ $paymentDone == 1 ? 'checked disabled' : '' }}>
                                                                        </div>
                                                                    </div>
                                                                @elseif(trim($orderStatus->status_name)
                                                                    == 'Under QC')

                                                                    <div class="p-1 ps-2 d-flex"
                                                                        style="margin-top: -5px;">
                                                                        <div class="form-check form-check-inline">
                                                                            <input
                                                                                class="form-check-input radio-custom qcStatusUpdated"
                                                                                {{ $qcDisabled == 1 ? 'disabled' : '' }}
                                                                                type="radio"
                                                                                name="qcStatusOptions{{ $order->id }}"
                                                                                id="qcpass{{ $order->id }}"
                                                                                data-order-id={{ $order->id }}
                                                                                value="11"
                                                                                {{ in_array($order->order_status, [12]) ? 'checked' : '' }}>
                                                                            <label
                                                                                class="form-check-label radio-custom-label"
                                                                                for="qcpass{{ $order->id }}">Pass</label>
                                                                        </div>
                                                                        <div class="form-check form-check-inline">
                                                                            <input
                                                                                class="form-check-input radio-custom qcStatusUpdated"
                                                                                {{ $qcDisabled == 1 ? 'disabled' : '' }}
                                                                                type="radio"
                                                                                name="qcStatusOptions{{ $order->id }}"
                                                                                id="qcfail{{ $order->id }}"
                                                                                data-order-id={{ $order->id }}
                                                                                value="10"
                                                                                {{ in_array($order->order_status, [15, 13, 14]) ? 'checked' : '' }}>
                                                                            <label
                                                                                class="form-check-label radio-custom-label"
                                                                                for="qcfail{{ $order->id }}">Fail</label>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </h6>
                                                            <p><small>
                                                                    {{ $orderStatus->created_at && $class == 'active' ? date('d-m-Y H:i:s', strtotime($orderStatus->created_at)) :  " " }}</small>
                                                            </p>
                                                        </li>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>



<!-- Modal -->
<div class="modal" tabindex="-1" id="paymentDoneModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Payment Done Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <h6 class="mb-4">Please confirm your payment done before you change this status</h6>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Confirm</button>
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
<script>
    $(document).on('click', '.qcStatusUpdated', function(e) {
        var orderId = $(this).attr('data-order-id');
        var qualityCheckedValue = $('#collapse' + orderId + ' input[name="qcStatusOptions' + orderId +
            '"]:checked').val();
        var text = '';
        if (qualityCheckedValue == 10) {
            text = 'Please confirm product quality is not good.';
        } else {
            text = 'Please confirm product quality is good.';
        }
        swal({
                title: "Quality Checked.",
                text: text,
                icon: "/assets/images/info.png",
                buttons: ["No", "confirm"],
                dangerMode: false,
            })
            .then((changeit) => {
                if (changeit) {
                    var selectedStatus = qualityCheckedValue;
                    var orderId = $(this).attr('data-order-id');
                    $('#qcfail' + orderId).attr('disabled', true);
                    $('#qcpass' + orderId).attr('disabled', true);
                    var data = {
                        selectedStatusID: selectedStatus,
                        orderId: orderId,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    }
                    $.ajax({
                        url: "{{ route('order-status-change-ajax') }}",
                        data: data,
                        type: 'POST',
                        success: function(successData) {
                            new PNotify({
                                text: 'Order status changed successfully',
                                type: 'success',
                                styling: 'bootstrap3',
                                animateSpeed: 'fast',
                                delay: 1000
                            });

                            if (successData.orderTrack) {

                                var html =
                                    '<h6 class="d-flex"><a href="javascript:void(0)">Under QC</a>';
                                html += '<div class="p-1" style="margin-top: -8px;">';
                                html += '<div class="form-check form-check-inline px-0">';
                                html +=
                                    '<input class="form-check-input radio-custom qcStatusUpdated" type="radio" data-order-id="' +
                                    orderId +
                                    '" id="qcpass' + orderId + '" name="qcStatusOptions' +
                                    orderId + '" ' + (
                                        selectedStatus == 11 ? 'checked' : '') +
                                    ' disabled value="11">';
                                html +=
                                    '<label class="form-check-label radio-custom-label" for="qcpass' +
                                    orderId + '">Pass</label>';
                                html += '</div>';
                                html += '<div class="form-check form-check-inline px-0">';
                                html +=
                                    '<input class="form-check-input radio-custom qcStatusUpdated" type="radio" data-order-id="' +
                                    orderId +
                                    '" id="qcfail' + orderId + '" name="qcStatusOptions' +
                                    orderId + '" ' + (
                                        selectedStatus == 10 ? 'checked' : '') +
                                    ' disabled value="10">';
                                html +=
                                    '<label class="form-check-label radio-custom-label" for="qcfail' +
                                    orderId + '">Fail</label>';
                                html += '</div>';
                                html += '</div>';
                                html += '</h6>';
                                html += '<p><small>';
                                html += successData.orderTrack.created_at_proper;
                                html += '</small></p>';
                                $('#collapse' + orderId + ' .UndeQCBlock').html(html);
                                $('#collapse' + orderId + ' .UndeQCBlock').addClass(
                                    'active');
                                var updateOrderStatusBasedonQC = '';
                                if (selectedStatus == 10) {
                                    updateOrderStatusBasedonQC = 15;
                                } else {
                                    updateOrderStatusBasedonQC = 12;
                                }
                                var data = {
                                    selectedStatusID: updateOrderStatusBasedonQC,
                                    orderId: orderId,
                                    _token: $('meta[name="csrf-token"]').attr('content')
                                }
                                $.ajax({
                                    url: "{{ route('order-status-change-ajax') }}",
                                    data: data,
                                    type: 'POST',
                                    success: function(successData) {
                                        var orderStatus = '';
                                        if (selectedStatus == 10) {
                                            var orderStatus = '<li class="active">';
                                            orderStatus +=
                                                '<h6 class="mb-0"><a href="javascript:void(0)">Order Troubleshooting</a></h6>';

                                            orderStatus += '<p><small>';
                                            orderStatus += successData.orderTrack
                                                .created_at_proper;
                                            orderStatus += '</small></p></li>';
                                            $('#collapse' + orderId +
                                                ' .qcStatusBlock').after(
                                                orderStatus);

                                            $('#orderStatusNameBlock' + orderId)
                                                .html('Order Troubleshooting')
                                        } else {
                                            orderStatus =
                                                '<h6 class=""><a href="javascript:void(0)">Order Completed</a></h6>';
                                            orderStatus += '<p><small>';
                                            orderStatus += successData.orderTrack
                                                .created_at_proper;
                                            orderStatus += '</small></p>';
                                            $('#collapse' + orderId +
                                                ' .finalOrderStatusBlock').html(
                                                orderStatus);
                                            $('#collapse' + orderId +
                                                    ' .finalOrderStatusBlock')
                                                .addClass(
                                                    'active');
                                            $('#orderStatusNameBlock' + orderId)
                                                .html('Order Completed')
                                        }
                                        // new PNotify({
                                        //     text: 'Order status changed successfully',
                                        //     type: 'success',
                                        //     styling: 'bootstrap3',
                                        //     animateSpeed: 'fast',
                                        //     delay: 1000
                                        // });
                                    }
                                });
                            }
                        },
                    });
                } else {
                    $(this).prop('checked', false);
                }
            });
    });
    $(document).on('click', '#paymentDone', function(e) {
        swal({
                title: "Payment Done.",
                text: "Please confirm your payment is done.",
                icon: "/assets/images/info.png",
                buttons: ["No", "confirm"],
                dangerMode: false,
            })
            .then((changeit) => {
                if (changeit) {
                    var selectedStatus = 2;
                    var orderId = $(this).attr('data-order-id');
                    var data = {
                        selectedStatusID: selectedStatus,
                        orderId: orderId,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    }
                    $.ajax({
                        url: "{{ route('order-status-change-ajax') }}",
                        data: data,
                        type: 'POST',
                        success: function(successData) {
                            new PNotify({
                                text: 'Order status changed successfully',
                                type: 'success',
                                styling: 'bootstrap3',
                                animateSpeed: 'fast',
                                delay: 1000
                            });

                            if (successData.orderTrack) {

                                var html =
                                    '<h6 class="mb-0 d-flex"><a href="javascript:void(0)">Payment Done</a>';
                                html += '<div class="p-1" style="margin-top: -5px;">';
                                html += '<div class="form-check form-switch">';
                                html +=
                                    '<input class="form-check-input" type="checkbox" data-order-id="' +
                                    orderId +
                                    '" id="paymentDone" name="paymentDone" checked disabled>';
                                html += '</div>';
                                html += '</div>';
                                html += '</h6>';
                                html += '<p><small>';
                                html += successData.orderTrack.created_at_proper;
                                html += '</small></p>';
                                $('#collapse' + orderId + ' .paymentDoneBlock').html(html);
                                $('#collapse' + orderId + ' .paymentDoneBlock').addClass(
                                    'active');

                            }
                        },
                    });
                } else {
                    var orderId = $(this).attr('data-order-id');
                    $('#collapse' + orderId + ' #paymentDone').prop('checked', false); // Unchecks it
                    console.log(orderId)
                }
            });
    });
    $(document).on('click', '.refreshOrderStautsData', function(e) {
        e.stopImmediatePropagation();
        e.preventDefault();
        //$('#mainContentSection').html(defaultData);
        var orderId = $(this).attr('data-id');
        $.ajax({
            url: "{{ route('dashboard-refresh-order', '') }}" + "/" + orderId,
            type: 'GET',
            success: function(successData) {
                defaultData = successData.html;
                $('#orderStatusViewBlock' + orderId).html(successData.html);
                $('#orderStatusNameBlock' + orderId).html(successData.order_status_name)
            },
            error: function() {
                console.log('error');
            }
        });
    });
    // $(document).on('click', '#paymentDone', function(e) {
    //     swal({
    //             title: "Are you sure payment are done?",
    //             text: "You want to change order status.",
    //             icon: "info",
    //             buttons: ["No", "Yes"],
    //             dangerMode: false,
    //         })
    //         .then((changeit) => {
    //             if (changeit) {
    //                 var selectedStatus = 2;
    //                 var orderId = $(this).attr('data-order-id');
    //                 var data = {
    //                     selectedStatusID: selectedStatus,
    //                     orderId: orderId,
    //                     _token: $('meta[name="csrf-token"]').attr('content')
    //                 }
    //                 $.ajax({
    //                     url: "{{ route('order-status-change-ajax') }}",
    //                     data: data,
    //                     type: 'POST',
    //                     success: function(successData) {
    //                         new PNotify({
    //                             text: 'Order status changed successfully',
    //                             type: 'success',
    //                             styling: 'bootstrap3',
    //                             animateSpeed: 'fast',
    //                             delay: 1000
    //                         });

    //                         if (successData.orderTrack) {

    //                             var html = '<h6><a href="javascript:void(0)">Payment Done</a>';
    //                             html += '<p class="mb-4">';
    //                             html += successData.orderTrack.created_at_proper;
    //                             html += '</p>';
    //                             html += '</h6>';
    //                             $('#collapse' + orderId + ' .paymentDoneBlock').html(html);
    //                             $('#collapse' + orderId + ' .paymentDoneBlock').addClass(
    //                                 'active');

    //                         }
    //                     },
    //                 });
    //             } else {
    //                 var orderId = $(this).attr('data-order-id');
    //                 $('#collapse' + orderId + ' #paymentDone').prop('checked', false); // Unchecks it
    //             }
    //         });
    // });
</script>

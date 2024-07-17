<script>
    $(function(e) {

        @if (config('app.env')=='live')
            var socket = io('https://blitznet.co.id:3000');
        @elseif(config('app.env')=='staging')
            var socket = io.connect("https://beta.blitznet.co.id:3000", { secure: true, reconnect: true, rejectUnauthorized : false });
        @else
            var socket = io('http://localhost:8890');
        @endif
        socket.on("buyer-notification-chanel:App\\Events\\BuyerNotificationEvent", function(){
            getUserActivityNewDataCount();
        });
        socket.on("buyer-rfq-notification-chanel:App\\Events\\BuyerRfqNotificationEvent", function(){
            getBuyerNotificationIndicator('rfqs_count');
        });
        socket.on("buyer-order-notification-channel:App\\Events\\BuyerOrderNotificationEvent", function(){
            getBuyerNotificationIndicator('orders_count');
        });
        getBuyerNotificationIndicator();
        /*window.Echo.channel('buyer-rfq-notification-chanel').listen('.listen', (e) => {
             getBuyerNotificationIndicator('rfqs_count');
        });
        window.Echo.channel('buyer-order-notification-channel').listen('.listen', (e) => {
             getBuyerNotificationIndicator('orders_count');
        });
        window.Echo.channel('buyer-notification-chanel').listen('.listen', (e) => {
            getUserActivityNewDataCount();
        });*/



        $(document).on('click', '#userActivityBtn', function() {
            loadUserActivityData();
        });

        function getUserActivityNewDataCount() {
            $.ajax({
                url: "{{ route('dashboard-user-activity-new-data-count-ajax') }}",
                type: 'GET',
                success: function (successData) {
                    if (successData.userActivityNewDataCount != 0) {
                        $('#showCounterNotification').html('');
                        $('#showCounterNotification').html('<div class="counter">' + successData.userActivityNewDataCount + '</div>');
                    }
                },
                error: function () {
                    console.log('error');
                }
            });
        }

        function loadUserActivityData() {

            $.ajax({
                url: "{{ route('dashboard-user-activity-ajax') }}",
                type: 'GET',
                success: function(successData) {
                    $('#showCounterNotification').html('');
                    $('#userActivitySection').html(successData.userActivityHtml);
                },
                error: function() {
                    console.log('error');
                }
            });
        }
    });

    function notificationStore(type, id, quote_id = 0) {
        var data = {
            "type": type,
            "id":id,
            "quote_id":quote_id
        }
        sessionStorage.setItem("redirectGetData", JSON.stringify(data));
        window.location.href = "{{ route('dashboard') }}"
    }

    function markAsAll(e) {
        e.preventDefault()
        e.stopImmediatePropagation();
        $.ajax({
            url: "{{ route('buyer-mark-as-all-ajax') }}",
            type: 'GET',
            success: function(successData) {
                $('#showCounterNotification').html('');
                $('#userActivitySection').html(successData.userActivityHtml);
            },
            error: function() {
                console.log('error');
            }
        });
    }
    //@vrutika for side indicator for buyer notification
    function getBuyerNotificationIndicator(sideIndicator = 'All') {

        $.ajax({
            url: "{{ route('get-side-buyer-indicator-ajax','') }}" + "/" + sideIndicator,
            type: 'GET',
            dataType: 'json',
            success: function (successData) {
                console.log(successData);
                if (successData.rfqs != 0){
                    $('#buyerRfqNotification').removeClass('d-none');
                }

                if (successData.orders != 0 ){
                    $('#buyerOrderNotification').removeClass('d-none');

                }
            },
            error: function(error) {

            }
        });
    }

    $(document).on('click', '.userActivityPageRedirect', function(e) {

        @if(Request::segment(1) != 'dashboard')
        var id = $(this).data('id');
        var type = $(this).data('type');
        var quote_id = $(this).data('quote_id');
        notificationStore(type, id, quote_id);

        @else
        e.preventDefault();
        let id = $(this).attr('data-id');
        let type = $(this).attr('data-type');
        let quote_id = $(this).attr('data-quote_id');
        let notification_id = $(this).attr('data-notification_id');
        e.stopImmediatePropagation();
        redirectTo(type,id, quote_id);
        singleMark(e, notification_id);
        @endif
    });

    function singleMark(e, id) {
        e.preventDefault()
        e.stopImmediatePropagation();
        $.ajax({
            url: "{{ route('buyer-mark-as-single-ajax','') }}" + "/" + id,
            type: 'GET',
            dataType: 'json',
            success: function(successData) {
                $('#showCounterNotification').html('');
                $('#userActivitySection').html(successData.userActivityHtml);
            },
            error: function() {
                console.log('error');
            }
        });
    }
</script>

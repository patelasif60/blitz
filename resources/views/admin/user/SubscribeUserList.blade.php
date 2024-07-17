@extends('admin/adminLayout')
@section('content')
    <div class="card h-100 bg-transparent newtable_v2">
        <div class="card-body p-0">
            <h4 class="card-title">{{__('admin.subscribed_users')}}</h4>
            <div class="d-flex align-items-center justify-content-end">
                <input type="hidden" value="{!! csrf_token() !!}" name="_token">
            </div>
            <div class="row clearfix">
                <div class="col-12">
                    <div class="table-responsive">
                        <table id="subscribeUserTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{__('home_latest.name')}}</th>
                                    <th>{{__('admin.email')}}</th>
                                    <th>{{__('admin.mobile')}}</th>
                                    <th>{{__('admin.company_name')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($subscribeUsers) > 0)
                                    @foreach ($subscribeUsers as $user)
                                        <tr>
                                            <td>{{ $user->fullname ?? '-' }}</td>
                                            <td>{{ $user->email ?? '-'}}</td>
                                            <td>{{ $user->mobile ?? '-'}}</td>
                                            <td>{{ $user->company_name ?? '-'}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#subscribeUserTable').DataTable({
                "order": [
                    [1, "asc"]
                ],
      "aLengthMenu": [
        [10, 20, 50, -1],
        [10, 20, 50, "All"]
      ],
      "iDisplayLength": 10,
            });
        });
    </script>
@stop

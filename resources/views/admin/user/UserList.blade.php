@extends('admin/adminLayout')
@section('content')
    <div class="card h-100  bg-transparent newtable_v2">
        <div class="card-body p-0">
            <h4 class="card-title">{{ __('admin.users')}}</th></h4>
            <div class="d-flex align-items-center justify-content-end">
                <input type="hidden" value="{!! csrf_token() !!}" name="_token">
            </div>
            <div class="row clearfix">
                <div class="col-12">
                    <div class="table-responsive newtable_v2">
                        <table id="userTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th> {{ __('admin.type')}}</th>
                                    <th> {{ __('admin.user_type')}}</th>
                                    <th> {{ __('admin.firstname')}}</th>
                                    <th> {{ __('admin.lastname')}}</th>
                                    <th> {{ __('admin.email')}}</th>
                                    <th> {{ __('admin.mobile')}}</th>
                                    <th> {{ __('admin.date')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($users) > 0)
                                    @foreach ($users as $user)
										@php
											$t =  'Customers';
											if (strpos($user->email, 'blitznet') !== false || strpos($user->email, 'bcssarl') !== false) {
												$t = 'blitznet team';
											}
											$type =  'Frontend User';
											if ($user->role->id == "1") {
												$type= 'Backend User';
											}
											if ($user->role->id == "3") {
											    $t =  'Suppliers';
												$type= 'Backend Supplier User';
											}
										@endphp
                                        <tr>
                                            <td>{{ $t }}</td>
                                            <td>{{ $type }}</td>
                                            <td>{{ $user->firstname }}</td>
                                            <td>{{ $user->lastname }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->phone_code." ".$user->mobile }}</td>
                                            <td>{{ date('d-m-Y',strtotime($user->created_at)) }}</td>
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
            $('#userTable').DataTable({
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

@extends('admin/adminLayout')
@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Grades</h4>
            <div class="d-flex align-items-center justify-content-end">
                <div class="pe-1 mb-3 clearfix">
                    <a href="{{ route('grade-add') }}" type="button" class="btn btn-primary">Add</a>
                </div>
                <input type="hidden" value="{!! csrf_token() !!}" name="_token">
            </div>
            <div class="row clearfix">
                <div class="col-12">
                    <div class="table-responsive">
                        <table id="order-listing" class="table">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Added By</th>
                                    <th>Updated By</th>
                                    <th class="text-end" data-orderable="false">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($grades) > 0)
                                    @foreach ($grades as $grade)
                                        <tr>
                                            <td>{{ $grade->id }}</td>
                                            <td>{{ $grade->name }}</td>
                                            <td>{{ $grade->status == 1 ? 'Active' : 'Inactive' }}</td>
                                            <td>
                                                @if( !empty( $grade->trackAddData ) )

                                                {{ $grade->trackAddData->full_name }}
                                                @else {{"-"}}@endif
                                            </td>
                                            <td>
                                                @if( !empty( $grade->trackUpdateData ) )

                                                {{ $grade->trackUpdateData->full_name }}
                                                @else {{"-"}}@endif
                                            </td>
                                            <td>
                                                <a href="{{ route('grade-edit', ['id' => $grade->id]) }}"
                                                    class="show-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                        height="16" fill="currentColor" class="bi bi-pencil-square"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                        <path fill-rule="evenodd"
                                                            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                                                    </svg>
                                                </a>
                                                <a href="javascript:void(0)" id="deleteGrade_{{ $grade->id }}"
                                                    class="deleteGrade show-icon"><svg xmlns="http://www.w3.org/2000/svg"
                                                        width="16" height="16" fill="currentColor" class="bi bi-trash"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                                                        <path fill-rule="evenodd"
                                                            d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                                                    </svg>
                                                </a>
                                            </td>
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
            $('#categoriesTable').DataTable();
            $(document).on('click', '.deleteGrade', function() {
                var id = $(this).attr('id').split("_")[1];
                swal({
                        title: "Are you sure?",
                        text: "Once deleted, you will not be able to recover this.",
                        icon: "/assets/images/bin.png",
                        buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.ok') }}'],
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            // swal("Poof! Your imaginary file has been deleted!", {
                            //     icon: "success",
                            // });

                            var _token = $("input[name='_token']").val();
                            var senddata = {
                                id: id,
                                _token: _token
                            }
                            $.ajax({
                                url: '{{ route('grade-delete') }}',
                                type: 'POST',
                                data: senddata,
                                success: function(successData) {
                                    new PNotify({
                                        text: 'Grade deleted successfully',
                                        type: 'success',
                                        styling: 'bootstrap3',
                                        animateSpeed: 'fast',
                                        delay: 1000
                                    });
                                    location.reload();

                                },
                                error: function() {
                                    console.log('error');
                                }
                            });

                        }
                    });

            });

        });
    </script>
@stop

@extends('admin/adminLayout')
@section('content')
    <div class="card h-100 bg-transparent newtable_v2">
        <div class="card-body p-0">
            <h4 class="card-title">{{__('admin.newsletters_users')}}</h4>
            <div class="d-flex align-items-center justify-content-end">
                <input type="hidden" value="{!! csrf_token() !!}" name="_token">
            </div>
            <div class="row clearfix">
                <div class="col-12">
                    <div class="table-responsive">
                        <table id="order-listing" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{__('admin.email')}}</th>
                                    <th>{{__('admin.date')}}</th>
                                    <th>{{__('admin.added_by')}}</th>
                                    <th>{{__('admin.updated_by')}} </th>
                                    @can('edit newsletter')
                                    <th class="text-center">{{__('admin.action')}}</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($newsletters) > 0)
                                    @foreach ($newsletters as $news)
                                        <tr>
                                            <td>{{ $news->email }}</td>
                                            <td>{{ date('d-m-Y', strtotime($news->created_at)) }}</td>
                                            <td>
                                                @if( !empty( $news->trackAddData ) )

                                                {{ $news->trackAddData->full_name }}
                                                @else {{"-"}}@endif
                                            </td>
                                            <td>
                                                @if( !empty( $news->trackUpdateData ) )

                                                {{ $news->trackUpdateData->full_name }}
                                                @else {{"-"}}@endif
                                            </td>
                                            @can('edit newsletter')
                                            <td class="text-center">
                                                {{-- <a href="javascript:void(0)"><i class="fa fa-eye"></i></a> --}}
                                                <a href="{{ route('newsletter-edit', Crypt::encrypt($news->id)) }}"
                                                    class="px-2"  data-toggle="tooltip" ata-placement="top" title="{{__('admin.edit')}}"><i class="fa fa-edit"></i></a>

                                            </td>
                                            @endcan
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
            });
        });
    </script>
@stop

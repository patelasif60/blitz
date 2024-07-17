<table class="table dataTable dt-responsive" id="tblCompanyCoreTeam">
    <thead>
    <tr>
        <th style="max-width: 100px">{{ __('admin.photo')}}</th>
        <th>{{ __('admin.name')}}</th>
        <th>{{ __('admin.designation')}}</th>
        <th>{{ __('admin.email')}}</th>
        <th>{{ __('admin.position')}}</th>
        <th class="text-end">{{ __('admin.actions')}}</th>
    </tr>
    </thead>
    <tbody></tbody>
</table>
<script type="text/javascript">
    $(function () {

        var table = $('#tblCompanyCoreTeam').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            order:[],
            ajax: {
                url: "{{ route('supplier.members.list.ajax') }}",
                data: function (d) {
                    d.company_user_type = '1';
                    d.supplier_id = $('#coreTeamFrm #userId').val();
                }
            },
            language:{
                processing: "<i class='fa fa-spinner fa-spin fa-1x fa-fw'></i> Loading...",
            },
            drawCallback: function() {
                $('#tblCompanyCoreTeam [data-bs-toggle="tooltip"]').tooltip();
            },
            columns: [
                {
                    data: 'image',
                    name: 'image',
                    render: function (data) {
                        if(data !== '' && data){
                            var path = "{{ url('storage') }}/"+data;
                            return '<a href="'+path+'" target="_blank"><img style="width: 40px !important; height: 40px !important;" src="'+path+'"> </a>';
                        }else{
                            return '-';
                        }
                    },
                    width:'100px',
                    className: 'text-center'
                },
                {
                    data: 'name',
                    name: 'name',
                    render: function (data, type, row) {
                        return row.salutation+" "+row.firstname+" "+row.lastname;
                    }
                },
                {data: 'designation', name: 'designation'},
                {data: 'email', name: 'email'},
                {data: 'position', name: 'position', width: '100px'},
                {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-end', width: '80px'},
            ]
        });
    })

</script>

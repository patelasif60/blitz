<table class="table dataTable dt-responsive" id="tblCompanyPartner">
    <thead>
    <tr>
        <th style="max-width: 100px">{{__('admin.logo')}}</th>
        <th>{{__('admin.company_name')}}</th>
        <th>{{__('admin.description')}}</th>
        <th class="text-end">{{__('admin.actions')}}</th>
    </tr>
    </thead>
    <tbody></tbody>
</table>
<script type="text/javascript">
    $(function () {
        var table = $('#tblCompanyPartner').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            order:[],
            ajax: {
                url: "{{ route('supplier.members.list.ajax') }}",
                data: function (d) {
                    d.company_user_type = '3';
                    d.supplier_id = $('#companyPartnerFrm #userId').val();
                }
            },
            language:{
                processing: "<i class='fa fa-spinner fa-spin fa-1x fa-fw'></i> Loading...",
            },
            drawCallback: function() {
                $('#tblCompanyPartner [data-bs-toggle="tooltip"]').tooltip();
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
                { data: 'company_name', name: 'company_name', width:'200px'},
                {
                    data: 'description',
                    name: 'description',
                    render: function (data, type, row) {
                        if(data.length >= 120) {
                            return data.substring(0,120) +'....';
                        }else{
                            return data;
                        }
                    }
                },
                {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-end', width: '80px'},
            ]
        });
    })

</script>

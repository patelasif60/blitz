<table class="table dataTable dt-responsive" id="tblCompanyPortfolio">
    <thead>
    <tr>
        <th style="max-width: 100px">{{__('admin.logo')}}</th>
        <th>{{__('admin.company_name')}}</th>
        <th>{{__('admin.sector')}}</th>
        <th>{{__('admin.registation_nib') }}</th>
        <th>{{__('admin.type') }}</th>
        <th>{{__('admin.position') }}</th>
        <th class="text-end">{{__('admin.actions') }}</th>
    </tr>
    </thead>
    <tbody></tbody>
</table>
<script type="text/javascript">
    $(function () {
        var table = $('#tblCompanyPortfolio').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            order:[],
            ajax: {
                url: "{{ url('/admin/get-company-members') }}",
                data: function (d) {
                    d.company_user_type = '4';
                    d.supplier_id = $('#companyPortfolioFrm #userId').val();
                }
            },
            language:{
                processing: "<i class='fa fa-spinner fa-spin fa-1x fa-fw'></i> Loading...",
            },
            drawCallback: function() {
                $('#tblCompanyPortfolio [data-bs-toggle="tooltip"]').tooltip();
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
                {data: 'company_name', name: 'company_name'},
                {
                    data: 'sector',
                    name: 'sector',
                    render: function (data, type, row) {
                        if(data == 1) {
                            return "Primary";
                        }else if(data == 2) {
                            return "Secondary";
                        }else if(data == 3) {
                            return "Tertiary";
                        }else{
                            return data;
                        }
                    }
                },
                {data: 'registration_NIB', name: 'registration_NIB'},
                {
                    data: 'portfolio_type',
                    name: 'portfolio_type',
                    render: function (data, type, row) {
                        if(data == 1) {
                            return "Client";
                        }else if(data == 2) {
                            return "Supplier";
                        }else{
                            return data;
                        }
                    }
                },
                {data: 'position', name: 'position'},
                {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-end', width: '80px'},
            ]
        });
    })

</script>

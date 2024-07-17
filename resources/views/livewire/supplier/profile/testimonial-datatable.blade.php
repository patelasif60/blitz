<table class="table dataTable dt-responsive" id="tblCompanyTestimonial">
    <thead>
    <tr>
        <th style="min-width: 100px">{{__('admin.photo')}}</th>
        <th>{{__('admin.name')}}</th>
        <th>{{__('admin.designation')}}</th>
        <th>{{__('admin.company_name')}}</th>
        <th class="text-end">{{__('admin.actions')}}</th>
    </tr>
    </thead>
    <tbody></tbody>
</table>
<script type="text/javascript">
    $(function () {
        var table = $('#tblCompanyTestimonial').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            order:[],
            ajax: {
                url: "{{ url('/admin/get-company-members') }}",
                data: function (d) {
                    d.company_user_type = '2';
                    d.supplier_id = $('#testimonialFrm #userId').val();
                }
            },
            language:{
                processing: "<i class='fa fa-spinner fa-spin fa-1x fa-fw'></i> Loading...",
            },
            drawCallback: function() {
                $('#tblCompanyTestimonial [data-bs-toggle="tooltip"]').tooltip();
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
                    width:'250px'
                },
                {
                    data: 'name',
                    name: 'name',
                    render: function (data, type, row) {
                        return row.salutation+" "+row.firstname+" "+row.lastname;
                    }
                },
                {data: 'designation', name: 'designation'},
                {data: 'company_name', name: 'company_name'},
                {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-end', width: '80px'},
            ]
        });
    })

</script>

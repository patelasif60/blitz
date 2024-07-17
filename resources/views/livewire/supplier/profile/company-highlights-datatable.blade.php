<table class="table dataTable dt-responsive" id="tblCompanyHighlights">
    <thead>
    <tr>
        <th style="min-width: 100px">{{ __('admin.photo')}}</th>
        <th>{{ __('admin.achievement_category')}}</th>
        <th>{{ __('admin.name')}}</th>
        <th>{{ __('admin.number')}}</th>
        <th class="text-end">{{ __('admin.actions')}}</th>
    </tr>
    </thead>
    <tbody></tbody>
</table>
<script type="text/javascript">
    $(function () {
        var table = $('#tblCompanyHighlights').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            order:[],
            language:{
                processing: "<i class='fa fa-spinner fa-spin fa-1x fa-fw'></i> Loading...",
            },
            drawCallback: function() {
                $('#tblCompanyHighlights [data-bs-toggle="tooltip"]').tooltip();
            },
            ajax: {
                url: "{{ route('supplier.highlight.list.ajax') }}",
                data: function (d) {
                    d.supplier_id = $('#highlightsFrm #userId').val();
                }
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
                    data: 'category',
                    name: 'category',
                    render: function (data) {
                        if(data == '1'){
                            return 'Award';
                        }else if(data == '2'){
                            return 'Certificate';
                        }if(data == '3'){
                            return 'Media Recognition';
                        }else{
                            return data;
                        }
                    }
                },
                {data: 'name', name: 'name'},
                {data: 'number', name: 'number', width: '100px'},
                {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-end', width: '80px'},
            ]
        });
    })
    jQuery(document).ready(function(){
        SupplierProfileTab.init();
    });

</script>

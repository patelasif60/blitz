<div class="col-md-12 mt-2">
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h5><img height="20px" src="{{URL::asset('front-assets/images/icons/boxes.png')}}" alt="Supplier Details" class="pe-2">{{__('admin.shipment_info')}}</h5>
        </div>
        <div class="card-body p-3 pb-1">
            <div class="row rfqform_view bg-white">
                <div class="col-md-4 pb-2">
                    <label>{{__('admin.origin_shipment_pincode')}}:</label>
                    <div class="text-dark">{{$checkPriceData['origin_shipment_pincode']}}</div>
                </div>
                <div class="col-md-4 pb-2">
                    <label>{{__('admin.destination_shipment_pincode')}}:</label>
                    <div class="text-dark">{{$checkPriceData['destination_shipment_pincode']}}</div>
                </div>
                <div class="col-md-4 pb-2">
                    <label>{{__('admin.amount')}} <small>({{__('admin.per_unit')}})</small>:</label>
                    <div class="text-dark"> {{$checkPriceData['goods_amount']}}</div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-12 newtable_v2">
    <div class="mt-2  table-responsive">
    <table id="check-price-listing" class="table table-hover">
        <thead>
            <tr>
                <th scope="col">{{__('admin.services_name')}}</th>
                <th scope="col">{{__('admin.services_code')}}</th>
                <th scope="col">{{__('admin.estimated_days')}}</th>
                <th scope="col">{{__('admin.weight')}}</th>
                <th scope="col">{{__('admin.freight')}}</th>
                <th scope="col">{{__('admin.surcharge')}}</th>
                <th scope="col">{{__('admin.vat')}}</th>
                <th scope="col">{{__('admin.insurance')}}</th>
                <th scope="col">{{__('admin.insurance_admin')}}</th>
                <th scope="col">{{__('admin.total')}}</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($results) && sizeof($results)>0)
            @foreach($results as $result)
            <tr>
                <td class="text-nowrap">{{ $result->service_display }}</td>
                <td class="text-nowrap">{{ $result->service_code }}</td>
                <td class="text-nowrap">{{ ($result->etd_from && $result->etd_thru) ? $result->etd_from .' - '. $result->etd_thru .'D' : '-'}}</td>
                <td class="text-nowrap">{{ $result->weight_charged ? $result->weight_charged .' KG' : '-' }}</td>
                <td class="text-nowrap">Rp {{ $result->price->freight_amount }}</td>
                <td class="text-nowrap">Rp {{ $result->price->surcharge_amount }}</td>
                <td class="text-nowrap">Rp {{ $result->price->vat_amount }}</td>
                <td class="text-nowrap">Rp {{ $result->price->insurance_amount }}</td>
                <td class="text-nowrap"> {{ isset($result->price->insurance_admin_amount) ? 'Rp '.$result->price->insurance_admin_amount : '-'}}</td>
                <td class="text-nowrap">Rp {{ $result->price->total_amount }}</td>
             </tr>
            @endforeach
            @else
                <tr>
                    <td>{{ __('admin.no_data_available') }}</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
</div>
<script>
    $(document).ready(function() {
            $('#check-price-listing').DataTable();
        });
</script>

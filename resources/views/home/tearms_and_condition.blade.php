@extends('home/homeLayout2')

@section('content')
    <div id="carouselCaptions" class="carousel slide bannersection" data-bs-ride="carousel" data-aos="fade-down"></div>

    <div class="container py-5">
        <div class="pt-5">
            {!!__('tearms_and_condition.content')!!}
        </div>
    </div>
@endsection

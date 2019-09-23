@extends('layouts.app')

@section('content')

<div class="container py-5">

    <div class="row text-center text-primary mb-5">
        <div class="col-lg-7 mx-auto">
            <h1 class="display-4">{{ $title }}</h1>
            <p class="lead text-muted mb-0">{{ $subtitle }}@if (request()->is("my-wishlist") && $appliances->count() > 0)<a href="{{ route('share.create') }}">by email!</a>@endif</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            @each('appliances.item', $appliances, 'appliance', 'appliances.empty')
            {{ $appliances->links() }}
        </div>
    </div>

</div>
@endsection
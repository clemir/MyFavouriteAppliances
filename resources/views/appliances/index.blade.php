@extends('layouts.app')

@section('content')

<div class="container py-5">

    <div class="row text-center text-danger mb-5">
        <div class="col-lg-7 mx-auto">
            <h1 class="display-4">{{ $title }}</h1>
            <p class="lead text-muted mb-0">{{ $subtitle }}@if (request()->is("my-wishlist") && $appliances->count() > 0)<a href="{{ route('share.create') }}">by email!</a>@endif</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            @if ($appliances->total() > 0)
                <div class="d-flex mb-6 justify-content-between mb-4">
                    Total: {{ $appliances->total() }} appliances.
                    <form method="get" class="form form-inline">
                        <select class="form-control" name="sort" >
                            <option value="title" @if(request('sort') == 'title') selected @endif>Title A to Z</option>
                            <option value="-title"@if(request('sort') == '-title') selected @endif>Title Z to A</option>
                            <option value="-price" @if(request('sort') == '-price' || ! request()->has('sort')) selected @endif>Price high to low</option>
                            <option value="price" @if(request('sort') == 'price') selected @endif>Price low to high</option>
                        </select>
                        <button type="submit" class="ml-2 btn btn-sm btn-success">Order</button>
                    </form>
                </div>
            @endif
            @each('appliances.item', $appliances, 'appliance', 'appliances.empty')
            {{ $appliances->links() }}
        </div>
    </div>

</div>
@endsection
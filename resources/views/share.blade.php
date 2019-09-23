@extends('layouts.app')

@section('content')

<div class="container py-5">

    <div class="row text-center text-primary mb-5">
        <div class="col-lg-7 mx-auto">
            <h1 class="display-4">Share with friends</h1>
            <p class="lead text-muted mb-0">Send your favorite appliances wishlist to your friends by email!</a></p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            @if (auth()->user()->hasFavoritedAppliances())
                <form action="{{ route('share.send') }}" method="POST" novalidate="true">
                    @csrf
                    <div class="form-group">
                        <label for="email">Send to</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus aria-describedby="emailHelp" placeholder="Enter email">
                        <small id="emailHelp" class="form-text text-muted">In case of multiple email addresses, please separate them with a comma.</small>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Send my wishlist</button>
                </form>
            @else
                @include('appliances.empty')
            @endif
        </div>
    </div>

</div>
@endsection
<div class="text-center">
    @if (request()->is('my-wishlist'))
        <div class="alert alert-danger" role="alert">
            You don't have favorite appliances!
        </div>
        <p><a class="btn-link" href="{{ url('/') }}">Go back to home</a> and choose one!
        </p>
    @else
        <div class="alert alert-danger" role="alert">
            No appliances to show.
        </div>
    @endif
</div>

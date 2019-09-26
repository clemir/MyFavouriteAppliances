<article class="border p-3 mb-4 bg-white text-dark shadow">
    <div class="media align-items-lg-center flex-column flex-lg-row p-3">
        <div class="media-body order-2 order-lg-1">
            <a href="{{ $appliance->title }}"><h5 class="mt-0 font-weight-bold mb-2">{{ $appliance->title }}</h5></a>
            <ul class="list-unstyled">
                @foreach ($appliance->description as $element)
                    <li class="text-muted mb-0 small">{{ $element }}</li>
                @endforeach
            </ul>
            <div class="d-flex align-items-center justify-content-between mt-1">
                <h6 class="font-weight-bold my-2">{{$appliance->format_price}}</h6>
            </div>

        @unless (request()->is("user*"))
            @if(!optional(auth()->user())->hasFavorited($appliance) || auth()->guest())
                <form action="{{ route('favorite', $appliance->id) }}" method="POST">
                    @csrf
                    <button data-token="{{ csrf_token() }}" id="{{ $appliance->id }}" type="submit" class="btn btn-primary btn-sm favorite">
                        Save as favorite
                    </button>
                    </form>
            @elseif (auth()->user()->hasFavorited($appliance))
                <form action="{{ route('favorite.destroy', $appliance->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button data-token="{{ csrf_token() }}" id="{{ $appliance->id }}" type="submit" class="btn btn-danger btn-sm favorite">
                        Remove as favorite
                    </button>
                </form>
            @endif
        @endunless
        </div><img src="{{ $appliance->image }}" alt="{{ $appliance->title }} image" width="250" class="ml-lg-5 order-1 order-lg-2">
    </div>
</article>

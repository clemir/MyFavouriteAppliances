@component('mail::message')
# Hello!

{{ $userName }} wants to share you his/her favorited appliances wishlist.

@component('mail::button', ['url' => $url])
View the wishlist
@endcomponent

Grettings, <br>
{{ config('app.name')}}
@endcomponent

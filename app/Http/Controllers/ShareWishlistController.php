<?php

namespace App\Http\Controllers;

use App\Mail\SendWishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ShareWishlistController extends Controller
{
    public function create()
    {
        return view('share');
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->hasFavoritedAppliances(), 403, "You don't have favorite appliances! Choose one before share your wishlist.");

        $request->validate([
            'email' => 'required',
            'email.*' => 'email'
        ]);

        Mail::to($request->email)->send(new SendWishlist());
    }
}

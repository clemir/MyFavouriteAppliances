<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendWishlist extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user = auth()->user();
        return $this->from($user->email, $user->name)
                    ->markdown('emails.wishlist.shared')
                    ->with([
                        'userName' => $user->name,
                        'url' => $user->wishlistUrl,
                    ]);
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\User;
use Illuminate\Support\Facades\URL;

class NewUser extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $change_url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->change_url = URL::temporarySignedRoute('reset.password', now()->addDays(7), ['user' => $this->user->id]);

        return $this->view('mail.new_user');
    }
}

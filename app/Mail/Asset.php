<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Asset extends Mailable
{
    use Queueable, SerializesModels;

    // public $foo ='bar';
    public ?string $name;

    // public $name;

    /**
     * Create a new message instance.
     */
    public function __construct(?string $name = 'Guest')
    {
        $this->name = $name; 
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Asset',
           
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $content = new Content(
            view: 'mail.asset',
        );

        return $content->with(['name' => $this->name]);
    }

    // public function content(): Content
    // {
    //     return new Content(
    //         view: 'mail.testmail',
    //     );
    // }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

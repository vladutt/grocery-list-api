<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public string $name)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to GrocerMate: Your Ultimate Shopping Companion!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'vendor.notifications.email',
            with: [
                'greeting' => 'Hello, ' . $this->name,
                'introLines' => [
                    'We are thrilled to have you on board. GrocerMate is designed to make your shopping experience seamless and enjoyable. Whether you’re planning a grocery run, preparing for a special event, or just keeping track of your daily essentials, GrocerMate has got you covered.',
                    '🛒 Create Shopping Lists: Easily add items to your list, categorize them, and mark them off as you shop.',
                    '👫 Share with Friends: Share your lists with friends and family to make shopping together more efficient and fun. Collaborate in real-time and ensure you never miss an item.',
                ],
                'outroLines' => [
                    'We are working on: ',
                    '🔔 Get Reminders: Set reminders for your shopping trips and get notified so you never forget an important item.',
                    '📊 Track Your Spending: Keep an eye on your budget with our built-in spending tracker. Save money while you shop smarter.'
                ],
            ]
        );
    }

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

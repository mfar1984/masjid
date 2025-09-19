<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TestEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $fromName;
    public $testMessage;

    /**
     * Create a new message instance.
     */
    public function __construct($fromName = 'E-Masjid System', $testMessage = null)
    {
        $this->fromName = $fromName;
        $this->testMessage = $testMessage ?: 'Ini adalah test email dari sistem E-Masjid. Jika anda menerima email ini, bermakna konfigurasi SMTP berfungsi dengan baik.';
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Test Email - E-Masjid System',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            html: 'emails.test-email',
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

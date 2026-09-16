<?php

namespace App\Mail;

use App\Models\Matricula;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ComprovanteMatriculaMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Matricula $matricula
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Comprovante Matricula Mail',
        );
    }

    public function build()
    {
        return $this
            ->subject('Comprovante de Matrícula')
            ->html("
                <h1>Comprovante de Matrícula</h1>

                <p>Olá, {$this->matricula->aluno->nome}.</p>

                <p>Sua matrícula foi aprovada com sucesso.</p>

                <p><strong>Curso:</strong> {$this->matricula->curso->nome}</p>
                <p><strong>Status:</strong> {$this->matricula->status}</p>
                <p><strong>Data da matrícula:</strong> {$this->matricula->data_matricula}</p>

                <p>Obrigado por se matricular em nossa plataforma.</p>
            ");
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'view.name',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

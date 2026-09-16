<?php

namespace App\Listeners;

use App\Events\MatriculaAprovada;
use App\Mail\ComprovanteMatriculaMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class EnviarComprovantePorEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MatriculaAprovada $event): void
    {
        $matricula = $event->matricula->load(['aluno', 'curso']);

        Mail::to($matricula->aluno->email)
            ->send(new ComprovanteMatriculaMail($matricula));
    }
}

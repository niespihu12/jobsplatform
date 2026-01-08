<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EstadoCandidatura extends Notification
{
    use Queueable;

    protected $vacante;
    protected $estado;
    protected $mensaje;

    public function __construct($vacante, $estado, $mensaje = null)
    {
        $this->vacante = $vacante;
        $this->estado = $estado;
        $this->mensaje = $mensaje;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Actualización de tu candidatura - ' . $this->vacante->titulo)
            ->greeting('Hola ' . $notifiable->name . ',');

        switch ($this->estado) {
            case 'recibida':
                $mail->line('Hemos recibido tu postulación para el puesto de ' . $this->vacante->titulo . '.')
                     ->line('Tu CV ha sido evaluado por nuestro sistema de IA y está siendo revisado por nuestro equipo.')
                     ->line('Te contactaremos pronto con más información.');
                break;
            case 'en_revision':
                $mail->line('Tu candidatura para ' . $this->vacante->titulo . ' está siendo revisada.')
                     ->line('Nuestro equipo está evaluando tu perfil detalladamente.');
                break;
            case 'preseleccionado':
                $mail->line('¡Felicitaciones! Has sido preseleccionado para el puesto de ' . $this->vacante->titulo . '.')
                     ->line('Pronto nos pondremos en contacto contigo para coordinar una entrevista.');
                break;
            case 'rechazado':
                $mail->line('Gracias por tu interés en el puesto de ' . $this->vacante->titulo . '.')
                     ->line('Lamentablemente, en esta ocasión hemos decidido continuar con otros candidatos.')
                     ->line('Te invitamos a seguir postulándote a nuestras futuras vacantes.');
                break;
        }

        if ($this->mensaje) {
            $mail->line($this->mensaje);
        }

        return $mail->line('Gracias por tu interés en ' . $this->vacante->empresa . '.')
                    ->action('Ver Vacante', url('/vacantes/' . $this->vacante->id));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'vacante_id' => $this->vacante->id,
            'vacante_titulo' => $this->vacante->titulo,
            'estado' => $this->estado,
            'mensaje' => $this->mensaje
        ];
    }
}

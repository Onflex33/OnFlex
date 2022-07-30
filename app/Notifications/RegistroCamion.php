<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegistroCamion extends Notification
{
    use Queueable;
    protected $camion;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($camion)
    {
        $this->camion = $camion;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $variable = $this->camion->placa;
        return (new MailMessage)
                    ->greeting('Hola!')
                    ->subject('Notification de Registro de Camión')
                    ->line("Se agregó a su cuenta el camión placa $variable")
                    ->action('Notification Action', url('/'))
                    ->line('Gracias por ser parte de OnFlex!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
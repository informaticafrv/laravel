<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NuevoComentario extends Notification
{
    use Queueable;

    public function __construct(public Comment $comment) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'mensaje'   => "{$this->comment->user->name} comentó en {$this->comment->game->titulo}",
            'game_id'   => $this->comment->game_id,
            'game_titulo' => $this->comment->game->titulo,
            'usuario'   => $this->comment->user->name,
        ];
    }
}

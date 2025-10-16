<?php
declare(strict_types=1);

namespace Infrastructure\Persistence; // Namespace de Infraestructura

use Domain\Entity\Game;
use Domain\Repository\GameRepository;

final class SessionGameRepository implements GameRepository
{
    private string $key;

    public function __construct(string $key = 'ahorcado_game')
    {
        $this->key = $key;
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            session_start();
        }
    }

    public function find(): ?Game
    {
        if (!isset($_SESSION[$this->key])) {
            return null;
        }
        
        $state = $_SESSION[$this->key];
        return new Game($state['word'], 6, $state);
    }

    public function save(Game $game): void
    {
        $_SESSION[$this->key] = $game->toState();
    }

    public function clear(): void
    {
        if (isset($_SESSION[$this->key])) {
            unset($_SESSION[$this->key]);
        }
    }
}
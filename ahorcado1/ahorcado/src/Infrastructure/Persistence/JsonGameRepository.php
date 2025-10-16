<?php
declare(strict_types=1);

namespace Infrastructure\Persistence;

use Domain\Entity\Game;
use Domain\Repository\GameRepository;

final class JsonGameRepository implements GameRepository
{
    private const GAME_FILE = __DIR__ . '/../../../storage/game_state.json';

    public function find(): ?Game
    {
        if (!file_exists(self::GAME_FILE)) {
            return null;
        }

        $content = file_get_contents(self::GAME_FILE);
        if (empty($content)) {
            return null;
        }

        $state = json_decode($content, true);

        if ($state === null || !isset($state['word'])) {
            return null;
        }

        return new Game($state['word'], 6, $state);
    }

    public function save(Game $game): void
    {
        file_put_contents(self::GAME_FILE, json_encode($game->toState(), JSON_PRETTY_PRINT));
    }

    public function clear(): void
    {
        if (file_exists(self::GAME_FILE)) {
            unlink(self::GAME_FILE);
        }
    }
}
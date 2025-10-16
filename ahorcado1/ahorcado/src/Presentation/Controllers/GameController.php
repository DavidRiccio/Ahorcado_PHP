<?php
declare(strict_types=1);

namespace Presentation\Controllers; 

use Domain\Entity\Game;
use Domain\Repository\GameRepository;
use Domain\Repository\WordRepository;

final class GameController
{
    public function __construct(
        private GameRepository $gameRepository,
        private WordRepository $wordRepository
    ) {}

    public function start(string $category): void
    {
        $word = $this->wordRepository->getRandomWord($category);
        $game = new Game($word);
        $this->gameRepository->save($game);
    }

    public function guess(string $letter): void
    {
        $game = $this->gameRepository->find();
        if ($game && !$game->isWon() && !$game->isLost()) {
            $game->guessLetter($letter);
            $this->gameRepository->save($game);
        }
    }

    public function getCurrentGame(): ?Game
    {
        return $this->gameRepository->find();
    }

    public function getCategories(): array
    {
        return $this->wordRepository->getAvailableCategories();
    }
}
<?php
declare(strict_types=1);

namespace Domain\Repository; 

use Domain\Entity\Game; 


interface GameRepository
{
    public function save(Game $game): void;
    public function find(): ?Game;
    public function clear(): void;
}
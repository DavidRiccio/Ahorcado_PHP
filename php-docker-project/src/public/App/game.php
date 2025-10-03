<?php
declare(strict_types=1);
namespace src\public\App;

final class Game{
    public array $usedLetters ;

    public function __construct(
    public  int $maxAttempts,
    public string $word,
    public int $attemptsLeft = 6
    ) {}
        
    public function guessLetter(string $letter): void {

    }
    public function getMaskedWord(): string {
        
    }
    public function getAttempsLeft(): int {
        
    }
    public function getUsedLetters():array{

    }
    public function isWon(): bool{

    }
    public function isLost(): bool{
        
    }
    public function toState():array{
        
    }
}



?>
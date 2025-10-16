<?php
declare(strict_types=1);

namespace Domain\Entity; 



final class Game
{
    private string $word;
    private int $maxAttempts;
    private int $attemptsLeft;
    private array $usedLetters;

    public function __construct(string $word, int $maxAttempts = 6, ?array $state = null)
    {
        $this->word = strtoupper($word);
        $this->maxAttempts = $maxAttempts;
        
        if ($state !== null) {
            $this->attemptsLeft = $state['attemptsLeft'];
            $this->usedLetters = $state['usedLetters'];
        } else {
            $this->attemptsLeft = $maxAttempts;
            $this->usedLetters = [];
        }
    }

    
    
    public function getMaskedWord(): string
    {
        $masked = '';
        foreach (str_split($this->word) as $letter) {
            $masked .= in_array($letter, $this->usedLetters, true) ? $letter : '_';
        }
        return $masked;
    }

    public function guessLetter(string $letter): bool
    {
        $letter = strtoupper($letter);
        if (in_array($letter, $this->usedLetters, true)) {
            return false;
        }
        $this->usedLetters[] = $letter;
        if (strpos($this->word, $letter) === false) {
            $this->attemptsLeft--;
            return false;
        }
        return true;
    }

    public function isWon(): bool
    {
        foreach (str_split($this->word) as $char) {
            if (!in_array($char, $this->usedLetters, true)) {
                return false;
            }
        }
        return true;
    }

    public function isLost(): bool
    {
        return $this->attemptsLeft <= 0;
    }

    public function getAttemptsLeft(): int
    {
        return $this->attemptsLeft;
    }

    public function getUsedLetters(): array
    {
        return $this->usedLetters;
    }

    public function getWord(): string
    {
        return $this->word;
    }

    public function toState(): array
    {
        return [
            'word' => $this->word,
            'attemptsLeft' => $this->attemptsLeft,
            'usedLetters' => $this->usedLetters
        ];
    }
}
<?php
declare(strict_types=1);

namespace App\Domain\Entity;

final class Renderer
{
    public function ascii(int $attemptsLeft): string
    {
        $maxAttempts = 6;
        $failedAttempts = $maxAttempts - $attemptsLeft;
        
        $stages = [
            <<<'ASCII'
  ┌────┐
  │    │
       │
       │
       │
       │
═══════╧═
ASCII,
            <<<'ASCII'
  ┌────┐
  │    │
  ◯    │
       │
       │
       │
═══════╧═
ASCII,
            <<<'ASCII'
  ┌────┐
  │    │
  ◯    │
  │    │
       │
       │
═══════╧═
ASCII,
            <<<'ASCII'
  ┌────┐
  │    │
  ◯    │
 ╱│    │
       │
       │
═══════╧═
ASCII,
            <<<'ASCII'
  ┌────┐
  │    │
  ◯    │
 ╱│╲   │
       │
       │
═══════╧═
ASCII,
            <<<'ASCII'
  ┌────┐
  │    │
  ◯    │
 ╱│╲   │
 ╱     │
       │
═══════╧═
ASCII,
            <<<'ASCII'
  ┌────┐
  │    │
  ◯    │
 ╱│╲   │
 ╱ ╲   │
       │
═══════╧═
ASCII
        ];
        
        $index = max(0, min($maxAttempts, $failedAttempts));
        
        return '<pre>' . $stages[$index] . '</pre>';
}

}
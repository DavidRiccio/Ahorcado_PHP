<?php
declare(strict_types=1);

namespace Domain\Repository; 


interface WordRepository
{
    public function getRandomWord(string $category): string;
    public function getAvailableCategories(): array;
}
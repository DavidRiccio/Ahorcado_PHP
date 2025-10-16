<?php
declare(strict_types=1);

namespace Infrastructure\Persistence;

use Domain\Repository\WordRepository;

final class JsonWordRepository implements WordRepository
{
    private array $data;
   
    private const WORDS_FILE = __DIR__ . '/../../../storage/words.json';

    public function __construct() {
        if (!file_exists(self::WORDS_FILE)) {
            throw new \RuntimeException("El archivo de palabras no existe: " . self::WORDS_FILE);
        }
        $jsonContent = file_get_contents(self::WORDS_FILE);
        $this->data = json_decode($jsonContent, true);
        if ($this->data === null) {
            throw new \RuntimeException("El archivo JSON de palabras no es válido.");
        }
    }

    public function getRandomWord(string $category): string {
        if (!isset($this->data[$category]) || empty($this->data[$category]['words'])) {
            throw new \RuntimeException("La categoría '{$category}' no existe o no tiene palabras.");
        }
        $words = $this->data[$category]['words'];
        return strtoupper($words[array_rand($words)]);
    }

    public function getAvailableCategories(): array {
        return array_keys($this->data);
    }
}
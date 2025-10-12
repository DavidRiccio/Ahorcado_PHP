<?php

namespace App\Domain\Entity;

class WordProvider {
    private $words;
    private $category;
    private const WORDS_FILE = __DIR__ . '/../../../storage/words.json';

    public function __construct(string $category = 'programming') {
        $this->category = $category;
        $this->loadWords();
    }

    private function loadWords(): void {
        if (!file_exists(self::WORDS_FILE)) {
            throw new \RuntimeException("El archivo de palabras no existe");
        }

        $jsonContent = file_get_contents(self::WORDS_FILE);
        if ($jsonContent === false) {
            throw new \RuntimeException("No se pudo leer el archivo de palabras");
        }

        $data = json_decode($jsonContent, true);
        if ($data === null) {
            throw new \RuntimeException("El archivo no contiene un JSON válido");
        }

        if (!isset($data[$this->category]) || !isset($data[$this->category]['words'])) {
            throw new \RuntimeException("La categoría '{$this->category}' no existe en el archivo");
        }

        $this->words = $data[$this->category]['words'];
        
        if (empty($this->words)) {
            throw new \RuntimeException("No hay palabras disponibles para la categoría '{$this->category}'");
        }
    }

    public function randomWord(): string {
        if (empty($this->words)) {
            throw new \RuntimeException("No hay palabras disponibles");
        }
        return strtoupper($this->words[array_rand($this->words)]);
    }

    public function getCategory(): string {
        return $this->category;
    }

    public static function getAvailableCategories(): array {
        if (!file_exists(self::WORDS_FILE)) {
            throw new \RuntimeException("El archivo de palabras no existe");
        }

        $jsonContent = file_get_contents(self::WORDS_FILE);
        if ($jsonContent === false) {
            throw new \RuntimeException("No se pudo leer el archivo de palabras");
        }

        $data = json_decode($jsonContent, true);
        if ($data === null) {
            throw new \RuntimeException("El archivo no contiene un JSON válido");
        }

        return array_keys($data);
    }
}
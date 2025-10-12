<?php
declare(strict_types=1);

namespace App\Domain\Entity;

final class Storage
{
    private string $key;

    public function __construct(string $key = 'ahorcado')
    {
        $this->key = $key;
        $this->initSession();
    }

    private function initSession(): void
    {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            session_start();
        }
        
        if (!isset($_SESSION[$this->key])) {
            $_SESSION[$this->key] = [];
        }
    }

    public function get(string $name, $default = null)
    {
        if (isset($_SESSION[$this->key][$name])) {
            return $_SESSION[$this->key][$name];
        }
        
        return $default;
    }

    public function set(string $name, $value): void
    {
        if (!isset($_SESSION[$this->key])) {
            $_SESSION[$this->key] = [];
        }
        
        $_SESSION[$this->key][$name] = $value;
    }

    public function reset(): void
    {
        if (isset($_SESSION[$this->key])) {
            unset($_SESSION[$this->key]);
        }
        
        $_SESSION[$this->key] = [];
    }
}
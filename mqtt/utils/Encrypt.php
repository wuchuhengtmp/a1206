<?php

declare(strict_types=1);

namespace Utils;


class Encrypt
{
    public function hash(string $string): string
    {
        $k = env('ENCRYPT_KEY');
        $ns = $k . $string . $k;
        return hash('sha256', $ns);
    }

    public function check(string $encryptKey, string $string): bool
    {
        return $this->hash($string) === $encryptKey;
    }
}
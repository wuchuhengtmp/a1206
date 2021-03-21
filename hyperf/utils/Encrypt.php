<?php

declare(strict_types=1);

namespace Utils;


use App\Events\WebsocketEvents\BaseEvent;

class Encrypt
{
    static public function hash(string $string): string
    {
        $k = env('ENCRYPT_KEY');
        $ns = $k . $string . $k;
        return hash('sha256', $ns);
    }

    static public function check(string $encryptKey, string $string): bool
    {
        return self::hash($string) === $encryptKey;
    }
}
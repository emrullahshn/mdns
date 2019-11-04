<?php
namespace App\Library;

use Exception;

class Utils
{
    /**
     * @return string
     * @throws Exception
     */
    public static function generateToken(): string
    {
        return bin2hex(random_bytes(16));
    }
}

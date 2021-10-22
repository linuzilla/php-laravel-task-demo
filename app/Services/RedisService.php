<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use Redis;

class RedisService {
    private static Redis $client;

    public static function initialize() {
        if (!isset(self::$client)) {
            $config = Config::get('database.redis.default');
            self::$client = new Redis();
            self::$client->connect($config['host'], $config['port']);
        }
    }

    public static function store(string $variableName, string $value, int $ttl): bool {
        self::initialize();

        if (self::$client->set($variableName, $value, ['ex' => $ttl]) === false) {
            return false;
        }
        return true;
    }

    public static function atomic(string $variableName, string $value, int $ttl): bool {
        self::initialize();

        if (self::$client->set($variableName, $value, ['nx', 'ex' => $ttl]) === false) {
            return false;
        }
        return true;
    }

    /**
     * @param string $variableName
     * @return string|bool
     */
    public static function retrieve(string $variableName): string|bool {
        self::initialize();
        return self::$client->get($variableName);
    }

    /**
     * @param string $variableName
     * @return bool
     */
    public static function exists(string $variableName): bool {
        self::initialize();
        return self::$client->exists($variableName);
    }

    /**
     * @param string $variableName
     * @param string $defaultValue
     * @return string
     */
    public static function retrieveOrDefault(string $variableName, string $defaultValue): string {
        self::initialize();
        $storedValue = self::$client->get($variableName);

        if ($storedValue === false) {
            return $defaultValue;
        } else {
            return $storedValue;
        }
    }

    /**
     * @param string $variableName
     */
    public static function delete(string $variableName) {
        self::initialize();
        self::$client->del($variableName);
    }
}

<?php

namespace App\Models;

use App\Auth\NoRememberTokenAuthenticatable;
use Illuminate\Contracts\Auth\Authenticatable;

class PortalUser implements Authenticatable {
    use NoRememberTokenAuthenticatable;

    /** @var int */
    private int $id;
    /** @var string */
    private string $username;

    /**
     * @param int $id
     * @param string $username
     */
    public function __construct(int $id, string $username) {
        $this->id = $id;
        $this->username = $username;
    }

    /**
     * @return int
     */
    public function id(): int {
        return $this->id;
    }

    /**
     * @return string
     */
    public function username(): string {
        return $this->username;
    }


}

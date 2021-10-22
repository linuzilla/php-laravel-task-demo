<?php

declare(strict_types=1);

namespace App\Auth\Guards;

use App\Constants\SessionVariables;
use App\Models\PortalUser as User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

class OAuthGuard implements Guard {
    /**
     * @var null|Authenticatable|User
     */
    protected $user;

    /**
     * @var Request
     */
    protected $request;

    /**
     * OpenAPIGuard constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request) {
        $value = $request->session()->get(SessionVariables::USER_SESSION_VARS);
        if (!is_null($value)) {
            $this->setUser($value);
        }
        $this->request = $request;
    }

    /**
     * Check whether user is logged in.
     *
     * @return bool
     */
    public function check(): bool {
        return (bool)$this->user();
    }

    /**
     * Check whether user is not logged in.
     *
     * @return bool
     */
    public function guest(): bool {
        return !$this->check();
    }

    /**
     * Return user id or null.
     *
     * @return null|int
     */
    public function id(): ?int {
        $user = $this->user();
        return $user->id ?? null;
    }

    /**
     * Manually set user as logged in.
     *
     * @param null|User|Authenticatable $user
     * @return $this
     */
    public function setUser(?Authenticatable $user): self {
        $this->user = $user;
        return $this;
    }

    /**
     * @param array $credentials
     * @return bool
     */
    public function validate(array $credentials = []): bool {
        throw new \BadMethodCallException('Unexpected method call');
    }

    /**
     * Return user or throw AuthenticationException.
     *
     * @return User
     * @throws AuthenticationException
     */
    public function authenticate(): User {
        $user = $this->user();
        if ($user instanceof User) {
            return $user;
        }
        throw new AuthenticationException();
    }

    /**
     * Return cached user or newly authenticate user.
     *
     * @return null|User|Authenticatable
     */
    public function user(): ?User {
        return $this->user;
    }

    /**
     * Logout user.
     */
    public function logout(): void {
        if ($this->user) {
            $this->setUser(null);
//            $this->request->session()->forget(SessionVariables::USER_SESSION_VARS);
            $this->request->session()->invalidate();
        }
    }

    public function login(User $user) {
        $this->request->session()->put(SessionVariables::USER_SESSION_VARS, $user);
        $this->setUser($user);
    }
}


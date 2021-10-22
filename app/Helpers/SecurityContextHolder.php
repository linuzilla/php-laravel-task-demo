<?php

namespace App\Helpers;

use App\Models\PortalUser;
use Illuminate\Support\Facades\Auth;

class SecurityContextHolder {
    /**
     * @return string
     */
    public static function name(): string {
        $user = Auth::user();
        if ($user instanceof PortalUser) {
            /** @var PortalUser $user */
            return $user->username();
        }
        return "";
    }
}

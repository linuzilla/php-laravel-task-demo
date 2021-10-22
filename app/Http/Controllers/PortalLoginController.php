<?php

namespace App\Http\Controllers;

use App\Models\PortalUser;
use Illuminate\Support\Facades\Auth;

class PortalLoginController extends Controller {
    public function redirectToProvider() {
        return \Socialite::with('portal')->redirect();
    }

    public function handleProviderCallback() {
        $user = \Socialite::with('portal')->user();

        Auth::login(new PortalUser($user->id, $user->username));

        return redirect()->intended('dashboard');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function loginSocial(Request $request, string $provider)
    {
        $this->validateProvider($request);
        return Socialite::driver($provider)
            ->with(['config_id' => env('FACEBOOK_CONFIG_ID')])
            ->redirect();
    }

    public function callbackSocial(Request $request, string $provider)
    {
        $this->validateProvider($request);

        $response = Socialite::driver($provider)->stateless()->user();

        $user = User::firstOrCreate(
            ['email' => $response->getEmail()],
            ['password' => str()->password()]
        );
        $data = [$provider . '_id' => $response->getId()];

        if ($user->wasRecentlyCreated) {
            $data['name'] = $response->getName() ?? $response->getNickname();
            $data['avatar'] = $response->getAvatar();

            event(new Registered($user));
        }
        $user->update($data);

        Auth::login($user, remember: true);

        return redirect()->route('admin.dashboard');
    }

    protected function validateProvider(Request $request): array
    {
        return $this->getValidationFactory()->make(
            $request->route()->parameters(),
            ['provider' => 'in:facebook,google,github']
        )->validate();
    }
}

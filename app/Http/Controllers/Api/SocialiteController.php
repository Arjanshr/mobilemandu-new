<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends BaseController
{
    public function loginSocial(string $provider=null)
    {
        if ($provider == 'facebook') {
            $data['FACEBOOK_CLIENT_ID'] = env('FACEBOOK_CLIENT_ID');
            $data['FACEBOOK_CLIENT_SECRET'] = env('FACEBOOK_CLIENT_SECRET');
            $data['FACEBOOK_REDIRECT_URI'] = env('FACEBOOK_REDIRECT_URI');
            $data['FACEBOOK_CONFIG_ID'] = env('FACEBOOK_CONFIG_ID');
        } elseif ($provider == 'facebook') {

            $data['GOOGLE_CLIENT_ID'] = env('GOOGLE_CLIENT_ID');
            $data['GOOGLE_CLIENT_SECRET'] = env('GOOGLE_CLIENT_SECRET');
            $data['GOOGLE_REDIRECT_URI'] = env('GOOGLE_REDIRECT_URI');
        } else {
            $data['facebook']['FACEBOOK_CLIENT_ID'] = env('FACEBOOK_CLIENT_ID');
            $data['facebook']['FACEBOOK_CLIENT_SECRET'] = env('FACEBOOK_CLIENT_SECRET');
            $data['facebook']['FACEBOOK_REDIRECT_URI'] = env('FACEBOOK_REDIRECT_URI');
            $data['facebook']['FACEBOOK_CONFIG_ID'] = env('FACEBOOK_CONFIG_ID');
            $data['google']['GOOGLE_CLIENT_ID'] = env('GOOGLE_CLIENT_ID');
            $data['google']['GOOGLE_CLIENT_SECRET'] = env('GOOGLE_CLIENT_SECRET');
            $data['google']['GOOGLE_REDIRECT_URI'] = env('GOOGLE_REDIRECT_URI');
        }
        return $this->sendResponse($data, 'Data retrived successfully.');
    }

    public function callbackSocial(Request $request, string $provider)
    {
        $this->validateProvider($request);

        $response = Socialite::driver($provider)->stateless()->user();

        $user = User::firstOrCreate(
            ['email' => $response->getEmail()],
            ['password' => str()->password()]
        )->assignRole('customer');
        $data = [$provider . '_id' => $response->getId()];

        if ($user->wasRecentlyCreated) {
            $data['name'] = $response->getName() ?? $response->getNickname();
            $data['avatar'] = $response->getAvatar();

            event(new Registered($user));
        }
        $user->update($data);
        Auth::login($user, remember: true);
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')->plainTextToken;
            $success['name'] =  $user->name;

            return $this->sendResponse($success, 'User login successfully.');
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
        }
    }

    protected function validateProvider(Request $request): array
    {
        return $this->getValidationFactory()->make(
            $request->route()->parameters(),
            ['provider' => 'in:facebook,google,github']
        )->validate();
    }
}

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

    public function callbackSocial(Request $request, $provider)
    {
        $providers['provider'] = $provider;
        $this->validateProvider($providers);
        // return $request;

        // $response = Socialite::driver($provider)->stateless()->user();

        // return $response;

        $user = User::firstOrCreate(
            ['email' => $request->email],
            ['password' => str()->password()]
        )->assignRole('customer');
        $data = [$provider . '_id' => $request->facebook_id];

        if ($user->wasRecentlyCreated) {
            $data['name'] = $request->name?? $request->nickname;
            $data['avatar'] = $request->avatar_url;

            event(new Registered($user));
        }
        $user->update($data);
        // return $user->createToken('MyApp')->plainTextToken;
        // Auth::login($user, remember: true);
        // if (Auth::attempt(['email' => $request->email])) {
            // $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')->plainTextToken;
            $success['name'] =  $user->name;

            return $this->sendResponse($success, 'User login successfully.');
        // } else {
        //     return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
        // }
    }

    protected function validateProvider($provider): array
    {
        return $this->getValidationFactory()->make(
            $provider,
            ['provider' => 'in:facebook,google,github']
        )->validate();
    }
}

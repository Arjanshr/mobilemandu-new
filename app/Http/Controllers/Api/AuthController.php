<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;


class AuthController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'nullable|string',
            'email' => 'required|email',
            'password' => 'required',
            'password_confirmation' => 'required|same:password',
            'gender' => 'nullable|in:male,female',
            'dob' => 'nullable|date',
            'photo' => ['nullable', 'mimes:jpeg,png,jpg,gif,svg,ico,pdf', 'max:2048'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        if ($request->hasFile('photo')) {
            $image_name = rand(0, 99999) . time() . '.' . $request->photo->extension();
            $request->photo->move(storage_path('app/public/profile-photos/'), $image_name);
            $input['profile_photo_path'] = 'profile-photos/'.$image_name;
        }
        $user = User::create($input)->assignRole('customer');
        $success['token'] =  $user->createToken('MyApp')->plainTextToken;
        $success['name'] =  $user->name;

        return $this->sendResponse($success, 'User register successfully.');
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')->plainTextToken;
            $success['name'] =  $user->name;

            return $this->sendResponse($success, 'User login successfully.');
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
        }
    }

    public function logout()
    {
        $user = auth()->user()->tokens();
        $user->delete();
        return $this->sendResponse(null, 'User logout successfully.');
    }

    public function resetPassword(Request $request)
    {
        // return $request;
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|string|min:6'
        ]);
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill(
                    [
                        'password' => bcrypt($password)
                    ]
                )->save();
            }
        );
        return $status === Password::PASSWORD_RESET ? $this->sendResponse(null, __($status)) : $this->sendError(__($status));
    }

    public function resetPasswordSendEmail(Request $request)
    {
        $request->validate([
            'reset_url' => 'required|url',
            'email' => 'required|email'
        ]);
        $status = Password::sendResetLink($request->only('email'));
        return $status === Password::RESET_LINK_SENT ? $this->sendResponse(null, __($status)) : $this->sendError(__($status));
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\AddressResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    public function profile()
    {
        $user = auth()->user();
        return $this->sendResponse(UserResource::make($user), 'Profile retrieved successfully.');
    }
    public function addresses()
    {
        $addresses = auth()->user()->addresses;
        return $this->sendResponse(AddressResource::collection($addresses), 'Addresses retrieved successfully.');
    }

    public function editProfile(UserRequest $request)
    {
        return $request;
        $user = auth()->user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->dob = $request->dob;
        $user->gender = $request->gender;
        $user->address = $request->address;
        if ($this->passwordValidation($request->password))
            $user->password = bcrypt($request->password);
        $user->save();
        return $this->sendResponse($user, 'Profile updated successfully.');
    }

    private function passwordValidation($password)
    {
        if(strlen($password)>=6) return true;
        return false;
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
}

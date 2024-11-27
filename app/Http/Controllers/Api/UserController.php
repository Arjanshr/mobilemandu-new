<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\AddressResource;
use App\Http\Resources\OrderItemResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\UserResource;
use App\Models\Order;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
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

    public function editPassword(Request $request)
    {
        $user = auth()->user();
        $this->validate($request, [
            'password' => 'required',
            'new_password' => 'confirmed|min:6|different:password',
        ]);
        if (Hash::check($request->password, $user->password)) { 
            $user->fill([
             'password' => Hash::make($request->new_password)
             ])->save();
         
            return $this->sendResponse(null, 'Password updated successfully.');
         
         } else {
            return $this->sendError('error', 'Password does not match.');
         }
    }

    private function passwordValidation($password)
    {
        if (strlen($password) >= 6) return true;
        return false;
    }

    public function orders()
    {
        $orders = auth()->user()->orders;
        return $this->sendResponse(OrderResource::collection($orders), 'Orders retrieved successfully.');
    }
    

    public function orderItems(Order $order)
    {
        $order_items = $order->order_items;
        return $this->sendResponse(OrderItemResource::collection($order_items), 'Order Items retrieved successfully.');
    }
}

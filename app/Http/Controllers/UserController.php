<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(100);
        return view('admin.user.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all()->pluck('name');
        return view('admin.user.form', compact('roles'));
    }

    public function insert(UserRequest $request)
    {
        $data = $request->all();
        $data['password'] = bcrypt('password');
        $user = User::create($data);
        $roles = $this->checkRoles($request->role);
        // return $roles;
        $user->assignRole($request->role);
        toastr()->success('User Created Successfully!');
        return redirect()->route('users');
    }

    public function show(User $user)
    {
        return view('admin.user.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::all()->pluck('name');
        return view('admin.user.form', compact('roles', 'user'));
    }

    public function update(User $user, UserRequest $request)
    {
        // return $request;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->dob = $request->dob;
        $user->gender = $request->gender;
        $user->address = $request->address;
        if (auth()->user()->can('change-user-password')&&$this->passwordValidation($request->password))
            $user->password = bcrypt($request->password);
        $user->save();
        $user->syncRoles([$request->input('role')]);
        toastr()->success('User Edited Successfully!');
        return redirect()->route('users');
    }

    public function delete(User $user)
    {
        if ($user->isAdmin() & !auth()->user()->can('delete-admin'))
            return redirect()->route('users')->withError('User cannot be deleted!');
        $user->delete();
        toastr()->success('User Deleted Successfully!');
        return redirect()->route('users');
    }

    private function checkRoles(array $roles): array
    {
        if (in_array('super-admin', $roles)) {
            $index = array_search('super-admin', $roles);

            if ($index !== false) {
                unset($roles[$index]);
            }
        }
        if (in_array('admin', $roles) && !auth()->user()->can('add-admin')) {
            $index = array_search('admin', $roles);

            if ($index !== false) {
                unset($roles[$index]);
            }
        }
        return $roles;
    }

    public function activities(User $user)
    {
        $activities = Activity::where('causer_id', $user->id)->orderBy('id', 'DESC')->paginate(100);
        return view('admin.user.activities', compact('activities'));
    }

    public function showActivity(Activity $activity)
    {
        return view('admin.user.show_activity', compact('activity'));
    }

    private function passwordValidation($password)
    {
        if(strlen($password)>=6) return true;
        return false;
    }
}

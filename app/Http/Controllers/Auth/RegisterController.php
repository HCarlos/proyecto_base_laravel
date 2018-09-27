<?php

namespace App\Http\Controllers\Auth;

use App\Permission;
use App\Role;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new user as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect user after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => 'required|string|max:255|unique:user',
            'email' => 'required|string|email|max:255|unique:user',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $ip   = $_SERVER['REMOTE_ADDR'];
        $host = gethostbyaddr($_SERVER['REMOTE_ADDR']);
        $idemp = 1;
        $user = User::create([
            'name' => $data['username'],
            'username' => strtolower($data['username']),
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'empresa_id' => $idemp,
            'ip' => $ip,
            'host' => $host,
        ]);
        $role = Role::where('name', 'Profesor')->first();
        $user->roles()->attach($role);
        $permission = Permission::where('name', 'consultar')->first();
        $user->permissions()->attach($permission);

        return $user;

    }

}

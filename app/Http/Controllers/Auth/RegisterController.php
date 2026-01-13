<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

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
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone1' => ['required', 'string', 'max:20'],
            'phone2' => ['nullable', 'string', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */


    protected function create(array $data)
    {
        // Generate unique slug
        $baseSlug = Str::slug($data['first_name'] . '-' . $data['last_name']);
        $slug = $baseSlug;
        $count = 1;

        while (User::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $count++;
        }
        return User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'phone1' => $data['phone1'],
            'phone2' => $data['phone2'] ?? null,
            'email' => $data['email'],
            'slug' => $slug,
            'password' => Hash::make($data['password']),
        ]);
    }
}

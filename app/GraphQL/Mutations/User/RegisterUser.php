<?php declare(strict_types=1);

namespace App\GraphQL\Mutations\User;

use App\Models\CustomUser;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

final readonly class RegisterUser
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $validator = Validator::make($args, 
            [
                'name' => 'required|string|regex:/^[A-Za-z ]+$/|max:255',
                'email' => 'required|email|unique:custom_user,email',
                'password' => 'required|min:6',
                'confirm_password' => 'required|same:password',
                'role_id' => 'required|integer|exists:roles,id',
            ],
            [
                'name.required' => 'Please provide your name.',
                'name.regex' => 'Please provide only alphabet value',
                'email.required' => 'Email is required.',
                'email.email' => 'Please enter a valid email address.',
                'email.unique' => 'Email already exist.',
                'password.required' => 'You must set a password.',
                'password.min' => 'Password must be at least :min characters.',
                'confirm_password.same' => 'The password confirmation does not match.',
                'role_id.exists' => 'The selected role is invalid.',
            ]
        );
        $validator->validate();

        // $user = new CustomUser();
        // $user->name =  $args['name'];
        // $user->email =  $args['email'];
        // $user->password =  $args['password'];
        // $user->confirm_password =  $args['confirm_password'];
        // $user->role =  $args['role_id'];
        // $user->save();
        
        $user = CustomUser::create([
            'name' => $args['name'],
            'email' => $args['email'],
            'password' => Hash::make($args['password']),
            'confirm_password' => Hash::make($args['confirm_password']),
            'role_id' => $args['role_id'],
        ]);

        return [
            'user' => $user,
            'message' => "Register successfully, please proceed to login.",
        ];
    }
}

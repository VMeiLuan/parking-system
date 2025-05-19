<?php declare(strict_types=1);

namespace App\GraphQL\Mutations\User;

use App\Models\CustomUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

final readonly class LoginUser
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $validator = Validator::make($args, 
            [
                'email' => 'required|email',
                'password' => 'required|min:6',
            ],
            [
                'email.required' => 'Email is required.',
                'email.email' => 'Please enter a valid email address.',
                'password.required' => 'You must set a password.',
                'password.min' => 'Password must be at least :min characters.',
            ]
        );
        $validator->validate();

        $existingUser = CustomUser::where('email', $args['email'])->first();
        $inputPassword = Hash::check($args['password'], $existingUser->password);
        if(!$existingUser || !$inputPassword){
            return [ 'message' => 'Something wrong, please try again.' ];
        }

        $existingUser->tokens()->delete();
        $token = $existingUser->createToken('auth_token')->plainTextToken;
        // Auth::login($existingUser);

        return [ "user" => $existingUser, "token" => $token, "message" => "Login sucessfully." ];
    }
}

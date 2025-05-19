<?php declare(strict_types=1);

namespace App\GraphQL\Mutations\User;

use App\Models\CustomUser;
use Illuminate\Support\Facades\Auth;

final readonly class LogoutUser
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $user = Auth::user();

        if (!$user) return [ 'status' => false, 'message' => 'User not authenticated.' ];

        $user->currentAccessToken()->delete();

        return [ 'status' => true, 'message' => 'Logout successful.' ];
    }
}

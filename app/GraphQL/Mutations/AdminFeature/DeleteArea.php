<?php declare(strict_types=1);

namespace App\GraphQL\Mutations\AdminFeature;

use App\Models\Area as AreaModel;

final readonly class DeleteArea
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $user = auth('sanctum')->user();
        if(!$user) return [ 'status' => false, 'message' => 'No authentication'];
    
        $existingArea = AreaModel::find($args['id']);
        if(!$existingArea) return [ 'status' => false, 'message' => 'Invalid'];

        if($user->role->role == 'Superadmin' || $user->role->role == 'Admin'){
            $existingArea->delete();

            return [ 'status' => true, 'message' => 'Delete successfully.'];
        }

        return [ 'status' => false, 'message' => 'Invalid'];
    }
}

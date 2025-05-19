<?php declare(strict_types=1);

namespace App\GraphQL\Mutations\AdminFeature;

use App\Models\ParkingRate as ParkingRateModel;

final readonly class DeleteParkingRate
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $user = auth('sanctum')->user();
        if(!$user) return [ 'status' => false, 'message' => 'No authentication'];
    
        $existingParkingRate = ParkingRateModel::find($args['id']);
        if(!$existingParkingRate) return [ 'status' => false, 'message' => 'Invalid'];

        if($user->role->role == 'Superadmin' || $user->role->role == 'Admin'){
            $existingParkingRate->delete();

            return [ 'status' => true, 'message' => 'Delete successfully.'];
        }


        return [ 'status' => false, 'message' => 'Invalid'];
    }
}

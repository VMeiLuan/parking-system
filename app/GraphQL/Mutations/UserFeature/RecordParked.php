<?php declare(strict_types=1);

namespace App\GraphQL\Mutations\UserFeature;

use Illuminate\Support\Facades\Validator;
use App\Models\Parked as ParkedModel;
use App\Models\Area as AreaModel;


final readonly class RecordParked
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $validator = Validator::make($args, 
            [
                'area_id' => 'required|integer|exists:area,id',
                'custom_user_id' => 'required|integer|exists:custom_user,id',
            ],
            [
                'area_id.required' => 'The area is required.',
                'area_id.exists' => 'The area is invalid.',
                'custom_user_id.required' => 'Invalid user',
                'custom_user_id.exists' => 'Invalid user',
            ]
        );
        $validator->validate();

        $user = auth('sanctum')->user();
        if(!$user) return [ 'status' => false, 'message' => 'No authentication'];

        if ($user->role->role === 'User') {
            // user having record with payment status === 0 
            // return invalid

            $newRecord = new ParkedModel([
                'in' => $args['in'],
                'area_id' => $args['area_id'],
                'custom_user_id' => $args['custom_user_id'],
            ]);

            if ($newRecord->save()) {
                $updatedArea = AreaModel::find($args['area_id']);
                $users[] = $args['custom_user_id'];

                if($args['btn_type'] == 'btn_normal'){
                    $updatedArea->parking_space_normal = max(0, (int)$updatedArea->parking_space_normal - 1);

                    $updatedArea->parking_space_normal_user = $users;
                }    
                
                if($args['btn_type'] == 'btn_oku'){
                    $updatedArea->parking_space_oku = max(0, (int)$updatedArea->parking_space_oku - 1);
                    
                    $updatedArea->parking_space_oku_user = $users;
                }    
                
                $updatedArea->save();
            }

            return ['status' => true, 'message' => 'Added'];
        }
    }
}

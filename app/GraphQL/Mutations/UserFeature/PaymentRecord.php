<?php declare(strict_types=1);

namespace App\GraphQL\Mutations\UserFeature;

use App\Models\Parked as ParkedModel;
use App\Models\Area as AreaModel;
use Illuminate\Support\Facades\Validator;

final readonly class PaymentRecord
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $validator = Validator::make($args, 
            [
                'id' => 'required',
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
            $existingRecord = ParkedModel::find($args['id']);
            $existingRecord->total_payment = $args['total_payment'];
            $existingRecord->payment_status = true;
            $existingRecord->save();

            // update area record
            $updatedArea = AreaModel::find($args['area_id']);
            $customUserId = $args['custom_user_id']; 

            $wasUpdated = false;
            $normalUserRaw = $updatedArea->parking_space_normal_user ?? '[]';
            $okuUserRaw = $updatedArea->parking_space_oku_user ?? '[]';

            $normalUserIds = is_array($normalUserRaw) ? $normalUserRaw : json_decode($normalUserRaw, true);
            $okuUserIds = is_array($okuUserRaw) ? $okuUserRaw : json_decode($okuUserRaw, true);

            // normal parking
            if (($key = array_search($customUserId, $normalUserIds)) !== false) {
                unset($normalUserIds[$key]);
                $updatedArea->parking_space_normal_user = json_encode(array_values($normalUserIds));
                $updatedArea->parking_space_normal += 1;
                $wasUpdated = true;
            }

            // oku
            if (($key = array_search($customUserId, $okuUserIds)) !== false) {
                unset($okuUserIds[$key]);
                $updatedArea->parking_space_oku_user = json_encode(array_values($okuUserIds));
                $updatedArea->parking_space_oku += 1;
                $wasUpdated = true;
            }

            if ($wasUpdated) {
                $updatedArea->save();
            }

            return ['status' => true, 'message' => 'Updated'];
        }
    }
}
<?php declare(strict_types=1);

namespace App\GraphQL\Mutations\AdminFeature;
use Illuminate\Support\Facades\Auth;
use App\Models\Area as Area;
use Illuminate\Support\Facades\Validator;
use App\Models\Area as AreaModel;

final readonly class CreateArea
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $validator = Validator::make($args, 
            [
                'title' => 'required|string|max:255|unique:area,title',
                'parking_space_normal' => 'required|integer',
                'parking_space_oku' => 'required|integer',
                'parking_rate_id' => 'required|exists:parking_rates,id',
            ],
            [
                'title.required' => 'Title is required.',
                'title.unique' => 'Title exist.',
                'parking_space_normal.required' => 'Parking space amount is required.',
                'parking_space_oku.required' => 'Parking space amount is required.',
                'parking_rate_id.required' => 'Parking rate is required.',
                'parking_rate_id.exists' => 'The selected parking rate is invalid.',
            ]
        );
        $validator->validate();

        $user = auth('sanctum')->user();
        if(!$user) return [ 'status' => false, 'message' => 'No authentication'];
        
        if($user->role->role == 'Superadmin' || $user->role->role == 'Admin'){
            $newRecord = new AreaModel();
            $newRecord->title = $args['title'];
            $newRecord->parking_space_normal = $args['parking_space_normal'];
            $newRecord->parking_space_oku = $args['parking_space_oku'];
            $newRecord->parking_rate_id = $args['parking_rate_id'];
            $newRecord->save();

            return [ 'status' => true, 'area' => $newRecord, 'message' => 'Created Successfully'];
        }


        return [ 'status' => false, 'message' => 'Invalid'];

    }
}

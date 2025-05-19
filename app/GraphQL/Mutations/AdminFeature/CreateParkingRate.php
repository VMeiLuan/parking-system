<?php declare(strict_types=1);

namespace App\GraphQL\Mutations\AdminFeature;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\ParkingRate as ParkingRateModel;

final readonly class CreateParkingRate
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $validator = Validator::make($args, 
            [
                'title' => 'required|string|max:255|unique:parking_rates,title',
                'hours' => 'required|string',
                'fees' => 'required',
                'description' => 'required',
            ],
            [
                'title.required' => 'Please provide your name.',
                'title.unique' => 'Title exist.',
                'hours.required' => 'Email is required.',
                'fees.required' => 'Fees is required.',
                'description.required' => 'Description is required.',
            ]
        );
        $validator->validate();

        // $user = Auth::user();
        // if(!$user) return [ 'status' => false, 'message' => 'No authentication'];

        // if($user->role->role == 'Superadmin' || $user->role->role == 'Admin'){
        $user = auth('sanctum')->user();
        if(!$user) return [ 'status' => false, 'message' => 'No authentication'];

        if($user->role->role == 'Superadmin' || $user->role->role == 'Admin'){
            $newRecord = new ParkingRateModel();
            $newRecord->title = $args['title'];
            $newRecord->hours = $args['hours'];
            $newRecord->fees = $args['fees'];
            $newRecord->description = $args['description'];
            $newRecord->remark = $args['remark'];
            $newRecord->save();

            return [ 'status' => true, 'parkingrate' => $newRecord, 'message' => 'Created Successfully'];
        }

        // }

        return [ 'status' => false, 'message' => 'Invalid'];
    }
}

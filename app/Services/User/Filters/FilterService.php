<?php

namespace App\Services\User\Filters;

use App\Http\Requests\User\Filters\GetSearchSettingsRequest;
use App\Http\Requests\User\Filters\UpdateFiltersRequest;
use App\Models\City;
use App\Models\Country;
use App\Models\Gender;
use App\Models\Settings;
use App\Models\Sex;
use App\Models\User\InterestType;
use App\Models\User\UserFilter;
use Illuminate\Http\JsonResponse;

/**
 * Class FilterService
 */
class FilterService
{
    public function updateFilters(UpdateFiltersRequest $request): JsonResponse
    {
        $user = $request->user();
        $filters = $user->filters;

        if ($filters === null) {
            $data = $request->validated();
            $settings = Settings::first();

            UserFilter::create([
                'user_id' => $user->id,
                'age_from' => $data['age_from'] ?? $settings->minimum_age,
                'age_to' => $data['age_to'] ?? $settings->maximum_age,
                'sexes' => $data['sexes'] ?? [],
                'genders' => $data['countries'] ?? [],
                'countries' => $data['countries'] ?? [],
                'city' => $data['city'] ?? null,
                'online' => $data['online'] ?? false,
                'keywords' => $data['keywords'] ?? [],
                'username' => $data['username'] ?? null,
                'interests' => $data['interests'] ?? [],
            ]);
        } else {
            $filters->update($request->validated());
        }

        return response()->json([
            'success' => true,
            'message' => 'Filters updated successfully',
        ]);
    }

    public function getCountry(int $id): JsonResponse
    {
        $country = Country::where('id', $id)->with(['cities'])->first();

        return response()->json([
            'success' => true,
            'message' => 'Countries fetched successfully',
            'country' => $country,
        ]);
    }

    public function getSearchSettings(GetSearchSettingsRequest $request): JsonResponse
    {
        $settings = Settings::first();
        $filters = $request->user()->filters;

        return response()->json([
            'message' => 'Successfully fetched all search settings',
            'settings' => [
                'genders' => Gender::all(),
                'sexes' => Sex::all(),
                'countries' => Country::all(),
                'interests' => InterestType::all(),
                'minimum_age' => $settings->minimum_age,
                'maximum_age' => $settings->maximum_age,
            ],
            'values' => [
                'age_from' => $filters->age_from ?? $settings->minimum_age,
                'age_to' => $filters->age_to ?? $settings->maximum_age,
                'sexes' => Sex::whereIn('id', ($filters) ? $filters->sexes->toArray() : [])->get(),
                'genders' => Gender::whereIn('id', ($filters) ? $filters->genders->toArray() : [])->get(),
                'countries' => Country::whereIn('id', ($filters) ? $filters->countries->toArray() : [])->get(),
                'city' => ($filters) ? City::find($filters->city) : null,
                'online' => ($filters) ? boolval($filters->online) : false,
                'keywords' => ($filters) ? $filters->keywords->toArray() : [],
                'username' => $filters->username ?? null,
                'interests' => InterestType::whereIn('id', ($filters) ? $filters->interests->toArray() : [])->get(),
            ],
            'success' => true,
        ]);
    }
}

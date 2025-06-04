<?php

namespace App\Services;

use App\Models\User\User;
use App\Models\User\UserFilter;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class SearchService
 */
class SearchService
{
    public function search(UserFilter $filter): LengthAwarePaginator
    {
        $users = User::query();

        if ($filter->sexes->count() > 0) {
            $users->whereIn('sex_id', json_decode($filter->sexes));
        }

        if ($filter->genders->count() > 0) {
            $users->whereIn('gender_id', json_decode($filter->genders));
        }

        if ($filter->countries->count() > 0) {
            $users->whereIn('country_id', json_decode($filter->countries));
        }

        if ($filter->cities->count() > 0) {
            $users->where('city_id', json_decode($filter->cities));
        }

        if ($filter->keywords->count() > 0) {
            $users->whereHas('userProfile', function ($query) use ($filter) {
                foreach ($filter->keywords as $keyword) {
                    $query->orWhere('description', 'like', '%'.$keyword.'%');
                    $query->orWhere('status', 'like', '%'.$keyword.'%');
                }
            });
        }

        if ($filter->age_from) {
            $beforeYear = Carbon::now()->subYears($filter->age_from)->format('Y-m-d');
            $users->whereDate('date_of_birth', '<=', $beforeYear);
        }

        if ($filter->age_to) {
            $afterYear = Carbon::now()->subYears($filter->age_to)->format('Y-m-d');
            $users->whereDate('date_of_birth', '>=', $afterYear);
        }

        return $users->paginate(10);
    }
}

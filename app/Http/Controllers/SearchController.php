<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchUserRequest;
use App\Http\Resources\SearchCollectionResource;
use App\Services\SearchService;

class SearchController extends Controller
{
    public function __construct(private readonly SearchService $searchService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(SearchUserRequest $request)
    {
        $response = $this->searchService->search(
            $request->user()->userFilter
        );

        return SearchCollectionResource::collection($response);
    }
}

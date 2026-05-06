<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MovieApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $cacheKey = 'movies_api_' . md5($request->getQueryString() ?? '');

        $data = Cache::remember($cacheKey, 300, function () use ($request) {
            $query = Movie::query();

            // Search by title
            if ($search = $request->query('search')) {
                $query->where('title', 'like', '%' . $search . '%');
            }

            // Filter by genre
            if ($genre = $request->query('genre')) {
                $query->where('genre', $genre);
            }

            // Filter by director
            if ($director = $request->query('director')) {
                $query->where('director', 'like', '%' . $director . '%');
            }

            // Filter by year
            if ($year = $request->query('year')) {
                $query->where('release_year', $year);
            }

            // Sorting
            $sort = $request->query('sort', 'created_at');
            $direction = $request->query('direction', 'desc');
            $allowedSorts = ['title', 'release_year', 'rating', 'created_at', 'director'];

            if (in_array($sort, $allowedSorts)) {
                $query->orderBy($sort, $direction === 'asc' ? 'asc' : 'desc');
            }

            // Limit
            $limit = $request->query('limit');
            if ($limit && is_numeric($limit) && $limit > 0) {
                $query->limit((int)$limit);
            }

            return $query->get();
        });

        return response()->json([
            'success' => true,
            'count' => count($data),
            'data' => $data,
        ]);
    }

    public function show(Movie $movie): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $movie,
        ]);
    }
}

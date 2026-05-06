<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class MovieController extends Controller
{
    public function index(Request $request)
    {
        $query = Movie::with('user');

        if ($search = $request->query('search')) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        if ($genre = $request->query('genre')) {
            $query->where('genre', $genre);
        }

        $sort = $request->query('sort', 'created_at');
        $direction = $request->query('direction', 'desc');
        $allowedSorts = ['title', 'release_year', 'rating', 'created_at'];

        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction === 'asc' ? 'asc' : 'desc');
        }

        $movies = $query->get();
        $genres = Movie::whereNotNull('genre')->distinct()->pluck('genre');

        return Inertia::render('Movies/Index', [
            'movies' => $movies,
            'genres' => $genres,
            'filters' => [
                'search' => $search,
                'genre' => $genre,
                'sort' => $sort,
                'direction' => $direction,
            ],
        ]);
    }

    public function create()
    {
        return Inertia::render('Movies/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'director' => 'required|string|max:255',
            'release_year' => 'required|integer|min:1888|max:2030',
            'genre' => 'nullable|string|max:100',
            'rating' => 'nullable|numeric|min:0|max:10',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('movies', 'public');
        }

        $validated['user_id'] = auth()->id();

        Movie::create($validated);
        Cache::forget('movies_api');

        return redirect()->route('movies.index');
    }

    public function show(Movie $movie)
    {
        $movie->load('user');

        return Inertia::render('Movies/Show', [
            'movie' => $movie,
        ]);
    }

    public function edit(Movie $movie)
    {
        $this->authorizeMovie($movie);

        return Inertia::render('Movies/Edit', [
            'movie' => $movie,
        ]);
    }

    public function update(Request $request, Movie $movie)
    {
        $this->authorizeMovie($movie);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'director' => 'required|string|max:255',
            'release_year' => 'required|integer|min:1888|max:2030',
            'genre' => 'nullable|string|max:100',
            'rating' => 'nullable|numeric|min:0|max:10',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($movie->image) {
                Storage::disk('public')->delete($movie->image);
            }
            $validated['image'] = $request->file('image')->store('movies', 'public');
        }

        $movie->update($validated);
        Cache::forget('movies_api');

        return redirect()->route('movies.show', $movie);
    }

    public function destroy(Movie $movie)
    {
        $this->authorizeMovie($movie);

        if ($movie->image) {
            Storage::disk('public')->delete($movie->image);
        }

        $movie->delete();
        Cache::forget('movies_api');

        return redirect()->route('movies.index');
    }

    private function authorizeMovie(Movie $movie): void
    {
        if ($movie->user_id !== auth()->id()) {
            abort(403);
        }
    }
}

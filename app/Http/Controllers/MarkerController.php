<?php

namespace App\Http\Controllers;

use App\Models\Marker;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MarkerController extends Controller
{
    public function index()
    {
        $markers = Marker::all();

        return Inertia::render('Map/Index', [
            'markers' => $markers,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'description' => 'nullable|string|max:1000',
        ]);

        $validated['added'] = now();

        Marker::create($validated);

        return redirect()->route('map.index');
    }

    public function update(Request $request, Marker $marker)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'description' => 'nullable|string|max:1000',
        ]);

        $validated['edited'] = now();

        $marker->update($validated);

        return redirect()->route('map.index');
    }

    public function destroy(Marker $marker)
    {
        $marker->delete();

        return redirect()->route('map.index');
    }
}

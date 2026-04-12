<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Http\Requests\StoreRatingRequest;
use App\Http\Requests\UpdateRatingRequest;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function index()
    {
        $ratings = Rating::with('doctor.user', 'patient.user')->paginate(10);
        return view('rating.index', compact('ratings'));
    }

    public function create()
    {
        return view('rating.create');
    }

    public function store(StoreRatingRequest $request)
    {
        Rating::create($request->validated());
        return redirect()->route('ratings.index')->with('success', 'Rating created successfully.');
    }

    public function show(Rating $rating)
    {
        $rating->load('doctor.user', 'patient.user');
        return view('rating.show', compact('rating'));
    }

    public function edit(Rating $rating)
    {
        return view('rating.edit', compact('rating'));
    }

    public function update(UpdateRatingRequest $request, Rating $rating)
    {
        $rating->update($request->validated());
        return redirect()->route('ratings.index')->with('success', 'Rating updated successfully.');
    }

    public function destroy(Rating $rating)
    {
        $rating->delete();
        return redirect()->route('ratings.index')->with('success', 'Rating deleted successfully.');
    }
}

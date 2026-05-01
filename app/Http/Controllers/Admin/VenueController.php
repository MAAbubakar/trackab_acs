<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Venue\StoreVenueRequest;
use App\Http\Requests\Venue\UpdateVenueRequest;
use App\Models\Venue;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class VenueController extends Controller
{
    public function index(): View
    {
        $venues = Venue::latest()->paginate(10);

        return view('admin.venues.index', compact('venues'));
    }

    public function create(): View
    {
        return view('admin.venues.create');
    }

    public function store(StoreVenueRequest $request): RedirectResponse
    {
        Venue::create($request->validated());

        return redirect()
            ->route('admin.venues.index')
            ->with('success', 'Venue created successfully.');
    }

    public function show(Venue $venue): View
    {
        return view('admin.venues.show', compact('venue'));
    }

    public function edit(Venue $venue): View
    {
        return view('admin.venues.edit', compact('venue'));
    }

    public function update(UpdateVenueRequest $request, Venue $venue): RedirectResponse
    {
        $venue->update($request->validated());

        return redirect()
            ->route('admin.venues.index')
            ->with('success', 'Venue updated successfully.');
    }

    public function destroy(Venue $venue): RedirectResponse
    {
        $venue->delete();

        return redirect()
            ->route('admin.venues.index')
            ->with('success', 'Venue deleted successfully.');
    }
}

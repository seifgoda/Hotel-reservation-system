<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHotelRequest;
use App\Http\Requests\UpdateHotelRequest;
use App\Models\City;
use App\Models\Hotel;

class HotelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $hotels = Hotel::paginate(10);
        return view('admin.hotel.index' , compact('hotels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $cities = City::all();
        return view('admin.hotel.create' , compact('cities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHotelRequest $request)
    {
        //
        // dd($request->validated());
        $data = $request->validated();
        $hotel = Hotel::create($data);
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $hotel->addMedia($image)->toMediaCollection('hotel_images');
            }
        }
        return redirect()->route('admin.hotel.index')->with('success', 'Hotel Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Hotel $hotel)
    {
        $hotel = Hotel::with('media')->find($hotel->id);

        $images = $hotel->getMedia('hotel_images');
        // dd($images);

        return view('admin.hotel.show', compact('hotel', 'images'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Hotel $hotel)
    {
        //
        $hotel = Hotel::with('media')->find($hotel->id);
        $cities = City::all();
        return view('admin.hotel.edit' , compact('hotel' , 'cities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHotelRequest $request, Hotel $hotel)
    {
        //
        $data = $request->validated();
        $hotel->update($data);
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $hotel->clearMediaCollection('hotel_images');
                $hotel->addMedia($image)->toMediaCollection('hotel_images');
            }
        }
        return redirect()->route('admin.hotel.index')->with('success', 'Hotel Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Hotel $hotel)
    {
        //
        $hotel->clearMediaCollection(); // This will delete all media associated with the hotel
        $hotel->delete();
        return redirect()->route('admin.hotel.index')->with('success', 'Hotel Deleted Successfully');
    }
}

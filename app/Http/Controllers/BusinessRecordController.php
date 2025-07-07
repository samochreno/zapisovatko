<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\BusinessRecord;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class BusinessRecordController extends Controller
{
    public function index()
    {
        return Inertia::render('BusinessTracker', [
            'businesses' => Business::all(),
            'weatherOptions' => ['Sunny', 'Cloudy', 'Rainy', 'Windy'],
            'records' => BusinessRecord::with('business')
                ->orderBy('created_at', 'desc')
                ->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'business_id' => 'required|exists:businesses,id',
            'day' => 'required|date',
            'weather' => 'required|string',
            'temperature' => 'nullable|numeric|between:-50,50',
            'group_size' => 'nullable|integer|min:1',
            'ice_cream_purchases' => 'nullable|integer|min:0',
            'arrived_by_car' => 'nullable|boolean',
            'seating_status' => 'nullable|string|in:sat,not_sat,no_space',
            'visitor_type' => 'nullable|string|in:adults,man_woman,family,parent_child,children_only,teenagers,seniors,senior_children',
            'additional_purchases' => 'nullable|array',
            'additional_purchases.*' => 'string|in:coffee,water,sweets',
            'notes' => 'nullable|string',
        ]);
        
        // Ensure we're using today's date from the server
        $validated['day'] = Carbon::today()->toDateString();
        
        BusinessRecord::create($validated);
        
        return redirect()->route('home');
    }
    
    public function storeBusiness(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:businesses,name',
            'seating_count' => 'nullable|integer|min:0',
            'ice_cream_flavors_count' => 'nullable|integer|min:0',
            'has_additional_products' => 'boolean',
            'ice_cream_price' => 'nullable|numeric|min:0|max:999999.99',
            'business_hours' => 'nullable|array',
            'business_hours.*.day' => 'required|string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'business_hours.*.open' => 'required|date_format:H:i',
            'business_hours.*.close' => 'required|date_format:H:i|after:business_hours.*.open',
        ]);
        
        Business::create($validated);
        
        return redirect()->route('home');
    }

    public function updateBusiness(Request $request, Business $business)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:businesses,name,' . $business->id,
            'seating_count' => 'nullable|integer|min:0',
            'ice_cream_flavors_count' => 'nullable|integer|min:0',
            'has_additional_products' => 'boolean',
            'ice_cream_price' => 'nullable|numeric|min:0|max:999999.99',
            'business_hours' => 'nullable|array',
            'business_hours.*.day' => 'required|string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'business_hours.*.open' => 'required|date_format:H:i',
            'business_hours.*.close' => 'required|date_format:H:i|after:business_hours.*.open',
        ]);

        $business->update($validated);

        return redirect()->route('home');
    }
    
    public function updateRecord(Request $request, $recordId)
    {
        $validated = $request->validate([
            'business_id' => 'required|exists:businesses,id',
            'weather' => 'nullable|string',
            'temperature' => 'nullable|numeric',
            'group_size' => 'nullable|integer',
            'ice_cream_purchases' => 'nullable|integer',
            'arrived_by_car' => 'nullable|boolean',
            'seating_status' => 'nullable|string',
            'visitor_type' => 'nullable|string',
            'additional_purchases' => 'nullable|array',
            'notes' => 'nullable|string',
        ]);

        $record = BusinessRecord::findOrFail($recordId);
        $record->update($validated);

        return response()->json([
            'message' => 'Record updated successfully',
            'updatedRecord' => $record
        ]);
    }
}
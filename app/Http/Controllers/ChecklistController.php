<?php

namespace App\Http\Controllers;

use App\Http\Resources\Checklist\ChecklistItemResource;
use App\Http\Resources\Checklist\ChecklistResource;
use App\Models\Checklist;
use App\Models\ChecklistItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChecklistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $checklists = Checklist::with('items')->get();
        return $this->sendResponse(ChecklistResource::collection($checklists), 'all checklist data');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->only('name', 'check');
        $validator = Validator::make($input, [
            'name' => ['required'],
            'check' => ['boolean']
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors(), 'Validator Error', 422);
        }

        $checklist = Checklist::create($request->only('name', 'check'));
        return $this->sendResponse($checklist, 'Create success');
    }

    /**
     * Display the specified resource.
     */
    public function show($checklistId)
    {
        $checklist = Checklist::find($checklistId);
        if (!$checklist) {
            // Checklist not found, return error response
            return $this->sendError([], 'Checklist not found', 404);
        }
    
        return $this->sendResponse(new ChecklistResource($checklist), 'Find checklist');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Checklist $checklist)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Checklist $checklist)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($checklistId)
    {
        try {
            $checklist = Checklist::find($checklistId);
        } catch (\Throwable $th) {
            return $this->sendError([], $th->getMessage(), 402);
        }
        $checklist->delete();
        return $this->sendResponse([], 'Data has been deleted', 200);
    }

     /**
     * Items of specified checklist
     */
    public function item($checklistId)
    {
        $checklist = Checklist::where('id', $checklistId)->with('items')->first();
        if (!$checklist) {
            // Checklist not found, return error response
            return $this->sendError([], 'Checklist not found', 404);
        }
        
        if ($checklist->items->count() === 0) {
            return $this->sendResponse([], 'Find checklist');
        }
        return $this->sendResponse(ChecklistItemResource::collection($checklist->items), 'Find checklist');
    }
}

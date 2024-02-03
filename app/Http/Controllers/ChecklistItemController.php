<?php

namespace App\Http\Controllers;

use App\Http\Resources\Checklist\ChecklistItemResource;
use App\Models\ChecklistItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChecklistItemController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $checklistId)
    {
        $input = $request->only('name', 'check');
        $validator = Validator::make($input, [
            'name' => ['required'],
            'check' => ['boolean']
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(), 'Validator Error', 422);
        }

        $input['checklist_id'] = $checklistId;
        try {
            $item = ChecklistItem::create($input);
            return $this->sendResponse(new ChecklistItemResource($item), 'Create success');
        } catch (\Exception $e) {
            return $this->sendError([], $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($checklistId, $checklistItemId)
    {
        try {
            $item = ChecklistItem::where('id', $checklistItemId)->where('checklist_id', $checklistId)->first();
            return $this->sendResponse(new ChecklistItemResource($item), 'Data Item');
        } catch (\Exception $e) {
            return $this->sendError([], $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function rename(Request $request, $checklistId, $checklistItemId)
    {
        $item = ChecklistItem::find($checklistItemId);
        $item->name =  $request->name;
        $item->save();
        return $this->sendResponse(new ChecklistItemResource($item), 'Rename Success');
    }

    public function updateStatus($checklistId, $checklistItemId)
    {
        $item = ChecklistItem::find($checklistItemId);
        $item->check = !$item->check;
        $item->save();
        return $this->sendResponse(new ChecklistItemResource($item), 'Update status');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($checklistId, $checklistItemId)
    {
        $item = ChecklistItem::find($checklistItemId);
        $item->delete();
        return $this->sendResponse([], 'Item deleted');
    }

}

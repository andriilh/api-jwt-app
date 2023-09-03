<?php

namespace App\Http\Controllers;

use App\Models\ChecklistItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChecklistItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($checklistId)
    {
        $data = ChecklistItem::where('checklist_id', $checklistId)->get();
        return $this->sendResponse($data, 'All Item of check id: ' . $checklistId, 200);
    }

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
        $checklist = ChecklistItem::create($input);
        return $this->sendResponse($checklist, 'Create success');
    }

    /**
     * Display the specified resource.
     */
    public function show($checklistId, $checklistItemId)
    {
        $item = ChecklistItem::where('id', $checklistItemId)->where('checklist_id', $checklistId)->get();
        return $this->sendResponse($item, 'Data Item');
    }

    /**
     * Update the specified resource in storage.
     */
    public function rename(Request $request, $checklistId, $checklistItemId)
    {
        $item = ChecklistItem::find($checklistItemId);
        $item->name =  $request->name;
        $item->save();
        return $this->sendResponse($item, 'Rename Success');
    }

    public function updateStatus($checklistId, $checklistItemId)
    {
        $item = ChecklistItem::find($checklistItemId);
        $item->check = !$item->check;
        $item->save();
        return $this->sendResponse($item, 'Update status');
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

    public function sendResponse($data, $message, $status = 200)
    {
        $response = [
            'data' => $data,
            'message' => $message
        ];

        return response()->json($response, $status);
    }

    public function sendError($errorData, $message, $status = 500)
    {
        $response = [];
        $response['message'] = $message;
        if (!empty($errorData)) {
            $response['data'] = $errorData;
        }

        return response()->json($response, $status);
    }
}

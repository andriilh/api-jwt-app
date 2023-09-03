<?php

namespace App\Http\Controllers;

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
        $checklists = Checklist::all();
        return $this->sendResponse($checklists, 'all checklist data');
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
    public function show(Checklist $checklist)
    {
        //
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
    public function destroy($id)
    {
        try {
            $checklist = Checklist::find($id);
        } catch (\Throwable $th) {
            return $this->sendError([], $th->getMessage(), 402);
        }
        $checklist->delete();
        return $this->sendResponse([], 'Data has been deleted', 200);
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

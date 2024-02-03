<?php

namespace App\Http\Resources\Checklist;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChecklistItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'checklist_id'  => $this->checklist_id,
            'name'          => $this->name,
            'check'         => $this->check,
            'created_at'    => $this->created_at
        ];
    }
}

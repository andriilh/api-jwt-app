<?php

namespace App\Http\Resources\Checklist;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChecklistResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'check'         => $this->check,
            'created_at'    => $this->created_at,
            'items'         => ChecklistItemResource::collection($this->items)
        ];
    }
}

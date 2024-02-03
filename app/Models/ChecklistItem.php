<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChecklistItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'checklist_id', 'name', 'check'
    ];

    protected $casts = [
        'check' => 'boolean',
    ];

    public function checklist(): BelongsTo
    {
        return $this->belongsTo(Checklist::class, 'checklist_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BrandMilestone extends Model
{
    protected $table = 'brand_milestones';

    protected $fillable = [
        'brand_id',
        'year',
        'title',
        'description',
        'sort_order',
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Outfits extends Model
{
    /**
     * The maximum number of outfits a user may generate per day.
     */
    public const int DAILY_LIMIT = 3;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

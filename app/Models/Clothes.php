<?php

namespace App\Models;

use App\Enums\ImageType;
use Illuminate\Database\Eloquent\Model;

class Clothes extends Model
{
    protected $guarded = [];

    protected $casts = [
        'type' => ImageType::class,
    ];
    //casting types to always be onw of the enum values

    public function user() {
        return $this->belongsTo(User::class);
    }
}

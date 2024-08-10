<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PostBookmark extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "user_id",
        "bookmarks",
    ];

    protected function casts(): array
    {
        return [
            'bookmarks' => 'array',
        ];
    }
}

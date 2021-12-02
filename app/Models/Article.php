<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'SKU', 'title', 'content', 'cover', 'like', 'user_id', 'created_at', 'updated_at'
    ];

    // un article pertenece a un user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

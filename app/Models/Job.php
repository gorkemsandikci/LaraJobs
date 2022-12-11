<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    public function scopeFilter($query, array $filters)
    {
        if($filters['tag'] ?? false)
        {
            return $query->where('tags', 'like', '%' . $filters['tag'] . '%');
        }
    }
}

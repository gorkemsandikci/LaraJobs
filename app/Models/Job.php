<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'tags',
        'company',
        'location',
        'url',
        'email',
        'description',
        'logo',
        'user_id'
    ];

    public function scopeFilter($query, array $filters)
    {
        if($filters['tag'] ?? false)
        {
            return $query->where('tags', 'like', '%' . $filters['tag'] . '%');
        }

        if($filters['search'] ?? false)
        {
            return $query->where('title', 'like', '%' . $filters['search'] . '%')
                ->orWhere('slug', 'like', '%' . $filters['search'] . '%')
                ->orWhere('description', 'like', '%' . $filters['search'] . '%')
                ->orWhere('company', 'like', '%' . $filters['search'] . '%')
                ->orWhere('location', 'like', '%' . $filters['search'] . '%')
                ->orWhere('url', 'like', '%' . $filters['search'] . '%')
                ->orWhere('tags', 'like', '%' . $filters['search'] . '%');
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

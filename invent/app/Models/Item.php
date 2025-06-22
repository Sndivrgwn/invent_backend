<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'brand',
        'type',
        'status',
        'condition',
        'image',
        'category_id',
        'location_id',
        'description',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function loans()
    {
        return $this->belongsToMany(Loan::class, 'item_loan')->withPivot('quantity')->withTimestamps();
    }
}

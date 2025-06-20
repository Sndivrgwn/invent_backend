<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
    'user_id',
    'code_loans',
    'loan_date',
    'return_date',
    'status',
    'loaner_name',
    'description',
];




    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
{
    return $this->belongsToMany(Item::class)->withPivot('quantity')->withTimestamps();
}
public function returns()
{
    return $this->hasMany(Returns::class); // Ganti ReturnModel dengan nama model return kamu
}

}

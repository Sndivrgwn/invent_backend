<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Returns extends Model
{
    use HasFactory;

    protected $table = 'return';

    protected $fillable = [
        'loan_id',
        'return_date',
        'condition',
        'notes',
    ];

    public function loan() {
        return $this->belongsTo(Loan::class);
    }
}

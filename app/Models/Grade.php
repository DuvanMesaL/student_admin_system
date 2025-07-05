<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'enrollment_id',
        'evaluation_type',
        'score',
        'max_score',
        'evaluation_date',
        'comments',
    ];

    protected $casts = [
        'evaluation_date' => 'date',
        'score' => 'decimal:2',
        'max_score' => 'decimal:2',
    ];

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function getPercentageAttribute()
    {
        return ($this->score / $this->max_score) * 100;
    }
}

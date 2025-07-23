<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'phone_number',
        'second_phone_number',
        'parent_phone_number',
        'preferred_contact_method',
        'year_of_study',
        'date_of_birth',
        'address',
        'credit',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

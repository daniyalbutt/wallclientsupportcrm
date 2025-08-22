<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadGenerationLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'lead_data_old',
        'lead_data_new',
        'action_applied'
    ];

    /**
     * Get the user associated with the lead.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

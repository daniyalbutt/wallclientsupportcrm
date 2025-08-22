<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadGeneration extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'service',
        'medium',
        'brand_id',
        'status',
        'notes',
        'user_id',
        'sale_agent',
        'assigned_by',
        'assigned_on',
        'manager_assign'
    ];

    /**
     * Get the user associated with the lead.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the user associated with the lead.
     */
    public function sale()
    {
        return $this->belongsTo(User::class, 'sale_agent', 'id');
    }
    
    /**
     * Get the brand associated with the lead.
     */
    public function brand(){
        return $this->hasOne(Brand::class, 'id', 'brand_id');
    }

    /**
     * Get the services data associated with the lead.
     */
    public function getServicesAttribute()
    {
        $serviceIds = json_decode($this->service);
    
        if ($serviceIds) {
            return Service::whereIn('id', $serviceIds)->get();
        }
    
        return collect();
    }

    /**
     * Get the user who assigned the lead.
     */
    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}

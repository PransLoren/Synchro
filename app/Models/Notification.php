<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Notification extends Model
{
    public $timestamps = false; // Disable automatic timestamps

    protected $fillable = [
        'user_id', 'notifiable_type', 'notifiable_id', 'message', 'is_read', 'type', 'read_at', 'created_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function notifiable()
    {
        return $this->morphTo(); 
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectInvitation extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'email', 'status'];

    // Constants for status
    const STATUS_PENDING = 'pending';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REJECTED = 'rejected';

    // Define relationship with ProjectModel
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}

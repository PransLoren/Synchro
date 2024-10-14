<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['task_name', 'task_description', 'status', 'project_id', 'assigned_to'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    
    public function assignedUser() 
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
    
}
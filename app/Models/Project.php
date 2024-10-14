<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    // Explicitly set the table name
    protected $table = 'project';  // If your table is named 'project'

    protected $fillable = [
        'class_name', 'description', 'submission_date', 'submission_time', 'created_by', 'is_delete'
    ];

    // Relationships
    public function users()
    {
        return $this->belongsToMany(User::class, 'project_user', 'project_id', 'user_id')
        ->withPivot('role');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'project_id');
    }

    public function invitations()
    {
        return $this->hasMany(ProjectInvitation::class, 'project_id');
    }

    // Static methods to get records
    static public function getSingle($id)
    {
        return self::find($id);
    }

    static public function getRecord()
    {
        return self::select('project.*')
                    ->join('users', 'users.id', '=', 'project.created_by')
                    ->orderBy('project.id', 'desc')
                    ->where('project.is_delete', '=', 0)
                    ->paginate(20);
    }

    // Document path getter
    public function getDocument()
    {
        if (!empty($this->document_file) && file_exists('upload/project/' . $this->document_file)) {
            return url('upload/project/' . $this->document_file);
        } else {
            return "";
        }
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

}

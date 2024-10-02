<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Request;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function projects()
    {
        return $this->belongsToMany(ProjectModel::class, 'project_user', 'user_id', 'project_id');
    }

    public function teacherStudents()
    {
        return $this->hasMany(AssignSubjectApiTeacher::class, 'teacher_id', 'id');
    }

    static public function getSingle($id)
    {
        return self::find($id);
    }

    static public function getAdmin()
    {
        $query = self::query()->where('user_type', 1)->where('is_delete', 0);
        if (!empty(Request::get('email'))) {
            $query->where('email', 'like', '%' . Request::get('email') . '%');
        }
        return $query->orderBy('id', 'desc')->paginate(10);
    }

    static function getTeacherStudent($teacher_id)
    {
        return self::select('users.*', 'subject.name as subject_name')
            ->join('assign_subject_teacher', 'assign_subject_teacher.teacher_id', '=', 'users.id')
            ->join('subject', 'subject.id', '=', 'assign_subject_teacher.subject_id')
            ->where('assign_subject_teacher.teacher_id', $teacher_id)
            ->where('users.user_type', 3)
            ->where('users.is_delete', 0)
            ->orderBy('users.id', 'desc')
            ->groupBy('users.id')
            ->paginate(20);
    }
    
    static function getStudent()
    {
        $return = User::select('users.*')
                        ->where('user_type','=',3)
                        ->where('is_delete','=',0);
        $return = $return->orderBy('id','desc')
                        ->paginate(10);

        
        return $return;
    }

    static function getTeacherClass()
    {
        $return = User::select('users.*')
                        ->where('user_type','=',2)
                        ->where('is_delete','=',0);
        $return = $return->OrderBy('users.id','desc')
                        ->get();

        
        return $return;
    }

    static public function getEmailSingle($email)
    {
        return User::where('email', '=', $email)->first();
    }

    static public function getTokenSingle($remember_token)
    {
        return User::where('remember_token', '=', $remember_token)->first();
    }
}

<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Request;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
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
        return $this->belongsToMany(ProjectModel::class, 'project_user', 'user_id', 'project_id')
                    ->where('is_delete', '=', 0);
    }

    public static function getSingle($id)
    {
        $user = self::find($id);
        if (!$user) {
            throw new \Exception("User not found.");
        }
        return $user;
    }

    public static function getAdmin()
    {
        $query = self::query()->where('user_type', 1)->where('is_delete', 0);
        if (!empty(Request::get('email'))) {
            $query->where('email', 'like', '%' . Request::get('email') . '%');
        }
        return $query->orderBy('id', 'desc')->paginate(10);
    }

    public static function getTeacherStudent($teacher_id)
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
    
    public static function getStudent()
    {
        $return = User::select('users.*')
                        ->where('user_type', '=', 3)
                        ->where('is_delete', '=', 0);
        return $return->orderBy('id', 'desc')
                      ->paginate(10);
    }

    public static function getTeacherClass()
    {
        $return = User::select('users.*')
                        ->where('user_type', '=', 2)
                        ->where('is_delete', '=', 0);
        return $return->orderBy('users.id', 'desc')
                      ->get();
    }

    public static function getEmailSingle($email)
    {
        $user = User::where('email', '=', $email)->first();
        if (!$user) {
            throw new \Exception("User not found with the provided email.");
        }
        return $user;
    }

    public static function getTokenSingle($remember_token)
    {
        $user = User::where('remember_token', '=', $remember_token)->first();
        if (!$user) {
            throw new \Exception("User not found with the provided token.");
        }
        return $user;
    }
}
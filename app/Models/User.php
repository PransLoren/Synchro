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


    
    public static function getStudent()
    {
        $return = User::select('users.*')
                        ->where('user_type', '=', 3)
                        ->where('is_delete', '=', 0);
        return $return->orderBy('id', 'desc')
                      ->paginate(10);
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

    public function pendingInvitationsCount()
    {
        return $this->unreadNotifications()->where('type', 'invitation')->count();
    }

}
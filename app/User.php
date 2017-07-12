<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use App\Models\League;
use App\Models\Image;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','firstname','surname'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function role()
    {
        return $this->belongsTo(Models\Role::class);
    }
    public function image()
    {
        return $this->hasOne(Image::class);
    }
    public function setPasswordAttribute($value)
    {
        $this->attributes['password']=  bcrypt($value);
    }
    public function isAdmin()
    {
        return $this->role->slug == 'admin' ? true : false;
    }
    public function isManager()
    {
        return $this->role->slug == 'manager' ? true : false;
    }
    public function isOrganizer()
    {
        return $this->role->slug == 'org' ? true : false;
    }
    public function leagues()
    {
        return $this->belongsToMany(League::class);
    }
    public function getSelectedLeaguesAttribute()
    {
       return $this->leagues->pluck('id')->all();
       
    }
    public function getAvatarAttribute()
    {
        $path = $this->image ? $this->id.'/'.$this->image->name : 'noimage.png';
        return $path;
    }

}

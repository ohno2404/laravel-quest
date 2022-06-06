<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    public function movies(){
        
        return $this->hasMany(Movie::class);
    }
    
    //フォロー中のユーザを取得する
    public function followings(){
        
        return $this->belongsToMany(User::class,'user_follow','user_id','follow_id')->withTimestamps();
        
    }
    //フォロワーのユーザを取得する
    public function followers(){
        
        return $this->belongsToMany(User::class,'user_follow','follow_id','user_id')->withTimestamps();
    }
    //フォロー対象のユーザIDが重複していないか確認
    public function is_following($userId){
        
        return $this->followings()->where('follow_id',$userId)->exists();
        
    }
    //フォローして、対象のユーザIDを登録する
    public function follow($userId){
        
        //既にフォロー済みでないか
        $existing = $this->is_following($userId);
        
        //フォロー相手が自分でないか
        $myself = $this->id == $userId;
        
        //フォロー済みでない、自分でない場合フォローとうろく
        if( !$existing && !$myself){
            $this->followings()->attach($userId);
        }
    }
    //フォローを外す
    public function unfollow($userId){
        
        $existing = $this->is_following($userId);
        
        $myself = $this->id == $userId;
        
        if( $existing && !$myself){
            
            $this->followings()->detach($userId);
        }
    }
    
}

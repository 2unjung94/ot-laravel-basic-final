<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google2fa_secret',
        'username',
        'postcode',
        'address',
        'detailAddress'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'google2fa_secret'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected function google2faSecret():Attribute
    {
      return new Attribute(
        get: fn($value) => decrypt($value),
        set: fn($value) => encrypt($value)
      );
    }

    public function getRouteKeyName(): string   
    {
        return 'username';
    }
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);  // user가 글을 여러개 갖고 있음
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);  // user가 댓글을 여러개 갖고 있음
    }


    // 유저 대 유저 다대다 관계 설정
    public function followers(): BelongsToMany
    {
      
      return $this->belongsToMany(User::class,'followers','user_id', 'follower_id'); // followers 테이블 참조하는데 user_id가 여러 follower_id를 갖는다
    }

    // 반대 개념 내가 팔로우 하는 유저 목록
    public function followings(): BelongsToMany
    {
      return $this->belongsToMany(User::class, 'followers', 'follower_id', 'user_id'); // 내가 팔로워로서 내가 팔로우 하는 목록
    }

    public function isFollowing(User $user): bool
    {
        return $this->followings()->where('user_id', $user->id)->exists();  // 현재 로그인한 사용자가 팔로우하는 사람 중에 매개변수의 user가 있느냐
    }

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }
}

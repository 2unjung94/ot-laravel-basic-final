<?php

namespace App\Models;

use BaconQrCode\Renderer\RendererStyle\Fill;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Article extends Model
{
    use HasFactory;
    protected $fillable = ['body', 'user_id', 'file_name', 'file_path'];  

    public function user(): BelongsTo{
        return $this->belongsTo(User::class);   // User라는 클래스에 article이 속한다
    }

    public function comments(): HasMany{
        return $this->hasMany(Comment::class);
    }

    public function files() : HasMany{
      return $this->hasMany(File::class);
    }
}

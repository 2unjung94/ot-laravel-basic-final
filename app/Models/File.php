<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class File extends Model
{
  use HasFactory;
  protected $fillable = ['file_name', 'file_path', 'article_id'];

  public function articles(): BelongsTo{
    return $this->belongsTo(Article::class);
  }
}

<?php

namespace App\Models;

use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable implements JWTSubject
{
  use HasApiTokens, HasFactory, Notifiable;

  protected $primaryKey = 'id';
  public $incrementing = false;
  protected $keyType = 'string';

  protected static function boot()
  {
    parent::boot();

    static::creating(function ($model) {
      $model->id = Str::uuid();
    });
  }

  
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

  public function getJWTIdentifier() {
    return $this->getKey();
  }
  
  public function getJWTCustomClaims() {
    return [];
  }  
}

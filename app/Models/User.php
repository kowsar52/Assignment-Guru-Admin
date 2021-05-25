<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Notifications;
use Carbon\Carbon;


class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable,SoftDeletes;


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'username',
        'first_name',
        'last_name',
        'email',
        'email_verified_at',
        'phone',
        'phone_verified_at',
        'password',
        'birth_date',
        'country',
        'role',
        'short_about',
        'about',
        'avater',
        'balance',
        'timezone',
        'status',
        'gender',
        'remember_token',
        'created_at',
        'last_seen',
        'deleted_at',
        'referal_username',
        'stripe_connect_id',
        'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function myPayments()
    {
          return $this->hasMany('App\Models\Transactions');
      }


    public function withdrawals()
    {
        return $this->hasMany('App\Models\Withdrawals');
    }

  	public function country()
    {
          return $this->belongsTo('App\Models\Countries', 'countries_id')->first();
      }

      public function notifications()
      {
            return $this->hasMany('App\Models\Notifications', 'destination');
        }

      public function messagesInbox()
      {
          return $this->hasMany('App\Models\Messages', 'to_user_id')->where('status','new')->count();
      }
      
      public function payment_methods()
      {
          return $this->hasMany('App\Models\UserPaymentMethod', 'user_id','id')->first();
      }



      public static function notificationsCount()
      {
        // Notifications Count
      	$notifications_count = auth()->user()->notifications()->where('status', '0')->count();
        // Messages
      	$messages_count = auth()->user()->messagesInbox();

        if( $messages_count != 0 &&  $notifications_count != 0 ) {
          $totalNotifications = ( $messages_count + $notifications_count );
        } else if( $messages_count == 0 &&  $notifications_count != 0  ) {
          $totalNotifications = $notifications_count;
        } else if ( $messages_count != 0 &&  $notifications_count == 0 ) {
          $totalNotifications = $messages_count;
        } else {
          $totalNotifications = null;
        }

       return $totalNotifications;
    }

    
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    
    public function getJWTCustomClaims()
    {
        return [];
    }
}

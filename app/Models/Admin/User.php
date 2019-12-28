<?php

namespace App\Models\Admin;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

// class User extends Authenticatable
class User extends Authenticatable implements JWTSubject
{
	use SoftDeletes;

    use Notifiable;

	use HasRoles;
	// 这里使用api而不是web，是因为用了tymon/jwt-auth
	protected $guard_name = 'api';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uid', 'name', 'department', 'applicant_group', 'auditing', 'configs', 'ldapname', 'email', 'displayname', 'password', 'login_time', 'login_ttl', 'login_ip', 'login_counts',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        // 'password', 'remember_token',
        'password',
    ];
    
    /**
     * 这个属性应该被转换为原生类型.
     * 用于json与array互相转换
     * @var array
     */
    protected $casts = [
        'configs' => 'array',
        'applicant_group' => 'array',
        'auditing' => 'array',
    ];

    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
	
	// public function getAuthIdentifierName()
	// {
		// return 'id';
	// }
	
	// public function getAuthIdentifier()
	// {
		
	// }
	
	// public function getAuthPassword()
	// {
		
	// }
	
	// public function getRememberToken()
	// {
		
	// }

	// public function setRememberToken($value)
	// {
		
	// }
	// public function getRememberTokenName()
	// {
		
	// }
}

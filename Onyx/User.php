<?php namespace Onyx;

use Carbon\Carbon;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

/**
 * Class User
 * @package Onyx
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $remember_token
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $account_id
 * @property string $avatar
 * @property string $google_id
 * @property string $google_url
 * @property boolean $admin
 * @property string $chat_id
 * @property boolean $isPanda
 * 
 * @property Account $account
 */
class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'email', 'password', 'chat_id'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

	//---------------------------------------------------------------------------------
	// Mutators
	//---------------------------------------------------------------------------------

	public function setAvatarAttribute($value)
	{
		$this->attributes['avatar'] = str_replace('sz=50', 'sz=450', $value);
	}

	//---------------------------------------------------------------------------------
	// Public Methods
	//---------------------------------------------------------------------------------

	/**
	 * @return string
	 */
	public function isPandaText()
	{
		return $this->isPanda ? 'Yes' : 'No';
	}

	/**
	 * @return Account
	 */
	public function account()
	{
		return $this->hasOne('Onyx\Account', 'id', 'account_id');
	}
}

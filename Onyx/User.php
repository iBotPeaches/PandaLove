<?php namespace Onyx;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

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

	public function account()
	{
		return $this->hasOne('Onyx\Account', 'id', 'account_id');
	}
}

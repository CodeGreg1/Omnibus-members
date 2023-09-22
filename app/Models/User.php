<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Modules\Base\Models\Address;
use Modules\Base\Models\Country;
use Laravel\Sanctum\HasApiTokens;
use Modules\Profile\Models\Company;
use Modules\Profile\Models\Session;
use Modules\Roles\Support\RoleType;
use Modules\Users\Support\UserStatus;
use Modules\Wallet\Traits\HasWallets;
use Modules\Base\Traits\FormattedDate;
use Spatie\Permission\Traits\HasRoles;
use Modules\Profile\Models\SocialLogin;
use Illuminate\Notifications\Notifiable;
use Modules\Deposits\Traits\HasDeposits;
use Camroncade\Timezone\Facades\Timezone;
use Lab404\Impersonate\Models\Impersonate;
use Modules\Affiliates\Traits\IsAffiliate;
use Modules\Users\Presenters\UserPresenter;
use Modules\Subscriptions\Services\Billable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Modules\Withdrawals\Traits\HasWithdrawals;
use Illuminate\Contracts\Auth\CanResetPassword;
use Modules\Base\Presenters\Traits\Presentable;
use Modules\Auth\Notifications\VerifyEmailQueued;
use Modules\Profile\Models\ProfilePasswordChange;
use Modules\Auth\Notifications\ResetPasswordQueued;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail, CanResetPassword
{
    use HasApiTokens,
        Impersonate,
        Presentable,
        HasFactory,
        Notifiable,
        HasRoles,
        Billable,
        FormattedDate,
        HasWallets,
        HasDeposits,
        HasWithdrawals,
        IsAffiliate;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'username',
        'email_verified_at',
        'password',
        'authy_status',
        'authy_id',
        'authy_country_code',
        'authy_phone',
        'remember_token',
        'avatar',
        'timezone',
        'timezone_display',
        'date_format',
        'time_format',
        'locale',
        'currency',
        'country_id',
        'last_login',
        'last_logout',
        'last_activity',
        'status',
        'invited',
        'customer_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'authy_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Append modified field values
     */
    protected $appends = [
        'full_name',
        'last_login_for_humans',
        'created_at_for_humans',
        'can_impersonate',
        'can_be_impersonated'
    ];

    /**
     * User presenter
     *
     * @var UserPresenter
     */
    protected $presenter = UserPresenter::class;

    /**
     * @var array $with
     */
    protected $with = ['roles'];

    /**
     * The "boot" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $timezones = Timezone::getTimezones();

            //this is user saving without passing the timezone field
            // we will use the setting timezone or the config timezone value
            if(!$model->timezone) {
               $model->timezone = setting('timezone') ?? config('app.timezone');
            }

            // save timezone display this is useful for determining the timezones key in the list
            $model->timezone_display = array_search($model->timezone, $timezones);
            
            // save setting date format if exist
            if(!is_null(setting('date_format'))) {
                $model->date_format = setting('date_format');
            }

            // save setting time format if exist
            if(!is_null(setting('time_format'))) {
                $model->time_format = setting('time_format');
            }

            // save setting time locale if exist
            if(!is_null(setting('locale'))) {
                $model->locale = setting('locale');
            }

            // save setting time currency if exist
            if(!is_null(setting('currency'))) {
                $model->currency = setting('currency');
            }
        });
    }

    /**
     * Always encrypt password when it is updated.
     *
     * @param $value
     * 
     * @return string
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    /**
     * Get email
     *
     * @param $value
     * 
     * @return string
     */
    public function getEmailAttribute($value)
    {
        return $value;
    }

    /**
     * Get fullname
     *
     * @param $value
     * 
     * @return string
     */
    public function getFullNameAttribute($value)
    {
        return !is_null($this->first_name) 
            ? ucwords($this->first_name . ' ' . $this->last_name) 
            : 'N/A';
    }
    
    /**
    * Get name of user
    * @return string
    */
    public function getName()
    {
        if ($this->first_name && $this->last_name) {
            return ucwords($this->first_name . ' ' . $this->last_name);
        }

        return $this->email;
    }

    /**
     * Get avatar with default if no avatar uploaded yet
     *
     * @param $value
     * 
     * @return string
     */
    public function getAvatarAttribute($value) 
    {
        if (is_null($value)) {
            return setting('default_profile_photo');
        }

        return $value;
    }

    /**
     * Decrypt two factor secret.
     *
     * @param  string  $value
     * @return string
     */
    public function getGoogle2faSecretAttribute($value)
    {
        return ! is_null($value) 
            ? decrypt($value) 
            : null;
    }

    /**
     * Decrypt two factor secret recovery codes.
     *
     * @param string  $value
     * @return string
     */
    public function getRecoveryCodesAttribute($value)
    {
        return ! is_null($value) 
            ? json_decode( decrypt($value), true ) 
            : null;
    }

    /**
     * Get fullname
     *
     * @param $value
     * 
     * @return string
     */
    public function getLastLoginForHumansAttribute()
    {   
        if (is_null($this->last_login)) {
            return 'N/A';
        }

        return Carbon::parse($this->last_login)->diffForHumans();
    }

    /**
     * Get created at for human readable
     * 
     * @return string|DateTime
     */
    public function getCreatedAtForHumansAttribute() 
    {
        return $this->formattedCreatedAt();
    }

    /**
     * Get the value of user if can impersonate
     */
    public function getCanImpersonateAttribute() 
    {
        return $this->canImpersonate();
    }

    /**
     * Get the value of user if can be impersonate
     */
    public function getCanBeImpersonatedAttribute() 
    {
        return $this->canBeImpersonated();
    }

    /**
     * Get user status if is unconfirmed
     */
    public function isUnconfirmed()
    {
        return $this->email_verified_at === null;
    }

    /**
     * Get user status if is active
     */
    public function isActive()
    {
        return $this->status == UserStatus::ACTIVE;
    }

    /**
    * Get use status if is banned
    */
    public function isBanned()
    {
        return $this->status == UserStatus::BANNED;
    }

    /**
     * Get users social login
     */
    public function socialLogin() 
    {
        return $this->hasMany(SocialLogin::class);
    }

    /**
     * Get users company
     */
    public function company() 
    {
        return $this->hasOne(Company::class);
    }

    /**
     * Get all of the addresses for the user.
     */
    public function addresses()
    {
        return $this->morphToMany(Address::class, 'addressable');
    }

    /**
     * Get country own this address
     */
    public function country() 
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Get users last password change
     */
    public function lastPasswordChange() 
    {
        return $this->hasOne(ProfilePasswordChange::class)->orderBy('id', 'desc');
    }

    /**
     * Get user sessions
     */
    public function sessions() 
    {
        return $this->hasMany(Session::class);
    }

    /**
     * Send the email verification notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendEmailVerificationNotification()
    {   
        if(setting('registration_email_confirmation')) {
            $this->notify(new VerifyEmailQueued);
        }
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordQueued($token));
    }

    /**
     * Determine if user can impersonate
     * 
     * @return bool
     */
    public function canImpersonate()
    {
        return $this->isAdmin();
    }

    /**
     * Determine if user can be impersonated
     * 
     * @return bool
     */
    public function canBeImpersonated()
    {
        return $this->isUser();
    }
    
    /**
     * Determine user if has an Admin role type
     * 
     * @return bool
     */
    public function isAdmin()
    {
        $statuses = $this->roles->map(function($entry) {
            return $entry->type == RoleType::ADMIN;
        });

        return $statuses->contains(true);
    }
    
    /**
     * Determine user if has a User role type
     * 
     * @return bool
     */
    public function isUser()
    {
        $statuses = $this->roles->map(function($entry) {
            return $entry->type == RoleType::USER;
        });
        
        return $statuses->contains(true);
    }
}

<?php

namespace APPJU\Models\Security;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

/**
 * User Domain Model for security
 * 
 * @author Ivo Hutasoit <if09051@gmail.com>
 * @version 1.0.0
 * @since 1.0
 */
class User extends Authenticatable {
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'level', 'status'
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
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        /**
         * Attach to the 'creating' Model Event to provide a UUID
         * for the `id` field (provided by $model->getKeyName())
         */
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string)$model->generateNewId();
        });
    }

    /**
     * Get a new version 4 (random) UUID.
     *
     * @return \Ramsey\Uuid\Uuid
     */
    public function generateNewId() {
        try {
            return Uuid::uuid4();
        } catch (UnsatisfiedDependencyException $e) {
            throw new Exception($e);
        }
    }

    /**
     * User has common user level 
     *
     * @return booelan
     */
    public function isUserLevel() {
        return $this->level == '2';
    }
    
    /**
     * User has administrator level 
     *
     * @return booelan
     */
    public function isAdministratorLevel() {
        return $this->level == '1';
    }

    /**
     * User has superadministrator level 
     *
     * @return booelan
     */
    public function isSuperAdministratorLevel() {
        return $this->level == '0';
    }
}

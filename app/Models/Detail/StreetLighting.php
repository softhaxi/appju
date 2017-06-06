<?php

namespace APPJU\Models\Detail;

use APPJU\Models\Common\BaseModel as Model;

use APPJU\Models\Detail\Survey;
use APPJU\Models\Master\Customer;

/**
 * Definition of Street Lighting
 * 
 * @author Raja Sihombing <if09051@gmail.com>
 * @version 1.0.0
 * @since 1.0
 */
class StreetLighting extends Model {

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'street_lightings';
    
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'customer_id', 'name', 'address', 'power', 'rate', 
        'number_of_lamp', 'latitude', 'longitude', 'geolocation', 
        'status', 'created_by', 'updated_by'
    ];
    
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    /**
     * Get customer
     *
     * @return customer
     */
    public function customer() {
        return $this->belongTo(Customer::class, 'customer_id', 'id');
    }

    /**
     * Get survey
     *
     * @return survey
     */
    public function survey() {
        return $this->morphOne(Survey::class, 'surveyable');
    }

    /**
     * Get photo
     *
     * @return photo
     */
    public function photo() {
        return $this->morphOne(Photo::class, 'photoable');
    }

    /**
     * Get list of lamps
     *
     * @return list of lamp
     */
    public function lamps() {
        return $this->hasMany(StreetLigtingLamp::class, 'street_lighting_id', 'id');
    }
}

<?php

namespace APPJU\Models\Detail;

use APPJU\Models\Common\BaseModel as Model;
use APPJU\Models\Detail\Photo;
use APPJU\Models\Detail\StreetLighting;
use APPJU\Models\Detail\Survey;

/**
 * Definition of Street Lighting Lamp
 * 
 * @author Raja Sihombing <if09051@gmail.com>
 * @version 1.0.0
 * @since 1.0
 */
class StreetLightingLamp extends Model {
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'street_lighting_lamps';
    
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'street_lighting_id', 'code', 'type', 
        'power', 'latitude', 'longitude', 'geolocation',
        'remark','status', 'created_by', 'updated_by'
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
     * Get Street Lighting
     *
     * @return street lighting
     */
    public function streetLighting() {
        return $this->belongTo(StreetLighting::class, 'street_lighting_id', 'id');
    }

    /**
     * Get survey name
     *
     * @return survey class
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
}

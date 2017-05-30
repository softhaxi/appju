<?php

namespace APPJU\Models\Detail;

use APPJU\Models\Common\BaseModel as Model;

/**
 * Definition of Survey
 * 
 * @author Raja Sihombing <if09051@gmail.com>
 * @version 1.0.0
 * @since 1.0
 */
class Survey extends Model {
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'surveys';
    
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'class', 'level', 'parent_id', 'action', 'url',
        'surveyable_id', 'surveyable_type',  'status', 'created_by', 'updated_by'
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
     * Polimorphic Survey
     *
     * @return Morph
     */
    public function surveyable() {
        return $this->morphTo();
    }
}

<?php

namespace APPJU\Models\Detail;

use APPJU\Models\Common\BaseModel as Model;

/**
 * Definition of Photo
 * 
 * @author Raja Sihombing <if09051@gmail.com>
 * @version 1.0.0
 * @since 1.0
 */
class Photo extends Model {
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'photos';
    
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'path', 'height', 'width', 
        'photoable_id', 'photoable_type', 'status', 'created_by', 'updated_by'
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
     * Polymorphic Survey
     *
     * @return Morph
     */
    public function photoable() {
        return $this->morphTo();
    }
}

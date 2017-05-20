<?php

namespace APPJU\Models\Master;

use APPJU\Models\Common\BaseModel as Model;

/**
 * Definition of Costomer
 * 
 * @author Raja Sihombing <if09051@gmail.com>
 * @version 1.0.0
 * @since 1.0
 */
class Customer extends Model {
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'customers';
    
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'code', 'name', 'address', 'address2', 'address3',
        'rate', 'power', 'stand_start', 'stand_end', 'kwh', 'ptl', 'stamp', 
        'bank_fee', 'ppn', 'monthly_bill', 'status', 'created_by', 'updated_by'
    ];
    
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id', 'created_at', 'updated_at', 'deleted_at'
    ];
}

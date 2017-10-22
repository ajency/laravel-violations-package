<?php

namespace Ajency\Violations\Models;

use Illuminate\Database\Eloquent\Model;

class Violation extends Model
{
	protected $table = "aj_vio_violations";

	
	//only allow the following items to be mass-assigned to our model
	protected $fillable = array('status','who_id','who_type','who_meta','whom_id','whom_type','whom_meta','cc_list','bcc_list' );


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'who_meta' => 'array',
        'whom_meta' => 'array',
        'cc_list' => 'array',
        'bcc_list' => 'array',
    ];	
}

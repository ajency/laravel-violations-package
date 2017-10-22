<?php

namespace Ajency\Violations\Models;

use Illuminate\Database\Eloquent\Model;

class ViolationType extends Model
{
    protected $table = "aj_vio_types";

    protected $fillable = array('shortcode', 'title', 'description', 'severity', 'published' );
}

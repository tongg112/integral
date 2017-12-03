<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IntegralLog extends Model
{
    protected $table = 'integral_log';

    /**
     * 不可被批量赋值的属性。
     *
     * @var array
     */
    protected $guarded = [];
}

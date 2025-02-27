<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SqlExecutionLogs extends Model
{
    use HasFactory;

    protected $table = 'sql_execution_logs';

}

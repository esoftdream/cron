<?php

namespace Esoftdream\Cron\Models;

use CodeIgniter\Model;

class LogCronModel extends Model
{
    protected $table            = 'log_cron';
    protected $primaryKey       = 'cron_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'cron_job_name',
        'cron_command',
        'cron_start_datetime',
        'cron_end_datetime',
        'cron_duration_seconds',
        'cron_status',
        'cron_output',
        'cron_error_message',
    ];
    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'cron_created_datetime';
    protected $updatedField  = 'cron_updated_datetime';
    // protected $deletedField  = 'deleted_at';
}

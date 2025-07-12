<?php

namespace Esoftdream\Cron\Config;

use CodeIgniter\Config\BaseConfig;

class CronLimits extends BaseConfig
{
    /**
     * Maksimum eksekusi per cron job per hari.
     * Format:
     *
     * 'job_name' => [
     *    'limit' => jumlah_maksimum,
     *    'period' => 'daily' | 'weekly' | 'monthly'
     * ]
     */
    public array $limits = [
    ];
}

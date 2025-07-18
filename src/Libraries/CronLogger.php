<?php

namespace Esoftdream\Cron\Libraries;

use CodeIgniter\Database\BaseConnection;
use Esoftdream\Cron\Models\LogCronModel;

class CronLogger
{
    protected int $logId;
    protected string $startTime;
    protected LogCronModel $model;
    protected string $jobName;
    protected string $command;
    protected array $dailyLimits;

    public function __construct(string $jobName, string $command, ?BaseConnection $db = null)
    {
        $this->jobName     = $jobName;
        $this->command     = $command;
        $this->model       = new LogCronModel($db);
        $this->dailyLimits = config('CronLimits')->limits ?? [];
    }

    /**
     * Periksa apakah cron sudah mencapai batas eksekusi harian/weekly/monthly.
     *
     * @return bool true jika cron sudah mencapai batas, false jika belum
     */
    public function hasReachedLimit(): bool
    {
        $config = $this->dailyLimits[$this->jobName] ?? ['limit' => 1, 'period' => 'daily'];
        $limit  = $config['limit'];
        $period = $config['period'];

        $start = null;
        $end   = date('Y-m-d 23:59:59');

        switch ($period) {
            case 'daily':
                $start = date('Y-m-d 00:00:00');
                break;

            case 'weekly':
                // Mulai dari hari Senin minggu ini
                $monday = strtotime('monday this week');
                $start  = date('Y-m-d 00:00:00', $monday);
                break;

            case 'monthly':
                $start = date('Y-m-01 00:00:00');
                break;

            default:
                // fallback ke daily
                $start = date('Y-m-d 00:00:00');
        }

        $count = $this->model
            ->where('cron_job_name', $this->jobName)
            ->where('cron_start_datetime >=', $start)
            ->where('cron_start_datetime <=', $end)
            ->countAllResults();

        return $count >= $limit;
    }

    /**
     * Memulai cron dengan menyimpan log di database.
     *
     * @return bool true jika berhasil memulai cron, false jika sudah mencapai batas eksekusi
     */
    public function start(): bool
    {
        if ($this->hasReachedLimit()) {
            return false;
        }

        $this->startTime = microtime(true);

        $this->logId = $this->model->insert([
            'cron_job_name'         => $this->jobName,
            'cron_command'          => $this->command,
            'cron_start_datetime'   => date('Y-m-d H:i:s'),
            'cron_status'           => 'running',
            'cron_created_datetime' => date('Y-m-d H:i:s'),
        ], true); // return insert ID

        return true;
    }

    /**
     * Menyimpan log akhir cron di database.
     *
     * @param string|null $output output dari command, jika ada
     */
    public function end(?string $output = null): void
    {
        $duration = microtime(true) - $this->startTime;

        $this->model->update($this->logId, [
            'cron_end_datetime'     => date('Y-m-d H:i:s'),
            'cron_duration_seconds' => $duration,
            'cron_status'           => 'success',
            'cron_output'           => $output,
        ]);
    }

    /**
     * Menyimpan log akhir cron di database dengan status error.
     *
     * @param string $errorMessage pesan error yang terjadi
     */
    public function fail(string $errorMessage): void
    {
        $this->model->update($this->logId, [
            'cron_end_datetime'  => date('Y-m-d H:i:s'),
            'cron_status'        => 'error',
            'cron_error_message' => $errorMessage,
        ]);
    }
}

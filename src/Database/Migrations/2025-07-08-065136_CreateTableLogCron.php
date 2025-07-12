<?php

namespace Esoftdream\Cron\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableLogCron extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'cron_id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'cron_job_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'comment'    => 'Nama alias atau jenis cron',
            ],
            'cron_command' => [
                'type'    => 'TEXT',
                'comment' => 'Perintah atau method yang dijalankan',
            ],
            'cron_start_datetime' => [
                'type'    => 'DATETIME',
                'comment' => 'Waktu mulai cron',
            ],
            'cron_end_datetime' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'comment' => 'Waktu selesai cron',
            ],
            'cron_duration_seconds' => [
                'type'    => 'FLOAT',
                'null'    => true,
                'comment' => 'Durasi dalam detik',
            ],
            'cron_status' => [
                'type'       => 'ENUM',
                'constraint' => ['running', 'success', 'error'],
                'default'    => 'running',
                'comment'    => 'Status hasil cron',
            ],
            'cron_output' => [
                'type'    => 'TEXT',
                'null'    => true,
                'comment' => 'Output dari command',
            ],
            'cron_error_message' => [
                'type'    => 'TEXT',
                'null'    => true,
                'comment' => 'Pesan error jika gagal',
            ],
            'cron_created_datetime' => [
                'type'    => 'DATETIME',
                'null'    => false,
                'comment' => 'Waktu dibuat',
            ],
            'cron_updated_datetime' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'comment' => 'Waktu diupdate',
            ],
        ]);

        $this->forge->addKey('cron_id', true);
        $this->forge->createTable('log_cron', true, [
            'comment' => 'Log cron',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('log_cron', true);
    }
}

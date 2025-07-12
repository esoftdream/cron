<?php

namespace Esoftdream\Atc\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class ConfigCron extends BaseCommand
{
    protected $group       = 'Cron';
    protected $name        = 'cron:publish';
    protected $description = 'Publishes the ATC config files';

    public function run(array $params)
    {
        $sourcePath = ROOTPATH . 'vendor/esoftdream/cron/src/Config/CronLimits.php'; // Sesuaikan dengan lokasi file asli
        $targetPath = APPPATH . 'Config/CronLimites.php';

        if (! is_file($sourcePath)) {
            CLI::error("Source file not found: $sourcePath");
            return;
        }

        if (is_file($targetPath)) {
            $overwrite = CLI::prompt('File already exists at ' . $targetPath . '. Overwrite? (yes/no)', ['yes', 'no']);
            if ($overwrite === 'no') {
                CLI::write('Publishing canceled.', 'yellow');
                return;
            }
        }

        if (! copy($sourcePath, $targetPath)) {
            CLI::error("Failed to publish config file.");
        } else {
            // Replace the namespace
            $contents = file_get_contents($targetPath);
            $contents = str_replace('namespace Esoftdream\Cron\Config', 'namespace Config', $contents);
            file_put_contents($targetPath, $contents);

            CLI::write("Config file published to: $targetPath", 'green');
        }
    }
}

# Cron Log

## Contoh Penggunaan

1. Jalankan `php spark cron:publish` untuk menambahkan config cron diproject.
1. Jalankan `php spark migrate -n Esoftdream\Cron` untuk melakukan migrasi tabel.
1. Untuk penyimpanan log dapat menggunakan sample berikut atau kembangkan sendiri
    ```php
    public function index()
    {
        $cronName = 'url-cron'; // nama url cron atau lainnya
        $log      = new \Esoftdream\Cron\Libraries\CronLogger($cronName, __METHOD__);

        if (! $log->start()) {
            echo 'Cron ' . $cronName . " mencapai batas eksekusi, skip...\n";

            return;
        }

        $db = Database::connect(null, false);

        try {
            // output ini berupa string dan merupakan proses eksekusi cronnya
            // pastikan throw nya sudah ada didalamnya
            $output = $this->process($db);

            $log->end($output);
        } catch (Throwable $th) {
            $log->fail($th->getMessage());
        }

        $db->close();
    }
    ```

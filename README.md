# Cron

## Contoh Penggunaan

```php
public function index()
{
    $cronName = 'url-cron'; // nama url cron atau lainnya
    $log      = new CronLogger($cronName, self::class . '::' . __FUNCTION__);

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

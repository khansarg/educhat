<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Exception;

class DBRetry
{
    public static function queryWithRetry($callback, $retries = 3)
    {
        while ($retries > 0) {
            try {
                return $callback();
            } catch (\Exception $e) {
                if (
                    $retries > 0 &&
                    str_contains($e->getMessage(), 'MySQL server has gone away') ||
                    str_contains($e->getMessage(), 'Connection refused') ||
                    str_contains($e->getMessage(), 'ECONNRESET') ||
                    str_contains($e->getMessage(), 'PROTOCOL_CONNECTION_LOST')
                ) {
                    \Log::warning("⚠ DB tidur / putus. Mencoba reconnect... ($retries)");
                    $retries--;
                    sleep(2); // tunggu 2 detik
                    DB::reconnect(); // reconnect database
                    continue;
                }

                throw $e; // kalau bukan error sleep, lempar asli
            }
        }

        throw new Exception("❌ DB gagal reconnect setelah retry.");
    }
}

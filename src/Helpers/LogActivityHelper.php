<?php

namespace PhpHelpers\Helpers;

use App\Models\LogActivity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LogActivityHelper
{
    public static function log(array $params): void
    {
        try {
            DB::connection('logs')->beginTransaction();
            $origin = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3)[2] ?? null;

            LogActivity::create([
                'context' => app()->runningInConsole() ? 'console' : 'web',
                'origin_class' => $origin['class'] ?? null,
                'origin_function' => $origin['function'] ?? null,
                'errors' => $params['errors'] ?? null,
                'user_id' => request()?->refValue ?? null,
                'ip_address' => request()?->ip(),
                'user_agent' => request()?->userAgent(),
                'url' => request()?->url(),
                'url_method' => request()?->method(),
                'status' => $params['status'],
                'data_request' => $params['data_request'] ?? (request()?->all() ?? null),
                'data_response' => $params['data_response'] ?? null,
            ]);
            DB::connection('logs')->commit();
        } catch (\Throwable $th) {
            DB::connection('logs')->rollBack();
            Log::error($th->getMessage(), [
                'file' => $th->getFile(),
                'line' => $th->getLine(),
                'user_id' => request()?->refValue ?? null,
                'trace' => $th->getTraceAsString(),
            ]);
        }
    }
}

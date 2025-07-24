<?php

namespace App\Helpers;

use App\Models\Audit_log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\AuditRequest;

class AuditLogger
{
    public static function log(
        $userId = null,
        string $action,
        ?string $tableName = null,
        $oldValue = null,
        $newValue = null,
        ?string $ipAddress = null,
        ?string $recordId = null,
        ?string $method = null
    ): void {
        $userId = $userId ?? Auth::id();
        $ipAddress = $ipAddress ?? request()->ip();

        if (!$ipAddress) {
            throw new \Exception('IP address is required for logging audit events.');
        }

        $oldValueFormatted = self::formatValue($oldValue, 'old');
        $newValueFormatted = self::formatValue($newValue, $oldValueFormatted ? 'new' : 'create');

        $data = [
            'user_id'    => $userId,
            'action'     => $action,
            'table_name' => $tableName,
            'old_value'  => $oldValueFormatted,
            'new_value'  => $newValueFormatted,
            'ip_address' => $ipAddress,
            'record_id'  => $recordId,
            'method'     => $method,
        ];

        self::validateAndStore($data);
    }

    private static function formatValue($value, string $type): ?string
    {
        if (!is_array($value)) {
            return $value;
        }

        $masked = [];
        foreach ($value as $key => $val) {
            $key = strtolower($key);

            if (in_array($key, ['password', 'password_confirmation'])) {
                $val = '********';
            }

            if (!in_array($key, ['updated_at', 'created_at', 'deleted_at']) && $val) {
                $masked[] = "$key: $val";
            }
        }

        if (empty($masked)) {
            return null;
        }

        $joined = implode(', ', $masked);

        if ($type === 'old') {
            $timestamp = $value['updated_at'] ?? now();
            return "It changed from: $joined at $timestamp";
        }

        return $type === 'new'
            ? "It changed to: $joined"
            : "The value is: $joined";
    }

    private static function validateAndStore(array $data): void
    {
        $rules = (new AuditRequest())->rules();
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new \Exception(
                'Validation failed for audit log: ' .
                implode(', ', $validator->errors()->all())
            );
        }

        Audit_log::create($validator->validated());
    }
}

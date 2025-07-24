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

        // Format old value
        if ($oldValue) {
            $formatted = [];

            foreach ($oldValue as $key => $value) {
                $key = strtolower($key);

                if (in_array($key, ['password', 'password_confirmation'])) {
                    $value = '********';
                }

                if (!in_array($key, ['updated_at', 'created_at', 'deleted_at']) && $value) {
                    $formatted[] = "$key: $value";
                }
            }

            $timestamp = $oldValue['updated_at'] ?? now();
            $oldValue = 'It changed from: ' . implode(', ', $formatted) . ' at ' . $timestamp;
        }

        // Format new value
        if ($newValue) {
            $formatted = [];

            foreach ($newValue as $key => $value) {
                $key = strtolower($key);

                if (in_array($key, ['password', 'password_confirmation'])) {
                    $value = '********';
                }

                if (!in_array($key, ['updated_at', 'created_at', 'deleted_at']) && $value) {
                    $formatted[] = "$key: $value";
                }
            }

            if (!$oldValue) {
                $newValue = 'The value is: ' . implode(', ', $formatted);
            } else {
                $newValue = 'It changed to: ' . implode(', ', $formatted);
            }
        }

        // Prepare data
        $data = [
            'user_id'    => $userId,
            'action'     => $action,
            'table_name' => $tableName,
            'old_value'  => $oldValue,
            'new_value'  => $newValue,
            'ip_address' => $ipAddress,
            'record_id'  => $recordId,
            'method'     => $method,
        ];

        // Validate using AuditRequest rules
        $rules = (new AuditRequest())->rules();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new \Exception('Validation failed for audit log: ' . implode(', ', $validator->errors()->all()));
        }

        Audit_log::create($validator->validated());
    }
}

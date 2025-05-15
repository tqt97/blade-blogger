<?php

namespace App\Traits;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait HandleBulkDelete
{
    /**
     * Handles the bulk deletion of model records based on the provided request.
     *
     * @param  Request  $request  The incoming HTTP request containing a comma-separated list of IDs to delete.
     * @param  string  $modelClass  The fully qualified class name of the model to delete records from.
     * @param  string|null  $successKey  Optional translation key for a success message.
     * @param  string|null  $failKey  Optional translation key for a failure message.
     * @param  string|null  $emptyKey  Optional translation key for an empty selection message.
     * @return RedirectResponse Redirects back with a success, warning, or error message.
     */
    public function bulkDeleteGeneric(
        Request $request,
        string $modelClass,
        ?string $successKey = null,
        ?string $failKey = null,
        ?string $emptyKey = null
    ): RedirectResponse {
        try {
            $ids = $request->input('ids');

            if (empty($ids)) {
                $key = $emptyKey ?? $this->resolveMessageKey($modelClass, 'bulk_delete_empty');

                return back()->with('warning', __($key));
            }

            DB::transaction(function () use ($modelClass, $ids) {
                $modelClass::query()->whereIn('id', $ids)->delete();
            });

            $key = $successKey ?? $this->resolveMessageKey($modelClass, 'bulk_delete_success');

            return back()->with('success', __($key));
        } catch (\Throwable $th) {
            Log::error($th);
            $key = $failKey ?? $this->resolveMessageKey($modelClass, 'bulk_delete_fail');

            return back()->with('error', __($key));
        }
    }

    /**
     * Resolves the translation message key for a given model class and suffix.
     *
     * @param  string  $modelClass  The fully qualified class name of the model.
     * @param  string  $suffix  The message suffix to append.
     * @return string The resolved translation message key in the format 'model.messages.suffix'.
     */
    protected function resolveMessageKey(string $modelClass, string $suffix): string
    {
        $basename = class_basename($modelClass);
        $key = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $basename));

        return "{$key}.messages.{$suffix}";
    }
}

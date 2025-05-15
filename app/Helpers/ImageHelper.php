<?php

namespace App\Helpers;

use App\Exceptions\FileNotFoundOnDiskException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class ImageHelper
{
    /**
     * Store the uploaded file and return the file path.
     *
     * @param  UploadedFile  $file  The file to store
     * @param  string  $collection  The collection to store the file in
     * @param  string  $disk  The disk to store the file on
     * @return bool|string The file path
     */
    public static function store(UploadedFile $file, string $collection, string $disk = 'public'): bool|string
    {
        $folder = self::resolveFolder($collection);
        // self::ensureWritable($disk, $folder);
        $filename = self::generateFileName($file);

        return Storage::disk($disk)->putFileAs($folder, $file, $filename);
    }

    /**
     * Delete a single file from storage.
     *
     * @param  string  $path  The path of the file to delete
     * @param  string  $disk  The disk to delete the file from
     *
     * @throws FileNotFoundOnDiskException
     */
    public static function delete(string $path, string $disk = 'public', bool $throwIfMissing = false): bool
    {
        if (Storage::disk($disk)->missing($path)) {
            if ($throwIfMissing) {
                throw new FileNotFoundOnDiskException($path, $disk);
            }
            Log::warning("File [{$path}] does not exist on disk [{$disk}].");

            return false;
        }

        return Storage::disk($disk)->delete($path);
    }

    /**
     * Delete multiple files from storage.
     *
     * @param  array  $paths  The paths of the files to delete
     * @param  string  $disk  The disk to delete the files from
     *
     * @throws RuntimeException
     */
    public static function deleteMany(array $paths, string $disk = 'public'): void
    {
        foreach ($paths as $path) {
            self::delete($path, $disk);
        }
    }

    /**
     * Ensure the folder is writable.
     *
     * @param  string  $disk  The disk to check
     * @param  string  $folder  The folder to check
     *
     * @throws RuntimeException
     */
    protected static function ensureWritable(string $disk, string $folder): void
    {
        if (
            ! Storage::disk($disk)->exists($folder) ||
            ! is_writable(Storage::disk($disk)->path($folder))
        ) {
            throw new RuntimeException("Cannot write to folder: {$folder}");
        }

    }

    /**
     * Generate a unique filename for the uploaded file.
     *
     * @param  UploadedFile  $file  The file to generate a filename for
     * @return string The generated filename
     */
    protected static function generateFileName(UploadedFile $file): string
    {
        $originalName = $file->getClientOriginalName();
        $basename = pathinfo($originalName, PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();

        return Str::slug($basename).'-'.uniqid().'.'.$extension;

    }

    /**
     * Resolve the folder path for the given collection.
     *
     * @param  string  $collection  The collection to resolve the folder for
     * @return string The folder path
     */
    protected static function resolveFolder(string $collection): string
    {
        return config('media.paths.images', 'images').'/'.$collection;
    }
}

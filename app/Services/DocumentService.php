<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentService {

    public static function save_file(Request $request, $column_name = 'file', $folder = '', $name = '', $ext = '')
    {
        if ($request->hasFile($column_name)) {
            $file = $request->file($column_name);
            if ($folder === '') $folder = $column_name;
            if ($name === '') $name = Str::uuid();
            if ($ext === '') $filename = $name . '.'. $file->extension();
            else $filename = $name . '.'. $ext;
            // If caller passed a folder that starts with "public/" or "storage/",
            // treat it as a path on the public disk and strip the prefix so
            // Storage::url() produces the correct /storage/... URL.
            if (str_starts_with($folder, 'public/')) {
                $rel = preg_replace('#^public/#', '', $folder);
                return Storage::disk('public')->putFileAs($rel, $file, $filename);
            }
            if (str_starts_with($folder, 'storage/')) {
                $rel = preg_replace('#^storage/#', '', $folder);
                return Storage::disk('public')->putFileAs($rel, $file, $filename);
            }

            return Storage::putFileAs($folder, $file, $filename);
        }
        return '';
    }

    public static function delete_file($filename)
    {
        try {
            Storage::delete($filename);
        } catch (\Exception $e) {}
    }

}

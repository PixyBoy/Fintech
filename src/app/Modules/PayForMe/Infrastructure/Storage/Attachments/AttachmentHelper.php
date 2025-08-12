<?php

namespace App\Modules\PayForMe\Infrastructure\Storage\Attachments;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AttachmentHelper
{
    public static function store(UploadedFile $file): array
    {
        $path = $file->store('payforme/'.date('Y/m/d'), 'public');
        return [
            'path' => $path,
            'name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'type' => $file->getMimeType(),
        ];
    }
}

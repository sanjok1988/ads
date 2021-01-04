<?php

namespace Sanjok\Blog\Traits\UploaderTrait;

use Illuminate\Http\Request;

trait UploadTrait
{
    public function upload($file, $folder = 'default')
    {

        $upload_path = 'uploads/' . $folder;
        if (is_file($file)) {
            $fileNameWithExt = $file->getClientOriginalName();
            $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            //$upload_path = $file->storeAs($folder, $fileNameToStore, 'local');
            $file->move($upload_path, $fileNameToStore);
            return $upload_path . '/' . $fileNameToStore;
        } else {
            $upload_path = '';
        }
    }
}

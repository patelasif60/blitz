<?php
namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use ZipStream\File;

trait FileUpload
{
    protected function getFileName(UploadedFile $file, $prefix = null)
    {
        return empty($file) ? (Str::random(10) . '_' . time() . $prefix) : (Str::random(10) . '_' . time() . $prefix.$file->getClientOriginalName());
    }

    /**
     * Upload single file
     *
     * @param UploadedFile $file
     * @param null $folder
     * @param string $disk
     * @param null $filename
     * @param null $prefix
     * @return string
     */
    public function uploadOne(UploadedFile $file, $folder = null, $disk = 'public',$prefix = null,$filename = null)
    {
        $fileName = $this->getFileName($file,$prefix);

        $path = Storage::disk($disk)->putFileAs($folder,$file,$fileName);

        return $path;

    }

    /**
     * Remove single file
     *
     * @param $filePath
     * @param string $disk
     * @return bool
     */
    public function removeOne($filePath, $disk = 'public')
    {

        if (!empty($filePath)) {

            if (Storage::disk($disk)->has($filePath)) {

                Storage::disk($disk)->delete($filePath);

                return true;
            }
            return false;
        }

        return true;
    }

    /**
     * Remove multi files
     *
     * @param $files
     * @param string $disk
     */
    public function removeFiles($files, $disk = 'public')
    {
        foreach ($files as $file) {
            $this->removeOne($file,$disk);
        }
    }

    /**
     * Upload multi files
     *
     * @param $files
     * @param null $folder
     * @param string $disk
     */
    public function uploadFiles($files, $folder = null, $disk = 'public')
    {
        foreach ($files as $file) {
            $this->uploadOne($file,$folder,$disk);
        }
    }
}

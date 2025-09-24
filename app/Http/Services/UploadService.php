<?php
namespace App\Http\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Auth;
use Carbon\Carbon;
use Throwable;


class UploadService
{

    /**
     * Handle single or multiple image upload.
     *
     * @param bool $isMultiple
     * @param Request $request
     * @param string $inputName
     * @param string $directory
     * @return array|string
     */
    public function handleUpload(bool $isMultiple, Request $request, string $inputName, string $directory, string $disk)
    {
        $uploadedFiles = []; // Ensure this is initialized for both single and multiple uploads

        Log::info('UploadService: handleUpload triggered', [
            'inputName' => $inputName,
            'isMultiple' => $isMultiple,
            'hasFile' => $request->hasFile($inputName),
        ]);


        try{
                if ($isMultiple) {
                    // Handle multiple files
                    if ($request->hasFile($inputName)) {
                        foreach ($request->file($inputName) as $file) {
                            if ($file instanceof UploadedFile) {

                                // generate filename
                                $filename = $this->generateFileName($file);
                                $path = $file->storeAs($directory, $filename, $disk);
                                Storage::disk($disk)->setVisibility($path, 'public');

                                $uploadedFiles[] = Storage::disk($disk)->url($path);
                            }
                        }
                    }
                    
                } else {

                    // Handle single file
                    if ($request->hasFile($inputName)) {
                        $file = $request->file($inputName);
                        if ($file instanceof UploadedFile) {

                                Log::info('UploadService: Single file is valid', [
                                    'originalName' => $file->getClientOriginalName(),
                                    'mimeType' => $file->getMimeType(),
                                ]);

                                //generate filename
                                $filename = $this->generateFileName($file);
                                $path = $file->storeAs($directory, $filename, $disk);
                                Storage::disk($disk)->setVisibility($path, 'public');

                            return Storage::disk($disk)->url($path);

                            Log::info('UploadService: File uploaded successfully', [
                                'path' => $path,
                                'url' => Storage::disk($disk)->url($path),
                            ]);
                        }
                    }
                    
                }

            return $uploadedFiles;

        } catch (Throwable $e) {
                // Custom logging to 'upload-service-error.log'
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/upload-service-error.log')
                ])->error("Upload Failed: " . $e->getMessage(), [
                    'directory' => $directory,
                    'user_id' => Auth::id() ?? 'N/A',
                    'request' => json_encode($request->all()),
                    'exception' => $e->getTraceAsString()
                ]); 

            return $arrResponse = [
                            "error" => true,
                            "message" => __('messages.file.upload_failed')
                        ]; 
        }

    }

    

    /**
     * Generate a unique filename for the uploaded file.
     *
     * @param UploadedFile $file
     * @return string
     */
    protected function generateFileName(UploadedFile $file)
    {
        return Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '-' . time() . '.' . $file->getClientOriginalExtension();
    }

}
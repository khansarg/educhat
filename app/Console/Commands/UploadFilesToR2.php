<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\MaterialFile;

class UploadFilesToR2 extends Command
{
    protected $signature = 'files:upload-to-r2';
    protected $description = 'Upload existing files to Cloudflare R2 and update their URLs in the database';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Starting to upload files to Cloudflare R2...');

        $files = MaterialFile::all(); // Get all the records from the database

        foreach ($files as $file) {
            $localPath = storage_path('app/public/' . $file->pdf_path); // Assuming the files are in public storage
            $newPath = 'materials/' . basename($file->pdf_path); // New file path in Cloudflare R2

            if (Storage::disk('cloudflare_r2')->put($newPath, fopen($localPath, 'r'))) {
                // If the file upload is successful, update the database record
                $file->update([
                    'pdf_path' => $newPath,
                    'pdf_url'  => Storage::disk('cloudflare_r2')->url($newPath), // Generate the URL for Cloudflare R2
                ]);
                $this->info('Uploaded file: ' . $file->original_name);
            } else {
                $this->error('Failed to upload file: ' . $file->original_name);
            }
        }

        $this->info('File upload to Cloudflare R2 completed.');
    }
}

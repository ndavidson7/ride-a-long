<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Auth\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use SplFileInfo;

class UploadProfilePicture implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public User $user,
        public string $path
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $file = new SplFileInfo(Storage::path($this->path));
        $this->user->updateMedia($file, [
            'upload_preset' => config('cloudinary.upload_preset')
        ]);
        Storage::delete($this->path);
    }
}

<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HandlesProfilePhoto
{
    /**
     * Update the user's profile photo.
     *
     * @param UploadedFile $photo
     * @param string $directory
     * @return void
     */
    public function updateProfilePhoto(UploadedFile $photo, string $directory = 'profiles')
    {
        if ($this->photo_profile) {
            Storage::disk('public')->delete($this->photo_profile);
        }

        $path = $photo->store($directory, 'public');

        $this->update([
            'photo_profile' => $path
        ]);
    }

    /**
     * Delete the user's profile photo from storage and database.
     *
     * @return void
     */
    public function deleteProfilePhoto()
    {
        if ($this->photo_profile) {
            Storage::disk('public')->delete($this->photo_profile);
            $this->update(['photo_profile' => null]);
        }
    }
}

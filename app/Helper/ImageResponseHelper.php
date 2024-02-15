<?php

namespace App\Helper;

use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request;

class ImageResponseHelper{
    public function ImageFile($image, $userRole)
    {
        // Check if a file was uploaded
        if ($image->hasFile('photo')) {
            // Validate the uploaded file (you can add more validation rules if needed)
            $image->validate([
                'photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Example validation rules
            ]);

            $data = $image->file('photo');

            // Generate a unique image file name using timestamp and original file name
            $image_file_name = date('YmdHi') . '_' . $data->getClientOriginalName();

            // Determine the target directory based on the user role
            $targetDirectory = '';

            if ($userRole === 'vendor') {
                $targetDirectory = public_path('upload/vendor_images');
            } elseif ($userRole === 'admin') {
                $targetDirectory = public_path('upload/admin_images');
            } else {
                $targetDirectory = public_path('upload/user_images');
            }

            // Move the uploaded file to the desired directory
            $data->move($targetDirectory, $image_file_name);

            // Return the generated image file name
            return $image_file_name;
        } else {
            // Return a response indicating no file was uploaded
            return response()->json(['error' => 'No file uploaded.'], 400);
        }
    }

   
    
}
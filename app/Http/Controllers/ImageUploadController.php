<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use Image;

use App\Models\Avatar;

/**
 * This class is a controller for uploading images via file or URL.
 */
class ImageUploadController extends Controller
{
    /**
     * Uploads a new image.
     *
     * @param mixed $request
     * @author Niklas Fandrich
     * @return mixed
     */
    public function upload(Request $request) 
    {
        Validator::make($request->all(), [
            'image' => [
                Rule::requiredIf(!$request->has('url')),
                'image|mimes:jpeg,png,jpg,gif,svg'
            ],
            'url' => [
                Rule::requiredIf(!$request->has('image')),
                'url'
            ]
        ]);

        if ($request->has('image')) {
            $this->storeImage($request);
        } else {
            $avatar = Avatar::where('user_id', auth()->id());

            if ($avatar->exists()) {
              $avatar->update([
                'image_type' => 'url',
                'image_url' => $request->input('url'),
                'image_upload' => null
              ]);
            } else {
              Avatar::create([
                'user_id' => auth()->id(),
                'image_type' => 'url',
                'image_url' => $request->input('url'),
                'image_upload' => null
              ]);
            }
        }

        $request->session()->flash('status', 'Avatar successfully created!');

        return back();

    }
  
  
      /**
       * Prepares a image for storing.
       *
       * @param mixed $request
       * @author Niklas Fandrich
       * @return bool
       */
      public function storeImage($request) {
        // Get file from request
        $file = $request->file('image');
  
        // Get filename with extension
        $filenameWithExt = $file->getClientOriginalName();
  
        // Get file path
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
  
        // Remove unwanted characters
        $filename = preg_replace("/[^A-Za-z0-9 ]/", '', $filename);
        $filename = preg_replace("/\s+/", '-', $filename);
  
        // Get the original image extension
        $extension = $file->getClientOriginalExtension();
  
        // Create unique file name
        $fileNameToStore = $filename.'_'.time().'.'.$extension;
        $smallFileNameToStore = $filename.'_small_'.time().'.'.$extension;
  
        // Refer image to method resizeImage
        $save = $this->resizeImage($file, $fileNameToStore, $smallFileNameToStore);
  
        return $filename;
      }
  
      /**
       * Resizes a image using the InterventionImage package.
       *
       * @param object $file
       * @param string $fileNameToStore
       * @author Niklas Fandrich
       * @return bool
       */
      public function resizeImage($file, $fileNameToStore, $smallFileNameToStore) {
        // Resize image
        $resize = Image::make($file)->resize(600, null, function ($constraint) {
          $constraint->aspectRatio();
        })->encode('jpg');

        $resizeSmall = Image::make($file)->resize(300, null, function ($constraint) {
          $constraint->aspectRatio();
        })->encode('jpg');
  
        // Create hash value
        $hash = md5($resize->__toString());
        $hashSmall = md5($resizeSmall->__toString());
  
        // Prepare qualified image name
        $image = $hash."jpg";
        $imageSmall = $hashSmall."jpg";
  
        // Put image to storage
        $save = Storage::put("public/images/{$fileNameToStore}", $resize->__toString());
        $saveSmall = Storage::put("public/images/{$smallFileNameToStore}", $resizeSmall->__toString());

        // Save image url to DB
        $avatar = Avatar::where('user_id', auth()->id());

        if ($avatar->exists()) {
          $avatar->update([
            'image_type' => 'upload',
            'image_url' => null,
            'image_upload' => "/storage/images/{$fileNameToStore}",
            'image_upload_small' => "/storage/images/{$smallFileNameToStore}"
          ]);
        } else {
          Avatar::create([
            'user_id' => auth()->id(),
            'image_type' => 'upload',
            'image_url' => null,
            'image_upload' => "/storage/images/{$fileNameToStore}",
            'image_upload_small' => "/storage/images/{$smallFileNameToStore}"
          ]);
        }
  
        if($save && $saveSmall) {
          return true;
        }
        return false;
      }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;

class ImageRepository extends Controller
{
  /**
   * Constructor de la clase, el cual inicializa los valores por defecto.
   */
  public function __construct()
  {
  }
  // define a method to upload our image
  public function upload_image($base64_image, $image_path)
  {
    //The base64 encoded image data
    $image_64 = $base64_image;
    // exploed the image to get the extension
    $extension = explode(';base64', $image_64);
    //from the first element
    $extension = explode('/', $extension[0]);
    // from the 2nd element
    $extension = $extension[1];

    $replace = substr($image_64, 0, strpos($image_64, ',') + 1);

    // finding the substring from 
    // replace here for example in our case: data:image/png;base64,
    $image = str_replace($replace, '', $image_64);
    // replace
    $image = str_replace(' ', '+', $image);
    // set the image name using the time and a random string plus
    // an extension
    $imageName = time() . '_' . $this->generateRandomString(20) . '.' . $extension;
    // save the image in the image path we passed from the 
    // function parameter.
    $path = Storage::disk('s3')->put($image_path . '/' . $imageName, base64_decode($image));

    // return the image path and feed to the function that requests it
    return substr(Storage::disk('s3')->url($path), 0, -1) . $image_path . '/' . $imageName;
  }
  function generateRandomString($length = 10)
  {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }
}

<?php
/**
 * Created by Cao Jiayuan.
 * Date: 17-5-16
 * Time: 下午3:26
 */

namespace App\Http\Controllers;


use App\Traits\UploadHelper;
use App\Util\FileAPI;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class InfoController extends Controller
{

  use UploadHelper;

  public function uploadImg(Request $request)
  {
    $max = 5 * 1024;
    $data = $this->getValidatedData([
      'file' => 'required|image|max:' . $max,
    ]);
    /** @var UploadedFile $img */
    $img = $data['file'];
    $image = get_image_form_upload_file($img);
    $info = get_image_info($image);
    $path = $this->uploadAndStore($img, 'images');
    $data = array_merge([
      'url' => '/storage/' . $path,
    ], $info);
    $agent = $request->header('User-Agent');
    if (preg_match('/MSIE 9/i', $agent)) {
      $jsonp = $request->get('callback');
      FileAPI::makeResponse([
        'status'     => FileAPI::OK,
        'statusText' => 'OK',
        'body'       => $data,
      ], $jsonp);
      exit();
    }

    return $data;
  }
}
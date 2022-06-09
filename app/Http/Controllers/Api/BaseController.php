<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class BaseController extends Controller
{
    public function checkDirectoryExist()
    {
        //check folder
        if (!file_exists(public_path($this->folder_path))) {
            mkdir(public_path($this->folder_path));
        }
    }

    //check folder exits
    public function checkFolderExist($path)
    {
        if (!file_exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
            File::makeDirectory($path . '/thumbs', $mode = 0777, true, true);
        }
    }
    //rename directory if already exists
    public function renameDirectory($old_dir, $new_dir)
    {
        rename($old_dir, $new_dir);
    }
    //making image with thumb
    public function makeImageWithThumb($slug, $photo, $path)
    {
        $ext = explode('/', explode(':', substr($photo, 0, strpos($photo, ';')))[1])[1];
        $name = $slug . '.' .$ext;
        Image::make($photo)->save($path.'/'. $name);
        Image::make($photo)->resize(1024, 700)->save($path.'/thumbs/'.'big_'.$name);//resize image
        Image::make($photo)->resize(100, 100)->save($path.'/thumbs/'.'small_'.$name);//resize image
        return $path.'/'. $name;
    }
    public function getRandId()
    {
        $alphabet = array('A','B','C','D','E','F','G','H','J','K','L','M','N','P','Q','R','S','T','U','V','W','X','Y');
        $rand_char = $alphabet[array_rand($alphabet, 1)];

        $time_stamp = Carbon::today()->format('ymd');

        $seconds = $this->convertTimeToSecond(Carbon::now()->format('H:i:s'));
        return str_pad(\rand(1, 1000), 4, "0", STR_PAD_LEFT).'-'.$time_stamp.'-'.str_pad($seconds, 5, "0", STR_PAD_LEFT).$rand_char;
    }
    private function convertTimeToSecond(string $time): int
    {
        $d = explode(':', $time);

        $time =  ($d[0] * 3600) + ($d[1] * 60) + $d[2];
        return $time;
    }
}

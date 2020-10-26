<?php

namespace App\Http\Controllers;
use Exception, Log;
use Illuminate\Http\Request;
use App\Models\Videos;
use Storage;
use Illuminate\Support\Str;


// use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class VideoRecording extends Controller
{
    public function saveVideo(Request $request)
    {
        // $name = Str::random(8);
        // dd($name);
        $blob = $request->file('file');
        $name = request()->file('file')->getClientOriginalName();
        $subject = $name;
            $filename = str_replace(' ', '_',  $subject);
            $path = 'public/videos/';
            $imagePath = "{$path}{$filename}.mp4";
//  dd($imagePath);
try{
     if($request->hasFile('file')){
        //save the wav file to 'storage/app/audio' path with fileanme test.wav
        $file = Storage::put($imagePath, file_get_contents($blob));
        $save = Videos::create([
            'name' => $filename,
            'file' =>$imagePath
        ]);
        return response()->json([
                    'pass' => true
                ]);
        return view("layouts.video")->with('filename', $filename);
        }
}catch(Exception $e){
    Log::info("videorecording error". $e);
            return response()->json([
                'pass' => false
            ]);
}

        // try{
        //     if($request->hasFile('file')){
        //         $blobInput = $request->file('file');
        //         dd($blobInput);
        //     //save the wav file to 'storage/app/audio' path with fileanme test.wav
        //     Storage::put('audio/test.wav', file_get_contents($blobInput));
        //     return response()->json([
        //         'pass' => true
        //     ]);
        //     }
        // } catch(Exception $e){
        //     Log::info("videorecording error". $e);
        //         return response()->json([
        //             'pass' => false
        //         ]);
        // }


        // // dd($request->all());

        // if($request->new_data){
        //     // dd('ok');

        //     $file = $request->new_data;
        //     // dd($file);

        //     $filename = $file->getClientOriginalName();
        //     $path = public_path().'/uploads/';
        //     return $file->move($path, $filename);
        //     dd('ok');
        // }else{
        //     dd('not ok');
        // }
        // try {
        //     if ($request->hasFile('video')){
        //         dd('fetch blob');
        //         $blob = new Videos;
        //         $blob->name;
        //         $blob->file;
        //         $video = $request->file('video');
        //         $filename = $video->getClientOriginalName();
        //         dd($filename);
        //         $path = public_path().'/uploads/';
        //         $video->move($path, $filename);
        //     }
        //     // Log::info(">>>>".$video);
        //     // return response()->json([
        //     //     'pass' => true
        //     // ]);
        // } catch(Exception $e) {
        //     Log::info("videorecording error". $e);
        //     return response()->json([
        //         'pass' => false
        //     ]);
        // }
    }
}


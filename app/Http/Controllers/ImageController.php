<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function store(Request $request, Driver $driver)
    {
        $this->authorize('create', Driver::class);


        $request->validate([
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:2048']
        ]);

        $files = $request->file('images');

        //dd($files);

        if($request->hasFile('images'))
        {
            foreach ($files as $key => $file) {

//                $extension = $file->getClientOriginalExtension();
//
//                $fileName = time() .'-'. $key .'.'. $extension;
//
//                $filePath = 'images/drivers/' .$driver->id . '/' . $fileName;
//
//                Storage::disk('public')->put($filePath, file_get_contents($file));

                //полный путь
                //$path = Storage::disk('public')->url($path);
                $file = Storage::disk('public')->put('images/drivers/' .$driver->id, $file);

                $data = [
                    'driver_id' => $driver->id,
                    //'filename' => $fileName
                    'filename' => $file
                ];

                Image::query()->create($data);
            }
            return redirect()->back()->with('success', 'Successfully added!');
        }
        return false;
    }

    public function delete(Driver $driver, Image $image)
    {
        $this->authorize('create', Driver::class);


        $file = Image::query()->findOrFail($image->id);

        //$absolutePath = 'storage/images/drivers/'.$driver->id. '/' . $image->filename;

        $absolutePath = 'storage/'. $image->filename;

        if (file_exists($absolutePath)) {

            unlink($absolutePath);
            $file->delete();

            return redirect()->back()->with('success', 'Successfully added!');
        }

        return redirect()->back()->with('danger', 'Something went wrong!');

    }
}

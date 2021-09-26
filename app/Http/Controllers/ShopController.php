<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class ShopController extends Controller
{
    public function index()
    {
        return view('shop/index');
    }

    public function upload(Request $request)
    {
        $this->validate($request, [
            'image' => 'required|image|max:2048'
        ]);

        if($request->hasFile('image')) {
            $file = $request->file('image');
            $name = $file->getClientOriginalName();
            $filePath = 'pending/' . $name;
            $s3 = Storage::disk('s3');
            $s3->put($filePath, file_get_contents($file), 'public');
        }
        return Storage::disk('s3')->url($filePath);
    }

    public function download()
    {
        return Storage::disk('s3')->download('pending/jajaja.jpg');
    }

    public function move()
    {
        return Storage::disk('s3')->move('pending/jajaja.jpg', 'exported/jajaja.jpg');
    }
}

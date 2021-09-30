<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Aws\S3\S3Client;
use Aws\S3\Transfer;

class ShopController extends Controller
{
    public function index()
    {
        return view('shop/index');
    }

    public function upload(Request $request)
    {
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
        //return Storage::disk('s3')->download('pending/jajaja.jpg');
        $file = Storage::disk('s3')->get('pending/tmp.csv');
        $name = "tmp.csv";
        $headers = [
            'Content-Type' => "text/csv",
            'Content-Description' => "File Transfer",
            'Content-Disposition' => 'attachment; filename="'.$name.'"',
            'filename' => $name,
        ];
       //return response()->download($file, $name, $headers);
       return response($file, 200, $headers);
    }

    public function move()
    {
        return Storage::disk('s3')->move('pending/jajaja.jpg', 'exported/jajaja.jpg');
    }

    public function getObjects()
    {
        $client = new S3Client([
            'version'     => '2006-03-01',
            'region'      => env('AWS_REGION'),
            'credentials' => [
                'key'      => env('AWS_KEY'),
                'secret'   => env('AWS_SECRET'),
            ]
        ]);

        $response = $client->listObjectsV2([
            'Bucket' => env('AWS_BUCKET'),
            'Prefix' => 'exported/'
        ]);

        return response()->json($response['Contents']);
    }

    public function getObject()
    {
        $client = new S3Client([
            'version'     => '2006-03-01',
            'region'      => env('AWS_REGION'),
            'credentials' => [
                'key'      => env('AWS_KEY'),
                'secret'   => env('AWS_SECRET'),
            ]
        ]);
        
        $result = $client->headObject([
            'Bucket' => env('AWS_BUCKET'),
            'Key' => 'exported/jajaja.jpg'
        ]);
        //ContentLength
        return response()->json($result['ContentLength']);
    }

    public function uploadImg()
    {
        $client = new S3Client([
            'version'     => '2006-03-01',
            'region'      => env('AWS_REGION'),
            'credentials' => [
                'key'      => env('AWS_KEY'),
                'secret'   => env('AWS_SECRET'),
            ]
        ]);

        $result = $client->putObject([
            'Bucket' => env('AWS_BUCKET'),
            'Key' => 'new-folder/jajaja.jpg',
            'SourceFile' => '../public/images/jajaja.jpg'
        ]);
        //ContentLength
        return response()->json($result['ObjectURL']);
    }

    public function downloadBatch()
    {
        $client = new S3Client([
            'version'     => '2006-03-01',
            'region'      => env('AWS_REGION'),
            'credentials' => [
                'key'      => env('AWS_KEY'),
                'secret'   => env('AWS_SECRET'),
            ]
        ]);

        $source = 's3://' . env('AWS_BUCKET') . '/exported';
        $target = 'C:\Sites\shop\public\images';
        $manager = new Transfer($client, $source, $target);
        $manager->transfer();

        return response()->json(['msg' => 'aca']);
    }
}

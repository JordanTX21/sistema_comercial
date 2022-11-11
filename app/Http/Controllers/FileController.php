<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\File as FileModel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Intervention\Image\ImageManagerStatic as Image;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $allFiles = $request->file('files');

        if (!$allFiles) {
            return response(array('url' => '', 'data' => $allFiles, 'or' => $request));
        }
        //print_r($allFiles);
        $files = (!is_array($allFiles)) ? array($allFiles) : $allFiles;
        // Files allowed
        $allowedImages = ['jpg', 'jpeg', 'JPEG', 'png', 'gif', 'JPG', 'PNG', 'GIF', 'jfif', 'JFIF'];
        $allowedDocuments = ['pptx', 'PPTX', 'ppt', 'pdf', 'PDF', 'docx', 'doc', 'DOCX', 'DOC', 'xls', 'XLS', 'xlsx', 'XLSX', 'txt', 'TXT'];
        $allowedAudios = ['m4a', 'M4A', 'mp3', 'MP3', 'wav', 'WAV', 'wma', 'WMA', 'ogg', 'OGG'];
        $allowedVideos = ['mp4', 'MP4', 'm4v', 'M4V', 'mpeg', 'MPEG', 'mpg', 'MPG', 'wmv', 'WMV', 'avi', 'AVI'];
        $allowedApks = ['apk'];
        // Files allowed
        $allowedfileExtension = array_merge($allowedImages, $allowedDocuments, $allowedAudios, $allowedVideos, $allowedApks);
        //Files stored
        $filesStored = array();
        foreach ($files as $file) {
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $check = in_array($extension, $allowedfileExtension);
            if (!$check) {
                return [];
            }
            if (in_array($extension, $allowedImages)) {
                $folder = 'images';
            } else if (in_array($extension, $allowedDocuments)) {
                $folder = 'documents';
            } else if (in_array($extension, $allowedAudios)) {
                $folder = 'audios';
            } else if (in_array($extension, $allowedVideos)) {
                $folder = 'videos';
            } else if (in_array($extension, $allowedApks)) {
                $folder = 'apks';
            }
            $length = 20;
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }

            $random = $randomString;
            $fileUrl = $file->storeAs('files/' . $folder, $random . $filename);
            $path = storage_path('app\\' . $fileUrl);

            /**  SE AGREGO ESTE FRAGMENTO DE CODIGO PARA REDUCIR LAS IMAGENES EN TAMAÑO Y CALIDAD  **/
            $path2 = storage_path('app/' . $fileUrl);

            if (in_array($extension, $allowedImages)) {
                $image = Image::make($path2);

                $image->resize(900, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $image->save($path2, 80);
                //Storage::put($path2, (string) $image->encode('jpg', 30));
            }
            /**  FIN  **/

            $fileStored = FileModel::create([
                'type' => $extension,
                'name' => $filename,
                'url' => explode("files/", $path)[1]
            ]);
            array_push($filesStored, $fileStored);
        }
        return response($filesStored[0]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    public function viewFile($folder, $filename)
    {
        $path = storage_path('app/files/' . $folder . '/' . $filename);

        if (!File::exists($path)) {
            abort(404);
        }
        $file = File::get($path);
        $type = File::mimeType($path);
        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);
        return $response;
    }

    public function downloadFile($folder, $filename)
    {
        $path = storage_path('app/files/' . $folder . '/' . $filename);

        if (!File::exists($path)) {
            abort(404);
        }
        return Response::download($path);
    }

    public static function storeFile($allFiles)
    {

        if (!$allFiles) {
            return array('url' => '', 'data' => $allFiles);
        }
        //print_r($allFiles);
        $files = (!is_array($allFiles)) ? array($allFiles) : $allFiles;
        // Files allowed
        $allowedImages = ['jpg', 'jpeg', 'JPEG', 'png', 'gif', 'JPG', 'PNG', 'GIF', 'jfif', 'JFIF'];
        $allowedDocuments = ['pptx', 'PPTX', 'ppt', 'pdf', 'PDF', 'docx', 'doc', 'DOCX', 'DOC', 'xls', 'XLS', 'xlsx', 'XLSX', 'txt', 'TXT'];
        $allowedAudios = ['m4a', 'M4A', 'mp3', 'MP3', 'wav', 'WAV', 'wma', 'WMA', 'ogg', 'OGG'];
        $allowedVideos = ['mp4', 'MP4', 'm4v', 'M4V', 'mpeg', 'MPEG', 'mpg', 'MPG', 'wmv', 'WMV', 'avi', 'AVI'];
        $allowedApks = ['apk'];
        // Files allowed
        $allowedfileExtension = array_merge($allowedImages, $allowedDocuments, $allowedAudios, $allowedVideos, $allowedApks);
        //Files stored
        $filesStored = array();
        foreach ($files as $file) {
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $check = in_array($extension, $allowedfileExtension);
            if (!$check) {
                return [];
            }
            if (in_array($extension, $allowedImages)) {
                $folder = 'images';
            } else if (in_array($extension, $allowedDocuments)) {
                $folder = 'documents';
            } else if (in_array($extension, $allowedAudios)) {
                $folder = 'audios';
            } else if (in_array($extension, $allowedVideos)) {
                $folder = 'videos';
            } else if (in_array($extension, $allowedApks)) {
                $folder = 'apks';
            }
            $length = 20;
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }

            $random = $randomString;
            $fileUrl = $file->storeAs('files/' . $folder, $random . $filename);
            $path = storage_path('app\\' . $fileUrl);

            /**  SE AGREGO ESTE FRAGMENTO DE CODIGO PARA REDUCIR LAS IMAGENES EN TAMAÑO Y CALIDAD  **/
            $path2 = storage_path('app/' . $fileUrl);

            if (in_array($extension, $allowedImages)) {
                $image = Image::make($path2);

                $image->resize(900, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $image->save($path2, 80);
                //Storage::put($path2, (string) $image->encode('jpg', 30));
            }
            /**  FIN  **/

            $fileStored = FileModel::create([
                'type' => $extension,
                'name' => $filename,
                'url' => explode("files/", $path)[1]
            ]);
            array_push($filesStored, $fileStored);
        }
        return $filesStored[0];
    }
}

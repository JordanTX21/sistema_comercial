<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\FileController;
use App\Clientes;
use App\Expediente;
use App\TipoDocumentos;
use Intervention\Image\ImageManagerStatic as Image;
use App\File as FileModel;
use App\Utils\WithUtils;

class ExpedientesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $expedientes = Expediente::with(WithUtils::withExpediente())->orderBy('id','desc')->get();

        foreach($expedientes as $expediente){
            $expediente->titulo_propiedad = $expediente->titulo_propiedad? json_decode($expediente->titulo_propiedad,true)["url"]: null;
            $expediente->mapa_ubicacion = $expediente->mapa_ubicacion? json_decode($expediente->mapa_ubicacion,true)["url"]: null;
        }

        $tipo_documentos = TipoDocumentos::where('status','=',1)->get();
        return view('modules.expedientes.expedientes',[
            "tipo_documentos" => $tipo_documentos,
            "expedientes" => $expedientes
        ]);
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
        $tipo_documento = $request->tipo_documento;
        $numero_documento = $request->numero_documento;
        $mombre_cliente = $request->mombre_cliente;
        $apellido_cliente = $request->apellido_cliente;
        $titulo_propiedad = $request->file('titulo_propiedad');
        $croquis_ubicacion = $request->file('croquis_ubicacion');
        //return response($request->all());
        session(['status_error' => null]);
        session(['status_create' => null]);

        $tamanio_docs = [
            1 => "8",
            2 => "9",
            3 => "12",
            4 => "8",
            5 => "8",
            6 => "11",
        ];

        $validator = Validator::make($request->all(),[
            'tipo_documento' => 'required',
            'numero_documento' => 'required|size:'.$tamanio_docs[$tipo_documento],
            'mombre_cliente' => 'required',
            'apellido_cliente' => 'required',
            'titulo_propiedad' => 'required',
            'croquis_ubicacion' => 'required',
        ], $messages = [
            'required' => 'El campo :attribute es obligatorio.',
            'min' => 'El campo :attribute debe tener al menos :min caracteres.',
            'max' => 'El campo :attribute no puede tener más de :max caracteres.',
            'size' => 'El campo :attribute debe tener exáctamente :size caracteres.',
        ]);

        if(count($validator->errors()) > 0){
            return redirect('/expedientes')->withErrors($validator)
            ->withInput();
        }

        try{
            $cliente_new = Clientes::updateOrCreate(
                ['documento' => $numero_documento],
                [
                    'documento' => $numero_documento,
                    'nombre' => $mombre_cliente,
                    'apellido' => $apellido_cliente,
                    'tipo_documento_id' => $tipo_documento,
                    'status' => 1
                ]
            );
            if(!$cliente_new){
                session(['status_error' => 'Ocurrió un error al registrar los datos del cliente!']);
                return redirect('/expedientes');
            }
        }catch(\Exception $e){
            //session(['status_error' => 'Ocurrió un error interno al generar el expediente!']);
            session(['status_error' => $e->getMessage()]);
            return redirect('/expedientes');
        }

        $cliente = Clientes::where('documento',$numero_documento)->first();

        $url_croquis_ubicacion = $this->storeFile($croquis_ubicacion);

        $url_titulo_propiedad = $this->storeFile($allFiles = $titulo_propiedad);

        try{
            $expediente = Expediente::create(
                [
                    'cliente_id' => $cliente->id,
                    'titulo_propiedad' => json_encode($url_titulo_propiedad),
                    'mapa_ubicacion' => json_encode($url_croquis_ubicacion),
                    'status' => 1
                ]
            );
            if(!$expediente){
                session(['status_error' => 'Ocurrió un error al registrar los datos del expediente!']);
                return redirect('/expedientes');
            }
        }catch(\Exception $e){
            //session(['status_error' => 'Ocurrió un error interno al generar el expediente!']);
            session(['status_error' => $e->getMessage()]);
            return redirect('/expedientes');
        }

        session(['status_create' => 'Registro creado con éxito!']);

        return redirect('/expedientes');
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
    public function storeFile($allFiles)
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

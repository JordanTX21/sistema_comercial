<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Clientes;
use App\TipoDocumentos;

class ClientesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tipo_documentos = TipoDocumentos::where('status','=',1)->get();

        return view('modules.clientes.clientes',[
            "tipo_documentos" => $tipo_documentos,
            "clientes" => [],
            "count" => 0,
            "paginate" => 0,
            "pages" => [],
            "mombre_cliente" => null,
            "numero_documento" => null,
            "tipo_documento" => null,
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
        //
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
    public function search(Request $request)
    {
        $mombre_cliente = $request->mombre_cliente;
        $numero_documento = $request->numero_documento;
        $tipo_documento = $request->tipo_documento;
        $tipo_documentos = TipoDocumentos::where('status','=',1)->get();
        $start = $request->start?max($request->start,1):1;
        $form = $request->form;
        $lenght = 10;

        $where = [
            ['status','=',1],
        ];

        if($mombre_cliente){
            $where[] = ['nombre','LIKE',"%$mombre_cliente%"];
        }

        if($numero_documento){
            $where[] = ['documento','LIKE',"%$numero_documento%"];
        }

        if($tipo_documento){
            $where[] = ['tipo_documento_id','=',"$tipo_documento"];
        }

        if(!$form){
            return view('modules.clientes.clientes',[
                "tipo_documentos" => $tipo_documentos,
                "clientes" => [],
                "count" => 0,
                "paginate" => 0,
                "pages" => [],
                "mombre_cliente" => null,
                "numero_documento" => null,
                "tipo_documento" => null,
            ]);
        }
        $clientes = Clientes::where($where)->orderBy('deuda','desc');
        $count = $clientes->count();

        $last_page = ceil($count/$lenght);

        $start = min($start,$last_page);

        $clientes = $clientes->limit($lenght)->offset(($start-1)*$lenght);

        $pages = [];

        for($i = 1; $i <= $last_page; $i++){
            $pages[] = $i;
        }

        $pages = array_slice($pages,max($start-3,0),5);

        return view('modules.clientes.clientes',[
            "tipo_documentos" => $tipo_documentos,
            "clientes" => $clientes->get(),
            "count" => $count,
            "pages" => $pages,
            "paginate" => $start,
            "last_page" => $last_page,
            "mombre_cliente" => $mombre_cliente,
            "numero_documento" => $numero_documento,
            "tipo_documento" => $tipo_documento,
        ]);
    }
}

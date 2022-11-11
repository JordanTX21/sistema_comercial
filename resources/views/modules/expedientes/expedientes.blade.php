@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            @if (session('status_create'))
                <div class="col-md-12 mt-3">
                    <div class="alert alert-success" role="alert">
                        {{ session('status_create') }}
                    </div>
                </div>
            @endif
            @if (session('status_error'))
                <div class="col-md-12 mt-3">
                    <div class="alert alert-danger" role="alert">
                        {{ session('status_error') }}
                    </div>
                </div>
            @endif
            <div class="col-md-12 mt-3">
                <div class="card">
                    <div class="card-header bg-dark text-white h4">Registro</div>
                    <div class="card-body">
                        <form class="row" action="/expedientes" method="POST" id="expedientes_form" enctype="multipart/form-data">
                            @csrf
                            <div class="col-md-12">
                                <h5 class="card-title">Datos Usuario</h5>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="tipo_documento" class="sr-only">Tipo documento</label>
                                    <select class="form-control @error('tipo_documento') is-invalid @enderror"
                                        id="tipo_documento" name="tipo_documento">
                                        <option value="{{ null }}">TODOS</option>
                                        @foreach ($tipo_documentos as $tipo_doc)
                                            <option value="{{ $tipo_doc->id }}"
                                                {{ old('tipo_documento') == $tipo_doc->id ? 'selected' : '' }}>
                                                {{ $tipo_doc->tipo }}</option>
                                        @endforeach
                                    </select>
                                    @error('tipo_documento')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="numero_documento" class="sr-only">N° Documento</label>
                                    <input type="number"
                                        class="form-control @error('numero_documento') is-invalid @enderror"
                                        id="numero_documento" name="numero_documento" placeholder="N° Documento"
                                        value="{{ old('numero_documento') }}">
                                    @error('numero_documento')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="mombre_cliente" class="sr-only">Nombre</label>
                                    <input type="text" class="form-control @error('mombre_cliente') is-invalid @enderror"
                                        id="mombre_cliente" name="mombre_cliente" placeholder="Nombre"
                                        value="{{ old('mombre_cliente') }}">
                                    @error('mombre_cliente')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="apellido_cliente" class="sr-only">Apellidos</label>
                                    <input type="text"
                                        class="form-control @error('apellido_cliente') is-invalid @enderror"
                                        id="apellido_cliente" name="apellido_cliente" placeholder="Apellidos"
                                        value="{{ old('apellido_cliente') }}">
                                    @error('apellido_cliente')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <h5 class="card-title">Datos Técnicos</h5>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="custom-file">
                                        <input type="file"
                                            class="custom-file-input @error('titulo_propiedad') is-invalid @enderror"
                                            id="titulo_propiedad" name="titulo_propiedad">
                                        <label class="custom-file-label" for="titulo_propiedad">Titulo de propiedad</label>
                                    </div>
                                    @error('titulo_propiedad')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="custom-file">
                                        <input type="file"
                                            class="custom-file-input @error('croquis_ubicacion') is-invalid @enderror"
                                            id="croquis_ubicacion" name="croquis_ubicacion">
                                        <label class="custom-file-label" for="croquis_ubicacion">Croquis de
                                            ubicación</label>
                                    </div>
                                    @error('croquis_ubicacion')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <input type="hidden" value="form" name="form">
                                <button type="submit" class="btn btn-primary">Registrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-3">
                <div class="card">
                    <div class="card-header bg-dark text-white p-0 pt-2"></div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-borderless">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">COD. EXPEDIENTE</th>
                                    <th scope="col">CLIENTE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($expedientes as $expediente)
                                    <tr>
                                        <th scope="row">{{ "EXP".date('mY',strtotime($expediente->created_at)).str_pad($expediente->id,2,'0', STR_PAD_LEFT) }}</th>
                                        <td>{{ $expediente->cliente->nombre }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 mt-3">
                <div class="card">
                    <div class="card-header bg-dark text-white h4">Busqueda</div>
                    <div class="card-body">
                        <form class="row" action="/clientes_search" method="POST" id="search_form">
                            @csrf
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="mombre_cliente" class="sr-only">Nombre</label>
                                    <input type="text" class="form-control" id="mombre_cliente" name="mombre_cliente"
                                        placeholder="Nombre" value="{{$mombre_cliente}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="numero_documento" class="sr-only">N° Documento</label>
                                    <input type="number" class="form-control" id="numero_documento" name="numero_documento"
                                        placeholder="N° Documento" value="{{$numero_documento}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="tipo_documento" class="sr-only">Tipo documento</label>
                                    <select class="form-control" id="tipo_documento" name="tipo_documento">
                                        <option value="{{ null }}">TODOS</option>
                                        @foreach ($tipo_documentos as $tipo_doc)
                                            <option value="{{ $tipo_doc->id }}" {{$tipo_documento==$tipo_doc->id?'selected':''}}>{{ $tipo_doc->tipo }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <input type="hidden" value="form" name="form">
                                <button type="submit" class="btn btn-primary">Buscar</button>
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
                                    <th scope="col">COD. CLIENTE</th>
                                    <th scope="col">NOMBRE</th>
                                    <th scope="col">DEUDA</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($clientes as $cliente)
                                    <tr>
                                        <th scope="row">{{ $cliente->documento }}</th>
                                        <td>{{ $cliente->nombre }}</td>
                                        <td>S/.{{ number_format($cliente->deuda, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-3">
                <nav aria-label="Page navigation example">
                    @if($count > 0)
                        <ul class="pagination">
                            <li class="page-item"><label class="page-link" for="search_prev" style="cursor: pointer">Anterior<input class="d-none" type="submit" form="search_form" value="{{$paginate-1}}" name="start" id="search_prev"></label></li>
                            @if(!in_array(1,$pages))
                                <li class="page-item "><input class="page-link" type="submit" form="search_form" value="{{1}}" name="start" id="search_{{1}}"></li>
                            @endif
                            @foreach ($pages as $page)
                                <li class="page-item {{(($paginate == ($page))? 'active':'' )}}"><input class="page-link" type="submit" form="search_form" value="{{$page}}" name="start" id="search_{{$page}}"></li>
                            @endforeach
                            @if(!in_array($last_page,$pages))
                                <li class="page-item"><input class="page-link" type="submit" form="search_form" value="{{$last_page}}" name="start" id="search_{{$last_page}}"></li>
                            @endif
                            <li class="page-item"><label class="page-link" for="search_next" style="cursor: pointer">Siguiente<input class="d-none" type="submit" form="search_form" value="{{$paginate+1}}" name="start" id="search_next"></label></li>
                        </ul>
                    @endif
                </nav>
            </div>
        </div>
    </div>
@endsection

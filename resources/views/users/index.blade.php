@extends('adminlte::page')

@section('title', 'Gestión de Usuarios')

@section('content_header')
    <h1>Gestión de Usuarios</h1>
@endsection

@section('content')
    <button class="btn btn-primary mb-3" id="btn-add">Añadir usuario</button>

    <table class="table table-bordered" id="users-table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Administrador</th>
                <th>Acciones</th>
            </tr>
        </thead>
    </table>

    @include('users.modal')
@endsection

@section('footer')
    @include('vendor.adminlte.partials.footer.footer')
@endsection


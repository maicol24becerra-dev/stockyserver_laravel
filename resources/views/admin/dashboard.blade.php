@extends('layouts.admin')

@section('title', 'Panel de Administrador - El Cielo')
@section('page_title', 'Estadísticas del Mes')

@section('content')

    {{-- BLOQUE 1: KPIS PRINCIPALES DEL MES (VENTAS, PEDIDOS, CLIENTES) --}}
    @include('admin.partials._kpis')

    {{-- BLOQUE 2: RANKING DE PLATOS MÁS VENDIDOS --}}
    @include('admin.partials._platos_vendidos')

    {{-- BLOQUE 3: ALERTAS DE STOCK MÍNIMO Y MATERIA PRIMA --}}
    @include('admin.partials._alertas_stock')

@endsection

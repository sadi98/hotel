@extends('admin.layouts.main')

@section('page_title', 'Edit Pesanan ' . $order->order_number)
@section('meta_description', 'Edit pesanan restoran.')
@section('header_title', 'Edit Pesanan')
@section('header_subtitle', 'Perbarui pesanan ' . $order->order_number . '.')
@section('header_icon', 'edit-2')

@section('header_action')
    <a href="{{ route('management.orders.show', $order) }}" class="btn btn-light">
        <i data-feather="arrow-left" class="me-2"></i>
        Kembali
    </a>
@endsection

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}">
            Dashboard
        </a>
    </li>

    <li class="breadcrumb-item">
        <a href="{{ route('management.orders.index') }}">
            Pesanan
        </a>
    </li>

    <li class="breadcrumb-item">
        <a href="{{ route('management.orders.show', $order) }}">
            {{ $order->order_number }}
        </a>
    </li>

    <li class="breadcrumb-item active">
        Edit
    </li>
@endsection

@section('content')
    @include('admin.restaurant.orders._form')
@endsection

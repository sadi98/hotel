@extends('admin.layouts.main')

@section('page_title', 'Buat Akun Staff')
@section('meta_description', 'Buat akun staff restoran.')
@section('header_title', 'Buat Akun Staff')
@section('header_subtitle', 'Tambahkan akun baru untuk operasional restoran.')
@section('header_icon', 'user-plus')

@section('header_action')
    <a href="{{ route('management.staff-accounts.index') }}" class="btn btn-light">
        <i data-feather="arrow-left" class="me-2"></i>
        Kembali
    </a>
@endsection

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}">Dashboard</a>
    </li>

    <li class="breadcrumb-item">
        <a href="{{ route('management.staff-accounts.index') }}">
            Akun Staff
        </a>
    </li>

    <li class="breadcrumb-item active">
        Buat
    </li>
@endsection

@section('content')
    @include('admin.staff-accounts._form')
@endsection

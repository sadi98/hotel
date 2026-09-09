@extends('admin.layouts.main')

@section('page_title', 'Edit Staff ' . $user->name)
@section('meta_description', 'Edit akun staff restoran.')
@section('header_title', 'Edit Akun Staff')
@section('header_subtitle', 'Perbarui informasi akun ' . $user->name . '.')
@section('header_icon', 'edit-2')

@section('header_action')
    <a href="{{ route('management.staff-accounts.show', $user) }}" class="btn btn-light">
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
        Edit
    </li>
@endsection

@section('content')
    @include('admin.staff-accounts._form')
@endsection

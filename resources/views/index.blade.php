@extends('layouts.master-layouts')
@section('title') Dashboard @endsection
@section('body') <body data-topbar="dark" data-layout="horizontal"> @endsection

@section('content')
    @if ($role == 'admin' || $role == 'doctor')
        @include('layouts.admin-dashboard')
    @elseif ($role == 'receptionist' || $role == 'biomedical')
        @include('layouts.receptionist-dashboard')
    @endif
@endsection

@section('script')
    @if ($role == 'admin' || $role == 'doctor')
        <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
        <script src="{{ asset('assets/js/pages/dashboard.init.js') }}"></script>
    @endif
@endsection

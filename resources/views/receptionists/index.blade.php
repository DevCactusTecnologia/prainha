@extends('layouts.master-layouts')
@section('title') {{ __('Lista de Recepcionistas') }} @endsection
@section('body')
    <body data-topbar="dark" data-layout="horizontal">
@endsection

@section('content')
        
    @component('components.breadcrumb')
        @slot('title') Lista de recepcionistas @endslot
        @slot('li_1') Dashboard @endslot
        @slot('li_2') Recepcionistas @endslot
    @endcomponent

    {{-- ALERT --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {!! session()->get('success') !!}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        {{ session()->forget('success') }}
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if ($role == 'admin')
                        <a href="{{ route('receptionists.create') }}" class="btn btn-primary waves-effect mb-4"
                        >
                            <i class="bx bx-plus font-size-16 align-middle mr-2"></i> {{ __('Novo Recepcionista') }}
                        </a>
                    @endif
                    <table class="table table-sm table-centered table-hover table-bordered dt-responsive nowrap">
                        <thead class="bg-light">
                            <tr>
                                <th>{{ __('Nº') }}</th>
                                <th>{{ __('Nome') }}</th>
                                <th>{{ __('Nº de Contato') }}</th>
                                <th>{{ __('E-mail') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Opções') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $currentPage = $receptionists->currentPage();
                            @endphp
                            @foreach ($receptionists as $receptionist)
                                <tr>
                                    <td>{{ $loop->index + 1 + $limit * ($currentPage - 1) }}</td>
                                    <td>
                                        <a href="{{ route('receptionists.show', $receptionist->id) }}">
                                            {{ $receptionist->first_name }}
                                        </a>
                                    </td>
                                    <td>{{ $receptionist->mobile }}</td>
                                    <td>{{ $receptionist->email }}</td>
                                    <td>
                                        <span class="{{ $receptionist->receptionist->is_deleted?->getColor() }}">
                                            {{ $receptionist->receptionist->is_deleted?->getName() }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($role == 'admin')
                                            <a href="{{ route('receptionists.show', $receptionist->id) }}" 
                                                class="btn btn-primary btn-sm btn-rounded waves-effect mb-2 mb-md-0"
                                                title="Visualizar Perfil"
                                            >
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                            <a href="{{ route('receptionists.edit', $receptionist->id) }}"
                                                class="btn btn-primary btn-sm btn-rounded waves-effect mb-2 mb-md-0"
                                                title="Editar Perfil"
                                            >
                                                <i class="mdi mdi-lead-pencil"></i>
                                            </a>
                                        @elseif ($role == 'doctor')
                                            <a href="{{ route('receptionists.show', $receptionist->id) }}"
                                                class="btn btn-primary btn-sm btn-rounded waves-effect mb-2 mb-md-0"
                                                title="Visualizar Perfil"
                                            >
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="col-md-12 text-center mt-3">
                        <div class="d-flex justify-content-start">
                            Mostrando {{ $receptionists->firstItem() }} de {{ $receptionists->lastItem() }} de
                            {{ $receptionists->total() }} entradas
                        </div>
                        <div class="d-flex justify-content-end">
                            {{ $receptionists->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
@endsection

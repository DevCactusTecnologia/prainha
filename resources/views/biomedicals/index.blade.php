@extends('layouts.master-layouts')
@section('title') Lista de Analistas @endsection
@section('body')
    <body data-topbar="dark" data-layout="horizontal">
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title') Lista de analistas  @endslot
        @slot('li_1') Dashboard @endslot
        @slot('li_2') Analistas @endslot
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
                        <a href=" {{ route('biomedicals.create') }} ">
                            <button type="button" class="btn btn-primary waves-effect waves-light mb-4">
                                <i class="bx bx-plus font-size-16 align-middle mr-2"></i> Novo Analista
                            </button>
                        </a>
                    @endif
                    <table class="table table-bordered table-sm table-centered table-hover nowrap">
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
                            @foreach ($biomedicals as $biomedical)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <a href="{{ route('biomedicals.show', $biomedical->id) }}">
                                            {{ $biomedical->first_name }}
                                        </a>
                                    </td>
                                    <td>{{ $biomedical->mobile }}</td>
                                    <td>{{ $biomedical->email }}</td>
                                    <td>
                                        <span class="{{ $biomedical->biomedical->is_deleted?->getColor() }}">
                                            {{ $biomedical->biomedical->is_deleted?->getName() }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($role == 'admin')
                                            <a href="{{ route('biomedicals.show', $biomedical->id) }}" title="Visualizar perfil"
                                                class="btn btn-primary btn-sm btn-rounded waves-effect mb-2 mb-md-0"
                                            >
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                            <a href="{{ route('biomedicals.edit', $biomedical->id) }}" title="Editar perfil"
                                                class="btn btn-primary btn-sm btn-rounded waves-effect mb-2 mb-md-0"
                                            >
                                                <i class="mdi mdi-lead-pencil"></i>
                                            </a>
                                        @elseif ($role == 'doctor')
                                            <a href="{{ route('biomedicals.show', $biomedical->id) }}" title="Visualizar perfil"
                                                class="btn btn-primary btn-sm btn-rounded waves-effect mb-2 mb-md-0"
                                            >
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.master-layouts')
@section('title') {{ __('Lista de Atendimento') }} @endsection
@section('body')

    <body data-topbar="dark" data-layout="horizontal">
    @endsection
    @section('content')
        @component('components.breadcrumb')
            @slot('title') Lista de Atendimento @endslot
            @slot('li_1') Dashboard @endslot
            @slot('li_2') Atendimentos @endslot
        @endcomponent
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">

                        <div class="tab-content p-3 text-muted">
                            <div class="tab-pane active" id="PendingAppointmentList" role="tabpanel">
                                <table class="table table-bordered dt-responsive nowrap "
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Nº') }}</th>
                                            <th>{{ __('Nome do Médico') }}</th>
                                            <th>{{ __('Data') }}</th>
                                            <th>{{ __('Status') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (session()->has('page_limit'))
                                            @php
                                                $per_page = session()->get('page_limit');
                                            @endphp
                                        @else
                                            @php
                                                $per_page = Config::get('app.page_limit');
                                            @endphp
                                        @endif
                                        @php
                                            $currentpage = $appointment->currentPage();
                                        @endphp
                                        @forelse ($appointment as $item)
                                            <tr>
                                                <td>{{ $loop->index + 1 + $per_page * ($currentpage - 1) }}</td>
                                                <td> {{ $item->doctor->first_name . ' ' . $item->doctor->last_name }}
                                                </td>
                                                <td>{{ $item->appointment_date }}</td>
                                                <td>
                                                    @if ($item->status == 0)
                                                        <span class="badge badge-warning">Pendente</span>
                                                    @elseif ($item->status == 1 )
                                                        <span class="badge badge-success">Finalizado</span>
                                                    @elseif ($item->status == 2 )
                                                        <span class="badge badge-danger">Cancelado</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <p>Nenhum registro encontrado</p>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="col-md-12 text-center mt-3">
                                    <div class="d-flex justify-content-start">
                                        Mostrando {{ $appointment->firstItem() }} de {{ $appointment->lastItem() }} de
                                        {{ $appointment->total() }} entradas
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        {{ $appointment->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @section('script')
        <!-- Init js-->
        <script src="{{ URL::asset('assets/js/pages/notification.init.js') }}"></script>
        <script src="{{ URL::asset('assets/js/pages/appointment.js') }}"></script>
    @endsection

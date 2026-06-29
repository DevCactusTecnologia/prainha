@extends('layouts.master-layouts')
@section('title')
{{ __('Atualizar dados do médico') }}
@endsection
@section('css')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/select2/select2.min.css') }}">
@endsection
@section('body')

<body data-topbar="dark" data-layout="horizontal">
    @endsection
    @section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18">
                    {{ __('Atualizar dados do médico') }}
                </h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('doctors.index') }}">{{ __('Médicos') }}</a></li>
                        <li class="breadcrumb-item active">
                            {{ __('Atualizar dados do médico') }}
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-12">
            @if ($doctor && $doctor_info)
            @if ($role == 'doctor')
            <a href="{{ url('/') }}">
                <button type="button" class="btn btn-primary waves-effect mb-4">
                    <i class="bx bx-arrow-back font-size-16 align-middle mr-2"></i>{{ __('Voltar Dashboard') }}
                </button>
            </a>
            @else
            <a href="{{ route('doctors.show', $doctor->id) }}">
                <button type="button" class="btn btn-primary waves-effect mb-4">
                    <i class="bx bx-arrow-back font-size-16 align-middle mr-2"></i>{{ __('Voltar ao perfil') }}
                </button>
            </a>
            @endif
            @else
            <a href="{{ route('doctors.index') }}">
                <button type="button" class="btn btn-primary waves-effect mb-4">
                    <i class="bx bx-arrow-back font-size-16 align-middle mr-2"></i>{{ __('Voltar à lista de médicos') }}
                </button>
            </a>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <blockquote>{{ __('Basic Information') }}</blockquote>
                    <form action="{{ url('profile-update') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label class="control-label">{{ __('Nome ') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" id="firstname" tabindex="1" value="@if ($doctor && $doctor_info){{ $doctor->first_name }}@elseif(old('first_name')){{ old('first_name') }}@endif" placeholder="{{ __('Digite o nome') }}">
                                        @error('first_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label class="control-label">{{ __('CPF ') }}<span class="text-danger"></span></label>
                                        <input type="text" class="form-control @error('doctor_cpf') is-invalid @enderror" name="doctor_cpf" id="doctor_cpf" tabindex="5" value="@if ($doctor && $doctor_info){{ $doctor_info->doctor_cpf }}@elseif(old('doctor_cpf')){{ old('doctor_cpf') }}@endif" placeholder="{{ __('Digite o nº do CPF') }}">
                                        @error('doctor_cpf')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="control-label">{{ __('CNS ') }}<span class="text-danger"></span></label>
                                        <input type="text" class="form-control @error('doctor_cns') is-invalid @enderror" name="doctor_cns" id="doctor_cns" tabindex="5" value="@if ($doctor && $doctor_info){{ $doctor_info->doctor_cns }}@elseif(old('doctor_cns')){{ old('doctor_cns') }}@endif" placeholder="{{ __('Digite o nº do cartão SUS') }}">
                                        @error('doctor_cns')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label class="control-label">{{ __('Conselho de Classe') }}<span class="text-danger">*</span></label>
                                        <select class="form-control select2 @error('class_council') is-invalid @enderror" name="class_council" id="class_council">
                                            <option value="Health Board">Health Board</option>
                                        </select>
                                        @error('class_council')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <div class="row">
                                            <div class="col-md-6 form-group">
                                                <label class="control-label">{{ __('Estado Emissor') }}<span class="text-danger">*</span></label>
                                                <select class="form-control select2 @error('issuing_state') is-invalid @enderror" name="issuing_state" id="issuing_state">
                                                    <option value="gujarat">Gujarat</option>
                                                </select>
                                                @error('issuing_state')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label class="control-label">{{ __('Nº de Registro do Conselho') }}<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error('counsil_number') is-invalid @enderror" name="counsil_number" id="counsil_number" tabindex="5" value="@if ($doctor && $doctor_info){{ $doctor_info->counsil_number }}@elseif(old('counsil_number')){{ old('counsil_number') }}@endif" placeholder="{{ __('Digite o nº do conselho') }}">
                                                @error('counsil_number')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label class="control-label">{{ __('E-mail ') }}<span class="text-danger"></span></label>
                                        <input type="tel" class="form-control @error('email') is-invalid @enderror" name="email" id="email" tabindex="4" value="@if ($doctor && $doctor_info){{ $doctor->email }}@elseif(old('email')){{ old('email') }}@endif" placeholder="{{ __('Digite o e-mail') }}">
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <div class="row">
                                            <div class="col-md-6 form-group">
                                                <label class="control-label">{{ __('Senha de Acesso ') }}<span class="text-danger"></span></label>
                                                <input type="tel" class="form-control @error('password') is-invalid @enderror" name="password" id="password" tabindex="4" value="@if ($doctor && $doctor_info){{ $doctor->password }}@elseif(old('password')){{ old('password') }}@endif" placeholder="{{ __('Digite a senha') }}">
                                                @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label class="control-label">{{ __('Nº de Contato ') }}<span class="text-danger"></span></label>
                                                <input type="tel" class="form-control @error('mobile') is-invalid @enderror" name="mobile" id="patientMobile" tabindex="4" value="@if ($doctor && $doctor_info){{ $doctor->mobile }}@elseif(old('mobile')){{ old('mobile') }}@endif" placeholder="{{ __('Digite o nº de contato') }}">
                                                @error('mobile')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 form-group">
                                            <label class="control-label">{{ __('Foto do Perfil ') }}<span class="text-danger"></span></label>
                                            <img class="@error('profile_photo') is-invalid @enderror" src="@if ($doctor && $doctor_info && $doctor->profile_photo != 'noImage.png' && $doctor && $doctor_info && $doctor->profile_photo != '') {{ URL::asset('storage/images/users/' . $doctor->profile_photo) }}  @else {{ URL::asset('assets/images/users/noImage.png') }} @endif" id="profile_display" onclick="triggerClick()" data-toggle="tooltip" data-placement="top" title="Clique para enviar a foto do perfil" />
                                            <input type="file" class="form-control @error('profile_photo') is-invalid @enderror" tabindex="8" name="profile_photo" id="profile_photo" style="display:none;" onchange="displayProfile(this)">
                                            @error('profile_photo')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    @if ($doctor && $doctor_info)
                                    {{ __('Atualizar detalhes') }}
                                    @else
                                    {{ __('Adicionar Novo Médico') }}
                                    @endif
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->
    @endsection
    @section('script')
    <script src="{{ URL::asset('assets/libs/jquery-repeater/jquery-repeater.min.js') }}"></script>
    <!-- form init -->
    <script src="{{ URL::asset('assets/js/pages/form-repeater.int.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/pages/form-advanced.init.js') }}"></script>
    <script>
        // Profile Photo
        function triggerClick() {
            document.querySelector('#profile_photo').click();
        }

        function displayProfile(e) {
            if (e.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.querySelector('#profile_display').setAttribute('src', e.target.result);
                }
                reader.readAsDataURL(e.files[0]);
            }
        }
        // Time validattion
        var timecount = $('.timecount').length;
        let cf = 0;
        let error = 0;

        function valinput0() {
            var startTime = $('input[name="TimeSlot[0][from]"]').val();
            var endTime = $('input[name="TimeSlot[0][to]"]').val();
            var st = startTime.split(":");
            var et = endTime.split(":");
            var sst = new Date();
            sst.setHours(st[0]);
            sst.setMinutes(st[1]);
            var eet = new Date();
            eet.setHours(et[0]);
            eet.setMinutes(et[1]);
            if (sst > eet) {
                error = 1;
                $('.para').html('to value is bigger then from');
                $('.para').addClass('d-block');
            } else {
                error = 0;
                $('.para').removeClass('d-block');
            }
        }

        function change() {
            cf++;
            setTimeout(function() {
                $(document).on('change', `input[name="TimeSlot[${cf}][to]"]`, function() {
                    validate1();
                });
            }, 100);
        }

        function validate1() {
            timecount = $('.timecount').length;
            for (let i = 0; i < timecount; i++) {
                var startTime = $('input[name="TimeSlot[' + i + '][from]"]').val();
                var endTime = $('input[name="TimeSlot[' + i + '][to]"]').val();
                currenttime = $(`input[name="TimeSlot[${cf}][from]"]`).val();
                currentto = $(`input[name="TimeSlot[${cf}][to]"]`).val();
                var st = startTime.split(":");
                var et = endTime.split(":");
                var ct = currenttime.split(":");
                var cft = currentto.split(":");
                var sst = new Date();
                sst.setHours(st[0]);
                sst.setMinutes(st[1]);
                var eet = new Date();
                eet.setHours(et[0]);
                eet.setMinutes(et[1]);
                var cct = new Date();
                cct.setHours(ct[0]);
                cct.setMinutes(ct[1]);
                var cff = new Date();
                cff.setHours(cft[0]);
                cff.setMinutes(cft[1]);
                if (cct < cff) {
                    if (sst < cct && eet > cct) {
                        error = 1;
                        $('.para').html('Value not accepted');
                        $('.para').addClass('d-block');
                        break
                    } else {
                        error = 0;
                        $('.para').removeClass('d-block');
                    }
                } else {
                    $('.para').html('to value is bigger then from');
                    $('.para').addClass('d-block');
                    break
                }
            }
        }
        // checkbox value check
        $('#inlineCheckbox1').on('change', function() {
            var inlineCheckbox1 = $('#inlineCheckbox1').is(':checked') ? '1' : '0';
            $('#inlineCheckbox1').val(inlineCheckbox1);
        }).change();
        $('#inlineCheckbox2').on('change', function() {
            var inlineCheckbox2 = $('#inlineCheckbox2').is(':checked') ? '1' : '0';
            $('#inlineCheckbox2').val(inlineCheckbox2);
        }).change();
        $('#inlineCheckbox3').on('change', function() {
            var inlineCheckbox3 = $('#inlineCheckbox3').is(':checked') ? '1' : '0';
            $('#inlineCheckbox3').val(inlineCheckbox3);
        }).change();
        $('#inlineCheckbox4').on('change', function() {
            var inlineCheckbox4 = $('#inlineCheckbox4').is(':checked') ? '1' : '0';
            $('#inlineCheckbox4').val(inlineCheckbox4);
        }).change();
        $('#inlineCheckbox5').on('change', function() {
            var inlineCheckbox5 = $('#inlineCheckbox5').is(':checked') ? '1' : '0';
            $('#inlineCheckbox5').val(inlineCheckbox5);
        }).change();
        $('#inlineCheckbox6').on('change', function() {
            var inlineCheckbox6 = $('#inlineCheckbox6').is(':checked') ? '1' : '0';
            $('#inlineCheckbox6').val(inlineCheckbox6);
        }).change();
        $('#inlineCheckbox7').on('change', function() {
            var inlineCheckbox7 = $('#inlineCheckbox7').is(':checked') ? '1' : '0';
            $('#inlineCheckbox7').val(inlineCheckbox7);
        }).change();

    </script>
    @endsection

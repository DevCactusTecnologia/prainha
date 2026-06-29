@extends('layouts.master-layouts')
@section('title') Novo modelo de laudo @endsection
@section('body')
    <body data-topbar="dark" data-layout="horizontal">
@endsection

@section('content')
    
    @component('components.breadcrumb')
        @slot('title') Novo modelo de laudo @endslot
        @slot('li_1') <a href="{{ url('/') }}">Dashboard</a> @endslot
        @slot('li_2') <a href="{{ route('exams.edit', $exam->id) }}">Editar Exame</a> @endslot
        @slot('li_3') Novo modelo de laudo @endslot
    @endcomponent

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">

            <div class="row">
                <div class="col-lg-12">
                    <div>
                        <form action="{{ route('exams.models.store', $exam->id) }}" method="POST">
                            @csrf

                            {{-- NOME DO EXAME, ABREVIAÇÃO, CATEGORIA, PRAZO E DESTINO --}}
                            <div class="d-md-flex">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Nome do Exame</label>
                                    <input type="text" class="form-control bg-light" 
                                        value="{{ $exam->name }}" readonly 
                                    />
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">Abreviação</label>
                                    <input type="text" class="form-control bg-light"
                                        value="{{ $exam->abbreviation }}" readonly 
                                    />
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">Categoria</label>
                                    <input type="text" class="form-control bg-light"
                                        value="{{ $exam->category }}" readonly 
                                    />
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">Prazo</label>
                                    <input type="text" class="form-control bg-light"
                                        value="{{ $exam->deadline }}" readonly
                                    />
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">Destino</label>
                                    <input type="text" class="form-control bg-light" 
                                        value="{{ $exam->destiny }}" readonly
                                    />
                                </div>
                            </div>

                            {{-- NOME DO MODELO E OBSERVACOES --}}
                            <div class="d-md-flex">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">
                                        Nome do modelo <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" name="name"
                                        value="{{ old('name') }}" required 
                                    />
                                </div>
                                <div class="col-md-8 mb-3">
                                    <label class="form-label">Observações</label>
                                    <input type="text" class="form-control" name="observation"
                                        value="{{ old('observation') }}" 
                                    />
                                </div>
                            </div>

                            {{-- EDITOR DE LAUDOS --}}
                            <div class="d-md-flex mb-3">
                                <div class="col-md-12">
                                    <label class="form-label" style="font-weight: bold;">
                                        EDITOR DE TEXTO <span class="text-danger">*</span>
                                    </label>
                                    <textarea id="summery-ckeditor" class="form-control" 
                                        name="exam_editor" required>{{ old('exam_editor') }}</textarea>
                                </div>
                            </div>

                            <div class="d-md-flex justify-content-md-end mb-3">
                                <div class="col-md-3 d-flex justify-content-md-end">
                                    <button type="submit" class="btn btn-primary" onclick="loader(this)">
                                        Registrar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
    
@section('script')
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script>
        CKEDITOR.replace('summery-ckeditor');

        function loader(button) {
            setTimeout(() => {
                button.innerHTML = ( 
                    `<span class="spinner-border spinner-border-sm mr-2" 
                        role="status" aria-hidden="true">
                    </span>Aguarde...`
                );
                button.disabled = true;
            }, 10);

            setTimeout(() => {
                button.disabled = false;
                button.innerText = 'Registrar';
            }, 7000);
        }
    
    </script>
@endsection

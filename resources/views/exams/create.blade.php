@extends('layouts.master-layouts')
@section('title') {{ __('Novo Exame') }} @endsection

@section('body')
    <body data-topbar="dark" data-layout="horizontal">
@endsection

@section('content')

    @component('components.breadcrumb')
        @slot('title') Novo Exame @endslot
        @slot('li_1') <a href="{{ url('/') }}">{{ __('Dashboard') }}</a> @endslot
        @slot('li_2') <a href="{{ route('exams.index') }}">{{ __('Exames') }}</a> @endslot
        @slot('li_3') Novo Exame @endslot
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
                    <form action="{{ route('exams.store') }}" method="POST">
                        @csrf

                        {{-- NOME DO EXAME, ABREVIAÇÃO, CATEGORIA E PRAZO --}}
                        <div class="d-md-flex">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Nome do Exame <span class="text-danger">*</span></label>
                                <input class="form-control text-uppercase" type="text" name="name" 
                                   value="{{ old('name') }}" required 
                                />
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Abreviação <span class="text-danger">*</span></label>
                                <input class="form-control text-uppercase" type="text" name="abbreviation" 
                                    value="{{ old('abbreviation') }}" required 
                                />
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Categoria <span class="text-danger">*</span></label>
                                <select class="form-control" name="category" required>
                                    <option value="">Selecione</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->abbreviation }}"
                                            @selected(old('category') == $category->abbreviation)
                                        >
                                            {{ $category->abbreviation }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Prazo</label>
                                <input type="number" min="0" class="form-control" name="deadline"  
                                    value="{{ old('deadline') }}" required
                                />
                            </div>
                        </div>

                        {{-- DESTINO, G. RÓTULOS, QUANTIDADE DE ETIQUETAS, KIT E CÓDIGO --}}
                        <div class="d-md-flex">
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Destino</label>
                                <input class="form-control" type="text" name="destiny" value="{{ old('destiny') }}" />
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">G. Rótulos</label>
                                <input class="form-control" type="text" name="label_group" 
                                    value="{{ old('label_group') }}" 
                                />
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Qtd. Etiquetas</label>
                                <input type="number" min="0" class="form-control" name="quantity_label" 
                                    value="{{ old('quantity_label') }}"
                                />
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Kit</label>
                                <input type="number" min="0" class="form-control" name="exam_kit" 
                                    value="{{ old('exam_kit') }}" 
                                />
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Código</label>
                                <input type="text" class="form-control" name="code" 
                                    value="{{ old('code') }}" 
                                />
                            </div>
                        </div>

                        {{-- EDITOR DE TEXTO --}}
                        <div class="d-md-flex">
                            <div class="col-md-12 mb-3">
                                <label class="form-label" style="font-weight: bold;">EDITOR DE TEXTO</label>
                                <textarea id="summery-ckeditor" name="exam_editor">{{ old('exam_editor') }}</textarea>
                            </div>
                        </div>
                        
                        <p class="text-right">
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/libs/inputmask/jquery.inputmask.min.js') }}"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/35.3.1/classic/ckeditor.js"></script>
    <script>
        $('[name="code"]').inputmask({
            mask: [{'mask': '##.##.##.###-#'}], 
            greedy: false, 
            definitions: {'#': {validator: '[0-9]', cardinality: 1}} 
        });

        ClassicEditor
            .create(document.querySelector('#summery-ckeditor'))
            .then(function (editor) {
                editor.ui.view.editable.element.style.height = '100px';
            })
            .catch(error => {
                console.error( error );
            });
    </script>
@endsection

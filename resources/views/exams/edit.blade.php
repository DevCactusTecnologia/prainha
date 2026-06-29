@extends('layouts.master-layouts')
@section('title') Editar dados do Exame @endsection
@section('body')
    <body data-topbar="dark" data-layout="horizontal">
@endsection

@section('content')
    
    @component('components.breadcrumb')
        @slot('title') Editar dados do Exame @endslot
        @slot('li_1') <a href="{{ url('/') }}">{{ __('Dashboard') }}</a> @endslot
        @slot('li_2') <a href="{{ route('exams.index') }}">{{ __('Exames') }}</a> @endslot
        @slot('li_3') Editar dados do Exame @endslot
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
                    <div class="detail_box">
                        <form action="{{ route('exams.update', $exam->id) }}" name="examform" method="POST">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="id" value="{{ $exam->id }}" id="form_id" />

                            {{-- NOME DO EXAME, ABREVIAÇÃO, CATEGORIA, PRAZO E DESTINO --}}
                            <div class="d-md-flex">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Nome do Exame <span class="text-danger">*</span></label>
                                    <input class="form-control text-uppercase" type="text" name="name" 
                                    value="{{ old('name', $exam->name) }}" required 
                                    />
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">Abreviação <span class="text-danger">*</span></label>
                                    <input class="form-control text-uppercase" type="text" name="abbreviation" 
                                        value="{{ old('abbreviation', $exam->abbreviation) }}" required 
                                    />
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">Categoria <span class="text-danger">*</span></label>
                                    <select class="form-control" name="category" required>
                                        <option value="">Selecione</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->abbreviation }}"
                                                @selected(old('category', $exam->category) == $category->abbreviation)
                                            >
                                                {{ $category->abbreviation }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">Prazo</label>
                                    <input type="number" min="0" class="form-control" name="deadline"  
                                        value="{{ old('deadline', $exam->deadline) }}" required
                                    />
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">Destino</label>
                                    <input class="form-control" type="text" name="destiny" 
                                        value="{{ old('destiny', $exam->destiny) }}" />
                                </div>
                            </div>

                            {{-- G. RÓTULOS, QUANTIDADE DE ETIQUETAS, KIT, CÓDIGO E STATUS --}}
                            <div class="d-md-flex">
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">G. Rótulos</label>
                                    <input class="form-control" type="text" name="label_group" 
                                        value="{{ old('label_group', $exam->label_group) }}" 
                                    />
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">Qtd. Etiquetas</label>
                                    <input type="number" min="0" class="form-control" name="quantity_label" 
                                        value="{{ old('quantity_label', $exam->quantity_label) }}"
                                    />
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">Kit</label>
                                    <input type="number" min="0" class="form-control" name="exam_kit" 
                                        value="{{ old('exam_kit', $exam->exam_kit) }}" 
                                    />
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">Código</label>
                                    <input type="text" class="form-control" name="code" 
                                        value="{{ old('code', $exam->code) }}" 
                                    />
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-control" name="is_active" required>
                                        <option value="1" @selected($exam->is_active == '1')>
                                            Ativo
                                        </option>
                                        <option value="0" @selected($exam->is_active == '0')>
                                            Inativo
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">Modelo <span class="text-danger">*</span></label>
                                    <select class="form-control" name="model_id" required>
                                        <option value="">Selecione</option>
                                        @foreach ($exam->models as $model)
                                            <option value="{{ $model->id }}"
                                                @selected(old('model_id', $exam->model_id) == $model->id)
                                            >
                                                {{ $model->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-lg-3"></div>
                                <div class="col-lg-9 text-right">
                                    <div class="form-group d-inline-flex">
                                        <button type="button" class="btn waves-effect waves-light mb-2" 
                                            data-toggle="modal" data-target="#listFilterModal" 
                                            data-backdrop="static" data-keyboard="false" 
                                            style="color: black;background-color: #e3ebf2;"
                                        >
                                            <i class="bx bx-hash font-size-16 align-middle mr-2"></i> {{ __('Filtros') }}
                                        </button>
                                    </div>
                                    <div class="form-group d-inline-flex">
                                        <button type="button" class="btn waves-effect waves-light mb-2" 
                                            data-toggle="modal" data-target="#listParameterModal" data-backdrop="static" 
                                            data-keyboard="false" style="color: black;background-color: #e3ebf2;"
                                        >
                                            <i class="bx bx-hash font-size-16 align-middle mr-2"></i> {{ __('Parâmetros do Exame') }}
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="d-md-flex justify-content-md-end mb-3">
                                <button type="submit" class="btn btn-primary">
                                    Atualizar
                                </button>
                            </div>

                            <div class="mb-2" style="font-weight: bold;">EDITOR DE TEXTO</div>
                            <textarea id="summery-ckeditor" name="exam_editor">{{ old('exam_editor', $exam->exam_editor) }}</textarea>

                            
                        </form>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-lg-12">
                    <div class="d-md-flex align-items-center justify-content-md-between mb-3">
                        <h4 class="text-primary mb-0">Modelos de laudos</h4>
                        <a href="{{ route('exams.models.create', $exam->id) }}" 
                            class="btn btn-sm btn-primary rounded-pill px-3 py-2"
                        >
                            <i class="mdi mdi-plus fs-2"></i>
                            Novo laudo
                        </a>
                    </div>
                    
                    <table class="table table-sm table-centered table-bordered table-hover dt-responsive nowrap">
                        <thead class="bg-light">
                            <tr>
                                <th>Nº</th>
                                <th>Nome</th>
                                <th>Data de registro</th>
                                <th>Ações</th>
                            </tr>
                        </thead>                           
                        <tbody>
                            @foreach ($exam->models as $model)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $model->name }}</td>
                                    <td>{{ $model->created_at?->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('exams.models.edit', [$exam->id, $model->id]) }}" title="Editar modelo"
                                            class="btn btn-primary btn-sm btn-rounded waves-effect mb-2 mb-md-0"
                                        >
                                            <i class="mdi mdi-lead-pencil"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
            </div>
        </div>
    </div>

    <input type="hidden" id="baseUrl" value="{{ url('/') }}">
     
    {{-- MODAL DE LISTAGEM DE PARÂMETROS, SALVAR E EDITAR PARÂMETRO --}}
    @include('exams.modal.parameters.index')
    @include('exams.modal.parameters.save')

    {{-- MODAL DE LISTAGEM DE FILTROS, SALVAR E EDITAR FILTRO --}}
    @include('exams.modal.filters.index')
    @include('exams.modal.filters.save')

@endsection
    
@section('script')
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('assets/js/pages/exams/parameter-filter.js') }}"></script>

    <script src="{{ asset('assets/libs/inputmask/jquery.inputmask.min.js') }}"></script>
    <script>
        $('[name="code"]').inputmask({
            mask: [{'mask': '##.##.##.###-#'}], 
            greedy: false, 
            definitions: {'#': {validator: '[0-9]', cardinality: 1}} 
        });
    </script>
@endsection

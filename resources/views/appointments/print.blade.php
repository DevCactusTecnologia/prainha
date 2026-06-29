<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8" />
    <title>Imprimir comprovante de atendimento - {{ $appointment->id }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Sistema para Laboratórios, Clinicas e Hospitais" />
    <meta name="author" content="Sislac" />
    <meta name="robots" content="noindex, nofollow">

    <style>
        .horizontal-dotted-line {
            display: flex;
            width: 100%;
        } 
        .horizontal-dotted-line:after {
            border-bottom: 1px dashed black;
            content: '';
            flex: 1;
        }
    </style>
</head>

<body>

    {{-- <div style="font-family: Arial, Helvetica, sans-serif; margin-bottom: 25px;">
        <div style="font-size: 2mm; display: flex; justify-content: flex-start; margin-bottom: 5px;">
            <div style="display: flex; justify-content: center; align-items: center; margin-right: 8px;">
                <img src="{{ asset('assets/images/brasao.png') }}" width="30mm" alt="brasão" style="filter: grayscale(1);">
            </div>
            <div style="display: flex; flex-direction: column; justify-content: center;">
                <div>PREFEITURA DE SÃO BENTO</div>
                <div>SECRETARIA MUNICIPAL DE SAÚDE</div>
                <div>LABORATÓRIO M. DE ANÁLISES CLÍNICAS DR. A. ALEXANDRE DA SILVA</div>
            </div>
        </div>
        <hr>

        <div style="display: flex; justify-content: space-between;">
            <div style="display: flex; flex-direction: column;">
                <div style="font-size: 4mm; font-weight: bold;">Solicitação de exames</div>
                <div style="font-size: 3.5mm;">
                    Paciente: {{ $appointment->patient->first_name }}
                </div>
                <div style="font-size: 3.5mm;">
                    Convênio: {{ $appointment->company?->name }}
                </div>
                <div style="font-size: 3.5mm;">
                    Data do cadastro: {{ date('d/m/Y', strtotime($appointment->appointment_date)) }}
                </div>
                <div style="font-size: 3.5mm;">
                    Protocolo: {{ $appointment->id }}
                </div>
                <div style="font-size: 3.5mm; margin-bottom: 10px;">
                    Nº da Guia: {{ $appointment->guide_number }}
                </div>
                
            </div>

            <div style="display: flex; justify-content: center; flex-direction: column; align-items: center;">
                @if ($urlPatient)
                    <div>{!! QrCode::size(100)->generate($urlPatient); !!}</div>
                    <div>Consulte o resultado pela chave de acesso em:</div>
                    <div>{{ route('patient.result.search.index') }}</div>
                    <div>Chave de acesso: <strong>{{ $appointment->access_key }}</strong></div>
                @endif
            </div>
        </div>

        <div style="font-size: 4mm; font-weight: bold;">Exames:</div>
        @foreach ($appointment->exams as $exam)
            <div style="font-size: 3mm; display: flex; justify-content: space-between;">
                <div class="horizontal-dotted-line">{{ $exam->name }}</div>
                <div>1</div>
            </div>
        @endforeach

        <div style="font-size: 3mm; margin-top: 10px;">
            Previsão de entrega: {{ date('d/m/Y', strtotime($appointment->delivery_date)) }}
        </div>
    </div>

    <div style="display: flex; align-items: center; width: 100%;">
        <svg xmlns="http://www.w3.org/2000/svg" height="18" width="18" viewBox="0 0 48 48">
            <path d="M39.1 42.3 24.05 27.25 18.2 33.1q.55.85.675 1.65.125.8.125 1.75 0 3.2-2.15 5.35Q14.7 44 11.5 44q-3.2 0-5.35-2.15Q4 39.7 4 36.5q0-3.2 2.15-5.35Q8.3 29 11.5 29q.9 0 1.775.25.875.25 1.825.75l5.8-5.8-5.9-5.9q-.85.4-1.725.55Q12.4 19 11.5 19q-3.2 0-5.35-2.15Q4 14.7 4 11.5q0-3.2 2.15-5.35Q8.3 4 11.5 4q3.2 0 5.35 2.15Q19 8.3 19 11.5q0 .95-.125 1.8-.125.85-.525 1.6l25.7 25.7v1.7Zm-9.15-20.65-3.3-3.3L39.1 5.9h4.95v1.65ZM11.5 16q1.9 0 3.2-1.3 1.3-1.3 1.3-3.2 0-1.9-1.3-3.2Q13.4 7 11.5 7 9.6 7 8.3 8.3 7 9.6 7 11.5q0 1.9 1.3 3.2Q9.6 16 11.5 16Zm12.65 9.15q.4 0 .675-.275t.275-.675q0-.4-.275-.675t-.675-.275q-.4 0-.675.275t-.275.675q0 .4.275.675t.675.275ZM11.5 41q1.9 0 3.2-1.3 1.3-1.3 1.3-3.2 0-1.9-1.3-3.2-1.3-1.3-3.2-1.3-1.9 0-3.2 1.3Q7 34.6 7 36.5q0 1.9 1.3 3.2Q9.6 41 11.5 41Z"/>
        </svg>------------------------------------------------------------------------------------------------------------------------------
    </div> --}}
      
    {{-- PARA SER UTILIZADO COM A IMPRESSORA TÉRMICA --}}

        <div style="font-family: Arial, Helvetica, sans-serif">
            <div style="font-size: 2mm; display: flex; justify-content: flex-start; margin-bottom: 5px;">
                <div style="display: flex; justify-content: center; align-items: center; margin-right: 8px;">
                    <img src="{{ asset('assets/images/brasao.png') }}" width="30mm" alt="brasão" style="filter: grayscale(1);">
                </div>
                <div style="display: flex; flex-direction: column; justify-content: center;">
                    <div>PREFEITURA DE SÃO BENTO</div>
                    <div>SECRETARIA MUNICIPAL DE SAÚDE</div>
                    <div>LABORATÓRIO M. DE ANÁLISES CLÍNICAS DR. A. ALEXANDRE DA SILVA</div>
                </div>
            </div>
            <hr>

            <div style="font-size: 4mm; font-weight: bold;">Solicitação de exames</div>
            <div style="font-size: 3mm;">
                Paciente: {{ $appointment->patient->first_name }}
            </div>
            <div style="font-size: 3mm;">
                Convênio: {{ $appointment->company?->name }}
            </div>
            <div style="font-size: 3mm;">
                Data do cadastro: <span style="font-family: cursive;">{{ date('d/m/Y', strtotime($appointment->appointment_date)) }}</span>
            </div>
            <div style="font-size: 3mm;">
                Protocolo: <span style="font-family: cursive;">{{ $appointment->id }}</span>
            </div>
            <div style="font-size: 3mm; margin-bottom: 10px;">
                Nº da Guia: <span style="font-family: cursive;">{{ $appointment->guide_number }}</span>
            </div>

            <div style="font-size: 4mm; font-weight: bold;">Exames:</div>
            @foreach ($appointment->exams as $exam)
                <div style="font-size: 3mm; display: flex; justify-content: space-between;">
                    <div class="horizontal-dotted-line">{{ $exam->name }}</div>
                    <div>1</div>
                </div>
            @endforeach

            <div style="display: flex; flex-direction: column; justify-content: center; align-items: center; margin-top: 10px; margin-bottom: 10px;">
                <div style="text-align: center;">
                    @if ($urlPatient)
                        <div style="margin: 2mm 0mm;">{!! QrCode::size(100)->generate($urlPatient); !!}</div>
                    @endif
                    <div style="font-size: 3mm;">Consulte o resultado pela chave de acesso em:</div>
                    <div style="font-size: 3mm; word-break: break-word;">{{ route('patient.result.search.index') }}</div>
                    <div style="font-size: 4mm;">Chave de acesso: <strong style="font-family: Consolas;">{{ $appointment->access_key }}</strong></div>
                </div>
            </div>

            <div style="font-size: 4mm; margin-top: 10px; text-align: center;">
                Previsão de entrega: <span style="font-family: cursive;">{{ date('d/m/Y', strtotime($appointment->delivery_date)) }} <strong>09:00 horas</strong></span>
            </div>
        </div> 
   
</body>

<script>
    window.print()

    if ('matchMedia' in window) {
        window.matchMedia('print').addListener((mediaQueryListEvent) => {
            if (!mediaQueryListEvent.matches) {
                window.close()
            }
        })
    } else {
        window.onafterprint = function () {
            window.close()
        }
    }
</script>

</html>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8" />
    <title>Mapa indididual - guia - {{ $appointment->guide_number }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta content="Sistema para Laboratórios, Clinicas e Hospitais" name="description" />
    <meta content="Sislac" name="author" />
</head>

<body style="font-family: Arial, Helvetica, sans-serif">
    
    <h4 style="text-align: center; margin-bottom: 20px;">
        MAPA INDIVIDUAL - GUIA <strong style="background-color: #CCC;">{{ $appointment->guide_number }}</strong>
    </h4>

    <div style="margin-bottom: 15px; display: flex;">
        @php $patient = App\Models\Patient::firstWhere('user_id', $appointment->patient->id); @endphp
        <div style="width: 50%;">
            <div style="font-size: 14px;">
                <div>
                    Paciente: <strong>{{ "{$appointment->patient->first_name} {$appointment->patient->last_name}" }}</strong>
                </div>
                <div><strong>{{ $patient->gender === 'Female' ? 'F' : 'M'}}</strong> {{ $patient->age_extended }}</div>
                <div><strong>Protocolo:</strong> {{ $appointment->id }}</div>
            </div>
        </div>
    </div>

    @foreach ($appointment->exams as $exam)
            
        @if (in_array($exam->id, [347, 367, 546, 709]))
            {{-- HEMOGRAMA COMPLETO --}}
            @if ($exam->id == '347')
                @include('routine.map.patient.inc.hemogram')
            @endif

            {{-- HIV (1 E 2 ANTICORPOS) --}}
            @if ($exam->id == '367')
                @include('routine.map.patient.inc.hiv')
            @endif

            {{-- PARASITOLOGICO DE FEZES --}}
            @if ($exam->id == '546')
                @include('routine.map.patient.inc.fezes')
            @endif

            {{-- URINA DE JATO MEDIO --}}
            @if ($exam->id == '709')
                @include('routine.map.patient.inc.urina')
            @endif
        @else 
            
            <table style="width: 100%; font-size: 11px; border-collapse: collapse; border: 1px solid #000; margin-bottom: 10px;">
                <tr>
                    <td colspan="100" style="border: 1px solid #000; height: 8px; padding-left: 5px; background-color: #e7e6e6; font-weight: 600;">{{ $exam->name }}</td>
                </tr>
                <tr>
                    <td style="display: flex; flex-wrap: wrap;">
                        @foreach ($exam->parameters as $parameter)
                            @if ($parameter->with_printed_map == '1')
                                <span style="text-align: center; border-right: 1px solid #000;">
                                    <div style="padding: 0px 15px; text-align: center;">{{ str_replace('##', '', $parameter->parameter) }}</div>
                                    <div style="border-top: 1px solid #000; height: 1px;"></div>
                                    <div style="padding: 0px 15px; height: 15px; border-bottom: 1px solid #000;"></div>
                                </span>
                            @endif
                        @endforeach
                    </td>
                </tr>
            </table>
        @endif
        
    @endforeach

</body>

<script>
    window.onafterprint = () => window.close();
    window.print();
</script>

</html>

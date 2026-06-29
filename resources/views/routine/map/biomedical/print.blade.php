<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8" />
    <title>Mapa do bio - {{ $registers['biomedical_name'] }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Sistema para Laboratórios, Clinicas e Hospitais" />
    <meta name="author" content="Sislac" />
</head>

<body style="font-family: Arial, Helvetica, sans-serif">

    <h4 style="text-align: center; margin-bottom: 20px;">
        MAPA DE TRABALHO - {{ $registers['biomedical_name'] }}
    </h4>

    @foreach ($registers as $key => $register)

        @if ($key === 'patients_map_page_separated')

                @foreach ($register as $item)

                    @foreach ($item as $examName => $records)

                        <table style="width: 100%; font-size: 11px; border-collapse: collapse; border: 1px solid #000;">
                            <tr>
                                <td colspan="19" style="padding: 5px; text-align: right; background-color: #F2F2F2; font-weight: 600;">
                                    {{ $examName }}, Impresso em {{ date('d/m/Y H:i:s') }} por {{ $user->first_name }}
                                </td>
                            </tr>

                            @foreach ($records as $record)

                                {{-- HEMOGRAMA COMPLETO --}}
                                @if ($record['exam_id'] == '347')
                                    @include('routine.map.biomedical.inc.hemogram')
                                @endif

                                {{-- HIV (1 E 2 ANTICORPOS) --}}
                                @if ($record['exam_id'] == '367')
                                    @include('routine.map.biomedical.inc.hiv')
                                @endif

                                {{-- PARASITOLOGICO DE FEZES --}}
                                @if ($record['exam_id'] == '546')
                                    @include('routine.map.biomedical.inc.fezes')
                                @endif

                                {{-- URINA DE JATO MEDIO --}}
                                @if ($record['exam_id'] == '709')
                                    @include('routine.map.biomedical.inc.urina')
                                @endif

                            @endforeach
                        </table>

                        @if ( (count($item) >= 2) && !array_key_exists('patients_map_normal', $registers))
                            <div style="page-break-before: always;"></div>
                        @endif  

                        @if ( (count($item) >= 1) && array_key_exists('patients_map_normal', $registers))
                            <div style="page-break-before: always;"></div>
                        @endif                
                        
                    @endforeach

                @endforeach

        @endif

        @if ($key === 'patients_map_normal')

            @foreach ($register as $item)
                <table style="width: 100%; font-size: 11px; border-collapse: collapse; border: 1px solid #000;">
                    <tr>
                        <td colspan="19" style="padding: 5px; text-align: right; background-color: #F2F2F2; font-weight: 600;">
                            MAPA DO ANALISTA {{ $registers['biomedical_name'] }}, Impresso em {{ date('d/m/Y H:i:s') }} por {{ $user->first_name }}
                        </td>
                    </tr>

                    @foreach ($item  as $patientName => $record)
                        @include('routine.map.biomedical.inc.others-exams')
                    @endforeach

                </table>
            @endforeach

        @endif
        
    @endforeach

</body>

<script>
    window.onafterprint = () => window.close();
    window.print();
</script>

</html>

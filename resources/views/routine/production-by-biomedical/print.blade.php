<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8" />
    <title>Produção do analista - {{ $biomedicals['name'] }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Sistema para Laboratórios, Clinicas e Hospitais" />
    <meta name="author" content="Sislac" />
</head>

<body style="font-family: Arial, Helvetica, sans-serif;">

    <div style="display: flex; margin-bottom: 5px; align-items: center;">
        <div style="margin-right: 10px;">
            <img src="{{ asset('assets/images/brasao.png') }}" width="60px" alt="brasão">
        </div>
        <div style="display: flex; flex-direction: column; justify-content: center; align-items: flex-start;">
            <h5 style="font-size: 14px; font-weight: 700; margin: 1px 0px;">
                PREFEITURA DE SÃO BENTO
            </h5>
            <h5 style="font-size: 14px; font-weight: 700; margin: 1px 0px;">
                SECRETARIA MUNICIPAL DE SAÚDE
            </h5>
            <h5 style="font-size: 14px; font-weight: 700; margin: 1px 0px;">
                LABORATÓRIO MUNICIPAL DE ANÁLISES CLÍNICAS DR. ALICIO ALEXANDRE DA SILVA
            </h5>
        </div>
    </div>
    <hr>

    <div style="display: flex;">
        <div style="width: 50%;">
            <div style="font-size: 14px;">
                <div>
                    Biomedico:&nbsp;<strong>{{ $biomedicals['name'] }}</strong>
                </div>
                <div>
                    CPF:&nbsp;<strong>{{ $biomedicalInfo->cpf_masked }}</strong>
                </div>
                <div>
                    CNS:&nbsp;<strong>{{ $biomedicalInfo->cns_masked }}</strong>
                </div>
            </div>
        </div>
        <div style="width: 50%;">
            <div style="font-size: 14px;">
                <div>
                    Conselho de Classe:&nbsp;<strong>{{ $biomedicalInfo->council->sigla }}</strong>
                </div>
                <div>
                    Estado Emissor:&nbsp;<strong>{{ $biomedicalInfo->state->name }}</strong>
                </div>
                <div>
                    Número de Registro do Conselho:&nbsp;<strong>{{ $biomedicalInfo->counsil_number }}</strong>
                </div>
            </div>
        </div>
    </div>

    <div style="text-align: center; margin-top: 20px; margin-bottom: 10px;">
        PRODUÇÃO REALIZADA ENTRE 
        <strong>({{ date('d/m/Y', strtotime($dateStart)) }} à {{ date('d/m/Y', strtotime($dateEnd)) }})</strong>
    </div>

    <div style="display: flex; justify-content: center; margin-bottom: 20px;">
        <table style="border-collapse: collapse; border: 1px solid #000;">
            <thead>
                <tr style="background-color: #eff2f7;">
                    <th style="padding: 5px; border: 1px solid #000;">Exames analisados</th>
                    <th style="text-align: center; padding: 5px; border: 1px solid #000;">Data da coleta</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($biomedicals['exam_collected_at'] as $collectedAt => $exams)
                    @foreach ($exams as $name => $total)
                        <tr>
                            <td style="border: 1px solid #000; padding: 3px 4px">{{ $total }}x {{ $name }}</td>
                            <td style="text-align: center; border: 1px solid #000; padding: 3px 4px">{{ $collectedAt }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>

</body>

<script>
    // window.onafterprint = function() {
    //     window.close();  
    // }

    // window.print();
</script>

</html>

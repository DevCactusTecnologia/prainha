<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8" />
    <title>Orçamento</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Sistema para Laboratórios, Clinicas e Hospitais" />
    <meta name="author" content="Sislac" />
    <meta name="robots" content="noindex, nofollow">
</head>

<body style="font-family: Arial, Helvetica, sans-serif;">

    {{-- CABEÇALHO --}}
    <header style="display: flex; flex-direction: column; justify-content: center; align-items: center;">
        <img src="@if($budget->unity?->logo_path) {{ asset('storage/images/tenants/' . tenant('id') . '/' . $budget->unity->logo_path) }}@else{{ asset('assets/images/users/noImage.png') }}@endif" 
            width="auto" height="50px" alt="logo"
        >
        <div>LABORATÓRIO DE ANÁLISES CLÍNICAS</div>
        <div>{{ $budget->unity?->name }}</div>
        <div style="font-weight: bold; margin: 25px 0px;">ORÇAMENTO</div>
    </header>

    {{-- DADOS DO PACIENTE --}}
    <section style="display: flex;">
        <div style="width: 60%">
            <div><strong>Nome:</strong> {{ $budget->patient }}</div>
            <div><strong>Convênio:</strong> {{ $budget->company->name }}</div>
        </div>
        <div style="width: 40%">
            <div><strong>CPF:</strong> {{ $budget->cpf_masked }}</div>
            <div><strong>Data:</strong> {{ $budget->registered_at?->format('d/m/Y') }}</div>
        </div>
    </section>
    <hr style="margin: 10px 0px;">

    {{-- EXAMES --}}
    <section>
        <div style="font-size: 18px; margin-bottom: 10px;">Exames solicitados:</div>
        <table style="border-collapse: collapse; width: 100%">
            <tbody>
                <tr>
                    <td style="width: 85%; border-bottom: 1px solid #000;"></td>
                    <td style="width: 15%; border-bottom: 1px solid #000;"></td>
                </tr>
                @php $subtotal = 0 @endphp
                @foreach ($budget->exams_prices as $exam)
                    <tr>
                        <td style="width: 85%; border-bottom: 1px solid #000; padding: 5px;">
                            {{ $exam->name }}
                        </td>
                        <td style="width: 15%; border-bottom: 1px solid #000; padding: 5px; text-align: right;">
                            R$ {{ number_format($exam->pivot->price, 2, ',', '.') }}
                            @php $subtotal += $exam->pivot->price @endphp
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td style="width: 85%; text-align: right; padding: 5px;">Subtotal:</td>
                    <td style="width: 15%; padding: 5px; text-align: right;">
                        R$ {{ number_format($subtotal, 2, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <td style="width: 85%; text-align: right; padding: 5px;">Desconto:</td>
                    <td style="width: 15%; padding: 5px; text-align: right;">{{ $budget->discount_masked }}</td>
                </tr>
                <tr>
                    <td style="width: 85%; text-align: right; padding: 5px;">Total com desconto:</td>
                    <td style="width: 15%; text-align: right; background-color: #F2F2F2;">
                        <strong>{{ $budget->amount_masked }}</strong>
                    </td>
                </tr>
            </tbody>
        </table>
    </section>
    
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

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8" />
    <title>Paciente não encontrado</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Sistema para Laboratórios, Clinicas e Hospitais" />
    <meta name="author" content="Sislac" />
    <meta name="robots" content="noindex,nofollow">
</head>

<body style="background-color: #F2F2F2; font-family: Arial, Helvetica, sans-serif; padding: 20px;">
   <div style="display: flex; flex-direction: column; justify-content: center; align-items: center;">
        <div style="background-color: #556ee6; border-radius: 50%; padding: 20px;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="#FFF" height="90" width="90" viewBox="0 0 48 48">
                <path d="M20 11.3q-1.5 0-2.575-1.075Q16.35 9.15 16.35 7.65q0-1.5 1.075-2.575Q18.5 4 20 4q1.5 0 2.575 1.075Q23.65 6.15 23.65 7.65q0 1.5-1.075 2.575Q21.5 11.3 20 11.3Zm13 25.2q2.3 0 3.9-1.6t1.6-3.9q0-2.3-1.6-3.9T33 25.5q-2.3 0-3.9 1.6T27.5 31q0 2.3 1.6 3.9t3.9 1.6Zm10.2 6.8-5.35-5.35q-1.1.75-2.325 1.15-1.225.4-2.525.4-3.55 0-6.025-2.475Q24.5 34.55 24.5 31q0-3.55 2.475-6.025Q29.45 22.5 33 22.5q3.55 0 6.025 2.475Q41.5 27.45 41.5 31q0 1.3-.4 2.525-.4 1.225-1.15 2.325l5.35 5.35Zm-20.85.7v-8.65q.5 1.25 1.25 2.325.75 1.075 1.75 1.925V44Zm-7.7 0V17.35q-3.2-.25-6.35-.675Q5.15 16.25 2 15.5l.75-3q4.25 1 8.55 1.425 4.3.425 8.7.425 4.4 0 8.7-.425Q33 13.5 37.25 12.5l.75 3q-3.15.75-6.3 1.175-3.15.425-6.35.675v5.05q-1.75 1.6-2.8 3.825Q21.5 28.45 21.5 31h-3.85v13Z"/>
            </svg>
        </div>
        <h1 style="color: #556ee6; font-size: 18px; margin-top: 15px; text-align: center; margin-bottom: 10px;">
            <em style="color: #888;">Oops!</em> <strong>Paciente não encontrado!</strong>
        </h1>
        <div style="margin-bottom: 15px; width: 50%; border-top: 1px dashed #556ee6;"></div>
        <div style="color: #888; font-size: 16px; text-align: center;">
            Por favor, para mais detalhes entre em contato com o setor responsável pelo seu atendimento!
        </div>
        <div>
            <img src="{{ asset('assets/images/error-img.png') }}" style="width: 100%;" alt="Não encontrado">
        </div>
    </div>
</body>

</html>


const containerPadraoDoEsfregaco = document.querySelector('[data-js="container-padrao-do-esfregaco"]');
const padraoDoEsfregacoTrofico = document.querySelector('[name="padrao_do_esfregaco_id"][value="1"]');
const padraoDoEsfregacoAtrofico = document.querySelector('[name="padrao_do_esfregaco_id"][value="2"]');
const padraoDoEsfregacoHipotrofico = document.querySelector('[name="padrao_do_esfregaco_id"][value="3"]');

const containerPadraoInsatisfatoria = document.querySelector('[data-js="container-padrao-insatisfatoria"]');
const adequacaoAmostraInsatisfatoria = document.querySelectorAll('[name="adequacao_da_amostra_insatisfatoria_id[]"]');
const adequacaoAmostraInsatisfatoriaOutro = document.querySelector('[name="adequacao_da_amostra_insatisfatoria_outro"]');

const containerPadraoInsatisfatoriaMicrobiologia = document.querySelector('[data-js="container-padrao-insatisfatoria-microbiologia"]');
const adequacaoAmostraInsatisfatoriaMicrobiologia = document.querySelectorAll('[name="adequacao_da_amostra_insatisfatoria_microbiologia_id[]"]');
const adequacaoAmostraInsatisfatoriaMicrobiologiaOutro = document.querySelector('[name="adequacao_da_amostra_insatisfatoria_microbiologia_outro"]');

const containerEpiteliosRepresentadosNaAmostra = document.querySelector('[data-js="container-epitelios-representados-na-amostra"]');
const epiteliosRepresentadosNaAmostraEscamoso = document.querySelector('[name="epitelios_representados_na_amostra_id"][value="1"]');
const epiteliosRepresentadosNaAmostraEscamosoGlandular = document.querySelector('[name="epitelios_representados_na_amostra_id"][value="2"]');
const epiteliosRepresentadosNaAmostraEscamosoGlandularMetaplastico = document.querySelector('[name="epitelios_representados_na_amostra_id"][value="3"]');
const epiteliosRepresentadosNaAmostraEscamosoMetaplastico = document.querySelector('[name="epitelios_representados_na_amostra_id"][value="4"]');

const containerResultadosConclusao = document.querySelector('[data-js="container-resultados-conclusao"]');
const resultadoAlteracaoSim = document.querySelector('[name="se_possui_resultado_com_alteracao"][value="1"]');
const resultadoAlteracaoNao = document.querySelector('[name="se_possui_resultado_com_alteracao"][value="2"]');

const containerResultadosConclusaoAlteracoesNaoMicrobiologia = document.querySelector('[data-js="container-resultados-conclusao-alteracoes-nao-microbiologia"]');
const resultadoAlteracaoMicrobiologiaNao = document.querySelectorAll('[name="resultado_alteracao_nao_microbiologia_id[]"]');
const resultadoAlteracaoMicrobiologiaNaoOutro = document.querySelector('[name="resultado_alteracao_nao_microbiologia_outro"]');

const adequecaoDaAmostraSatisfatoria = document.querySelector('[name="adequacao_da_amostra_id"][value="1"]');
adequecaoDaAmostraSatisfatoria.addEventListener('change', ({target}) => {

    if (target.checked) {
        containerPadraoDoEsfregaco.style.display = 'block';
        enableInput(padraoDoEsfregacoTrofico);
        enableInput(padraoDoEsfregacoAtrofico);
        enableInput(padraoDoEsfregacoHipotrofico);

        enableInput(epiteliosRepresentadosNaAmostraEscamoso);
        enableInput(epiteliosRepresentadosNaAmostraEscamosoGlandular);
        enableInput(epiteliosRepresentadosNaAmostraEscamosoGlandularMetaplastico);
        enableInput(epiteliosRepresentadosNaAmostraEscamosoMetaplastico);

        enableInput(resultadoAlteracaoSim);
        enableInput(resultadoAlteracaoNao);

        //  ALTERAÇÕES NAO MICROBIOLOGIA

        Array.from(resultadoAlteracaoMicrobiologiaNao).forEach((element) => {
            enableInput(element);
        });
        enableInput(resultadoAlteracaoMicrobiologiaNaoOutro);

        containerPadraoInsatisfatoria.style.display = 'none';
        Array.from(adequacaoAmostraInsatisfatoria).forEach((element) => {
            disableInput(element);
        });
        disableInput(adequacaoAmostraInsatisfatoriaOutro);

        containerPadraoInsatisfatoriaMicrobiologia.style.display = 'none';
        Array.from(adequacaoAmostraInsatisfatoriaMicrobiologia).forEach((element) => {
            disableInput(element);
        });
        disableInput(adequacaoAmostraInsatisfatoriaMicrobiologiaOutro);
    }
});

const adequecaoDaAmostraInsatisfatoria = document.querySelector('[name="adequacao_da_amostra_id"][value="2"]');
adequecaoDaAmostraInsatisfatoria.addEventListener('change', ({target}) => {
    if (target.checked) {

        containerPadraoDoEsfregaco.style.display = 'none';
        disableInput(padraoDoEsfregacoTrofico);
        disableInput(padraoDoEsfregacoAtrofico);
        disableInput(padraoDoEsfregacoHipotrofico);

        containerEpiteliosRepresentadosNaAmostra.style.display = 'none';
        disableInput(epiteliosRepresentadosNaAmostraEscamoso);
        disableInput(epiteliosRepresentadosNaAmostraEscamosoGlandular);
        disableInput(epiteliosRepresentadosNaAmostraEscamosoGlandularMetaplastico);
        disableInput(epiteliosRepresentadosNaAmostraEscamosoMetaplastico);

        containerResultadosConclusao.style.display = 'none';
        disableInput(resultadoAlteracaoSim);
        disableInput(resultadoAlteracaoNao);

        // ALTERAÇÕES? NAO MICROBIOLOGIA

        containerResultadosConclusaoAlteracoesNaoMicrobiologia.style.display = 'none';
        Array.from(resultadoAlteracaoMicrobiologiaNao).forEach((element) => {
            disableInput(element);
        });
        disableInput(resultadoAlteracaoMicrobiologiaNaoOutro);

        // ALTERACAO BENIGNA

        containerAlteracoesCelularesBenignaSimOuNao.style.display = 'none';
        disableInput(sePossuiAlteracaoCelularBenignaSim);
        disableInput(sePossuiAlteracaoCelularBenignaNao);

        containerAlteracoesCelularesBenignaSimMicrobiogia.style.display = 'none';
        Array.from(alteracaoCelularBenignaMicrobiologia).forEach((element) => {
            disableInput(element);
        });
        disableInput(alteracaoCelularBenignaMicrobiologiaOutro);

        containerAlteracoesCelularesBenignaReatividade.style.display = 'none';
        Array.from(alteracaoCelularBenignaReatividade).forEach((element) => {
            disableInput(element);
        });
        disableInput(alteracaoCelularBenignaReatividadeOutro);
        disableInput(containerAlteracoesCelularesBenignaMensagem);

        // ALTERACAO EPITELIAL ESCAMOSA

        containerAlteracoesCelularesEpiteliaisEscamosasSimOuNao.style.display = 'none';
        disableInput(sePossuiAlteracaoEpitelialEscamosaSim);
        disableInput(sePossuiAlteracaoEpitelialEscamosaNao);

        containerAlteracoesCelularesEpiteliaisEscamosasMicrobiologiaSim.style.display = 'none';
        Array.from(alteracaoCelularEpitelialMicrobiologia).forEach((element) => {
            disableInput(element);
        });
        disableInput(alteracaoCelularEpitelialMicrobiologiaOutro);

        containerAlteracoesCelularesEpiteliaisEscamosas.style.display = 'none';
        Array.from(alteracaoCelularEpitelialEscamosa).forEach((element) => {
            disableInput(element);
        });
        disableInput(alteracaoCelularEpitelialEscamosaOutro);

        // ALTERACAO GLANDULAR

        containerAlteracoesCelularesGlandularesSimOuNao.style.display = 'none';
        disableInput(sePossuiAlteracaoCelulaGlandularSim);
        disableInput(sePossuiAlteracaoCelulaGlandularNao);

        containerAlteracoesCelularesGlandularMicrobiologiaSim.style.display = 'none';
        Array.from(alteracaoCelularGlandularMicrobiologia).forEach((element) => {
            disableInput(element);
        });
        disableInput(alteracaoCelularGlandularMicrobiologiaOutro);

        containerAlteracoesCelularesGlandulares.style.display = 'none';
        Array.from(alteracaoCelularGlandular).forEach((element) => {
            disableInput(element);
        });
        disableInput(alteracaoCelularGlandularOutro);

        containerPadraoInsatisfatoria.style.display = 'block';
        Array.from(adequacaoAmostraInsatisfatoria).forEach((element) => {
            enableInput(element);
        });
        enableInput(adequacaoAmostraInsatisfatoriaOutro);

        containerPadraoInsatisfatoriaMicrobiologia.style.display = 'block';
        Array.from(adequacaoAmostraInsatisfatoriaMicrobiologia).forEach((element) => {
            enableInput(element);
        });
        enableInput(adequacaoAmostraInsatisfatoriaMicrobiologiaOutro);
    }
});

// CHECA PADRAO DO ESFREGACO
containerPadraoDoEsfregaco.addEventListener('click', ({target}) => {
    if (target.nodeName == 'INPUT' || target.nodeName == 'LABEL') {
        containerEpiteliosRepresentadosNaAmostra.style.display = 'block';
    }
});

// CHECA PADRAO DOS EPITELIOS REPRESENTADOS NA AMOSTRA
containerEpiteliosRepresentadosNaAmostra.addEventListener('click', ({target}) => {
    if (target.nodeName == 'INPUT' || target.nodeName == 'LABEL') {
        containerResultadosConclusao.style.display = 'block';
        enableInput(resultadoAlteracaoSim);
        enableInput(resultadoAlteracaoNao);
    }
});

resultadoAlteracaoNao.addEventListener('click', ({target}) => {
    if (target.checked) {

        // ALTERACAO CELULAR BENIGNA NAO

        containerAlteracoesCelularesBenignaSimOuNao.style.display = 'none';
        disableInput(sePossuiAlteracaoCelularBenignaSim);
        disableInput(sePossuiAlteracaoCelularBenignaNao);

        containerAlteracoesCelularesBenignaSimMicrobiogia.style.display = 'none';
        Array.from(alteracaoCelularBenignaMicrobiologia).forEach((element) => {
            disableInput(element);
        });
        disableInput(alteracaoCelularBenignaMicrobiologiaOutro);

        containerAlteracoesCelularesBenignaReatividade.style.display = 'none';
        Array.from(alteracaoCelularBenignaReatividade).forEach((element) => {
            disableInput(element);
        });
        disableInput(alteracaoCelularBenignaReatividadeOutro);
        disableInput(containerAlteracoesCelularesBenignaMensagem);

        // ALTERACAO CELULAR EPITELIAL ESCAMOSA NAO

        containerAlteracoesCelularesEpiteliaisEscamosasSimOuNao.style.display = 'none';
        disableInput(sePossuiAlteracaoEpitelialEscamosaSim);
        disableInput(sePossuiAlteracaoEpitelialEscamosaNao);

        containerAlteracoesCelularesEpiteliaisEscamosasMicrobiologiaSim.style.display = 'none';
        Array.from(alteracaoCelularEpitelialMicrobiologia).forEach((element) => {
            disableInput(element);
        });
        disableInput(alteracaoCelularEpitelialMicrobiologiaOutro);

        containerAlteracoesCelularesEpiteliaisEscamosas.style.display = 'none';
        Array.from(alteracaoCelularEpitelialEscamosa).forEach((element) => {
            disableInput(element);
        });
        disableInput(alteracaoCelularEpitelialEscamosaOutro);

        // ALTERACAO CELULAR GLANDULAR NAO

        containerAlteracoesCelularesGlandularesSimOuNao.style.display = 'none';
        disableInput(sePossuiAlteracaoCelulaGlandularSim);
        disableInput(sePossuiAlteracaoCelulaGlandularNao);

        containerAlteracoesCelularesGlandularMicrobiologiaSim.style.display = 'none';
        Array.from(alteracaoCelularGlandularMicrobiologia).forEach((element) => {
            disableInput(element);
        });
        disableInput(alteracaoCelularGlandularMicrobiologiaOutro);

        containerAlteracoesCelularesGlandulares.style.display = 'none';
        Array.from(alteracaoCelularGlandular).forEach((element) => {
            disableInput(element);
        });
        disableInput(alteracaoCelularGlandularOutro);

        // ALTERAÇÕES NAO MICROBIOLOGIA

        containerResultadosConclusaoAlteracoesNaoMicrobiologia.style.display = 'block';
        Array.from(resultadoAlteracaoMicrobiologiaNao).forEach((element) => {
            enableInput(element);
        });
        enableInput(resultadoAlteracaoMicrobiologiaNaoOutro);
    }
});

// ALTERACAO CELULAR BENIGNA

const containerAlteracoesCelularesBenignaSimOuNao = document.querySelector('[data-js="container-alteracoes-celulares-benigna-sim-ou-nao"]');
const sePossuiAlteracaoCelularBenignaSim = document.querySelector('[name="se_possui_alteracao_celular_benigna"][value="1"]');
const sePossuiAlteracaoCelularBenignaNao = document.querySelector('[name="se_possui_alteracao_celular_benigna"][value="2"]');

const containerAlteracoesCelularesBenignaSimMicrobiogia = document.querySelector('[data-js="container-alteracoes-celulares-benigna-sim-microbiogia"]');
const alteracaoCelularBenignaMicrobiologia = document.querySelectorAll('[name="alteracao_celular_benigna_microbiologia_id[]"]');
const alteracaoCelularBenignaMicrobiologiaOutro = document.querySelector('[name="alteracao_celular_benigna_microbiologia_outro"]');

const containerAlteracoesCelularesBenignaReatividade = document.querySelector('[data-js="container-alteracoes-celulares-benigna-reatividade"]');
const alteracaoCelularBenignaReatividade = document.querySelectorAll('[name="alteracao_celular_reatividade_id[]"]');
const alteracaoCelularBenignaReatividadeOutro = document.querySelector('[name="alteracao_celular_reatividade_outro"]');
const containerAlteracoesCelularesBenignaMensagem = document.querySelector('[data-js="container-alteracoes-celulares-benigna-sim-mensagem"]');

// ALTERACAO CELULAR EPITELIAL ESCAMOSA SIM OU  NAO

const containerAlteracoesCelularesEpiteliaisEscamosasSimOuNao = document.querySelector('[data-js="container-alteracoes-celulares-epiteliais-escamosas-sim-ou-nao"]');
const sePossuiAlteracaoEpitelialEscamosaSim = document.querySelector('[name="se_possui_alteracao_celular_epitelial_escamosa"][value="1"]');
const sePossuiAlteracaoEpitelialEscamosaNao = document.querySelector('[name="se_possui_alteracao_celular_epitelial_escamosa"][value="2"]');

const containerAlteracoesCelularesEpiteliaisEscamosasMicrobiologiaSim = document.querySelector('[data-js="container-alteracoes-celulares-epiteliais-escamosas-microbiologia-sim"]');
const alteracaoCelularEpitelialMicrobiologia = document.querySelectorAll('[name="alteracao_celular_epitelial_escamosa_microbiologia_id[]"]');
const alteracaoCelularEpitelialMicrobiologiaOutro = document.querySelector('[name="alteracao_celular_epitelial_escamosa_microbiologia_outro"]');

const containerAlteracoesCelularesEpiteliaisEscamosas = document.querySelector('[data-js="container-alteracoes-celulares-epiteliais-escamosas"]');
const alteracaoCelularEpitelialEscamosa = document.querySelectorAll('[name="alteracao_celular_epitelial_escamosa_id[]"]');
const alteracaoCelularEpitelialEscamosaOutro = document.querySelector('[name="alteracao_celular_epitelial_escamosa_outro"]');

//  ALTERACAO CELULAR GLANDULAR SIM OU NAO

const containerAlteracoesCelularesGlandularesSimOuNao = document.querySelector('[data-js="container-alteracoes-celulares-glandulares-sim-ou-nao"]');
const sePossuiAlteracaoCelulaGlandularSim = document.querySelector('[name="se_possui_alteracao_em_celulas_glandulares"][value="1"]');
const sePossuiAlteracaoCelulaGlandularNao = document.querySelector('[name="se_possui_alteracao_em_celulas_glandulares"][value="2"]');

const containerAlteracoesCelularesGlandularMicrobiologiaSim = document.querySelector('[data-js="container-alteracoes-celulares-glandulares-microbiologia-sim"]');
const alteracaoCelularGlandularMicrobiologia = document.querySelectorAll('[name="alteracao_celular_glandular_microbiologia_id[]"]');
const alteracaoCelularGlandularMicrobiologiaOutro = document.querySelector('[name="alteracao_celular_glandular_microbiologia_outro"]');

const containerAlteracoesCelularesGlandulares = document.querySelector('[data-js="container-alteracoes-celulares-glandulares"]');
const alteracaoCelularGlandular = document.querySelectorAll('[name="alteracao_em_celula_glandular_id[]"]');
const alteracaoCelularGlandularOutro = document.querySelector('[name="alteracao_celular_glandular_outro"]');

resultadoAlteracaoSim.addEventListener('change', ({target}) => {
    if (target.checked) {
        containerAlteracoesCelularesBenignaSimOuNao.style.display = 'block';
        enableInput(sePossuiAlteracaoCelularBenignaSim);
        enableInput(sePossuiAlteracaoCelularBenignaNao);

        containerAlteracoesCelularesEpiteliaisEscamosasSimOuNao.style.display = 'block';
        enableInput(sePossuiAlteracaoEpitelialEscamosaSim);
        enableInput(sePossuiAlteracaoEpitelialEscamosaNao);

        containerAlteracoesCelularesGlandularesSimOuNao.style.display = 'block';
        enableInput(sePossuiAlteracaoCelulaGlandularSim);
        enableInput(sePossuiAlteracaoCelulaGlandularNao);

        // ALTERAÇÕES NAO MICROBIOLOGIA

        containerResultadosConclusaoAlteracoesNaoMicrobiologia.style.display = 'none';
        Array.from(resultadoAlteracaoMicrobiologiaNao).forEach((element) => {
            disableInput(element);
        });
        disableInput(resultadoAlteracaoMicrobiologiaNaoOutro);
    }
});

sePossuiAlteracaoCelularBenignaSim.addEventListener('change', ({target}) => {
    if (target.checked) {
        containerAlteracoesCelularesBenignaSimMicrobiogia.style.display = 'block';
        Array.from(alteracaoCelularBenignaMicrobiologia).forEach((element) => {
            enableInput(element);
        });
        enableInput(alteracaoCelularBenignaMicrobiologiaOutro);

        containerAlteracoesCelularesBenignaReatividade.style.display = 'block';
        Array.from(alteracaoCelularBenignaReatividade).forEach((element) => {
            enableInput(element);
        });
        enableInput(alteracaoCelularBenignaReatividadeOutro);
        enableInput(containerAlteracoesCelularesBenignaMensagem);
    }
});

sePossuiAlteracaoCelularBenignaNao.addEventListener('change', ({target}) => {
    if (target.checked) {
        containerAlteracoesCelularesBenignaSimMicrobiogia.style.display = 'none';
        Array.from(alteracaoCelularBenignaMicrobiologia).forEach((element) => {
            disableInput(element);
        });
        disableInput(alteracaoCelularBenignaMicrobiologiaOutro);

        containerAlteracoesCelularesBenignaReatividade.style.display = 'none';
        Array.from(alteracaoCelularBenignaReatividade).forEach((element) => {
            disableInput(element);
        });
        disableInput(alteracaoCelularBenignaReatividadeOutro);
        disableInput(containerAlteracoesCelularesBenignaMensagem);
    }
});

sePossuiAlteracaoEpitelialEscamosaSim.addEventListener('change', ({target}) => {
    if (target.checked) {
        containerAlteracoesCelularesEpiteliaisEscamosasMicrobiologiaSim.style.display = 'block';
        Array.from(alteracaoCelularEpitelialMicrobiologia).forEach((element) => {
            enableInput(element);
        });
        enableInput(alteracaoCelularEpitelialMicrobiologiaOutro);

        containerAlteracoesCelularesEpiteliaisEscamosas.style.display = 'block';
        Array.from(alteracaoCelularEpitelialEscamosa).forEach((element) => {
            enableInput(element);
        });
        enableInput(alteracaoCelularEpitelialEscamosaOutro);
    }
});

sePossuiAlteracaoEpitelialEscamosaNao.addEventListener('change', ({target}) => {
    if (target.checked) {
        containerAlteracoesCelularesEpiteliaisEscamosasMicrobiologiaSim.style.display = 'none';
        Array.from(alteracaoCelularEpitelialMicrobiologia).forEach((element) => {
            disableInput(element);
        });
        disableInput(alteracaoCelularEpitelialMicrobiologiaOutro);

        containerAlteracoesCelularesEpiteliaisEscamosas.style.display = 'none';
        Array.from(alteracaoCelularEpitelialEscamosa).forEach((element) => {
            disableInput(element);
        });
        disableInput(alteracaoCelularEpitelialEscamosaOutro);
    }
});

sePossuiAlteracaoCelulaGlandularSim.addEventListener('change', ({target}) => {
    if (target.checked) {
        containerAlteracoesCelularesGlandularMicrobiologiaSim.style.display = 'block';
        Array.from(alteracaoCelularGlandularMicrobiologia).forEach((element) => {
            enableInput(element);
        });
        enableInput(alteracaoCelularGlandularMicrobiologiaOutro);

        containerAlteracoesCelularesGlandulares.style.display = 'block';
        Array.from(alteracaoCelularGlandular).forEach((element) => {
            enableInput(element);
        });
        enableInput(alteracaoCelularGlandularOutro);
    }
});

sePossuiAlteracaoCelulaGlandularNao.addEventListener('change', ({target}) => {
    if (target.checked) {
        containerAlteracoesCelularesGlandularMicrobiologiaSim.style.display = 'none';
        Array.from(alteracaoCelularGlandularMicrobiologia).forEach((element) => {
            disableInput(element);
        });
        disableInput(alteracaoCelularGlandularMicrobiologiaOutro);

        containerAlteracoesCelularesGlandulares.style.display = 'none';
        Array.from(alteracaoCelularGlandular).forEach((element) => {
            disableInput(element);
        });
        disableInput(alteracaoCelularGlandularOutro);
    }
});

function disableInput(element) {
    if (element.type == 'text') {
        element.value = '';
    } else {
        element.checked = false;
    }

    element.disabled = true;
}

function enableInput(element) {
    if (element.type != 'text') {
        element.checked = false;
    }

    element.disabled = false;
}

function loader(button) {
    setTimeout(() => {
        button.innerHTML = `<span class="spinner-border spinner-border-sm mr-2" 
            role="status" aria-hidden="true">
        </span>Aguarde...`;
        button.disabled = true;
    }, 20);

    setTimeout(() => {
        button.disabled = false;
        button.innerHTML = 'Salvar';
    }, 7000);
}

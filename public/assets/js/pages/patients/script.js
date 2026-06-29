$(document).ready(function() {
    const phones = [{ "mask": "(##) # ####-####"}, { "mask": "(##) # ####-####"}];
    $('#mobile').inputmask({ 
        mask: phones, 
        greedy: false, 
        definitions: { '#': { validator: "[0-9]", cardinality: 1}} });
        
    const cns = [{ "mask": "### #### #### ####"}, { "mask": "### #### #### ####"}];
    $('#cns').inputmask({ 
        mask: cns, 
        greedy: false, 
        definitions: { '#': { validator: "[0-9]", cardinality: 1}} });
    
    const cpf = [{ "mask": "###.###.###-##"}, { "mask": "###.###.###-##"}];
    $('#patient_cpf').inputmask({
        mask: cpf, 
        greedy: false, 
        definitions: { '#': { validator: "[0-9]", cardinality: 1}} });
});

function loader(button) {
    setTimeout(() => {
        button.innerHTML = `<span class="spinner-border spinner-border-sm mr-2" 
            role="status" aria-hidden="true">
        </span>Aguarde...`;
        button.disabled = true;
    }, 20);

    setTimeout(() => {
        button.disabled = false;
        button.innerHTML = 'Adicionar novo paciente';
    }, 7000);
}

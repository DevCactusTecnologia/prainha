$(document).ready(function() {
    const phones = [{ "mask": "(##) # ####-####"}, { "mask": "(##) # ####-####"}];
    $('#patientMobile').inputmask({ 
        mask: phones, 
        greedy: false, 
        definitions: { '#': { validator: "[0-9]", cardinality: 1}} 
    });
        
    const cns = [{ "mask": "### #### #### ####"}, { "mask": "### #### #### ####"}];
    $('#doctor_cns').inputmask({ 
        mask: cns, 
        greedy: false, 
        definitions: { '#': { validator: "[0-9]", cardinality: 1}} 
    });
    
    const cpf = [{ "mask": "###.###.###-##"}, { "mask": "###.###.###-##"}];
    $('#doctor_cpf').inputmask({
        mask: cpf, 
        greedy: false, 
        definitions: { '#': { validator: "[0-9]", cardinality: 1}} 
    });
});

// Profile photo
function triggerClick() {
    document.querySelector('#profile_photo').click();
}

function displayProfile(event) {
    if (event.files[0]) {
        const reader = new FileReader();
        reader.onload = (event) => { $('#profile_display').attr('src', event.target.result) }
        reader.readAsDataURL(event.files[0]);
    }
}

function loader(button) {
    setTimeout((button) => {
        button.disabled = true;
        button.innerHTML = (
            `<span class="spinner-border spinner-border-sm" 
                role="status" aria-hidden="true">
            </span> Aguarde...`
        );
    }, 10, button);

    setTimeout((button) => {
        button.disabled = false;
        button.innerHTML = 'Adicionar novo médico';
    }, 7000, button);
}
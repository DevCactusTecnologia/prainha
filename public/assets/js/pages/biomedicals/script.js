
const phones = [{ "mask": "(##) # ####-####"}, { "mask": "(##) # ####-####"}];
$('#mobile').inputmask({ 
    mask: phones, 
    greedy: false, 
    definitions: { '#': { validator: "[0-9]", cardinality: 1}} 
});
    
const cns = [{ "mask": "### #### #### ####"}, { "mask": "### #### #### ####"}];
$('#cns').inputmask({ 
    mask: cns, 
    greedy: false, 
    definitions: { '#': { validator: "[0-9]", cardinality: 1}} 
});

const cpf = [{ "mask": "###.###.###-##"}, { "mask": "###.###.###-##"}];
$('#cpf').inputmask({
    mask: cpf, 
    greedy: false, 
    definitions: { '#': { validator: "[0-9]", cardinality: 1}} 
});

function triggerClick() {
    const newProfilePhoto = document.querySelector('#new_profile_photo');
    if (newProfilePhoto) {
        newProfilePhoto.click();
    }
    
    const profilePhoto = document.querySelector('#profile_photo');
    if (profilePhoto) {
        profilePhoto.click();
    }
}

function displayProfile(event) {
    if (event.files[0]) {
        const reader = new FileReader();
        reader.onload = function(event) {
            $('#profile_display').attr('src', event.target.result)
        }

        reader.readAsDataURL(event.files[0]);
    }
}

function triggerClickSignature() {
    const newSignature = document.querySelector('#new_signature');
    if (newSignature) {
        newSignature.click();
    }

    const signature = document.querySelector('#signature');
    if (signature) {
        signature.click();
    }
}

function displayProfileSignature(event) {
    if (event.files[0]) {
        const reader = new FileReader();
        reader.onload = function(event) {
            $('#signature_display').attr('src', event.target.result)
        }

        reader.readAsDataURL(event.files[0]);
    }
}

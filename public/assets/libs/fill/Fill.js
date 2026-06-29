export let Fill = {

    data: {
        noNumbers: ',á,à,ã,â,a,Á,À,Ã,Â,A,b,B,c,C,ç,Ç,d,D,e,é,è,E,É,È,f,F,g,G,h,H,i,í,ì,I,Í,Ì,j,J,k,K,l,L,m,M,n,N,o,ó,ô,Ó,Ô,O,p,P,q,Q,r,R,s,S,t,T,ú,u,Ú,U,v,V,x,X,y,Y,w,W,z,Z,´,`,^,^^,~,~~,_,+,=,(,),¢,£,³,²,¹,\\,/,|,.§,{,},;,:,*,&,¨¨,¨,¬,%,$,#,@,!,\',",<,>,ª,º,[,],,,,Enter,',
        noLettersDefault: ',0,1,2,3,4,5,6,7,8,9,á,à,ã,â,Á,À,Ã,Â,ç,Ç,é,è,É,È,í,ì,Í,Ì,ó,ô,Ó,Ô,ú,Ú,´,`,^,^^,~,~~,_,+,=,(,),¢,£,³,²,¹,\\,/,|,.§,{,},;,:,*,&,¨¨,¨,¬,%,$,#,@,!,\',",<,>,ª,º,[,],,,,Enter,.,-,?,',
    },

    mask: function(object) {

        if (object.cpf) {
            this.cpf(object.cpf);
        }

        if (object.cnpj) {
            this.cnpj(object.cnpj);
        }

        if (object.nis) {
            this.nis(object.nis);
        }

        if (object.currencyBrl) {
            this.currencyBrl(object.currencyBrl);
        }

        if (object.cep) {
            this.cep(object.cep);
        }

    },

    cpf: function(cpfElement) {
        const cpf = document.querySelector(cpfElement);

        if (cpf) {
            const noNumber = this.data.noNumbers;
            cpf.addEventListener('keypress', function(event) {
                noNumber.includes(`,${event.key},`) ? event.preventDefault() : '';
            
                switch (this.value.length) {
                    case 3: this.value += '.'; break;
                    case 7: this.value += '.'; break;
                    case 11: this.value += '-'; break;
                }
            })
        }
    },

    cnpj: function(cnpjElement) {
        const cnpj = document.querySelector(cnpjElement);
        const noNumber = this.data.noNumbers;

        cnpj.addEventListener('keypress', function(event) {
            noNumber.includes(`,${event.key},`) ? event.preventDefault() : '';
        
            switch (this.value.length) {
                case 2: this.value += '.'; break;
                case 6: this.value += '.'; break;
                case 10: this.value += '/'; break;
                case 15: this.value += '-'; break;
            }
        })
    },

    nis: function(nisElement) {
        const nis = document.querySelector(nisElement);
        const noNumber = this.data.noNumbers;

        nis.addEventListener('keypress', function(event) {
            noNumber.includes(`,${event.key},`) ? event.preventDefault() : '';
        
            if (this.value.length === 10) {
                this.value += '-';
            }
        })
    },

    cep: function(cepElement) {
        const cep = document.querySelector(cepElement);

        if (cep) {
            const noNumber = this.data.noNumbers;
            cep.addEventListener('keypress', function(event) {
                noNumber.includes(`,${event.key},`) ? event.preventDefault() : '';
                switch (this.value.length) {
                    case 5:
                        this.value += '-';
                        break;
                }
            })
        }
    },

    currencyBrl: function(currencyBrlElement) {
        const currencyBrl = document.querySelector(currencyBrlElement);
        const noNumber = `${this.data.noNumbers}.,-,`;

        currencyBrl.addEventListener('keypress', function(event) {
            noNumber.includes(`,${event.key},`) ? event.preventDefault() : '';
        });

        currencyBrl.addEventListener('keyup', function(event) {
            let v = this.value.replace(/\D/g,'');

            v = (v/100).toFixed(2) + '';
            v = v.replace(".", ",");
            v = v.replace(/(\d)(\d{3})(\d{3}),/g, "$1.$2.$3,");
            v = v.replace(/(\d)(\d{3}),/g, "$1.$2,");
            this.value = v;
        })
    },

    onlyNumber: function(numberElement) {
        const number = document.querySelector(numberElement);

        if (number) {
            const noNumber = `${this.data.noNumbers}.,-,`;
            number.addEventListener('keypress', function(event) {
                noNumber.includes(`,${event.key},`) ? event.preventDefault() : '';
            })
        }
    },

    onlyLetter: function(letterElement) {
        const letter = document.querySelector(letterElement);

        if (letter) {
            letter.addEventListener('keydown', function(event) {
                return /[a-z ]/i.test(event.key) 
                    ? event.key 
                    : event.preventDefault();
            })
        }
    },
    
}

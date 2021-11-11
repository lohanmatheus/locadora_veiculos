function setVal(id, value) {
    document.getElementById(id).value = value;
}

function setMaskCPF(element) {
    element.value = element.value.replace(/\D/g, "").replace(/(\d{3})(\d)/, "$1.$2").replace(/(\d{3})(\d)/, "$1.$2").replace(/(\d{3})(\d{1,2})$/, "$1-$2")
}

function voltarTela(){
    window.history.back()
}

function mascara(o,f){
    v_obj=o
    v_fun=f
    setTimeout("execmascara()",1)
}

function execmascara(){
    v_obj.value=v_fun(v_obj.value)
}

function mtel(v){
    v=v.replace(/\D/g,""); //Remove tudo o que não é dígito
    v=v.replace(/^(\d{2})(\d)/g,"($1) $2"); //Coloca parênteses em volta dos dois primeiros dígitos
    v=v.replace(/(\d)(\d{4})$/,"$1-$2"); //Coloca hífen entre o quarto e o quinto dígitos
    return v;
}

function id( el ){
    return document.getElementById( el );
}

function startPageLoader(){
    window.document.body.innerHTML = `
        <!-- Preloader -->
        <div id="preloader">
            <div class="spinner"></div>
        </div>
        ${window.document.body.innerHTML}`;
}

function stopPageLoader(){
    window.setTimeout(() => { window.document.getElementById('preloader').remove() },360)
}

function selectHoraReserva(e){
    let selectReserva = document.getElementById("hora-reserva");
    if (e) {
        e = e.split(":")
        e = e[0] + ":" + e[1]
    }
    let bodyRequest = JSON.stringify({
        classe: 'user',
        acao: 'selectHora'
    })
    let configRequest = {
        method: 'POST',
        cache: 'default',
        body: bodyRequest,
        headers: {
            'Content-Type': 'application/json'
        }
    }
    let myRequest = new Request(`http://${location.host}/conexao.php`, configRequest);
    fetch(myRequest).then(function (response) {
        return response.json();
    }).then(response => {
        if (response.codigo === 1) {
            response.dados.forEach(registro => {

                let option = document.createElement("option");
                option.value = registro.id;
                option.text = registro.hora;
                selectReserva.appendChild(option);

                if(e === registro.hora){
                    selectReserva.selectedIndex = parseInt(registro.id);
                }

            });
        }
    })
}

function selectHoraEntrega(e){
    if (e) {
        e = e.split(":")
        e = e[0] + ":" + e[1]
    }

    let verificaSelect = document.getElementById('hora-entrega').getElementsByTagName('option');
    let selectEntrega = document.getElementById("hora-entrega");
    let selectReserva = document.getElementById('hora-reserva');
    let inputData = document.getElementById('data-entrega');
    let opcaoValor = selectReserva.options[selectReserva.selectedIndex].value

    if(opcaoValor === "0"){
        selectEntrega.disabled = true;
        inputData.disabled = true;
    }

    if(opcaoValor !== "0"){
        selectEntrega.removeAttribute("disabled")
        inputData.removeAttribute('disabled');

        let bodyRequest = JSON.stringify({
            classe: 'user',
            acao: 'selectHora'
        })
        let configRequest = {
            method: 'POST',
            cache: 'default',
            body: bodyRequest,
            headers: {
                'Content-Type': 'application/json'
            }
        }
        let myRequest = new Request(`http://${location.host}/conexao.php`, configRequest);
        fetch(myRequest).then(function (response) {
            return response.json();
        }).then(response => {
            if (response.codigo === 1) {
                response.dados.forEach(registro => {

                    let option = document.createElement("option");
                    option.value = registro.id;
                    option.text = registro.hora;
                    if (verificaSelect.length < 12) {
                        selectEntrega.appendChild(option);
                    }

                    if(e === registro.hora){
                        selectEntrega.selectedIndex = parseInt(registro.id);
                    }

                });
            }
        })
    }
}

$(function() {
    let dates = $( "#data-reserva, #data-entrega" ).datepicker({
        dateFormat: 'dd/mm/yy',
        minDate: new Date(),
        dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
        monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
        showOn: "button",
        buttonImage: "images/calendario.png",
        buttonImageOnly: true,
        showOtherMonths: true,
        selectOtherMonths: false,
        onSelect: function(selectedDate) {
            var option = this.id === "data-reserva" ? "minDate" : "maxDate",
                instance = $(this).data("datepicker"),
                date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
            dates.not(this).datepicker("option", option, date);
        }
    })
    $( ".ui-datepicker-trigger" ).css('cursor','pointer');
});

function navBarCadastroVeiculos(){
    document.getElementById('index').setAttribute('class','nav-link')
    document.getElementById('cadastro-veiculo').setAttribute('class','nav-link active')
    document.getElementById('lista-reservas').setAttribute('class', 'nav-link')
    document.getElementById('estoque-veiculos').setAttribute('class', 'nav-link')
}
function navBarIndex(){
    document.getElementById('index').setAttribute('class','nav-link  active')
    document.getElementById('cadastro-veiculo').setAttribute('class','nav-link')
    document.getElementById('lista-reservas').setAttribute('class', 'nav-link')
    document.getElementById('estoque-veiculos').setAttribute('class', 'nav-link')
}
function navBarListaReservas(){
    document.getElementById('index').setAttribute('class','nav-link')
    document.getElementById('cadastro-veiculo').setAttribute('class','nav-link')
    document.getElementById('lista-reservas').setAttribute('class', 'nav-link  active')
    document.getElementById('estoque-veiculos').setAttribute('class', 'nav-link')
}
function navBarEstoqueVeiculos(){
    document.getElementById('index').setAttribute('class','nav-link')
    document.getElementById('cadastro-veiculo').setAttribute('class','nav-link')
    document.getElementById('lista-reservas').setAttribute('class', 'nav-link')
    document.getElementById('estoque-veiculos').setAttribute('class', 'nav-link  active')
}

function navbarSupportedContent(){
     let menu = document.getElementById('navbarSupportedContent').getAttribute('class')
    if(menu === 'collapse navbar-collapse'){
        document.getElementById('navbarSupportedContent').setAttribute('class','navbar-collapse show collapse')
    }else if(menu === 'navbar-collapse show collapse'){
        document.getElementById('navbarSupportedContent').setAttribute('class','collapse navbar-collapse')
    }
}

$(document).ready(function () {
    $("#data-reserva, #data-entrega").mask("99/99/9999");
    $('#placa').mask('AAA 0U00', {
        translation: {
            'A': {
                pattern: /[A-Za-z]/
            },
            'U': {
                pattern: /[A-Za-z0-9]/
            },
        },
        onKeyPress: function (value, e, field, options) {
            // Convert to uppercase
            e.currentTarget.value = value.toUpperCase();

            // Get only valid characters
            let val = value.replace(/[^\w]/g, '');

            // Detect plate format
            let isNumeric = !isNaN(parseFloat(val[4])) && isFinite(val[4]);
            let mask = 'AAA 0U00';
            if (val.length > 4 && isNumeric) {
                mask = 'AAA-0000';
            }
            $(field).mask(mask, options);
        }
    });
});

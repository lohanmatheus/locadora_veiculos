const popularFormReservaVeiculo = (idVeiculo, idReserva) => {
    localStorage.removeItem("idVeiculo");
    localStorage.removeItem("idReserva");
    let dataReserva = localStorage.getItem("dataReserva")
    let horaReserva = localStorage.getItem("horaReserva")
    let dataEntrega = localStorage.getItem("dataEntrega")
    let horaEntrega = localStorage.getItem("horaEntrega")
    let verificaReserva = localStorage.getItem("verificaReserva")
    let verificaSelect = 'false'

    if(!localStorage.getItem("verificaReserva")){
        verificaSelect = "true";
        idReserva = 'undefined'
    }
    localStorage.removeItem("verificaReserva");

    if (idReserva === undefined) {
        idReserva = 'error'
        verificaReserva = null
    }

    let bodyRequest = JSON.stringify({
        classe: 'user',
        acao: 'selectVeiculoReserva',
        verificaSelect: verificaSelect,
        idReserva: idReserva,
        idVeiculo: idVeiculo
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
            if(verificaReserva){
                document.getElementById('tipo-reserva').options[response.dados.id_estado_reserva].selected = true
                setVal('id-reserva', response.dados.id_reserva)
            }
            document.getElementById('estado-veiculo').options[response.dados.id_estado_veiculo].selected = true
            selectHoraReserva(horaReserva)
            selectHoraEntrega(horaEntrega)
            setVal('id-veiculo', response.dados.id_veiculo)
            setVal('marca', response.dados.marca)
            setVal('model', response.dados.model)
            setVal('placa', response.dados.placa)
            setVal('ano', response.dados.ano)
            setVal('combustivel', response.dados.combustivel)
            setVal('data-reserva', dataReserva)
            setVal('data-entrega', dataEntrega)
            return false;
        }
        alert(response.msg);
    })

}

const verificarReserva = function (e) {
    e.preventDefault()
    let title = document.getElementById('title-reserva').innerHTML
    let tituloConfirm = "Efetuar reserva?"
    if(title === "Alterar Reserva") tituloConfirm = "Alterar reserva?";
    if (confirm(tituloConfirm)) {

        let idVeiculo = document.getElementById('id-veiculo').value

        let dataReserva = document.getElementById('data-reserva').value
        let horaReserva = document.getElementById('hora-reserva')
        horaReserva = horaReserva.options[horaReserva.selectedIndex].text
        let dataEntrega = document.getElementById('data-entrega').value
        let horaEntrega = document.getElementById('hora-entrega')
        horaEntrega = horaEntrega.options[horaEntrega.selectedIndex].text
        let tipoReserva = document.getElementById('tipo-reserva').value

        enviarReserva(idVeiculo, dataReserva, horaReserva, dataEntrega, horaEntrega, tipoReserva);
    }
}

function enviarReserva(idVeiculo, dataReserva, horaReserva, dataEntrega, horaEntrega, tipoReserva) {
    const params = {
        data: {
            id: idVeiculo,
            dataReserva: dataReserva,
            horaReserva: horaReserva,
            dataEntrega: dataEntrega,
            horaEntrega: horaEntrega,
            tipoReserva: tipoReserva
        },
        classe: 'user',
        acao: 'reservaVeiculo',
    }
    const id = document.getElementById('id-reserva').value || false;

    if (id !== false) {
        params['data']['id'] = parseInt(id);
        params['data']['idVeiculo'] = parseInt(idVeiculo);
        params['acao'] = 'alterarReserva';
    }

    let configRequest = {
        method: 'POST',
        cache: 'default',
        body: JSON.stringify(params),
        headers: {
            'Content-Type': 'application/json'
        }
    }

    let myRequest = new Request(`http://${location.host}/conexao.php`, configRequest);

    fetch(myRequest).then(response => {
        return response.json();
    }).then(function (response) {
        if (response.codigo === 1) {

            stopPageLoader();
            alert(response.msg)
            window.location.href = `http://${location.host}/lista_reservas.php`
            return;
        }
        alert(response.msg)

    })
    localStorage.removeItem("dataReserva")
    localStorage.removeItem("horaReserva")
    localStorage.removeItem("dataEntrega")
    localStorage.removeItem("horaEntrega")
}

function selectTipoReserva(){
    let verificaSelect = document.getElementById('tipo-reserva').getElementsByTagName('option');
    let select = document.getElementById("tipo-reserva");
    let verificaReserva = localStorage.getItem("verificaReserva");

    let bodyRequest = JSON.stringify({
        classe: 'user',
        acao: 'selectTipoReserva'
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
                option.text = registro.nome;

                if(verificaReserva){
                    select.appendChild(option)
                }else if(verificaSelect.length < 3) {
                    select.appendChild(option)
                }
            });
        }
    })
}
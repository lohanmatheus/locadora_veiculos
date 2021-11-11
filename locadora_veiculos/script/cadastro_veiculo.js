const popularFormCadastroVeiculo = (idVeiculo) => {
    localStorage.removeItem("idVeiculo");
    localStorage.removeItem("verificaVeiculo");

    let bodyRequest = JSON.stringify({
        classe: 'user',
        acao: 'selectVeiculo',
        id: idVeiculo
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
            document.getElementById('estado-veiculo').options[response.dados.id_estado_veiculo].selected = true
            setVal('id-veiculo', response.dados.id)
            setVal('marca', response.dados.marca)
            setVal('model', response.dados.model)
            setVal('placa', response.dados.placa)
            setVal('ano', response.dados.ano)
            setVal('combustivel', response.dados.combustivel)


            return false;
        }
        alert(response.msg);
    })

}

function selectEstadoVeiculo() {
    let select = document.getElementById("estado-veiculo");

    let bodyRequest = JSON.stringify({
        classe: 'user',
        acao: 'selectEstadoVeiculo'
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

                select.appendChild(option)

            });
        }
    })
}

const verificarCadastro = function (e) {
    e.preventDefault()
    if (confirm("Todos os dados estao corretos ?")) {
        let marca = document.getElementById('marca').value
        let model = document.getElementById('model').value
        let placa = document.getElementById('placa').value
        let ano = document.getElementById('ano').value
        let combustivel = document.getElementById('combustivel').value
        let estado = document.getElementById('estado-veiculo').value
        enviarCadastro(marca, model, placa, ano, combustivel, estado);
    }
}

function enviarCadastro(marca, model, placa, ano, combustivel, estado) {

    const params = {
        data: {
            marca: marca,
            model: model,
            placa: placa,
            ano: ano,
            combustivel: combustivel,
            estado: estado
        },
        classe: 'user',
        acao: 'insertVeiculo',
    }

    const idVeiculo = document.getElementById('id-veiculo').value || false;

    if (idVeiculo !== false) {
        params['data']['id'] = parseInt(idVeiculo);
        params['acao'] = 'alterarVeiculo';
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
            window.location.href = `http://${location.host}/estoque_veiculos.php`
            return;
        }
        alert(response.msg)

    })
}
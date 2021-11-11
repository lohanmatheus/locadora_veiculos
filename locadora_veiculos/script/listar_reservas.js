function listReservas() {

    const getTR = registro => {
        if(registro.nome === 'Cancelada'){
            return `<tr id="tr-${registro.id}">
                    <td>${registro.id}</td>
                    <td>${registro.marca}/${registro.model}</td>
                    <td>${registro.placa}</td>
                    <td>${registro.data_inicio}</td>
                    <td>${registro.data_fim}</td>
                    <td><button disabled class="btn-danger">${registro.nome}</button></td>                    
                    <td class="text-center"><button type="button" class="btn btn-sm btn-warning" 
                        onclick="alterarReserva('${registro.id}', '${registro.id_veiculo}', '${registro.data_inicio}','${registro.data_fim}')">Alterar
                        </button>  
                        <button type="button" class="btn btn-sm btn-danger" 
                        onclick="removerReserva('${registro.id}','${registro.marca}','${registro.model}')">Remover
                        </button> 
                    </td>
                </tr>`;
        } else if (registro.nome === 'Provisória'){
            return `<tr id="tr-${registro.id}">
                    <td>${registro.id}</td>
                    <td>${registro.marca}/${registro.model}</td>
                    <td>${registro.placa}</td>
                    <td>${registro.data_inicio}</td>
                    <td>${registro.data_fim}</td>
                    <td><button disabled class="btn-secondary">${registro.nome}</button></td>                    
                    <td class="text-center"><button type="button" class="btn btn-sm btn-warning" 
                        onclick="alterarReserva('${registro.id}', '${registro.id_veiculo}', '${registro.data_inicio}','${registro.data_fim}')">Alterar
                        </button>  
                        <button type="button" class="btn btn-sm btn-danger" 
                        onclick="removerReserva('${registro.id}','${registro.marca}','${registro.model}')">Remover
                        </button> 
                    </td>
                </tr>`;
        } else {
            return `<tr id="tr-${registro.id}">
                    <td>${registro.id}</td>
                    <td>${registro.marca}/${registro.model}</td>
                    <td>${registro.placa}</td>
                    <td>${registro.data_inicio}</td>
                    <td>${registro.data_fim}</td>
                    <td><button disabled class="btn-success">${registro.nome}</button></td>                    
                    <td class="text-center"><button type="button" class="btn btn-sm btn-warning" 
                        onclick="alterarReserva('${registro.id}', '${registro.id_veiculo}', '${registro.data_inicio}','${registro.data_fim}')">Alterar
                        </button>  
                        <button type="button" class="btn btn-sm btn-danger" 
                        onclick="removerReserva('${registro.id}','${registro.marca}','${registro.model}')">Remover
                        </button> 
                    </td>
                </tr>`;
        }
    }

    const getLoaginTr = function () {
        return `<tr>
                    <td colspan="8" style="text-align: center;">Carregando aguarde!!</td>
                </tr>`;
    }

    const params = {
        classe: 'user',
        acao: 'listarReservas',
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
    document.getElementById('list-reservas').innerHTML = getLoaginTr();

    fetch(myRequest).then(function (response) {
        return response.json();
    }).then(response => {
        if (response.codigo === 1) {
            document.getElementById('list-reservas').innerHTML = '';
            response.dados.forEach(registro => {
                document.getElementById('list-reservas').innerHTML += getTR(registro);
            })
            return false;
        }
        document.getElementById('list-reservas').innerHTML = `<tr>
                        <td colspan="8">${response.msg}</td>
                    </tr>`;
    })
}

const alterarReserva = (idReserva, idVeiculo, dataReserva, dataEntrega) => {
    let dataHoraReserva = dataReserva.split(" ");
    let dataHoraEntrega = dataEntrega.split(" ");
    if(!confirm(`Confirmar alteracao do id ${idReserva} ?`)){stopPageLoader(); return false;}

    localStorage.setItem("idReserva", idReserva)
    localStorage.setItem("idVeiculo", idVeiculo)
    localStorage.setItem("dataReserva", dataHoraReserva[0])
    localStorage.setItem("horaReserva", dataHoraReserva[1])
    localStorage.setItem("dataEntrega", dataHoraEntrega[0])
    localStorage.setItem("horaEntrega", dataHoraEntrega[1])
    localStorage.setItem("verificaReserva", "true");

    location.href = `http://${location.host}/reserva_veiculo.php`
}

function removerReserva(id, marca, model) {
    startPageLoader();
    if (id === '') {
        alert("Informe o ID")
        stopPageLoader();
        return;
    }

    if (!confirm(`Confirmar exclusao da reserva do veículo ${marca}-${model}, id ${id} ?`)){
        stopPageLoader();
        return;
    }

    let configRequest = {
        method: 'POST',
        cache: 'default',
        body: JSON.stringify({
            id,
            classe: 'user',
            acao: 'removeReserva'
        }),
        headers: {
            'Content-Type': 'application/json'
        }
    }

    let myRequest = new Request(`http://${location.host}/conexao.php`, configRequest);

    fetch(myRequest).then(response => {
        return response.json();
    }).then(function (response) {
        if(response.codigo === 0){
            alert(response.msg)
            stopPageLoader();
            return false;
        }
        alert(response.msg)
        document.getElementById(`tr-${id}`).remove()

        stopPageLoader();
    })
}
function listEstoqueVeiculos() {

    const getTR = registro => {
        if (registro.estado === 'Disponível') {
            return `<tr id="tr-${registro.id}">
                    <td>${registro.id}</td>
                    <td>${registro.marca}/${registro.model}</td>
                    <td>${registro.placa}</td>
                    <td>${registro.ano}</td>
                    <td><button class="btn-success" disabled>${registro.estado}</button></td>              
                    <td class="text-center"><button type="button" class="btn btn-sm btn-warning" 
                        onclick="alterarVeiculo('${registro.id}', '${registro.marca}','${registro.model}')">Alterar
                        </button> 
                        <button type="button" class="btn btn-sm btn-danger" 
                        onclick="removerVeiculo('${registro.id}','${registro.marca}','${registro.model}')">Remover
                        </button>  
                    </td>
                </tr>`;
        } else if (registro.estado === 'Manutenção'){
            return `<tr id="tr-${registro.id}">
                    <td>${registro.id}</td>
                    <td>${registro.marca}/${registro.model}</td>
                    <td>${registro.placa}</td>
                    <td>${registro.ano}</td>
                    <td><button class="btn-warning" disabled>${registro.estado}</button></td>              
                    <td class="text-center"><button type="button" class="btn btn-sm btn-warning" 
                        onclick="alterarVeiculo('${registro.id}', '${registro.marca}','${registro.model}')">Alterar
                        </button> 
                        <button type="button" class="btn btn-sm btn-danger" 
                        onclick="removerVeiculo('${registro.id}','${registro.marca}','${registro.model}')">Remover
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
        acao: 'listarEstoqueVeiculos',
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
    document.getElementById('estoque').innerHTML = getLoaginTr();

    fetch(myRequest).then(function (response) {
        return response.json();
    }).then(response => {
        if (response.codigo === 1) {
            document.getElementById('estoque').innerHTML = '';
            response.dados.forEach(registro => {
                document.getElementById('estoque').innerHTML += getTR(registro);
            })
            return false;
        }
        document.getElementById('estoque').innerHTML = `<tr>
                        <td colspan="8">${response.msg}</td>
                    </tr>`;
    })
}

const alterarVeiculo = (idVeiculo, marca, model) => {
    if(!confirm(`Confirmar alteracao do ${marca}-${model} id ${idVeiculo} ?`)){stopPageLoader(); return false;}

    localStorage.setItem("idVeiculo", idVeiculo)
    localStorage.setItem("verificaVeiculo", "true");

    location.href = `http://${location.host}/cadastro_veiculo.php`
}

function removerVeiculo(id, marca, model) {
    startPageLoader();
    if (id === '') {
        alert("Informe o ID")
        stopPageLoader();
        return;
    }

    if (!confirm(`Confirmar exclusao do cadastro do ${marca}-${model}, id ${id} ?`)){
        stopPageLoader();
        return;
    }

    let configRequest = {
        method: 'POST',
        cache: 'default',
        body: JSON.stringify({
            id,
            classe: 'user',
            acao: 'removeVeiculo'
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

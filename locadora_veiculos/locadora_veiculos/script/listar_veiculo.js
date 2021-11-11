const verificarCadastro = function (e) {
    e.preventDefault()
    let dataReserva = document.getElementById('data-reserva').value;
    let selectReserva = document.getElementById('hora-reserva');
    let horaReserva = selectReserva.options[selectReserva.selectedIndex].text;
    let dataEntrega = document.getElementById('data-entrega').value;
    let selectEntrega = document.getElementById('hora-entrega');
    let horaEntrega = selectEntrega.options[selectEntrega.selectedIndex].text;

    localStorage.setItem("dataReserva", dataReserva)
    localStorage.setItem("horaReserva", horaReserva)
    localStorage.setItem("dataEntrega", dataEntrega)
    localStorage.setItem("horaEntrega", horaEntrega)

    listarVeiculos(dataReserva, horaReserva, dataEntrega, horaEntrega);
}

function listarVeiculos(dataReserva, horaReserva, dataEntrega, horaEntrega){
    document.getElementById("tabela-veiculos").style.display = "block";

    const getTR = registro => {
        return `<tr id="tr-${registro.id}">
                    <td>${registro.id}</td>
                    <td>${registro.marca}/${registro.model}</td>
                    <td>${registro.placa}</td>
                    <td>${registro.ano}</td>
                    <td>${registro.combustivel}</td>
                    <td><button type="button" class="btn btn-sm btn-success" disabled>${registro.estado}</button></td>                    
                    <td><button type="button" class="btn btn-sm btn-primary"
                     onclick="reservaVeiculo('${registro.model}','${registro.id}'); selectHoraReserva();">Reservar
                     </button></td>                    
                </tr>`;
    }

    const getLoaginTr = function () {
        return `<tr>
                    <td colspan="8" style="text-align: center;">Carregando aguarde!!</td>
                </tr>`;
    }

    const params = {
        data: {
            dataReserva,
            horaReserva,
            dataEntrega,
            horaEntrega
        },
        classe: 'user',
        acao: 'listarVeiculos',
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
    document.getElementById('list-veiculos').innerHTML = getLoaginTr();
    fetch(myRequest).then(function (response) {
        return response.json();
    }).then(response => {
        if (response.codigo === 1) {
            document.getElementById('list-veiculos').innerHTML = '';
            response.dados.forEach(registro => {
                document.getElementById('list-veiculos').innerHTML += getTR(registro);
            })
            return false;
        }

        document.getElementById('list-veiculos').innerHTML = `<tr>
                        <td colspan="8">${response.msg}</td>
                    </tr>`;
    })
}

const reservaVeiculo = (model, id) => {
    if(!confirm(`Confirmar reserva do veiculo ${model} id ${id} ?`)){stopPageLoader(); return false;}
    localStorage.setItem("idVeiculo", id)
    location.href = `http://${location.host}/reserva_veiculo.php`
}
<?php
require_once(__DIR__ . './header/header.php');

pageHeader('Reserva do Veículo');
?>
<div id="wrapper">
<section class="container" >
    <h1 class="font-size-35" id="title-reserva">Reserva do Veículo</h1>
    <div id="insert-container" style="display: block; width: 100%;">
        <button type="button" class="btn btn-sm btn-close" onclick="window.history.back()"></button>
        <form method="post" id='insert-form-reserva' onsubmit="return false;" >
            <div class="form-group col-md-12 col-lg-6 mb-1">
                <input id="id-veiculo" value="" type="hidden">
                <input id="id-reserva" value="" type="hidden">
                <label for="marca">Marca:</label>
                <input type="text" id="marca" class="form-control" placeholder="Digite o modelo do veículo..." disabled required>
            </div>

            <div class="form-group col-md-12 col-lg-6 mb-1">
                <label for="model">Modelo:</label>
                <input type="text" class="form-control" name="model" id="model"
                       placeholder="Digite a Placa do veículo..." disabled required >
            </div>

            <div class="form-group col-md-12 col-lg-6 mb-1">
                <label for="placa">Placa:</label>
                <input type="text" class="form-control" name="placa" id="placa"
                       placeholder="AAA-0000" disabled required >
            </div>

            <div class="form-group col-md-12 col-lg-6 mb-1">
                <label for="ano">ANO:</label>
                <input type="text" class="form-control" name="ano" id="ano" disabled required
                       placeholder="Digite o ano do veículo...">
            </div>

            <div class="form-group col-md-12 col-lg-6 mb-1">
                <label for="combustivel">Combustível:</label>
                <input type="text" class="form-control" name="combustivel" id="combustivel" disabled required
                       placeholder="Gasolina, Álcool, Flex...">
            </div>

            <div class="form-control-lg">
                <label for="data-entrega">Data da reserva:</label><br/>
                <input style="border-radius:10px; " type="text" id="data-reserva"
                       placeholder="Data da reserva" required/>

                <label for="hora-reserva">
                    <select id="hora-reserva" class="form-control drivers-options"
                            style="border-radius: 10px; cursor: pointer;" required>
                        <option value="">00:00</option>
                    </select>
                </label>
            </div>

            <div class="form-control-lg">
                <label for="data-entrega">Data da entrega:</label><br/>
                <input style="border-radius:10px " type="text" id="data-entrega"
                       placeholder="Data da entrega" required/>
                <label for="hora-entrega">
                    <select id="hora-entrega" class="form-control drivers-options"
                            style="border-radius: 10px; cursor: pointer;" required>
                        <option value="">00:00</option>
                    </select>
                </label>
            </div>

            <br/>
            <div class="form-group col-md-12 col-lg-6 mb-1">
                <label for="tipo-reserva">Tipo da Reserva:</label>
                <select id="tipo-reserva" name="tipo-reserva" class="form-control-sm" required>
                    <option value="" selected>Selecione o tipo da reserva: </option>
                </select>
            </div>

            <br/>
            <div class="form-group col-md-12 col-lg-6 mb-1">
                <label for="estado-veiculo">Estado do Veiculo:</label>
                <select id="estado-veiculo" name="estado-veiculo" class="form-control-sm" required disabled>
                    <option value="" selected>Selecione o estado do veículo</option>
                </select>
            </div>

            <br/>

            <br/>
            <div>
                <button type="button" class="btn btn-sm btn-secondary" onclick="window.history.back()">Cancelar</button>
                <button id="btn-submit" type="submit" class="btn btn-sm btn-success">Reservar</button>
            </div>

        </form>
    </div>
</section>
</div>
<?php
require_once(__DIR__ . './footer/footer.php');
?>
<script src="script/cadastro_veiculo.js"></script>
<script src="script/reserva_veiculo.js"></script>
<script>
    window.onload = function(){
        if (localStorage.getItem("verificaReserva")){
            document.getElementById('title-reserva').innerHTML = "Alterar Reserva"
            document.getElementById('btn-submit').innerHTML = "Alterar"
        }

        document.getElementById(`insert-form-reserva`).addEventListener(`submit`, verificarReserva)

        selectEstadoVeiculo()
        selectTipoReserva()
        let idReserva = localStorage.getItem("idReserva") || '';
        let idVeiculo = localStorage.getItem("idVeiculo") || '';
        if (idVeiculo.length > 0) {
            if (idReserva > 0 ){
                popularFormReservaVeiculo(idVeiculo, idReserva)
                localStorage.setItem("verificaReserva", "true")
            }else {
                popularFormReservaVeiculo(idVeiculo)
                localStorage.removeItem("verificaReserva");
            }
        }
        stopPageLoader()
    }

</script>
</body>
</html>

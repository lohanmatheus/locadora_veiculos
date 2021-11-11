<?php
require_once(__DIR__ . './header/header.php');

pageHeader('Cadastro de Veículos');
?>
<section class="container" >
    <h1 class="font-size-35" id="title-cadastro">Cadastrar Veículo</h1>
    <div id="insert-container" style="display: block; width: 100%;">
        <button type="button" class="btn btn-sm btn-close" onclick="voltarTela()"></button>
        <form method="post" id='insert-form' onsubmit="return false;" >
            <div class="form-group col-md-12 col-lg-6 mb-1">
                <input id="id-veiculo" value="" type="hidden">
                <label for="marca">Marca:</label>
                <input type="text" id="marca" class="form-control" placeholder="Digite o modelo do veículo..." required>
            </div>

            <div class="form-group col-md-12 col-lg-6 mb-1">
                <label for="model">Modelo:</label>
                <input type="text" class="form-control" name="model" id="model"
                       placeholder="Digite o modelo do veículo..." required >
            </div>

            <div class="form-group col-md-12 col-lg-6 mb-1">
                <label for="placa">Placa:</label>
                <input type="text" class="form-control" name="placa" id="placa"
                       placeholder="AAA-0000 AAA9A99" maxlength="8" minlength="6" required >
            </div>

            <div class="form-group col-md-12 col-lg-6 mb-1">
                <label for="ano">ANO:</label>
                <input type="text" class="form-control" name="ano" id="ano" required="required"
                       placeholder="Digite o ano do veículo...">
            </div>

            <div class="form-group col-md-12 col-lg-6 mb-1">
                <label for="combustivel">Combustível:</label>
                <input type="text" class="form-control" name="combustivel" id="combustivel" required="required"
                       placeholder="Gasolina, Álcool, Flex...">
            </div>

            <br/>
            <div class="form-group col-md-12 col-lg-6 mb-1">
                <label for="estado-veiculo">Estado:</label>
                <select id="estado-veiculo" name="estado-veiculo" class="form-control-sm" required>
                    <option value="" selected>Selecione o estado do veículo</option>
                </select>
            </div>

            <br/>

            <br/>
            <div>
                <button type="button" class="btn btn-sm btn-secondary" onclick="voltarTela()">Cancelar</button>
                <button id="btn-submit" type="submit" class="btn btn-sm btn-success">Salvar</button>
            </div>

        </form>
    </div>
</section>
<?php
require_once(__DIR__ . './footer/footer.php');
?>
<script src="script/cadastro_veiculo.js"></script>
<script>
    window.onload = function(){
        navBarCadastroVeiculos()
        if (localStorage.getItem("verificaVeiculo")){
            document.getElementById('title-cadastro').innerHTML = "Alterar Veiculo"
            document.getElementById('btn-submit').innerHTML = "Alterar"
        }

        document.getElementById(`insert-form`).addEventListener(`submit`, verificarCadastro)

        selectEstadoVeiculo()

        let idVeiculo = localStorage.getItem("idVeiculo") || '';
        if (idVeiculo.length > 0) {
            popularFormCadastroVeiculo(idVeiculo)
        }
        stopPageLoader()
    }

</script>
</body>
</html>

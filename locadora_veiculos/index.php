<?php
require_once(__DIR__ .'./header/header.php');

pageHeader('Veículos Cadastrados');
?>
<section class="container-fluid">
        <div class='col-sm-6 container-fluid text-center' >
            <br/>
            <h1 class="text-center font-size-35">Data e Hora da sua reserva</h1>
            <br/>
            <form method="post" id='list-form' onsubmit="return false;" autocomplete="off">
                <div class="form-control-lg">
                    <input style="border-radius:10px; " type="text" id="data-reserva"
                           placeholder="Data da reserva" required/>

                    <label>
                        <select id="hora-reserva" class="form-control drivers-options"
                                style="border-radius: 10px; cursor: pointer;" onblur="selectHoraEntrega()" required>
                            <option value="">00:00</option>
                        </select>
                    </label>
                </div>

                <br/>
                <div class="form-control-lg">
                    <input style="border-radius:10px " type="text" id="data-entrega"
                           placeholder="Data da entrega" disabled="disabled" required/>
                    <label>
                        <select id="hora-entrega" class="form-control drivers-options"
                                style="border-radius: 10px; cursor: pointer;"
                                disabled="disabled" required>
                            <option value="">00:00</option>
                        </select>
                    </label>
                </div>
                <br/>
                <button class="btn btn-outline-primary" type="button"
                        onclick="window.location='cadastro_veiculo.php'">Cadastrar Veículo
                </button>
                <button class="btn btn-outline-success" type="submit">Buscar
                </button>
            </form>
        </div>
    <br/>
    <div id="tabela-veiculos" style="display: none;">
        <div class="row" id="tabela-veiculos">
            <div class="col-12">
                <div id="product-request-adm" class="card" style="">
                    <div class="card-header pt-3">
                        <div style="float: left;">
                            <h3 class="card-title">Veículos Disponíveis</h3>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div id="area-menu-request-adm" style="display: block" class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="bg-secondary text-light text-uppercase">
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Marca/Modelo</th>
                                    <th scope="col">Placa</th>
                                    <th scope="col">Ano</th>
                                    <th scope="col">Combustível</th>
                                    <th scope="col">STATUS</th>
                                    <th scope="col" colspan="2">Opcoes</th>
                                </tr>
                                </thead>
                                <tbody id="list-veiculos">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-foot p-1" style="text-align: right;">

                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
<?php
require_once(__DIR__ . './footer/footer.php');
?>
<script src="script/listar_veiculo.js"></script>

<script>
    window.onload = function(){
        navBarIndex()
        document.getElementById(`list-form`).addEventListener(`submit`, verificarCadastro)

        selectHoraReserva()
        stopPageLoader()
    }
</script>

</body>
</html>
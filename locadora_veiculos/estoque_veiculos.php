<?php
require_once(__DIR__ .'./header/header.php');

pageHeader('Estoque de Veículos');
?>
<section class="container-fluid">
    <div class='col-sm-6 container-fluid text-center' >
        <br/>
        <h1 class="font-size-35 text-center" >Estoque de Veículos</h1>
    </div>
    <div id="tabela-estoque" style="display: block;">
        <div class="row" id="tabela-lista-reserva">
            <div class="col-12">
                <div id="list-estoque" class="card" style="">
                    <div class="card-header pt-3">
                        <div style="float: left;">
                            <h3 class="card-title">Veículos</h3>
                        </div>
                        <div style="float: right;">
                            <button type="button" class="btn btn-primary"
                                    onclick="window.location='cadastro_veiculo.php'">Cadastrar Veículo
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div id="area-menu-estoque" style="display: block" class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="bg-darkslateblue text-light text-uppercase">
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Veículo</th>
                                    <th scope="col">Placa</th>
                                    <th scope="col">Ano</th>
                                    <th scope="col">Estatus do Veiculos</th>
                                    <th scope="col" colspan="2" class="text-center">Opcoes</th>
                                </tr>
                                </thead>
                                <tbody id="estoque">
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
<script src="script/listar_estoque.js"></script>
<script>
    window.onload = function(){
        navBarEstoqueVeiculos()
        listEstoqueVeiculos()
        stopPageLoader()
    }
</script>
</body>
</html>

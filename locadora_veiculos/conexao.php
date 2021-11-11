<?php
include 'User.php';
$parametros = (array)json_decode(file_get_contents('php://input'),true);
$link = pg_connect("host=localhost port=5432 dbname=locadora_veiculos user=postgres password=2032418202lo");

if(empty($parametros)){
    $parametros = $_REQUEST;
}

if ($parametros['classe'] == 'user') {
    $usuario = new User($link, $parametros);

    switch ($parametros['acao']){

        case "selectEstadoVeiculo":
            $arrayResposta = $usuario->selectEstadoVeiculo();
            break;

        case "insertVeiculo":
            $arrayResposta = $usuario->insertVeiculo();
            break;

        case "selectHora":
            $arrayResposta = $usuario->selectHora();
            break;

        case "listarVeiculos":
            $arrayResposta = $usuario->listarVeiculos();
            break;

        case "selectVeiculo":
            $arrayResposta = $usuario->selectVeiculo();
            break;

        case "selectVeiculoReserva":
            $arrayResposta = $usuario->selectVeiculoReserva();
            break;

        case "reservaVeiculo":
            $arrayResposta = $usuario->reservaVeiculo();
            break;

        case "selectTipoReserva":
            $arrayResposta = $usuario->selectTipoReserva();
            break;

        case "listarReservas":
            $arrayResposta = $usuario->listarReservas();
            break;

        case "alterarReserva":
            $arrayResposta = $usuario->alterarReserva();
            break;

        case "removeReserva":
            $arrayResposta = $usuario->removeReserva();
            break;

        case "listarEstoqueVeiculos":
            $arrayResposta = $usuario->listarEstoqueVeiculos();
            break;

        case "alterarVeiculo":
            $arrayResposta = $usuario->alterarVeiculo();
            break;

        case "removeVeiculo":
            $arrayResposta = $usuario->removeVeiculo();
            break;

        default:
            echo 0;
    }
    echo json_encode($arrayResposta);
    exit;
}
echo json_encode([
    'codigo' => 0,
    'msg' => 'Informe uma acao e ferramenta para utilizar a api!',
    'dados' => []
]);
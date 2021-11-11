<?php

class User
{
    private $dbConnect = null;
    private $parametros = null;

    public function __construct($dbConnect, $parametros)
    {
        if ($parametros)
            $this->parametros = $parametros;

        if ($dbConnect)
            $this->dbConnect = $dbConnect;
    }

    public function selectEstadoVeiculo()
    {
        $query = "SELECT * FROM locadora_veiculos.locadora.estado_veiculo ORDER BY id";

        try {
            $result = pg_query($this->dbConnect, $query);

            if (!$result) {
                return [
                    'codigo' => 0,
                    'msg' => 'Registro nao encontrado!',
                    'dados' => []
                ];
            }


            $resultSet = [];
            while ($row = pg_fetch_assoc($result)) {
                $resultSet[] = $row;
            }

            return [
                'codigo' => 1,
                'msg' => 'Registros selecionado com sucesso!',
                'dados' => $resultSet
            ];


        } catch (\Exception $exception) {
            return [
                'codigo' => 0,
                'msg' => $exception->getMessage()
            ];
        }
    }

    public function insertVeiculo()
    {
        $dataUser = (array)$this->parametros['data'];
        $marca = filter_var($dataUser['marca'], FILTER_SANITIZE_SPECIAL_CHARS);
        $model = filter_var($dataUser['model'], FILTER_SANITIZE_SPECIAL_CHARS);
        $placa = filter_var($dataUser['placa'], FILTER_SANITIZE_SPECIAL_CHARS);
        $ano = filter_var($dataUser['ano'], FILTER_SANITIZE_SPECIAL_CHARS);
        $combustivel = filter_var($dataUser['combustivel'], FILTER_SANITIZE_SPECIAL_CHARS);
        $id_estado = filter_var($dataUser['estado'], FILTER_SANITIZE_SPECIAL_CHARS);

        if ($id_estado == 'Selecione o estado do veículo') {
            return [
                'codigo' => 0,
                'msg' => 'Selecione o estado do veículo!',
                'dados' => []
            ];
        }

        $queryPlaca = "SELECT * FROM locadora_veiculos.locadora.veiculo WHERE placa = '$placa' ";


        try {
            $resultPlaca = pg_query($this->dbConnect, $queryPlaca);

            if (pg_affected_rows($resultPlaca) > 0) {
                return [
                    'codigo' => 0,
                    'msg' => 'Placa ja existe no sistema!',
                    'dados' => []
                ];
            }

        } catch (\Exception $exception) {
            return [
                'codigo' => 0,
                'msg' => 'Falha na verificação de veículos!',
                'dados' => []
            ];
        }

        $lista = [$marca, $model, $placa, $ano, $id_estado];

        foreach ($lista as $item) {
            if (empty($item)) {
                return [
                    'codigo' => 0,
                    'msg' => 'Campos obrigatorios nao preenchidos!',
                    'dados' => []
                ];
            }
        }

        $queryInsertVeiculo = "INSERT INTO
            locadora_veiculos.locadora.veiculo(marca, model, placa, ano, combustivel, id_estado_veiculo)
            VALUES ('$marca', '$model', '$placa', '$ano', '$combustivel', '$id_estado')";

        try {
            $resultInsertVeiculo = pg_query($this->dbConnect, $queryInsertVeiculo);

            if (!$resultInsertVeiculo) {
                return [
                    'codigo' => 0,
                    'msg' => 'Erro ao inserir dados do Cliente!',
                    'dados' => []
                ];
            }

            return [
                'codigo' => 1,
                'msg' => 'Registro inserido com sucesso!',
                'dados' => []
            ];

        } catch (\Exception $exception) {
            return [
                'codigo' => 0,
                'msg' => $exception->getMessage(),
                'dados' => []
            ];
        }
    }

    public function selectHora()
    {
        $query = "SELECT * FROM locadora_veiculos.locadora.hora ORDER BY id";

        try {
            $result = pg_query($this->dbConnect, $query);

            if (!$result) {
                return [
                    'codigo' => 0,
                    'msg' => 'Registro nao encontrado!',
                    'dados' => []
                ];
            }


            $resultSet = [];
            while ($row = pg_fetch_assoc($result)) {
                $resultSet[] = $row;
            }

            return [
                'codigo' => 1,
                'msg' => 'Registros selecionado com sucesso!',
                'dados' => $resultSet
            ];


        } catch (\Exception $exception) {
            return [
                'codigo' => 0,
                'msg' => $exception->getMessage()
            ];
        }
    }

    public function listarVeiculos(){

        $dataUser = (array)$this->parametros['data'];
        $dataReserva = filter_var($dataUser['dataReserva'], FILTER_SANITIZE_SPECIAL_CHARS);
        $horaReserva = filter_var($dataUser['horaReserva'], FILTER_SANITIZE_SPECIAL_CHARS);
        $dataEntrega = filter_var($dataUser['dataEntrega'], FILTER_SANITIZE_SPECIAL_CHARS);
        $horaEntrega = filter_var($dataUser['horaEntrega'], FILTER_SANITIZE_SPECIAL_CHARS);

        $novaDataReserva = array_reverse(explode("/", $dataReserva));
        $novaDataReserva = implode("-", $novaDataReserva);
        $dataHoraReserva = $novaDataReserva . " " . $horaReserva;

        $novaDataEntrega = array_reverse(explode("/", $dataEntrega));
        $novaDataEntrega = implode("-", $novaDataEntrega);
        $dataHoraEntrega = $novaDataEntrega . " " . $horaEntrega;

        $queryVerificaReserva = "SELECT v.id AS id_veiculo
                                    FROM locadora_veiculos.locadora.reserva
                                    JOIN locadora_veiculos.locadora.veiculo v on v.id = reserva.id_veiculo
                                 WHERE (reserva.data_inicio between '$dataHoraReserva'::timestamp and '$dataHoraEntrega'::timestamp
                                    OR reserva.data_fim between '$dataHoraReserva'::timestamp and '$dataHoraEntrega'::timestamp)
                                    and reserva.id_estado_reserva != 3
                                    and v.id_estado_veiculo != 2
                                 ORDER BY reserva.id";

        try {
            $resultVerificaReserva = pg_query($this->dbConnect, $queryVerificaReserva);

            $idVeiculosReservados = [];
            while ($row = pg_fetch_assoc($resultVerificaReserva)) {
                $idVeiculosReservados[] = $row;
            }

            if ($idVeiculosReservados) {
                $contador = count($idVeiculosReservados);
                $arrayIds = [];
                for ($c=0; $c<$contador; $c++){
                    $arrayIds[] = $idVeiculosReservados[$c]['id_veiculo'];
                }

                $idVeiculoArray = '(' . implode(',', $arrayIds) . ')';

                $query = "SELECT veiculo.*, ev.nome AS estado FROM locadora_veiculos.locadora.veiculo
                            JOIN locadora_veiculos.locadora.estado_veiculo ev on ev.id = veiculo.id_estado_veiculo
                            WHERE veiculo.id NOT IN $idVeiculoArray and veiculo.id_estado_veiculo != 2
                          ORDER BY veiculo.id";

                $resultQueryVeiculos = pg_query($this->dbConnect, $query);
                if (pg_affected_rows($resultQueryVeiculos) <= 0) {
                    return [
                        'codigo' => 0,
                        'msg' => "Nenhum registro encontrado no sistema!",
                        'dados' => []
                    ];
                }

                $resultVeiculo = [];
                while ($rowVeiculo = pg_fetch_assoc($resultQueryVeiculos)) {
                    $resultVeiculo[] = $rowVeiculo;
                }

                return [
                    'codigo' => 1,
                    'msg' => 'Listado com sucesso!',
                    'dados' => $resultVeiculo
                ];

            } else {
                $query = "SELECT veiculo.*, ev.nome AS estado FROM locadora_veiculos.locadora.veiculo
                            JOIN locadora_veiculos.locadora.estado_veiculo ev on ev.id = veiculo.id_estado_veiculo                            
                          ORDER BY veiculo.id";


                $result = pg_query($this->dbConnect, $query);
                if (pg_affected_rows($result) <= 0) {
                    return [
                        'codigo' => 0,
                        'msg' => "Nenhum registro encontrado no sistema!",
                        'dados' => []
                    ];
                }

                $resultVeiculo = [];
                while ($row = pg_fetch_assoc($result)) {
                    $resultVeiculo[] = $row;
                }

                return [
                    'codigo' => 1,
                    'msg' => 'Listado com sucesso!',
                    'dados' => $resultVeiculo
                ];
            }

        } catch (\Exception $exception) {
            return [
                'codigo' => 0,
                'msg' => $exception->getMessage(),
                'dados' => []
            ];
        }
    }

    public function selectVeiculo()
    {
        $idVeiculo = (int)$this->parametros['id'];

        $query = "SELECT veiculo.*, ev.nome
                    FROM locadora_veiculos.locadora.veiculo
                    JOIN locadora_veiculos.locadora.estado_veiculo ev on ev.id = veiculo.id_estado_veiculo
                  WHERE veiculo.id = '$idVeiculo'";
        try {
            $result = pg_query($this->dbConnect, $query);
            if (pg_affected_rows($result) < 1) {
                return [
                    'codigo' => 0,
                    'msg' => 'Registro nao encontrado!',
                    'dados' => []
                ];
            }

            $row = pg_fetch_assoc($result);

            return [
                'codigo' => 1,
                'msg' => 'Registro selecionado com sucesso!',
                'dados' => $row
            ];

        } catch (\Exception $exception) {
            return [
                'codigo' => 0,
                'msg' => $exception->getMessage(),
                'dados' => []
            ];
        }
    }

    public function selectVeiculoReserva()
    {
        $idVeiculo = (int)$this->parametros['idVeiculo'];
        $idReserva = $this->parametros['idReserva'];
        $verificaSelect = $this->parametros['verificaSelect'];

        if($verificaSelect !== 'false' or $idReserva == 'error'){
            $query = "SELECT veiculo.*, veiculo.id as id_veiculo, ev.nome
                         FROM locadora_veiculos.locadora.veiculo
                         JOIN locadora_veiculos.locadora.estado_veiculo ev on ev.id = veiculo.id_estado_veiculo
                      WHERE veiculo.id = '$idVeiculo'";
        }else{
            $query = "SELECT veiculo.*, r.id_estado_reserva, r.id_veiculo, r.id as id_reserva FROM locadora_veiculos.locadora.veiculo
                    JOIN locadora_veiculos.locadora.reserva r on veiculo.id = r.id_veiculo
                    WHERE r.id = '$idReserva'";
        }

        try {
            $result = pg_query($this->dbConnect, $query);
            if (pg_affected_rows($result) < 1) {
                return [
                    'codigo' => 0,
                    'msg' => 'Registro nao encontrado!',
                    'dados' => []
                ];
            }

            $row = pg_fetch_assoc($result);

            return [
                'codigo' => 1,
                'msg' => 'Registro selecionado com sucesso!',
                'dados' => $row
            ];

        } catch (\Exception $exception) {
            return [
                'codigo' => 0,
                'msg' => $exception->getMessage(),
                'dados' => []
            ];
        }
    }

    public function reservaVeiculo()
    {
        $dataUser = (array)$this->parametros['data'];
        $idVeiculo = filter_var($dataUser['id'], FILTER_SANITIZE_SPECIAL_CHARS);
        $dataReserva = filter_var($dataUser['dataReserva'], FILTER_SANITIZE_SPECIAL_CHARS);
        $horaReserva = filter_var($dataUser['horaReserva'], FILTER_SANITIZE_SPECIAL_CHARS);
        $dataEntrega = filter_var($dataUser['dataEntrega'], FILTER_SANITIZE_SPECIAL_CHARS);
        $horaEntrega = filter_var($dataUser['horaEntrega'], FILTER_SANITIZE_SPECIAL_CHARS);
        $tipoReserva = filter_var($dataUser['tipoReserva'], FILTER_SANITIZE_SPECIAL_CHARS);

        $dataReservaVerificar = str_replace('/','-', $dataReserva);
        $dataEntregaVerificar = str_replace('/','-', $dataEntrega);
        $horaReservaVerificar = $horaReserva . ":00";
        $horaEntregaVerificar = $horaEntrega . ":00";

        if(strtotime($dataReservaVerificar) > strtotime($dataEntregaVerificar)){
            return [
                'codigo' => 0,
                'msg' => 'A data de reserva nao pode ser maior que a de entrega!',
                'dados' => []
            ];
        }

        if( strtotime($dataReservaVerificar) == strtotime($dataEntregaVerificar) and
            strtotime($horaReservaVerificar) >= strtotime($horaEntregaVerificar)){
            return [
                'codigo' => 0,
                'msg' => 'Verifique a hora da Reserva!',
                'dados' => []
            ];
        }

        $lista = [$idVeiculo, $dataReserva, $horaReserva,
                  $dataEntrega, $horaEntrega, $tipoReserva];

        foreach ($lista as $linha) {
            if (empty($linha)) {
                return [
                    'codigo' => 0,
                    'msg' => 'Campos de confirmação da reserva nao Recebidos!',
                    'dados' => []
                ];
            }
        }
        $novaDataReserva = array_reverse(explode("/", $dataReserva));
        $novaDataReserva = implode("-", $novaDataReserva);
        $dataHoraReserva = $novaDataReserva." ".$horaReserva;

        $novaDataEntrega = array_reverse(explode("/", $dataEntrega));
        $novaDataEntrega = implode("-", $novaDataEntrega);
        $dataHoraEntrega = $novaDataEntrega." ".$horaEntrega;

        $queryInsertReserva = "INSERT INTO locadora_veiculos.locadora.reserva
                                (data_inicio, data_fim, id_veiculo, id_estado_reserva) 
                                VALUES 
                                ('$dataHoraReserva', '$dataHoraEntrega', '$idVeiculo', '$tipoReserva')";

        try {
            $resultInsertReserva = pg_query($this->dbConnect, $queryInsertReserva);
            if (!$resultInsertReserva) {
                return [
                    'codigo' => 0,
                    'msg' => pg_errormessage($this->dbConnect),
                    'dados' => []
                ];
            }

            return [
                'codigo' => 1,
                'msg' => 'Reserva inserida com sucesso!',
                'dados' => []
            ];

        } catch (\Exception $exception) {
            return [
                'codigo' => 0,
                'msg' => $exception->getMessage(),
                'dados' => []
            ];
        }
    }

    public function selectTipoReserva()
    {
        $query = "SELECT * FROM locadora_veiculos.locadora.estado_reserva ORDER BY id";

        try {
            $result = pg_query($this->dbConnect, $query);

            if (!$result) {
                return [
                    'codigo' => 0,
                    'msg' => 'Registro nao encontrado!',
                    'dados' => []
                ];
            }


            $resultSet = [];
            while ($row = pg_fetch_assoc($result)) {
                $resultSet[] = $row;
            }

            return [
                'codigo' => 1,
                'msg' => 'Registros selecionado com sucesso!',
                'dados' => $resultSet
            ];


        } catch (\Exception $exception) {
            return [
                'codigo' => 0,
                'msg' => $exception->getMessage()
            ];
        }
    }

    public function listarReservas()
    {
        $query = "SELECT reserva.*, v.marca, v.model, v.placa, er.nome
                    FROM locadora_veiculos.locadora.reserva
                    JOIN locadora_veiculos.locadora.veiculo v on v.id = reserva.id_veiculo
                    JOIN locadora_veiculos.locadora.estado_reserva er on er.id = reserva.id_estado_reserva
                  ORDER BY reserva.id";

        try {
            $result = pg_query($this->dbConnect, $query);
            if (pg_affected_rows($result) <= 0) {
                return [
                    'codigo' => 0,
                    'msg' => "Nenhum registro encontrado!",
                    'dados' => []
                ];
            }

            $resultSet = [];
            while ($row = pg_fetch_assoc($result)) {
                $row['data_inicio'] = (new \DateTime($row['data_inicio']))->format('d/m/Y H:i:s');
                $row['data_fim'] = (new \DateTime($row['data_fim']))->format('d/m/Y H:i:s');
                $resultSet[] = $row;
            }

            return [
                'codigo' => 1,
                'msg' => 'Reservas Listadas com sucesso!',
                'dados' => $resultSet
            ];

        } catch (\Exception $exception) {
            return [
                'codigo' => 0,
                'msg' => $exception->getMessage(),
                'dados' => []
            ];
        }
    }

    public function alterarReserva(){

        $dataUser = (array)$this->parametros['data'];
        $idReserva = (int)filter_var($dataUser['id'], FILTER_SANITIZE_SPECIAL_CHARS);
        $idVeiculo = (int)filter_var($dataUser['idVeiculo'], FILTER_SANITIZE_SPECIAL_CHARS);
        $dataReserva = filter_var($dataUser['dataReserva'], FILTER_SANITIZE_SPECIAL_CHARS);
        $horaReserva = filter_var($dataUser['horaReserva'], FILTER_SANITIZE_SPECIAL_CHARS);
        $dataEntrega = filter_var($dataUser['dataEntrega'], FILTER_SANITIZE_SPECIAL_CHARS);
        $horaEntrega = filter_var($dataUser['horaEntrega'], FILTER_SANITIZE_SPECIAL_CHARS);
        $tipoReserva = filter_var($dataUser['tipoReserva'], FILTER_SANITIZE_SPECIAL_CHARS);

        if ($idReserva <= 0 and $idVeiculo <= 0) {
            return [
                'codigo' => 0,
                'msg' => 'ids do registro nao chegou ao sistema.',
                'dados' => []
            ];
        }

        $lista = [$idReserva, $idVeiculo, $dataReserva, $horaReserva, $dataEntrega, $horaEntrega, $tipoReserva];

        foreach ($lista as $linha) {
            if (empty($linha)) {
                return [
                    'codigo' => 0,
                    'msg' => 'Campos de alteração nao Recebidos!',
                    'dados' => []
                ];
            }
        }

        $novaDataReserva = array_reverse(explode("/", $dataReserva));
        $novaDataReserva = implode("-", $novaDataReserva);
        $dataHoraReserva = $novaDataReserva." ".$horaReserva;

        $novaDataEntrega = array_reverse(explode("/", $dataEntrega));
        $novaDataEntrega = implode("-", $novaDataEntrega);
        $dataHoraEntrega = $novaDataEntrega." ".$horaEntrega;

        $queryVerificaReserva = "SELECT v.id AS id_veiculo, v.model
                                    FROM locadora_veiculos.locadora.reserva
                                    JOIN locadora_veiculos.locadora.veiculo v on v.id = reserva.id_veiculo
                                 WHERE (reserva.id != '$idReserva' and v.id = '$idVeiculo')
                                    and ((reserva.data_inicio between ('$dataHoraReserva')::timestamp and ('$dataHoraEntrega')::timestamp)
                                    OR (reserva.data_fim between ('$dataHoraReserva')::timestamp and ('$dataHoraEntrega')::timestamp))
                                 ORDER BY reserva.id";
        try {
            $resultVerificaReserva = pg_query($this->dbConnect, $queryVerificaReserva);

            if(pg_affected_rows($resultVerificaReserva) > 0){
                return [
                    'codigo' => 0,
                    'msg' => 'Veiculo ja reservado para esta data!!',
                    'dados' => []
                ];
            }

        } catch (\Exception $exception) {
            return [
                'codigo' => 0,
                'msg' => $exception->getMessage(),
                'dados' => []
            ];
        }

        $queryUpdate = "UPDATE locadora_veiculos.locadora.reserva
                     SET data_inicio = '$dataHoraReserva',
                         data_fim = '$dataHoraEntrega',
                         id_estado_reserva = '$tipoReserva'
                   WHERE id = '$idReserva' ";

        try {
            $resultUpdate = pg_query($this->dbConnect, $queryUpdate);
            if (!$resultUpdate) {
                return [
                    'codigo' => 0,
                    'msg' => pg_errormessage($this->dbConnect),
                    'dados' => []
                ];
            }

            return [
                'codigo' => 1,
                'msg' => 'Reserva Alterada com sucesso!',
                'dados' => []
            ];

        } catch (\Exception $exception) {
            return [
                'codigo' => 0,
                'msg' => $exception->getMessage(),
                'dados' => []
            ];
        }
    }

    public function removeReserva(){
        $idReserva = (int)$this->parametros['id'];

        if ($idReserva <= 0) {
            return [
                'codigo' => 0,
                'msg' => 'Id do registro a qual deseja remover não chegou ao sistema.',
            ];
        }

        $query = "DELETE FROM locadora_veiculos.locadora.reserva
                     WHERE id = '$idReserva' and id_estado_reserva = 3";

        try {
            $result = pg_query($this->dbConnect, $query);
            if (pg_affected_rows($result) <= 0) {
                return [
                    'codigo' => 0,
                    'msg' => 'Altere o estatus da reserva para cancelada primeiro!',
                    'dados' => []
                ];
            }

            return [
                'codigo' => 1,
                'msg' => 'Registro removido com sucesso!',
                'dados' => []
            ];

        } catch (\Exception $exception) {
            return [
                'codigo' => 0,
                'msg' => $exception->getMessage(),
                'dados' => []
            ];
        }
    }

    public function listarEstoqueVeiculos(){

        $query = "SELECT veiculo.*, ev.nome as estado FROM locadora_veiculos.locadora.veiculo
                    JOIN locadora_veiculos.locadora.estado_veiculo ev on ev.id = veiculo.id_estado_veiculo
                  ORDER BY veiculo.id";

        try {
            $result = pg_query($this->dbConnect, $query);
            if (pg_affected_rows($result) <= 0) {
                return [
                    'codigo' => 0,
                    'msg' => "Nenhum registro encontrado!",
                    'dados' => []
                ];
            }

            $resultSet = [];
            while ($row = pg_fetch_assoc($result)) {
                $resultSet[] = $row;
            }

            return [
                'codigo' => 1,
                'msg' => 'Veiculos Listados com sucesso!',
                'dados' => $resultSet
            ];

        } catch (\Exception $exception) {
            return [
                'codigo' => 0,
                'msg' => $exception->getMessage(),
                'dados' => []
            ];
        }
    }

    public function alterarVeiculo(){

        $dataUser = (array)$this->parametros['data'];
        $idVeiculo = (int)filter_var($dataUser['id'], FILTER_SANITIZE_SPECIAL_CHARS);
        $marca = filter_var($dataUser['marca'], FILTER_SANITIZE_SPECIAL_CHARS);
        $model = filter_var($dataUser['model'], FILTER_SANITIZE_SPECIAL_CHARS);
        $placa = filter_var($dataUser['placa'], FILTER_SANITIZE_SPECIAL_CHARS);
        $ano = filter_var($dataUser['ano'], FILTER_SANITIZE_SPECIAL_CHARS);
        $combustivel = filter_var($dataUser['combustivel'], FILTER_SANITIZE_SPECIAL_CHARS);
        $id_estado = filter_var($dataUser['estado'], FILTER_SANITIZE_SPECIAL_CHARS);

        $lista = [$idVeiculo, $marca, $model, $placa, $ano, $combustivel, $id_estado];

        foreach ($lista as $item){
            if(empty($item)){
                return [
                    'codigo' => 0,
                    'msg' => 'Campos obrigatorios nao preenchidos!',
                    'dados' => []
                ];
            }
        }

        $queryUpdate = "UPDATE locadora_veiculos.locadora.veiculo
                     SET marca = '$marca',
                         model = '$model',
                         placa = '$placa',
                         ano = '$ano',
                         combustivel = '$combustivel',
                         id_estado_veiculo = '$id_estado'
                   WHERE id = '$idVeiculo' ";

        try {
            $resultUpdate = pg_query($this->dbConnect, $queryUpdate);
            if (!$resultUpdate) {
                return [
                    'codigo' => 0,
                    'msg' => pg_errormessage($this->dbConnect),
                    'dados' => []
                ];
            }

            return [
                'codigo' => 1,
                'msg' => 'Veículo Alterado com sucesso!',
                'dados' => []
            ];

        } catch (\Exception $exception) {
            return [
                'codigo' => 0,
                'msg' => $exception->getMessage(),
                'dados' => []
            ];
        }
    }

    public function removeVeiculo(){
        $idVeiculo = (int)$this->parametros['id'];
        if ($idVeiculo <= 0) {
            return [
                'codigo' => 0,
                'msg' => 'Id do registro a qual deseja remover não chegou ao sistema.',
            ];
        }
        $queryVerificaReserva = "SELECT * FROM locadora_veiculos.locadora.reserva
                                    WHERE id_veiculo = '$idVeiculo'";

        try {
            $resultVerificaReserva = pg_query($this->dbConnect, $queryVerificaReserva);
            if (pg_affected_rows($resultVerificaReserva) > 0) {
                return [
                    'codigo' => 0,
                    'msg' => 'Veiculo possui reserva em andamento!',
                    'dados' => []
                ];
            }
        } catch (\Exception $exception) {
            return [
                'codigo' => 0,
                'msg' => $exception->getMessage(),
                'dados' => []
            ];
        }

        $query = "DELETE FROM locadora_veiculos.locadora.veiculo WHERE id = '$idVeiculo' ";

        try {
            $result = pg_query($this->dbConnect, $query);
            if (pg_affected_rows($result) <= 0) {
                return [
                    'codigo' => 0,
                    'msg' => 'Falha ao tentar excluir!',
                    'dados' => []
                ];
            }

            return [
                'codigo' => 1,
                'msg' => 'Registro removido com sucesso!',
                'dados' => []
            ];


        } catch (\Exception $exception) {
            return [
                'codigo' => 0,
                'msg' => $exception->getMessage(),
                'dados' => []
            ];
        }
    }
}
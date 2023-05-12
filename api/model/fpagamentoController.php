<?php

    include('../connection/conn.php');

    $requestData = $_REQUEST;

    if($requestData['operacao'] == 'create'){

        try {
            // Gerar a querie de inserção de dados no B.D.
            $sql = "INSERT INTO FPAGAMENTO (NOME) VALUES (?)";
            // Iremos preparar a nossa querie para gerar o objeto de inserção ao B.D.
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $requestData['NOME'],
            ]);
            $dados = array(
                'type' => 'success',
                'mensagem' => 'Registro salvo com sucesso!'
            );
        } catch (PDOException $e) {
            $dados = array(
                'type' => 'error',
                'mensagem' => 'Erro ao salvar o registro: '.$e
            );
        }
    
        echo json_encode($dados);

    }
    if($requestData['operacao'] == 'read'){
        // Obter o umero de colunas da nossa tabela
        $colunas = $requestData['columns'];

        // Gerar a nossa query de consulta ao banco de dados
        $sql = "SELECT * FROM FPAGAMENTO WHERE 1=1 ";

        // Obter o total de registro encontrados
        $resultado = $pdo->query($sql);
        $qtDeLinhas = $resultado->rowCount();

        // Verificar se existe algum filtro determinado
        $filtro = $requestData['search']['value'];
        if(!empty($filtro)){
            $sql .= " AND (ID LIKE '$filtro%' ";
            $sql .= " OR NOME LIKE '%$filtro') "; 
        }

        // Obter o total de registro encontrados filtrados
        $resultado = $pdo->query($sql);
        $totalFiltrados = $resultado->rowCount();

        // Obter os valores para a ordenação de registro
        $colunaOrdem = $requestData['order'][0]['column']; // Obtendo a posição da coluna na ordenação
        $ordem = $colunas[$colunaOrdem]['data']; // Obtendo o nome da coluna que será ordenada
        $direcao = $requestData['order'][0]['dir']; // Obtendo a diração da ordenação ASC | DESC

        // Obtendo os limites para paginação dos dados
        $inicio = $requestData['start']; // Obtendo o início do limite
        $tamanho = $requestData['length']; // Obtendo o tamanho do limite

        // Realizar a nossa ordenação e os limites
        $sql .= "ORDER BY $ordem $direcao LIMIT $inicio $tamanho ";
        $resultado = $pdo->query($sql);
        $dados = array();
        while($row = $resultado->fetch(PDO::FETCH_ASSOC)){
            $dados = array_map(null, $row);
        }

        //Montar o objeto JSON no padrão DataTables
        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordTotal" => intval($qtDeLinhas),
            "recodsFiltered" => intval($totalFiltrados),
            "data" => $dados
        );

        echo json_encode($json_data);
    }
    if($requestData['operacao'] == 'update'){

        try {
            // Gerar a querie de inserção de dados no B.D.
            $sql = "UPDATE FPAGAMENTO SET NOME = ? WHERE ID = ?";
            // Iremos preparar a nossa querie para gerar o objeto de inserção ao B.D.
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $requestData['NOME'],
                $requestData['ID']
            ]);
            $dados = array(
                'type' => 'success',
                'mensagem' => 'Registro atualizado com sucesso!'
            );
        } catch (PDOException $e) {
            $dados = array(
                'type' => 'error',
                'mensagem' => 'Erro ao atualizar o registro: '.$e
            );
        }
    
        echo json_encode($dados);
    }
    if($requestData['operacao'] == 'delete'){

        try {
            // Gerar a querie de inserção de dados no B.D.
            $sql = "DELETE FROM FPAGAMENTO WHERE ID = ?";
            // Iremos preparar a nossa querie para gerar o objeto de inserção ao B.D.
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $requestData['ID']
            ]);
            $dados = array(
                'type' => 'success',
                'mensagem' => 'Registro excluído com sucesso!'
            );
        } catch (PDOException $e) {
            $dados = array(
                'type' => 'error',
                'mensagem' => 'Erro ao excluir o registro: '.$e
            );
        }
    
        echo json_encode($dados);
    }
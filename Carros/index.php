<?php
require_once("../Classes/Carro.class.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = isset($_POST['id']) ? $_POST['id'] : 0;
    $modelo = isset($_POST['modelo']) ? $_POST['modelo'] : "";
    $marca = isset($_POST['marca']) ? $_POST['marca'] : "";
    $ano = isset($_POST['ano']) ? (int)$_POST['ano'] : 0;
    $placa = isset($_POST['placa']) ? $_POST['placa'] : "";
    $acao = isset($_POST['acao']) ? $_POST['acao'] : "";


$destino_foto = '';

if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
    if (!is_dir('../uploads')) {
        mkdir('../uploads', 0777, true);
    }

    $nomeArquivo = basename($_FILES['foto']['name']);
    $destino_foto = '../uploads/' . $nomeArquivo;

    if (move_uploaded_file($_FILES['foto']['tmp_name'], $destino_foto)) {
        echo "Arquivo enviado com sucesso!";
    } else {
        echo "Erro ao mover o arquivo.";
    }
}

    $carro = new Carro($id, $modelo, $marca, $ano, $placa, $destino_foto);

    if ($acao == 'salvar') {
        if ($id > 0)
            $resultado = $carro->alterar();
        else
            $resultado = $carro->inserir();
    } elseif ($acao == 'excluir') {
        $resultado = $carro->excluir();
    }

    if ($resultado)
        header("Location: index.php");
    else
        echo "Erro ao salvar dados: " . $carro;
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $formulario = file_get_contents('form_cad_carros.html');

    $id = isset($_GET['id']) ? $_GET['id'] : 0;
    $resultado = Carro::listar(1, $id);

    if ($resultado) {
        $carro = $resultado[0];
        $formulario = str_replace('{id}', $carro->getId(), $formulario);
        $formulario = str_replace('{modelo}', $carro->getModelo(), $formulario);
        $formulario = str_replace('{marca}', $carro->getMarca(), $formulario);
        $formulario = str_replace('{ano}', $carro->getAno(), $formulario);
        $formulario = str_replace('{placa}', $carro->getPlaca(), $formulario);
        $formulario = str_replace('{foto}', $carro->getFoto(), $formulario);
    } else {
        $formulario = str_replace('{id}', 0, $formulario);
        $formulario = str_replace('{modelo}', '', $formulario);
        $formulario = str_replace('{marca}', '', $formulario);
        $formulario = str_replace('{ano}', '', $formulario);
        $formulario = str_replace('{placa}', '', $formulario);
        $formulario = str_replace('{foto}', '', $formulario);
    }

    print($formulario);

    $busca = isset($_GET['busca']) ? $_GET['busca'] : 0;
    $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 0;
    $lista = Carro::listar($tipo, $busca);
    $itens = '';
    foreach($lista as $carro){
        $item = file_get_contents('itens_listagem_carros.html');
        $item = str_replace('{id}', $carro->getId(), $item);
        $item = str_replace('{modelo}', $carro->getModelo(), $item);
        $item = str_replace('{marca}', $carro->getMarca(), $item);
        $item = str_replace('{ano}', $carro->getAno(), $item);
        $item = str_replace('{placa}', $carro->getPlaca(), $item);
        $item = str_replace('{foto}', $carro->getFoto(), $item);
        $itens .= $item;
    }

    $listagem = file_get_contents('listagem_carros.html');
    $listagem = str_replace('{itens}', $itens, $listagem);
    print($listagem);
}
?>


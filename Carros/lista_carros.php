<?php
require_once("../Classes/Carro.class.php");

$busca = isset($_GET['busca']) ? $_GET['busca'] : '';
$tipo = isset($_GET['tipo']) ? intval($_GET['tipo']) : 0;

$lista = Carro::listar($tipo, $busca);
$itens = '';

foreach ($lista as $carro) {
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

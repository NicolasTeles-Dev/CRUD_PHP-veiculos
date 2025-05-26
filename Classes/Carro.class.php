<?php
require_once ("Database.class.php");

class Carro {
    private $id;
    private $modelo;
    private $marca;
    private $ano;
    private $placa;
    private $foto;

    public function __construct($id, $modelo, $marca, $ano, $placa, $foto) {
        $this->id = $id;
        $this->modelo = $modelo;
        $this->marca = $marca;
        $this->ano = $ano;
        $this->placa = $placa;
        $this->foto = $foto;
    }

    public function setId($id) {
        if ($id < 0) throw new Exception("Erro, o ID deve ser maior que 0!");
        $this->id = $id;
    }

    public function setModelo($modelo) {
        if (empty($modelo)) throw new Exception("Erro, o modelo deve ser informado!");
        $this->modelo = $modelo;
    }

    public function setMarca($marca) {
        if (empty($marca)) throw new Exception("Erro, a marca deve ser informada!");
        $this->marca = $marca;
    }

    public function setAno($ano) {
        if ($ano < 1886 || $ano > intval(date("Y")) + 1) // ano mínimo do primeiro carro + 1 ano para tolerância
            throw new Exception("Erro, ano inválido!");
        $this->ano = $ano;
    }

    public function setPlaca($placa) {
        if (empty($placa)) throw new Exception("Erro, a placa deve ser informada!");
        $this->placa = $placa;
    }

    public function setFoto($foto = '') {
        $this->foto = $foto;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getModelo(): string {
        return $this->modelo;
    }

    public function getMarca(): string {
        return $this->marca;
    }

    public function getAno(): int {
        return $this->ano;
    }

    public function getPlaca(): string {
        return $this->placa;
    }

    public function getFoto(): string {
        return $this->foto;
    }

    public function __toString(): string {
        return "Carro: $this->id - $this->modelo - $this->marca - $this->ano - $this->placa - Foto: $this->foto";
    }

    public function inserir(): bool {
        $sql = "INSERT INTO carro (modelo, marca, ano, placa, foto) 
                VALUES (:modelo, :marca, :ano, :placa, :foto)";
        $parametros = [
            ':modelo' => $this->getModelo(),
            ':marca' => $this->getMarca(),
            ':ano' => $this->getAno(),
            ':placa' => $this->getPlaca(),
            ':foto' => $this->getFoto()
        ];
        return Database::executar($sql, $parametros) == true;
    }

    public static function listar($tipo = 0, $info = ''): array {
        $sql = "SELECT * FROM carro";
        switch ($tipo) {
            case 1:
                $sql .= " WHERE id = :info ORDER BY id";
                break;
            case 2:
                $sql .= " WHERE modelo LIKE :info ORDER BY modelo";
                $info = '%' . $info . '%';
                break;
        }

        $parametros = [];
        if ($tipo > 0) $parametros = [':info' => $info];

        $comando = Database::executar($sql, $parametros);
        $carros = [];
        while ($registro = $comando->fetch()) {
            $carro = new Carro(
                $registro['id'],
                $registro['modelo'],
                $registro['marca'],
                $registro['ano'],
                $registro['placa'],
                $registro['foto']
            );
            $carros[] = $carro;
        }
        return $carros;
    }

    public function alterar(): bool {
        $sql = "UPDATE carro SET 
                    modelo = :modelo, 
                    marca = :marca, 
                    ano = :ano, 
                    placa = :placa, 
                    foto = :foto
                WHERE id = :id";
        $parametros = [
            ':id' => $this->getId(),
            ':modelo' => $this->getModelo(),
            ':marca' => $this->getMarca(),
            ':ano' => $this->getAno(),
            ':placa' => $this->getPlaca(),
            ':foto' => $this->getFoto()
        ];
        return Database::executar($sql, $parametros) == true;
    }

    public function excluir(): bool {
        $sql = "DELETE FROM carro WHERE id = :id";
        $parametros = [':id' => $this->getId()];
        return Database::executar($sql, $parametros) == true;
    }
}
?>


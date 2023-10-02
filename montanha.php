<?php

class Montanha {
    private $nome;
    private $altitude;
    private $cordilheira;
    private $pais;
    private $trilhasPopulares;

    public function __construct($nome, $altitude, $cordilheira, $pais, $trilhasPopulares) {
        $this->nome = $nome;
        $this->altitude = $altitude;
        $this->cordilheira = $cordilheira;
        $this->pais = $pais;
        $this->trilhasPopulares = $trilhasPopulares;
    }

    // Métodos getters e setters para encapsular o acesso aos atributos
    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getAltitude() {
        return $this->altitude;
    }

    public function setAltitude($altitude) {
        $this->altitude = $altitude;
    }

    public function getCordilheira() {
        return $this->cordilheira;
    }

    public function setCordilheira($cordilheira) {
        $this->cordilheira = $cordilheira;
    }

    public function getPais() {
        return $this->pais;
    }

    public function setPais($pais) {
        $this->pais = $pais;
    }

    public function getTrilhasPopulares() {
        return $this->trilhasPopulares;
    }

    public function setTrilhasPopulares($trilhasPopulares) {
        $this->trilhasPopulares = $trilhasPopulares;
    }
}

class RepositorioMontanhas {
    private $db;

    public function __construct() {
        // Conectar ao banco de dados SQLite (certifique-se de que o arquivo do banco de dados exista)
        $this->db = new SQLite3('banco_de_dados.db');
        // Criar a tabela "montanhas" se ela não existir
        $this->db->exec('CREATE TABLE IF NOT EXISTS montanhas (id INTEGER PRIMARY KEY, nome TEXT, altitude REAL, cordilheira TEXT, pais TEXT, trilhasPopulares TEXT)');
    }

    public function cadastrarMontanha(Montanha $montanha) {
        // Inserir uma montanha no banco de dados
        $stmt = $this->db->prepare('INSERT INTO montanhas (nome, altitude, cordilheira, pais, trilhasPopulares) VALUES (:nome, :altitude, :cordilheira, :pais, :trilhasPopulares)');
        $stmt->bindValue(':nome', $montanha->getNome());
        $stmt->bindValue(':altitude', $montanha->getAltitude());
        $stmt->bindValue(':cordilheira', $montanha->getCordilheira());
        $stmt->bindValue(':pais', $montanha->getPais());
        $stmt->bindValue(':trilhasPopulares', $montanha->getTrilhasPopulares());
        $stmt->execute();
    }

    public function listarMontanhas() {
        // Consultar todas as montanhas cadastradas
        $result = $this->db->query('SELECT * FROM montanhas');
        $montanhas = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $montanhas[] = $row;
        }
        return $montanhas;
    }

    public function fecharConexao() {
        // Fechar a conexão com o banco de dados
        $this->db->close();
    }
}

// Exemplo de uso:
$repositorio = new RepositorioMontanhas();
$montanha1 = new Montanha('Everest', 8848, 'Himalaia', 'Nepal', 'Rota Sul, Rota Norte');
$repositorio->cadastrarMontanha($montanha1);
$montanha2 = new Montanha('Matterhorn', 4478, 'Alpes', 'Suíça', 'Rota Hörnli');
$repositorio->cadastrarMontanha($montanha2);
$montanhasCadastradas = $repositorio->listarMontanhas();

// Exibir as montanhas cadastradas
foreach ($montanhasCadastradas as $montanha) {
    echo 'Nome: ' . $montanha['nome'] . '<br>';
    echo 'Altitude: ' . $montanha['altitude'] . '<br>';
    echo 'Cordilheira: ' . $montanha['cordilheira'] . '<br>';
    echo 'País: ' . $montanha['pais'] . '<br>';
    echo 'Trilhas Populares: ' . $montanha['trilhasPopulares'] . '<br>';
    echo '<hr>';
}

$repositorio->fecharConexao();
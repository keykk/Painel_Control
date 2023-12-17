<?php
  class Conexao extends PDO {
 
    private static $instancia;
 
    public function Conexao($dsn, $username = "", $password = "") {
        // O construtro abaixo é o do PDO
        parent::__construct($dsn, $username, $password);
    }
 
    public static function getInstance() {
        // Se o a instancia não existe eu faço uma
        if(!isset( self::$instancia )){
            try {
                self::$instancia = new Conexao("mysql:host=127.0.0.1;dbname=Painel", "root", "");
            } catch ( Exception $e ) {
                echo $e->getMessage();
                exit ();
            }
        }
        // Se já existe instancia na memória eu retorno ela
        return self::$instancia;
    }
}
//$connnect = Conexao::getInstance();
?>

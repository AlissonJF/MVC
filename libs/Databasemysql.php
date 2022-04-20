<?php

class Databasemysql extends PDO
{
    
    public function __construct()
    {
        $DB_TYPE="mysql";
        $DB_HOST="172.16.6.25";
        $DB_NAME="admin";
        $DB_USER="unimar";
        $DB_PASS="unimar";
        try{    
          $dsn= "$DB_TYPE:host=$DB_HOST;dbname=$DB_NAME;port=3306";
         
        parent::__construct($dsn, $DB_USER, $DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
        
        parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		parent::setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        }
        catch(Exception $e){
        
            print_r($e);
        }
    }
    
    /**
     * select
     * @param string $sql : comando sql
     * @param array $array parametros e dados para o bind
     * @param constant $fetchMode modo de fetch: PDO::FETCH_OBJ, PDO::FETCH_ASSOC, PDO::FETCH_BOTH...
     * @return todos os registros encontrados
     */
    public function select($sql, $array = array(), $fetchMode = PDO::FETCH_OBJ)
    {
        $sth = $this->prepare($sql);
		//percorre os elementos do array para fazer o bind de cada parametro
		//com o respectivo valor
        foreach ($array as $key => $value) {
            $sth->bindValue("$key",$value);
        }
        
        $sth->execute();
        return $sth->fetchAll($fetchMode);
    }
    
    /**
     * insert
     * @param string $table : tabela em que o dado sera inserido
     * @param string $data  : um array contendo os campos e valores
     */
    public function insert($table, $data)
    {
		//ordena o array pelas chaves (nome do campo)
        ksort($data);
        
		//monta a string do prepare com os campos e valores
        
        $fieldNames = implode('`, `', array_keys($data));
        $fieldValues = ':' . implode(', :', array_keys($data));
         
             

        $sth = $this->prepare("INSERT INTO $table (`$fieldNames`) VALUES ($fieldValues)");
      
       
        
		//percorre o array criando os parametros (:campo) e fazendo o bind com os respectivos valores
        foreach ($data as $key => $value) {
            $sth->bindValue(":$key", $value);
        }
        //executa o insert
        return $sth->execute();
    }
    
    /**
     * update
     * @param string $table : tabela que deseja alterar os dados
     * @param string $data  : um array contendo os campos e valores
     * @param string $where : condicao do update
     */
    public function update($table, $data, $where)
    {
		//ordena o array pelas chaves (nome do campo)
        ksort($data);
        
        $fieldDetails = NULL;
		//monta a string do prepare com os campos do set com os parametros (:campo)
        foreach($data as $key=> $value) {
              $fieldDetails .= "`$key`=:$key,";
        }
		//retira a ultima virgula
        $fieldDetails = rtrim($fieldDetails, ',');
        
        $sth = $this->prepare("UPDATE $table SET $fieldDetails WHERE $where");
		
        //percorre o array criando os parametros (:campo) e fazendo o bind com os respectivos valores
        foreach ($data as $key => $value) {
            $sth->bindValue(":$key", $value);
        }
        //executa o update
        return $sth->execute();
    }
    
    /**
     * delete
     * 
     * @param string $table
     * @param string $where
     * @param integer $limit
     * @return integer linhas afetadas
     */
    public function delete($table, $where, $limit = 1)
    {
		//ainda falta implementar mais recursos e pode-se fazer as mesmas verificacoes do update com prepare
        return $this->exec("DELETE FROM $table WHERE $where");
    }
	
	    
}
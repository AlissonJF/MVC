<?php

class Database
{
   

   public $link="";
    
    public function __construct($DB_TNS,$DB_USER, $DB_PASS)
    {

        try{
            $this->link=@oci_connect($DB_USER,$DB_PASS,$DB_TNS,'AL32UTF8');

        }
        catch(Exception $e){
            print_r($e->getMessage());
        }
            
        
    }

     /**
     * select
     * @param string $query : comando sql
     * @param array $param parametros e dados para o bind
     * @param string $frmt formato de retorno json ou array
     * @return todos os registros encontrados
     */
    function select($query,$params=array(),$frmt='',$partype=0){

        $result=oci_parse($this->link,$query);
        //print_r($result);exit;

        if($result){

        $out=null;
        if(!empty($params)){

            foreach($params as $k=>$v){

                    //oci_bind_by_name($result,$v->param,$v->val,$v->maxlen,$v->tipo);
                       // print_r($v);
                    if($partype==1){
                        oci_bind_by_name($result,$v->param,$v->val,$v->maxlen,$v->tipo);
                    }
                    else{
                        oci_bind_by_name($result,$k,$v,-1);
                    }


             }
            }
            try {

                if (oci_execute($result)) {

                    oci_fetch_all($result, $out, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);

                    if (strtolower($frmt) == 'json') {
                        $out = json_encode($out);
                    }
                   // echo("Ok");
                    return $out;
                }
                else{
                //    echo("ERRO:");
                    $e = oci_error($result);
                    print_r($e);

                }
            }
            catch(Exception $e){
                echo($e->getMessage());
            }
        }
        else{
            //$e = oci_error($this->link);
            //print_r($e);
            //print htmlentities($e['message']);
           // print "\n<pre>\n";
           // print htmlentities($e['sqltext']);
           // printf("\n%".($e['offset']+1)."s", "^");
           // print  "\n</pre>\n";
            return 0;
        }

    }




    public function toDate($dt,$m='DD/MM/YYYY'){

        $sql="select to_date('$dt','$m') as data from dual";
        $res=$this->select($sql);
        return $res[0]["DATA"];
    }

   
    
    /**
     * insert
     * @param string $table : tabela em que o dado sera inserido
     * @param string $data  : um array contendo os campos e valores
     */
    public function insert($table, $data,$commit=OCI_COMMIT_ON_SUCCESS)
    {
       
        if($commit!=OCI_COMMIT_ON_SUCCESS){ $commit=OCI_DEFAULT; }
		//ordena o array pelas chaves (nome do campo)
        ksort($data);
        
		//monta a string do prepare com os campos e valores
        
        $fieldNames = implode(',', array_keys($data));
        $fieldValues = ':' . implode(', :', array_keys($data));

        $result=oci_parse($this->link,"INSERT INTO $table ($fieldNames) VALUES ($fieldValues)");

        $teste=array();
        foreach($data as $key => $value){
                   $teste[$key]=$value; //bug na passagem por referencia
                   oci_bind_by_name($result,":$key",$teste[$key],-1);
                   

        }
         
        //executa o insert

       $t=oci_execute($result,$commit);
       
               
        if(!$t){
            //$e = oci_error($result);
           
            //print_r($fieldNames);
            //print_r($fieldValues);
           // print_r($e);
        }
        return $t;

            
        

    }
    
    /**
     * update
     * @param string $table : tabela que deseja alterar os dados
     * @param string $data  : um array contendo os campos e valores
     * @param string $where : condicao do update
     */
    public function update($table, $data, $where,$commit=OCI_COMMIT_ON_SUCCESS)
    {
        if($commit!=OCI_COMMIT_ON_SUCCESS){ $commit=OCI_DEFAULT; }
		//ordena o array pelas chaves (nome do campo)
        ksort($data);
        
        $fieldDetails = NULL;
		//monta a string do prepare com os campos do set com os parametros (:campo)
        foreach($data as $key=> $value) {
              $fieldDetails .= "$key=:$key,";
        }
		//retira a ultima virgula
        $fieldDetails = rtrim($fieldDetails, ',');

       $result= oci_parse($this->link,"UPDATE $table SET $fieldDetails WHERE $where");
		
        //percorre o array criando os parametros (:campo) e fazendo o bind com os respectivos valores
        $teste=array();
        foreach ($data as $key => $value) {
            $teste[$key]=$value; //bug na passagem por referencia
             oci_bind_by_name($result,":$key",$teste[$key],-1);
        }
        //executa o update
       oci_execute($result,$commit);
    }
    
    /**
     * delete
     * 
     * @param string $table
     * @param string $where
     * @param integer $limit
     * @return integer linhas afetadas
     */
    public function delete($table, $where,$commit=OCI_COMMIT_ON_SUCCESS)
    {

        if($commit!=OCI_COMMIT_ON_SUCCESS){ $commit=OCI_DEFAULT; }


        $result= oci_parse($this->link,"DELETE FROM $table WHERE $where");



        //executa o delete
        oci_execute($result,$commit);
    }


    public function gravaLog($table,$cmd,$usuario,$dados){

        $result=static::select("select eventosdba.seqlog.nextval as SEQ from dual");
        $seq=(int)$result[0]["SEQ"];
        $dados=print_r($dados,true);

        $result=static::select("select to_timestamp(to_char(sysdate,'DD/MM/YYYY HH24:MI:SS'),'DD/MM/YYYY HH24:MI:SS')  as DATAHORA from dual");
        $datahora=$result[0]["DATAHORA"];

        static::insert("eventosdba.log",array("SEQUENCIA"=>$seq,"TABELA"=>$table,"CMD"=>$cmd,"USUARIO"=>$usuario,"DADOS"=>$dados,"DATAHORA"=>$datahora));


    }

}
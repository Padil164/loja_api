<?php

class api_logic
{

    private $endpoint;
    private $params;

    //============================================================================================================
    public function __construct($endpoint, $params = null)
    {
        // defines the object/class properties
        $this->endpoint = $endpoint;
        $this->params = $params;
    }

    //============================================================================================================
    public function endpoint_exists(){
        // check if the endpoint is a valid class method
        return method_exists($this, $this -> endpoint); // aqui verifica se o endpoint/metodo existe dentro dessa classe, todos metodos que estao abaixo sao funcoes que levam o nome do endpoint existentes, se for diferente obviamente retorna false
    }

    //============================================================================================================
    public function error_response($message){
        return[
            'status' => 'ERROR',
            'message' => $message,
            'results' => []
        ];
    }

    //============================================================================================================
    // ENDPOINTS
    //============================================================================================================
    public function status()
    {
        return ['status' => 'SUCCESS',
                'message' => "API running Ok!",
                'results' => null];
    }

    //============================================================================================================
    public function get_totals(){
        $db = new database;
        $results = $db -> EXE_QUERY("
        SELECT 'Clientes', COUNT(*) Total FROM clientes WHERE deleted_at IS NULL UNION ALL
        SELECT 'Produtos', COUNT(*) Total FROM produtos WHERE deleted_at IS NULL
        ");

        return ['status' => 'SUCCESS',
        'message' => "",
        'results' => $results
                ];
    
    }

    // //============================================================================================================
    // public function get_all_clients()
    // {
    //     $sql = "SELECT * FROM clientes WHERE 1 "; // condiciona a query a existencia de um filtro, se por acaso tiver algum filtro a variavela $sql vai ser concatenada com o filtro para ser uma query dinamica

    //     if(key_exists('only_active', $this -> params)){
    //         if(filter_var($this -> params['only_active'], FILTER_VALIDATE_BOOLEAN) == true){ // aqui nesta linha estamos a tratar a variavel como se fosse booleana, sendo booleana podemos comparar se a variavel e true ou false, e nao necessariamente se existe a variavel dentro
    //             $sql .= "AND deleted_at IS NULL";
    //         }
    //     }

    //     $bd = new database;
    //     $results = $bd -> EXE_QUERY($sql);
    //     return ['status' => 'SUCCESS',
    //     'message' => "",
    //     'results' => $results
    
    // ];
    // }

           //============================================================================================================
           // CLIENTS
           //============================================================================================================
        //============================================================================================================
        public function get_all_clients()
        {
            $bd = new database;
            $results = $bd -> EXE_QUERY("SELECT * FROM clientes WHERE deleted_at IS NULL");
            return ['status' => 'SUCCESS',
            'message' => "",
            'results' => $results
                    ];
        }

        //============================================================================================================
        public function get_all_active_clients()
        {
            $bd = new database;
            $results = $bd -> EXE_QUERY("SELECT * FROM clientes WHERE deleted_at IS NULL");
            return ['status' => 'SUCCESS',
            'message' => "",
            'results' => $results
                    ];
        }

     //============================================================================================================
     public function get_all_inactive_clients()
     {
       $bd = new database;
       $results = $bd -> EXE_QUERY("SELECT * FROM clientes WHERE deleted_at IS NOT NULL");
       return ['status' => 'SUCCESS',
       'message' => "",
       'results' => $results
  ];
   }


   
           //============================================================================================================
           public function get_client()
           {

            $sql = "SELECT * FROM clientes WHERE 1 ";

            if(key_exists('id', $this -> params)){
                if(filter_var($this -> params['id'], FILTER_VALIDATE_INT)){
                    $sql .= " AND id_cliente = ". intval($this -> params['id']);
                }
            }else{
               return  $this -> error_response("Id client not specified");
            }
               $bd = new database;
               $results = $bd -> EXE_QUERY($sql);
               return ['status' => 'SUCCESS',
               'message' => "",
               'results' => $results
                       ];
           }

        
        //============================================================================================================
        public function create_new_client(){
        
            // cheeck if all data is available

            if($this -> params['nome'] == '' || $this -> params['email'] == '' || $this -> params['telefone'] == ''){
                return $this -> error_response("Insufficient client data");
            }

            // check if there is already another client with the same: email or name
            $db = new database;

            $params = [
                ':nome' => $this -> params['nome'],
                ':email' => $this -> params['email'],
            ];

            $results = $db -> EXE_QUERY("SELECT id_cliente FROM clientes WHERE nome = :nome OR email = :email", $params);
            if (count($results) != 0){
                return $this -> error_response("There is another client with the same name or email");
            }



            $params = [
                ':nome' => $this -> params['nome'],
                ':email' => $this -> params['email'],
                ':telefone' => $this -> params['telefone']
            ];

            
            $db -> EXE_QUERY("INSERT INTO clientes VALUES (
                0, :nome, :email, :telefone, NOW(), NOW(), NULL
            )", $params);

            return ['status' => 'SUCCESS',
            'message' => "New client added with success!!",
            'results' => $params
            ];

        }
        //============================================================================================================
        public function update_client(){

            if($this -> params['id_cliente'] == '' ||$this -> params['nome'] == '' || $this -> params['email'] == '' || $this -> params['telefone'] == ''){
                return $this -> error_response("Insufficient client data");
            }

            // check if there is already another client with the same: email or name
            $db = new database;

            $params = [
                ':id_cliente' => $this -> params['id_cliente'],
                ':nome' => $this -> params['nome'],
                ':email' => $this -> params['email'],
            ];

            $results = $db -> EXE_QUERY("SELECT id_cliente FROM clientes WHERE 1 
            AND (nome = :nome OR email = :email)
            AND deleted_at IS NULL
            AND id_cliente <> :id_cliente", $params);
            if (count($results) != 0){
                return $this -> error_response("There is another client with the same name or email");
            }



            $params = [
                'id_cliente' => $this -> params['id_cliente'],
                ':nome' => $this -> params['nome'],
                ':email' => $this -> params['email'],
                ':telefone' => $this -> params['telefone']
            ];

            
            $db -> EXE_NON_QUERY("UPDATE clientes SET nome = :nome, email = :email, telefone = :telefone, updated_at = NOW()
            WHERE id_cliente = :id_cliente", $params);

            return ['status' => 'SUCCESS',
            'message' => "client data updated with success!!",
            'results' => $params
            ];

        }

        //============================================================================================================
        public function delete_client(){
        
    // cheeck if all data is available

    if(!isset($this -> params['id'])){
        return $this -> error_response("Insufficient client data");
    }

    // hard delete client
    $db = new database;
    $params = [
        ':id' => $this -> params['id'],
    ];

    $db -> EXE_NON_QUERY("UPDATE clientes SET deleted_at = NOW() WHERE id_cliente = :id", $params);

    return ['status' => 'SUCCESS',
    'message' => "Client deleted with success!!",
    'results' => []
    ];

        } 
    
    //============================================================================================================
    // PRODUTOS
    //============================================================================================================

    //============================================================================================================
    public function get_all_products()
    {
        $bd = new database;
        $results = $bd -> EXE_QUERY("SELECT * FROM produtos WHERE deleted_at IS NULL");
        return ['status' => 'SUCCESS',
        'message' => "",
        'results' => $results
    
    ];
    }

            //============================================================================================================
            public function delete_product(){
        
                // cheeck if all data is available
            
                if(!isset($this -> params['id'])){
                    return $this -> error_response("Insufficient client data");
                }
            
                // hard delete client
                $db = new database;
                $params = [
                    ':id' => $this -> params['id'],
                ];
            
                $db -> EXE_NON_QUERY("UPDATE produtos SET deleted_at = NOW() WHERE id_produto = :id", $params);
            
                return ['status' => 'SUCCESS',
                'message' => "Product deleted with success!!",
                'results' => []
                ];
            
                    } 

      //============================================================================================================
      public function get_product()
      {

       $sql = "SELECT * FROM produtos WHERE 1 ";

       if(key_exists('id', $this -> params)){
           if(filter_var($this -> params['id'], FILTER_VALIDATE_INT)){
               $sql .= " AND id_produto = ". intval($this -> params['id']);
           }
       }else{
          return  $this -> error_response("Id product not specified");
       }
          $bd = new database;
          $results = $bd -> EXE_QUERY($sql);
          return ['status' => 'SUCCESS',
          'message' => "",
          'results' => $results
                  ];
      }

    //============================================================================================================
    public function get_all_active_products()
    {
        $bd = new database;
        $results = $bd -> EXE_QUERY("SELECT * FROM produtos WHERE deleted_at IS NULL");
        return ['status' => 'SUCCESS',
        'message' => "",
        'results' => $results
    
    ];
    }

    //============================================================================================================
    public function get_all_inactive_products()
    {
        $bd = new database;
        $results = $bd -> EXE_QUERY("SELECT * FROM produtos WHERE deleted_at IS NOT NULL");
        return ['status' => 'SUCCESS',
        'message' => "",
        'results' => $results
    
    ];
    }

    //============================================================================================================
    public function get_all__products_without_stock()
    {
        $bd = new database;
        $results = $bd -> EXE_QUERY("SELECT * FROM produtos WHERE quantidade <= 0 AND deleted_at IS NULL");
        return ['status' => 'SUCCESS',
        'message' => "",
        'results' => $results
    
    ];
    }

     //============================================================================================================
     public function create_new_product(){
        
        // cheeck if all data is available

        if($this -> params['produto'] == '' || $this -> params['quantidade'] == ''){
            return $this -> error_response("Insufficient product data");
        }

        // check if there is already another product with the same name
        $db = new database;

        $params = [
            ':produto' => $this -> params['produto'],
        ];

        $results = $db -> EXE_QUERY("SELECT id_produto FROM produtos WHERE produto = :produto AND deleted_at IS NULL", $params);
        if (count($results) != 0){
            return $this -> error_response("There is another product with the same name");
        }



        $params = [
            ':produto' => $this -> params['produto'],
            ':quantidade' => $this -> params['quantidade'],
        ];

        
        $db -> EXE_QUERY("INSERT INTO produtos VALUES (
            0, :produto, :quantidade, NOW(), NOW(), NULL
        )", $params);

        return ['status' => 'SUCCESS',
        'message' => "New product added with success!!",
        'results' => $params
        ];

    } 

     //============================================================================================================
     public function update_product(){
        
        // cheeck if all data is available

        if($this->params['id_produto'] == '' || $this -> params['produto'] == '' || $this -> params['quantidade'] == ''){
            return $this -> error_response("Insufficient product data");
        }

        // check if there is already another product with the same name
        $db = new database;

        $params = [
            ':id_produto' => $this -> params['id_produto'],
            ':produto' => $this -> params['produto'],
        ];

        $results = $db -> EXE_QUERY("SELECT id_produto FROM produtos WHERE produto = :produto AND deleted_at IS NULL AND id_produto <> :id_produto", $params);
        if (count($results) != 0){
            return $this -> error_response("There is another product with the same name");
        }



        $params = [
            ':id_produto' => $this -> params['id_produto'],
            ':produto' => $this -> params['produto'],
            ':quantidade' => $this -> params['quantidade'],
        ];

        
        $db -> EXE_NON_QUERY("UPDATE produtos SET produto = :produto, quantidade = :quantidade, updated_at = NOW() WHERE id_produto = :id_produto", $params);

        return ['status' => 'SUCCESS',
        'message' => "Product updated with success!!",
        'results' => $params
        ];

    }

}

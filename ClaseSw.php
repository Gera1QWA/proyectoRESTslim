<?php    
    namespace ClaseSw;
    class DB {
        private $project;

        public function __construct($project) {
                $this->project = $project;
        }
        private function runCurl($collection, $document) {
            $url = 'https://'.$this->project.'.firebaseio.com/'.$collection.'/'.$document.'.json';
            $ch =  curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
            $response = curl_exec($ch);
        
            curl_close($ch);
        
            // Se convierte a Object o NULL
            return json_decode($response);
        }
        public function isUser($name)
        {
            if( !is_null( $this->runCurl('usuarios',$name))) {
               return true;
            } else {
                return false;
            }
        }
        public function obtainPass($user)
       {
            return $this->runCurl('usuarios',$user);

       } 
       public function isCategoryDB($categoria)
       {
           
           if(!is_null($this->runCurl('productos',$categoria))) {
              return true;
           } else {
               return false;
           }
       }
    public function obtainProduc($categoria)
    {
        return $this->runCurl('productos',$categoria);

    }
    public function isIsbnDd($clave)
    {
        
        if(!is_null($this->runCurl('detalles',$clave))) {
           return true;
        } else {
            return false;
        }
    }
    public function obtainDetails($isbn)
    {
        return $this->runCurl('detalles',$isbn);
    }
    public function obtainMessage($code)
    {
       return $this->runCurl('respuestas',$code);
    }



    }
    // $dataBase = new DB('serviciosweb-2039-default-rtdb');
    // $res2 = $dataBase->obtainPass('pruebas2');
    // if(!is_null($res2)) {
    //  echo '<br>'.json_encode($res2).'<br>';
    // } else {
    //  echo '<br>No se encontraron resultados<br>';
    // }

?>
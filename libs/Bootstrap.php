<?php

class Bootstrap {
	
	// armazena a url atual
    private $_url = null;
	
	//armazena o controller
    private $_controller = null;
    
	
	//paths dos arquivos
    private $_controllerPath = 'controllers/'; // controller
    private $_modelPath = 'models/'; // models
    private $_errorFile = 'error.php'; //arquivo de erro padrao
    private $_defaultFile = 'index.php'; //pagina default
    
    /**
     * Inicializa o Bootstrap
     * 
     * @return boolean
     */
    public function init()
    {
        // seta a url $_url
        $this->_getUrl();
        
		// carrega o controller default caso a URL nao exista
        // 
        if (empty($this->_url[0])) {
            $this->_loadDefaultController();
            return false;
        }
		
        $this->_loadExistingController();
        $this->_callControllerMethod();
    }
    
    /**
     * Seta um path customizado para os controllers
     * @param string $path
     */
    public function setControllerPath($path)
    {
        $this->_controllerPath = trim($path, '/') . '/';
    }
    
    /**
     * (Optional) Seta um path customizado para os models
     * @param string $path
     */
    public function setModelPath($path)
    {
        $this->_modelPath = trim($path, '/') . '/';
    }
    
    /**
     * (Optional) Seta um path customizado para o arquivo de erro
     * @param string $path ex: error.php
     */
    public function setErrorFile($path)
    {
        $this->_errorFile = trim($path, '/');
    }
    
    /**
     * (Optional) Seta um path customizado para o arquivo da pagina default
     * @param string $path ex: index.php
     */
    public function setDefaultFile($path)
    {
        $this->_defaultFile = trim($path, '/');
    }
    
    /**
     * pega a url do $_GET
     */
    private function _getUrl()
    {
        $url = isset($_GET['url']) ? $_GET['url'] : null;
        $url = rtrim($url, '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $this->_url = explode('/', $url);
		// ou
		//$this->_url=explode('/',filter_var(rtrim($_GET['url'],'/')),FILTER_SANITIZE_URL);
    }
    
    /**
     *utilizado se não há um $_GET
     */
    private function _loadDefaultController()
    {
        require_once $this->_controllerPath . $this->_defaultFile;
        $this->_controller = new Index();
        $this->_controller->loadModel('index', $this->_modelPath);
        $this->_controller->index();		
    }
    
    /**
    
    
    /**
     * Carrega um controller existente passado no $_GET
     * 
     * @return boolean|string
     */
    private function _loadExistingController()
    {
      
        $file = $this->_controllerPath . $this->_url[0] . '.php';
		//testa se o arquivo do controller existe
        if (file_exists($file)) {
            require_once $file;
            $this->_controller = new $this->_url[0];
            $this->_controller->loadModel($this->_url[0], $this->_modelPath);
        } else {
            $this->_error();
            return false;
        }
    }
    
    /**
     * se for passado algum método na url
     * EX:
     *  http://localhost/controller/method/(param)/(param)/(param)
     *  url[0] = Controller
     *  url[1] = Method
     *  url[2] = Param
     *  url[3] = Param
     *  url[4] = Param
	 * Este método pode ser melhorado, para evitar o uso do case...
     */
    private function _callControllerMethod()
    {
        $length = count($this->_url);
        
        // verifica se o metodo chamado existe
        if ($length > 1) {
            if (!method_exists($this->_controller, $this->_url[1])) {
                $this->_error();
            }
        }
        
        // Verifica o que carregar
        switch ($length) {
            case 5:
                //Controller->Method(Param1, Param2, Param3)
                $this->_controller->{$this->_url[1]}($this->_url[2], $this->_url[3], $this->_url[4]);
                break;
            
            case 4:
                //Controller->Method(Param1, Param2)
                $this->_controller->{$this->_url[1]}($this->_url[2], $this->_url[3]);
                break;
            
            case 3:
                //Controller->Method(Param1, Param2)
                $this->_controller->{$this->_url[1]}($this->_url[2]);
                break;
            
            case 2:
                //Controller->Method(Param1, Param2)
                $this->_controller->{$this->_url[1]}();
                break;
            
            default:
                $this->_controller->index();
                break;
        }
    }
    
   /**
     * Mostra a pagina de erro caso nao encontre a pagina desejada
     * 
     * @return boolean
     */
    private function _error() {

        if (!class_exists('Erro', false)) {
            require_once $this->_controllerPath . $this->_errorFile;
            $this->_controller = new Erro();
        }
        $this->_controller->index();
        exit;
    }
}
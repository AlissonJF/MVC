<?php

if (count($argv) > 2) {
    echo 'O script builder.php só aceita 1 parametro: nome. Ele deve ser escrito com todas as palavras' .
        ' emendadas com as iniciais maiúsculas, sem acentos ou caracterers especiais. Ex: "php builder.php Login" ou "php builder.php CadastroPrincipal".';
    exit;
}

if (!folder_exist('models') || !folder_exist('controllers') || !folder_exist('views')) {
    echo 'Diretórios requeridos não foram encontrados. Possivelmente o arquivo builder.php não está no diretório root da aplicação.';
    exit;
}

if (!strtolower($argv[1])) {
    echo 'Você precisa digitar o parametro nome para executar o script. (O nome deve ser escrito com todas as palavras' .
        ' emendadas com as iniciais maiúsculas, sem acentos ou caracterers especiais. Ex: "php builder.php Login" ou "php builder.php CadastroPrincipal".)';
    exit;
}

if (!ctype_upper(substr($argv[1], 0, 1))) {
    echo 'Você precisa digitar corretamente o parametro nome para executar o script. (O nome deve ser escrito com todas as palavras' .
        ' emendadas com as iniciais maiúsculas, sem acentos ou caracterers especiais. Ex: "php builder.php Login" ou "php builder.php CadastroPrincipal".)';
    exit;
}

$fileName = strtolower(tirarAcentos($argv[1]));
$name = tirarAcentos($argv[1]);
$titleName = preg_replace('/(?<!\ )[A-Z]/', ' $0', $name);

$model = '
<?php
class ' . $name . '_Model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }
    public function example()
    {
        
    }
}';
file_put_contents('models/' . $fileName . '_model.php', $model);

$controller = '
<?php
class ' . $name . ' extends Controller {
    function __construct() {
        parent::__construct();
		$this->view->js = array("views/' . $fileName . '/index.js");
		$this->view->css = array("views/' . $fileName . '/index.css");
    }
    function index()
    {
        $this->view->title = "' . $titleName . '";
        $this->view->render("header");
        $this->view->render("'.$fileName.'/index");
        $this->view->render("footer");
    }
    function example()
    {
        $this->model->example();
    }
}';
file_put_contents('controllers/' . $fileName . '.php', $controller);

$indexView = '
<!-- Jumbotron -->
<div id="intro" class="py-5 text-center bg-light">
        <h1 class="mb-0 h4"><?=$this->title?></h1>
      </div>
      <!-- Jumbotron -->
    </header>
    <!--Main Navigation-->
    <!--Main layout-->
    <main class="mt-4 mb-5">
      <div class="container">
        <div class="row">
		</div>
      </div>
    </main>
    <!--Main layout-->
';

if (!is_dir('views/' . $fileName)) {
    mkdir('views/' . $fileName);
}

file_put_contents('views/' . $fileName . '/index.php', $indexView);
file_put_contents('views/' . $fileName . '/index.js', '');
file_put_contents('views/' . $fileName . '/index.css', '');

function folder_exist($folder)
{
    $path = realpath($folder);
    return ($path !== false and is_dir($path)) ? $path : false;
}

function tirarAcentos($string)
{
    return preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/"), explode(" ", "a A e E i I o O u U n N"), $string);
}
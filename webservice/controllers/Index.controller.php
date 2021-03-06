<?php

/**
 * Controlador que deverá ser chamado quando não for
 * especificado nenhum outro
 *
 * Camada - Controladores ou Controllers
 *
 * @package Sistema de Lead
 * @author Inaweb
 * @version 1.0
 */
class IndexController extends MainController {

    public function __construct() {
        echo json_encode('OK CONSTRUCT');
    }

    /**
     * Ação que deverá ser executada quando
     * nenhuma outra for especificada, do mesmo jeito que o
     * arquivo index.html ou index.php é executado quando nenhum
     * é referenciado
     */
    public function indexAction() {
        $this->parametros;
        $this->parametrosPost;
        echo json_encode('OK');
    }
}
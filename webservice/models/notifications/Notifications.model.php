<?php

/**
 * Usuario.model [ MODEL USUARIO ]
 * Responsável por gerenciar os usuários no Admin do sistema!
 */
class NotificationsModel {

    private $Data;
    private $User; //idUsuario
    private $Error;
    private $Result;

    //Paginacao
    private $paginacao;


    //Nome da tabela no banco de dados
    const Entity = 'central_de_noticacao';


    /**
     * <b>Verificar Cadastro:</b> Retorna TRUE se o cadastro ou update for efetuado ou FALSE se não.
     * Para verificar erros execute um getError();
     * @return BOOL $Var = True or False
     */
    public function getResult() {
        return $this->Result;
    }

    /**
     * <b>Obter Erro:</b> Retorna um array associativo com um erro e um tipo.
     * @return ARRAY $Error = Array associatico com o erro
     */
    public function getError() {
        return $this->Error;
    }




    /*
     * ***************************************
     * **********  PRIVATE METHODS  **********
     * ***************************************
     */


    /**
     * Seleciona o Usuário
     * @param Int $idUsuario
     */
    public function getNotifications($idUsuario, $idTipoNotificacao) {

        $sql = 'SELECT idUsuario, idReferencia, idTipoNotificacao  FROM  central_de_notificacao
                WHERE central_de_notificacao.idUsuario=:idUsuario  
                AND central_de_notificacao.idTipoNotificacao=:idTipoNotificacao 
                AND DATE(dataCadastro) = CURRENT_DATE()';

        $Select = new Select;
        $Select->FullSelect($sql, "idUsuario={$idUsuario}&idTipoNotificacao={$idTipoNotificacao}");
        if (!empty($Select->getResult())):
            $this->Result = $Select->getResult();
        else:
            $this->Result = false;
        endif;
    }


    public function registrarDispositivoNotifications($idUsuario, $idDispositivo, $gcmId){

        $idRegistroApp = $this->checkRegistroDeDispositivo($idUsuario, $idDispositivo);
        if(empty($idRegistroApp['gcmId'])){
            $ct = new Update;
            $ct->ExeUpdate('app_id_user',['gcmId'=>$gcmId], "WHERE idUsuario=:idUsuario AND idDispositivo=:idDispositivo","idUsuario={$idUsuario}&idDispositivo={$idDispositivo}" );
            $this->Result = $ct->getResult();
        }else{
            $this->Result = $idRegistroApp['idAppIdUser'];
        }
    }

    private function checkRegistroDeDispositivo($idUsuario, $idDispositivo){

        $sql = 'SELECT *  FROM  app_id_user
                WHERE app_id_user.idUsuario=:idUsuario  
                AND app_id_user.idDispositivo=:idDispositivo';

        $Select = new Select;
        $Select->FullSelect($sql, "idUsuario={$idUsuario}&idDispositivo={$idDispositivo}");
        if (!empty($Select->getResult())):
            return $Select->getResult()[0];
        else:
            return false;
        endif;
    }


}

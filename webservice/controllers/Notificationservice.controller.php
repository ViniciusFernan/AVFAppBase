<?php

/**
 * Controller Usuarios - Responsável por toda administração de usuario do sistema
 * @package Sistema de Lead
 * @author Inaweb
 * @version 1.0
 */

class NotificationserviceController extends MainController {

    public function indexAction() {
        $this->parametros;
        $this->parametrosPost;
        echo json_encode('NO Action');
    }

    public function getNotificationsAction(){
        $resp=[];
        $data=[];
        $resp['type'] = 'error';
        $resp['data'] = [];

        require ABSPATH . "/models/notifications/Notifications.model.php";
        $notifications = new NotificationsModel;

        if(!empty($this->parametrosPost)):
            if(!empty($this->parametrosPost['idUsuario']) && !empty($this->parametrosPost['tipoNotificacao'])):
                $notifications->getNotifications($this->parametrosPost['idUsuario'], $this->parametrosPost['tipoNotificacao']);
                if(!empty($notifications->getResult())):
                    $resp['type'] = 'success';
                    $resp['data'] = $notifications->getResult();
                endif;
            endif;
        endif;
        echo json_encode($resp);
        exit;
    }


    /**
     * Prepara alerta google GCM para disparo
     * @param Array $chaveDoDispositivo [chave_1, chave_2]
     * @param String $titulo "Titulo Mensagem ao usuario"
     * @param String $mensagem "Conteudo da mensagem"
     */
    public function sendMesagemGCMAction($chaveDoDispositivo, $titulo, $mensagem){

        if(!empty($chaveDoDispositivo)):

            $chaveDoDispositivo = (is_array($chaveDoDispositivo) ? $chaveDoDispositivo : [$chaveDoDispositivo] );
            $titulo = (!empty($titulo)? $titulo : 'Tem coisa novo na area!');
            $mensagem = (!empty($mensagem) ? $mensagem : 'Corre la para ver em primeira mão!!');

            $this->setGCMNotificationAction($chaveDoDispositivo, $titulo, $mensagem);
        endif;

    }


    /**
     * Dispara alerta google GCM
     * @param Array $chaveDoDispositivo [chave_1, chave_2]
     * @param String $titulo "Titulo Mensagem ao usuario"
     * @param String $mensagem "Conteudo da mensagem"
     */
    private function setGCMNotificationAction($chaveDoDispositivo, $titulo, $mensagem){

        $chaveDoDispositivo; //chaves dos aps instalados nos usuarios DEVE SER UM ARRAY 1 ou mais chave [chave_1, chave_2]

        $title = $titulo;

        $message = $mensagem;

        // Google Cloud Messaging GCM API Key
        define(
            "GOOGLE_API_KEY",
            "AAAAK9p-w04:APA91bEP5cSoEqjjtasG3k6q2ykky9GB4LWprDLb7N2fcw_p87eS2mlI0MZp4DXBRzxAtnDrix_V293yOhHP8X4M0E18DiVO95uhxFNB1KWUoGOA4rfekwsbt0KA7MT-XE_jONz3e5r2"
        );


        $headers = array(
            'Authorization: key='.GOOGLE_API_KEY,
            'Content-Type: application/json'
        );

        $fields = array(
            'registration_ids' => $chaveDoDispositivo,
            "data" => array(
                "title" => $title,
                "message" => $message,
                'msgcnt' => count($message),
                'timestamp' => date('Y-m-d h:i:s'),
                'sound' => 1,
                'vibrate' => 1,
            ),
        );



        //Google cloud messaging GCM-API url
        $url = 'https://gcm-http.googleapis.com/gcm/send'; //https://android.googleapis.com/gcm/send

        $GCM = curl_init();
        curl_setopt( $GCM,CURLOPT_URL, $url );
        curl_setopt( $GCM,CURLOPT_POST, true );
        curl_setopt( $GCM,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $GCM,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $GCM,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $GCM,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $resultJson = curl_exec($GCM);
        $result = json_decode($resultJson);
        curl_close( $GCM );

        $this->logCGMError($resultJson);
        if($result->failure==1){
            $this->logCGMError($resultJson);
        }
    }

    public function  registroDeDispositivoAction(){

        $resp=[];
        $data=[];
        $resp['type'] = 'error';
        $resp['data'] = [];

        require ABSPATH . "/models/notifications/Notifications.model.php";
        $notifications = new NotificationsModel;

        if(!empty($this->parametrosPost)):
            if( !empty($this->parametrosPost['idUsuario'])  && !empty($this->parametrosPost['registroDeDispositivo'])  &&  !empty($this->parametrosPost['gcmId']) ):
                $notifications->registrarDispositivoNotifications($this->parametrosPost['idUsuario'], $this->parametrosPost['registroDeDispositivo'], $this->parametrosPost['gcmId']);
                if(!empty($notifications->getResult())):
                    $this->sendMesagemGCMAction([$this->parametrosPost['gcmId']], 'Seja bem vindo', 'Seja bem vindo ao nosso sitema de indicações');
                    $resp['type'] = 'success';
                    $resp['data'] = $notifications->getResult();
                endif;
            endif;
        endif;
        echo json_encode($resp);
        exit;

    }

    private function logCGMError($msg){

        $fp = fopen("logGCM.txt", "a");
        fwrite($fp, $msg."\r\n");
        fclose($fp);

    }

}

<?php

/**
 * Usuario.model [ MODEL USUARIO ]
 * Responsável por gerenciar os usuários no Admin do sistema!
 */
class UsuariosModel {

    private $Data;
    private $User; //idUsuario
    private $Error;
    private $Result;

    //Paginacao
    private $paginacao;


    //Nome da tabela no banco de dados
    const Entity = 'usuario';


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

    //Gera a senha combinando com o HASH
    private function geraSenha($senha) {
        return md5(HASH . $senha);
    }




    /**
     * Seleciona o Usuário
     * @param Int $idUsuario
     */
    public function getUserFromId($idUsuario) {
        $this->User = $idUsuario;

        $sql = 'SELECT * FROM  usuarios
                WHERE usuarios.idUsuario =:user
                AND usuarios.status = 1';

        $Select = new Select;
        $Select->FullSelect($sql, "user={$this->User}");
        if (!$Select->getResult()):
            $this->Error = ["Usuário não encontrado", ERR_ERROR, true];
            $this->Result = false;
        else:
            $this->Result = $Select->getResult()[0];
        endif;
    }


    /**
     * Seleciona o Usuário
     * @param Int $idUsuario
     */
    public function getUserFromEmailAndPassword($email, $senha) {

        $sql = 'SELECT * FROM  usuarios
                WHERE usuarios.email=:email
                AND usuarios.senha=:senha ';

        $Select = new Select;
        $Select->FullSelect($sql, "email={$email}&senha={$this->geraSenha($senha)}");
        if (!empty($Select->getResult())):
            $this->Result = $Select->getResult()[0];
        else:
            $this->Result = false;
        endif;
    }

    public function updateUsuario($data){
        $usuarioAtivo = [];
        $serialAtivo = [];
        $idUsuario = $data['idUsuario'];
        $serialApp = $data['passID'];

        // Remove os valores nulos
        unset($data['idUsuario'],  $data['email'], $data['idPerfil'], $data['passID']);

        $data['telefone'] = (!empty($data['telefone']) ? str_replace(['(', ')', '-'], '', $data['telefone']) : NULL );
        $data['senha'] = (!empty($data['senha']) ?  $this->geraSenha($data['senha'])  : NULL );

        $data = array_filter($data);

        $this->getUserFromId($idUsuario);
        $usuarioAtivo = $this->getResult();
        $serialAtivo = $this->checkRegistroDeDispositivoDoUsuario($idUsuario, $serialApp);

        if(!empty($usuarioAtivo) && !empty($serialAtivo)){
            $update = new Update();
            $update->ExeUpdate('usuarios', $data, "WHERE idUsuario=:idUsuario", "idUsuario={$idUsuario}");
            if (!empty($update->getResult())):
                $this->Result = $update->getResult();
            else:
                $this->Result = false;
            endif;
        }else{
            $this->Result = false;
        }

    }



    public function getDispositivoUsuario($idUsuario){
        $this->Result=[];

        $sql = 'SELECT gcmid FROM  app_id_user
                WHERE app_id_user.idUsuario=:idUsuario';

        $Select = new Select;
        $Select->FullSelect($sql, "idUsuario={$idUsuario}");
        if (!empty($Select->getResult())):
            $this->Result = $Select->getResult();
        endif;

    }


    public function registrarDispositivoUsuario($idUsuario, $idDispositivo){
        $idRegistroApp = $this->checkRegistroDeDispositivoDoUsuario($idUsuario, $idDispositivo);
        if(empty($idRegistroApp)){
            $ct = new Create;
            $ct->ExeCreate('app_id_user',['idUsuario'=>$idUsuario, 'idDispositivo' => $idDispositivo ]);
            return $ct->getResult();
        }else{
            return $idRegistroApp[0]['idAppIdUser'];
        }

    }


    private function checkRegistroDeDispositivoDoUsuario($idUsuario, $idDispositivo){

        $sql = 'SELECT *  FROM  app_id_user
                WHERE app_id_user.idUsuario=:idUsuario  
                AND app_id_user.idDispositivo=:idDispositivo';

        $Select = new Select;
        $Select->FullSelect($sql, "idUsuario={$idUsuario}&idDispositivo={$idDispositivo}");
        if (!empty($Select->getResult())):
            return $Select->getResult();
        else:
            return false;
        endif;
    }


    public function updateImagemPerfilUsuario($post, $files){

        $newImageUploaded=NULL;
        if(!empty($post) && !empty($files)){
            //verificar se usuario existe e app esta liberado para usar metodo
            $usuarioApp = NULL;
            $usuarioApp = $this->checkRegistroDeDispositivoDoUsuario($post['idUsuario'], $post['appID']);

            //UPLOAD DE IMAGEM
            if(!empty($usuarioApp)){
                //_upload/usuarios/idUsuario/foto.jpeg => 800 x auto
                $NewName = 'imgPerfil';
                $newImageUploaded = $this->processaImagensUsuarios($post['idUsuario'], $files, $NewName, 800, 800, true, 'perfil',true);
                if($newImageUploaded){
                    //_upload/usuarios/idUsuario/foto-min.jpeg => 250 x 250
                    $NewName = 'min-imgPerfil';
                    $this->processaImagensUsuarios($post['idUsuario'], $files, $NewName, 250, 250, true, 'perfil',true);
                }

                //atualizar usuario com imagem de perfil
                $dataIMG['imgPerfil'] = $newImageUploaded;
                $dataIMG['idUsuario'] = $post['idUsuario'];
                $dataIMG['passID'] = $post['appID'];
                $this->updateUsuario($dataIMG);

            }
        }
        //return $dataIMG;
        return $newImageUploaded;
    }



    private function processaImagensUsuarios($idUsuario, $FILES, $NewName, $x=NULL, $y=NULL, $crop=false, $pacote, $rename){
        $dir = $_SERVER['DOCUMENT_ROOT']."/_uploads/".$idUsuario."/".(!empty($pacote)? $pacote.'/' : '' );
        require_once ABSPATH.'/lib/uploadVerot/class.upload.php';

        $handle = new upload($FILES);
        $handle->file_new_name_body  = $NewName;
        $handle->dir_auto_create     = true;
        $handle->image_convert       = 'jpg';
        $handle->image_resize        = true;
        $handle->jpeg_quality        = 100;
        $handle->image_ratio_fill    = true;
        $handle->image_ratio_crop    = $crop;

        if($x){ $handle->image_x     = $x; }

        if($y){ $handle->image_y     = $y;}

        $handle->file_overwrite      = ($rename==true ? false : true );
        $handle->file_auto_rename    = $rename; //rename arquivo se ja existir
        $handle->allowed             = array('image/jpeg','image/jpg','image/gif','image/png');
        $handle->process($dir);
        $newImage = $handle->file_dst_name;

        if($handle->processed) {
            return $newImage;
        }else{
            return false;
        }

    }


}

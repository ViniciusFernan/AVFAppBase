<?php

/**
 * Controller Usuarios - Responsável por toda administração de usuario do sistema
 * @package Sistema de Lead
 * @author Inaweb
 * @version 1.0
 */

class ExerciciosserviceController extends MainController {

    public function indexAction() {
        $this->parametros;
        $this->parametrosPost;
        echo json_encode('NO Action');
    }

    public function getAllExerciciosAction(){
        $resp=[];
        $data=[];
        $resp['type'] = 'error';
        $resp['data'] = [];

        require ABSPATH . "/models/exercicios/Exercicios.model.php";
        $exercicios = new ExerciciosModel();



        $exercicios->getAllExercicios();
        if(!empty($exercicios->getResult())):
            $resp['type'] = 'success';
            $resp['data'] = $exercicios->getResult();
        endif;

        echo json_encode($resp);
        exit;
    }

    public function getExercicioFromIdAction(){
        $resp['type'] = 'error';
        $resp['data'] = [];

        require ABSPATH . "/models/exercicios/Exercicios.model.php";
        $exercicios = new ExerciciosModel();

        if(!empty($this->parametrosPost)):
            $exercicios->getExercicioFromId($this->parametrosPost['idExercicio']);
            if(!empty($exercicios->getResult() )):
                $resp['type'] = 'success';
                $resp['data'] = $exercicios->getResult();
            endif;
        endif;
        echo json_encode($resp);
        exit;
    }


    public function getAllGrupoMuscularAction(){
        $resp['type'] = 'error';
        $resp['data'] = [];

        require ABSPATH . "/models/exercicios/Exercicios.model.php";
        $exercicios = new ExerciciosModel();

        $exercicios->getAllGrupoMuscular();
        if(!empty($exercicios->getResult() )):
            $resp['type'] = 'success';
            $resp['data'] = $exercicios->getResult();
        endif;

        echo json_encode($resp);
        exit;
    }


    public function getGrupoMuscularESeusExerciciosAction(){

        $resp['type'] = 'error';
        $resp['data'] = [];
        $grupo=[];
        $exercicios=[];

        require ABSPATH . "/models/exercicios/Exercicios.model.php";
        $grupoExercicios = new ExerciciosModel();
        $grupoExercicios->getAllGrupoMuscular();
        $grupo = $grupoExercicios->getResult();

        $grupoExercicios->getAllExercicios();
        $exercicios = $grupoExercicios->getResult();

        $respGME=[];
        if(!empty($grupo) && !empty($exercicios)):
            foreach($grupo as $key => $value):
                $respGME[$key] = $value;
                $respGME[$key]["exercicios"] = [];

                foreach($exercicios as $key2 => $value2):
                    if($value['idGrupoMuscular'] == $value2['idGrupoMuscular'] ):
                        $respGME[$key]["exercicios"][]=$value2;
                    endif;
                endforeach;
            endforeach;

            $resp['type'] = 'success';
            $resp['data'] = $respGME;
        endif;


        echo json_encode($resp);
        exit;
        
    }


}

<?php

/**
 * Usuario.model [ MODEL USUARIO ]
 * Responsável por gerenciar os usuários no Admin do sistema!
 */
class ExerciciosModel {

    private $Data;
    private $User; //idUsuario
    private $Error;
    private $Result;

    //Paginacao
    private $paginacao;


    //Nome da tabela no banco de dados
    const Entity = 'exercicios';


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



    /**
     * Seleciona todos os Exercicios
     * @param Int $idUsuario
     */
    public function getAllExercicios() {

        $sql = 'SELECT * FROM exercicios';

        $Select = new Select;
        $Select->FullSelect($sql);
        if(empty($Select->getResult() )):
            $this->Error = ["Exercicios não encontrado", ERR_ERROR, true];
            $this->Result = false;
        else:
            $this->Result = $Select->getResult();
        endif;
    }


    /**
     * Seleciona o Usuário
     * @param Int $idUsuario
     */
    public function getExercicioFromId($idExercicio) {

        $sql = "SELECT * FROM exercicios  WHERE exercicios.idExercicio =:idExercicio AND exercicios.status=1";

        $Select = new Select;
        $Select->FullSelect($sql, "idExercicio={$idExercicio}");
        if(empty($Select->getResult()) ):
            $this->Error = ["Exercicio não encontrado", ERR_ERROR, true];
            $this->Result = false;
        else:
            $this->Result = $Select->getResult()[0];
        endif;
    }


    public function getAllGrupoMuscular(){
        $sql = "SELECT * FROM grupo_muscular  WHERE grupo_muscular.status=1";
        $Select = new Select;
        $Select->FullSelect($sql);
        if(empty($Select->getResult()) ):
            $this->Error = ["Grupo muscular não encontrado", ERR_ERROR, true];
            $this->Result = false;
        else:
            $this->Result = $Select->getResult();
        endif;
    }

}

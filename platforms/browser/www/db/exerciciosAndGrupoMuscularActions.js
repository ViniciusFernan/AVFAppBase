var EXERCICIOSANDGRUPOMUSCULAR_ACTIONS = {
    constructor:function(){

    },



    getListExerciciosMicroService: function(){
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: urlWebservices+'/Exerciciosservice/getAllExercicios',
            beforeSend: function(){ },
            complete: function(){ },
            success: function(exercicios){
                if(exercicios.type=='success'){
                    window.localStorage.setItem('exercicios', JSON.stringify(exercicios.data));
                }
            },
            error: function (error) {
                return false;
            }
        });
    },


    getListExercicios: function(){
        var EXERCICIOS = JSON.parse(window.localStorage.getItem('exercicios'));
        if(EXERCICIOS==null){
            EXERCICIOSANDGRUPOMUSCULAR_ACTIONS.getListExerciciosMicroService();
        }
        return JSON.parse(window.localStorage.getItem('exercicios'));


    },



    getListGrupoMuscularMicroService: function(){
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: urlWebservices+'/Exerciciosservice/getAllGrupoMuscular',
            beforeSend: function(){ },
            complete: function(){ },
            success: function(grupoMuscular){
                if(grupoMuscular.type=='success'){
                    window.localStorage.setItem('grupoMuscular', JSON.stringify(grupoMuscular.data));
                }

            },
            error: function (error) {
                return false;
            }
        });
    },



    getListaGrupoMuscular: function(){
        var grupoMuscular = JSON.parse(window.localStorage.getItem('grupoMuscular'));
        if(grupoMuscular==null){
            EXERCICIOSANDGRUPOMUSCULAR_ACTIONS.getListGrupoMuscularMicroService();
        }
        return JSON.parse(window.localStorage.getItem('grupoMuscular'));
    }



}
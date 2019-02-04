var EXERCICIOSANDGRUPOMUSCULAR_ACTIONS = {
    constructor:function(){

    },



    getListExerciciosMicroService: function(){
        return $.ajax({
            type: 'POST',
            dataType: 'json',
            url: urlWebservices+'/webservice/Exerciciosservice/getAllExercicios',
            beforeSend: function(){ },
            complete: function(){ },
            success: function(exercicios){
                window.localStorage.setItem('exercicios', JSON.stringify(exercicios));
                return exercicios;
            },
            error: function (error) {
                return false;
            }
        });
    },


    getListExercicios: function(){
        return JSON.parse(window.localStorage.getItem('exercicios'));
    },



    getListGrupoMuscularMicroService: function(){
        return $.ajax({
            type: 'POST',
            dataType: 'json',
            url: urlWebservices+'/webservice/Exerciciosservice/getAllGrupoMuscular',
            beforeSend: function(){ },
            complete: function(){ },
            success: function(grupoMuscular){
                window.localStorage.setItem('grupoMuscular', JSON.stringify(grupoMuscular));
                return grupoMuscular;
            },
            error: function (error) {
                return false;
            }
        });
    },



    getListaGrupoMuscular: function(){
        return JSON.parse(window.localStorage.getItem('grupoMuscular'));
    }



}
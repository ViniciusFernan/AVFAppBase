var TREINOS_ACTIONS = {

    initPage: function () {
        app.splashShow();
        var usuario = JSON.parse( window.localStorage.getItem('usuario'));
        if( usuario==null ){
            window.localStorage.clear();
            window.location="index.html";
        };

        this.carregaDadosTabelaGrupoExercicios();
        app.splashHide();

    },


    carregaDadosTabelaGrupoExercicios: function () {
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: urlWebservices+'/Exerciciosservice/getGrupoMuscularESeusExercicios',
            beforeSend: function(){ },
            complete: function(){ },
            success: function(grupoMuscular){
                if(grupoMuscular.type=='success'){
                    window.localStorage.setItem('grupoMuscularExercicios', JSON.stringify(grupoMuscular.data));
                    var html = '';

                    $.each(grupoMuscular.data ,function(x, item){
                        var idCorrent = '';

                        html += '<div class="card" data-IDG="' + item.idGrupoMuscular + '">';
                        html += '<div class="card-header" id="card-' + item.idGrupoMuscular + '">';
                        html += '<a data-toggle="collapse" data-parent="#accordion" href="#collapse-' + item.idGrupoMuscular + '" aria-expanded="false" ><i class="fas fa-clipboard-list"></i>  Exercicios para ' + item.nome + ' <span class="qtitemselected"></span><i class="fa float-right iconRight" ></i> </a>';
                        html += '</div>';
                        html += '<div id="collapse-' + item.idGrupoMuscular + '" class="panel-collapse collapse in" data-parent="#accordion" style="padding: 0px 0px">';
                        html += '<ul class="list-group no-border-lr" id="grupo-'+item.idGrupoMuscular+'">';

                        if(item.exercicios!==null){
                            for(var j = 0; j < item.exercicios.length; j++){
                                var exercicio = item.exercicios[j];
                                if(item.idGrupoMuscular == exercicio.idGrupoMuscular) {
                                    html += '<li class="list-group-item"  data-IDE="' + exercicio.idExercicio + '" > <i class="fas fa-dumbbell"></i> ' + exercicio.nome + ' <span class="badge checkedExe  float-right"><i class="fas fa-check-double"></i></span></li>';
                                }
                            }
                        }

                        html += '</ul>';
                        html += '</div>';
                        html += '</div>';

                    });
                }

                $('#listaGrupoMuscular').html(html);
                var fichatreinos = JSON.parse(window.localStorage.getItem('fichatreinos'));
                if(fichatreinos!==null){
                    $.each(fichatreinos, function (x, val){
                        $('li[data-ide='+val+']').addClass('selectedItem');
                    });
                    TREINOS_ACTIONS.counItemSelected();
                }

            },
            error: function (error) {
                return false;
            }
        });
    },

    counItemSelected: function(){
        var n='';
        $.each($('#listaGrupoMuscular .card') ,function(x, card){
            n=$(card).find('.panel-collapse .list-group li.selectedItem').length;
            if(n>0){
                $(card).find('.card-header a .qtitemselected').html(n);
            }else{
                $(card).find('.card-header a .qtitemselected').html('');
            }
        });

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




}
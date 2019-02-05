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

        var grupoMuscularExercicios =  EXERCICIOSANDGRUPOMUSCULAR_ACTIONS.getListaGrupoMuscular();
        var exerciciosG = EXERCICIOSANDGRUPOMUSCULAR_ACTIONS.getListExercicios();


        var html = '';
        if (grupoMuscularExercicios!==null){
            var idCorrent = '';
            for (var i = 0; i < grupoMuscularExercicios.length; i++){
                var item = grupoMuscularExercicios[i];

                if(item && idCorrent != item.idGrupoMuscular){
                    html += '<div class="card" data-IDG="' + item.idGrupoMuscular + '">';
                    html += '<div class="card-header" id="card-' + item.idGrupoMuscular + '">';
                    html += '<h6 class="mb-0">';
                    html += '<a data-toggle="collapse" data-parent="#accordion" href="#collapse-' + item.idGrupoMuscular + '" aria-expanded="false" ><i class="fas fa-clipboard-list"></i>  Exercicios para ' + item.nome + ' <span class="qtitemselected"></span><i class="fa float-right" ></i> </a>';
                    html += '</h6>';
                    html += '</div>';
                    html += '<div id="collapse-' + item.idGrupoMuscular + '" class="panel-collapse collapse in" data-parent="#accordion" style="padding: 10px 8px">';
                    html += '<ul class="list-group" id="grupo-'+item.idGrupoMuscular+'">';

                    if (exerciciosG!==null) {
                        for (var j = 0; j < exerciciosG.length; j++) {
                            var exercicios = exerciciosG[j];
                            if (item.idGrupoMuscular == exercicios.idGrupoMuscular) {
                                html += '<li class="list-group-item"  data-IDE="' + exercicios.idExercicio + '" > <i class="fas fa-dumbbell"></i> ' + exercicios.nome + ' <span class="badge checkedExe  float-right"><i class="fas fa-check-double"></i></span></li>';
                            }
                        }
                    }

                    html += '</ul>';
                    html += '</div>';
                    html += '</div>';
                }

                idCorrent = item.idGrupoMuscular;


            }

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

    counItemSelected: function(){
    var n='';
    $.each($('#listaGrupoMuscular .card') ,function(x, card){
        n=$(card).find('.panel-collapse .list-group li.selectedItem').length;
        if(n>0){
            $(card).find('.card-header h6 a .qtitemselected').html(n);
        }else{
            $(card).find('.card-header h6 a .qtitemselected').html('');
        }
    });

}

}
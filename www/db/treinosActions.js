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

        var sqlite = 'SELECT *, exercicios.nome AS nomeE, exercicios.status AS statusE  FROM exercicios  INNER JOIN grupo_muscular  ON exercicios.idGrupoMuscular=grupo_muscular.idGrupoMuscular';
        window.AVFDB.transaction(function (tx) {
            tx.executeSql(sqlite, [], function (x, results) {
                var grupoMuscularExercicios = [];
                var html = '';
                if (results.rows.length > 0) {
                    var idCorrent = '';
                    for (var i = 0; i < results.rows.length; i++) {
                        var item = results.rows.item(i);

                        if(item && idCorrent != item.idGrupoMuscular){
                            html += '<div class="card" data-IDG="' + item.idGrupoMuscular + '">';
                            html += '<div class="card-header" id="card-' + item.idGrupoMuscular + '">';
                            html += '<h6 class="mb-0">';
                            html += '<a data-toggle="collapse" data-parent="#accordion" href="#collapse-' + item.idGrupoMuscular + '" aria-expanded="false" ><i class="fas fa-clipboard-list"></i>  Exercicios para ' + item.nome + ' <span class="qtitemselected"></span><i class="fa float-right" ></i> </a>';
                            html += '</h6>';
                            html += '</div>';
                            html += '<div id="collapse-' + item.idGrupoMuscular + '" class="panel-collapse collapse in" data-parent="#accordion" style="padding: 10px 8px">';
                            html += '<ul class="list-group" id="grupo-'+item.idGrupoMuscular+'">';

                            for (var j = 0; j < results.rows.length; j++) {
                                var exercicios = results.rows.item(j);
                                if(item.idGrupoMuscular==exercicios.idGrupoMuscular){
                                    html += '<li class="list-group-item"  data-IDE="'+ exercicios.idExercicio +'" > <i class="fas fa-dumbbell"></i> '+exercicios.nomeE+' <span class="badge checkedExe  float-right"><i class="fas fa-check-double"></i></span></li>';
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
                var fechatreinos = JSON.parse(window.localStorage.getItem('fichatreinos'));
                $.each(fechatreinos, function (x, val){
                    $('li[data-ide='+val+']').addClass('selectedItem');
                });
                TREINOS_ACTIONS.counItemSelected();
            });
        });

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
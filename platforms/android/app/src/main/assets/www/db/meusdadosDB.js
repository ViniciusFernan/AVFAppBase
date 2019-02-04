var MEUSDADOS_DB = {
    initPage: function () {
        var usuario = JSON.parse( window.localStorage.getItem('usuario'));
        if( usuario==null ){
            window.localStorage.clear();
            window.location="index.html";
        };

        if(usuario){

            var item = usuario;
            var table="";
            table +='<form class="updateMeusDados" method="post" >';
            table +='<input type="hidden" name="idUsuario" value="'+item.idUsuario+'">';

            table += '<div class="row mb-3"><div class="col-6 pr-2 boximgUser"><img src="./img/avatar.png" class="imgUser" id="imgemPerfilMeusDados" > <i class="fas fa-camera novaImagem"></i> </div><div class="col-6 pl-0"> <p class="card-title form-group" > /' + ((item.nomeUsuario) ? item.nomeUsuario : '')  + '</p> <p class="card-text form-group">Email: '+item.email+'</p></div></div>';

            table +='<p class="card-title form-group" >Nome: <input type="text" name="nome" value="' + item.nomeCompleto + '"  class="form-control" ></p>';
            table +='<p class="card-text form-group">CPF: <input type="text" name="CPF" value="'+((item.CPF) ? item.CPF : '')+'"  class="form-control" ></p>';
            table +='<p class="card-text form-group">Telefone: <input type="tel" name="telefone" value="'+((item.telefone)? item.telefone : '')+'"  class="form-control" ></p>';
            table +='<p class="card-text form-group">Sexo: <select class="form-control" name="sexo"><option value="1" '+((item.sexo==1)? 'selected' :'')+'>Masculino</option> <option value="2" '+((item.sexo==2)?  'selected ' : '')+'>Feminino</option></select></p>';
            table +='<p class="card-text form-group">Sobre Mim:  <textarea name="sobreMim" class="form-control" >'+((item.sobreMim)? item.sobreMim : '')+'</textarea></p>';

            table +='<p class="card-title form-group boxSenha" >Alterar Senha: <input type="password" name="senha" value=""  class="form-control" > <i class="fa fa-eye verSenha"></i></p>';


            table +='<p class="card-text form-group">Status: <select class="form-control" name="status"><option value="1" '+((item.status==1)? 'selected' :'')+'>Ativo</option> <option value="-1" '+((item.status!=1)?  'selected ' : '')+'>Desativado</option></select></p>';
            table +='<p class="card-text form-group text-right "><button type="button" class="btn btn-primary salvar">Atualizar</button></p>';
            table +='</form>';
            $('#conteudoBXf').append(table);
        };

    },



};

var LOGIN_DB = {
    initPageLogin: function () {

        var usuario = window.localStorage.getItem('usuario');
        if (usuario) {
            //notify('SUCCESS!', 'USUARIO LOGADO! ', 'success', true);
            window.location = "dashboard.html";
        }

        $('#loginForm').submit(function (avf) {
            if(app.isOnline()==true){

                avf.preventDefault();
                $("#loginMSG").html('<img class="loader" src="./img/loader.gif">');
                var email = $('[name="email"]').val();
                var senha = $('[name="password"]').val();

                //ussing Promise gotchas
                USUARIOS_ACTIONS.getUsuarioMicroService(email, senha).done(function (MicroServiceResponse){
                    if (MicroServiceResponse.type==="success") {
                        USUARIOS_ACTIONS.setUsuario(MicroServiceResponse.data);
                        window.location = "dashboard.html";
                    }else{
                        $('.loader').fadeOut();
                        notify('OPS!', 'Falha ao Logar, email ou senha incorretos.', 'error', true);
                        //$('#loginMSG').html("Falha ao Logar, email ou senha incorretos");
                    }
                });

            }else{
                navigator.notification.alert('Você não esta conectado à internet. \n Verifique sua conexão e tente de novo. ', '','Desconectado', 'OK');
            }

        });

    }

};

var socket = null;
var namespace = "glove";
var url = null;
var chatTimerMensagem = {};
var urlAplicacao;

$(document).ready(function () {

	
	
	
    urlAplicacao = $("#urlAplicacao").val();


    try {

        if (window.location.toString().search('.com.br') != -1) {


			url = 'http://www.glovebrasil.com.br:9090';

        } else {
            url = 'http://localhost:9090/';

        }


        if (io) {

			 socket = io.connect(url, { 'max reconnection attempts': 100, 'auto connect': true });

            var interfaceChat = $("body").interfaceChat
							(
								{
								    url: url,
								    onPegaMeusDados: function (data) {
										console.log(data);
								        socket.emit('adduser', { usuario: data, namespace: namespace });
								    },
								    onAbreJanela: function (data) {
								        //$.fn.interfaceChat.metodos.registraSessaoChat(data.destinatario);
								    },
								    onDigitandoMensagem: function (data) {
								        socket.emit('digitandomsg', { remetente: data.remetente, destinatario: data.destinatario, sala: data.sala, msg: data.msg });
								    },
								    onDigitandoMensagemLimpa: function (data) {
								        socket.emit('digitandomsglimpa', { remetente: data.remetente, destinatario: data.destinatario, sala: data.sala, msg: data.msg });
								    },
								    onEnviaMensagem: function (data) {
								        socket.emit('sendchat', { remetente: data.remetente, destinatario: data.destinatario, sala: data.sala, msg: data.msg });
								    },
									onListaAmigos: function (data) {
										
										socket.emit('listaconectados',  {});
									}
								}
							);

			
			
			 
			
			
			
            socket.on('connect', function () {
				
                var me = window.parent.$.fn.interfaceChat.metodos.me();
            	me.success(function (data) {					  
					  var amigos = $.fn.interfaceChat.metodos.amigos();
					  amigos.success(function (data){					
							var listaamigos = $.fn.interfaceChat.metodos.listaamigos(data);
							//listaamigos.success(function (data){
								
							//});							
					    });
				});
				
				/*$(document).ajaxSuccess(function (data, status, xhr) {
					
                    if (status.id == "meusdados") {
						
                      
						
                    } else if (status.id == "buscaamigos") {
						
                        var amigos = arguments[3];						
						$.fn.interfaceChat.metodos.listaamigos(amigos);
						socket.emit('listaconectados',  {});

                    }
                });*/
            });
            socket.on('connecting', function () { /*console.log("conectando...");*/ })
            socket.on('connect_failed', function () {   /*console.log("conexão falha...");*/ })
            socket.on('error', function () {  /*console.log("erro ocorrido...");*/ })
            socket.on('reconnect_failed', function () {  /*console.log("reconexão falhada...");*/ })
            socket.on('reconnect', function () {  /*console.log("reconectou...");*/ })
            socket.on('reconnecting', function () {  /*console.log("reconectando...");*/ })
            socket.on('disconnect', function () {  /*console.log('desconectou');*/ });


            socket.on('listaconectados', function (data) {
				
				
				alert('aqui');
				
				console.log(data);
				
                //$.fn.interfaceChat.metodos.conectados(data);
            });

			
			
			socket.on('AmigoAdd', function (data) {
				var x = window.parent.$.fn.InterfaceUsuario.metodos.SolicitacoesPega();
				x.success(function (data) {
					 if (data.dados) {
						 var online = $.map(data.dados, function (n, i) { return i; }).length;

						 if (online > 0) {
							 $("#contaSolicitacoes").html(online);
						 } else {
							 $("#contaSolicitacoes").html(0);
						 }
					 }
				});
			});
			
            socket.on('notificacao', function (data) {

                var tipo = data.tipo;
                switch (tipo) {
                    case "MensagensAdd":
                        $.fn.interfaceChat.metodos.BuscaAtualizacoes(null);
                        $.fn.InterfaceUsuario.metodos.MensagensPega(1);
                        break;
						
					case "AmigoAdd":
							
						alert('aquii');	
							
                        break;
						
                    default:
                        $.fn.interfaceChat.metodos.BuscaAtualizacoes(null);
                        break;

                }


            });

            socket.on('fazlogoff', function () {
				//socket.disconnect();
				//window.location = '/conta/logoff?another=place';
            });

            socket.on('reconecta', function () {
                socket.emit('adduser', { usuario: $.fn.interfaceChat.metodos.getMeusDados(), namespace: namespace });
            });



            socket.on('digitandomsg', function (data) {



                var remetente = data.remetente;
                var destinatario = data.destinatario;
                var msg = data.msg;
                var tipo = data.type;

                var windowChat = $("#window" + remetente + "_" + destinatario + ", #window" + destinatario + "_" + remetente + "");
                var item1 = $('.corpo .status');
                var status = windowChat.find(item1);

                var heightantes = $("#corpo" + remetente + "_" + destinatario + ", #corpo" + destinatario + "_" + remetente + "").prop('scrollHeight');

                if (windowChat.attr("id") != null || windowChat.css("display") != "none") {

                    if (tipo == "remetente") {
                        //status.html("");

                    } else {

                        status.html(data.dataremetente.nome + " está digitando uma mensagem...");
                    }
                }

                var heightdepois = $("#corpo" + remetente + "_" + destinatario + ", #corpo" + destinatario + "_" + remetente + "").prop('scrollHeight');
                var calcScroll = parseInt(heightdepois) - parseInt(heightantes);
                //$("#corpo" + remetente + "_" + destinatario + ", #corpo" + destinatario + "_" + remetente + "").slimScroll({ scroll: calcScroll + 'px' });

                if (document.getElementById("corpo" + remetente + "_" + destinatario + "")) {
                    document.getElementById("corpo" + remetente + "_" + destinatario + "").scrollTop = document.getElementById("corpo" + remetente + "_" + destinatario + "").scrollHeight;
                }

                if (document.getElementById("corpo" + destinatario + "_" + remetente + "")) {
                    document.getElementById("corpo" + destinatario + "_" + remetente + "").scrollTop = document.getElementById("corpo" + destinatario + "_" + remetente + "").scrollHeight;
                }


            });

            socket.on('usuariodesconectado', function (data) {

                var meus_dados = $.fn.interfaceChat.metodos.getMeusDados();

                $("#imgstatus" + meus_dados.tb_sys_usuario + "_" + data.data).attr("src", "/assets/portal/default/images/naologado.png");
				
				
			    $('ul#amigos>li').tsort({ attr: 'class' });

            });

            socket.on('digitandomsglimpa', function (data) {

                var remetente = data.remetente;
                var destinatario = data.destinatario;
                var msg = data.msg;
                var tipo = data.type;

                var windowChat = $("#window" + remetente + "_" + destinatario + ", #window" + destinatario + "_" + remetente + "");
                var item1 = $('.corpo .status');
                var status = windowChat.find(item1);

                var heightantes = $("#corpo" + remetente + "_" + destinatario + ", #corpo" + destinatario + "_" + remetente + "").prop('scrollHeight');

                if (windowChat.attr("id") != null || windowChat.css("display") != "none") {

                    if (tipo == "remetente") {
                        //status.html("");

                    } else {

                        status.html("");

                    }
                }

                var heightdepois = $("#corpo" + remetente + "_" + destinatario + ", #corpo" + destinatario + "_" + remetente + "").prop('scrollHeight');
                var calcScroll = parseInt(heightdepois) - parseInt(heightantes) + 27;
                //$("#corpo" + remetente + "_" + destinatario + ", #corpo" + destinatario + "_" + remetente + "").slimScroll({ scroll: calcScroll + 'px' });

                if (document.getElementById("corpo" + remetente + "_" + destinatario + "")) {
                    document.getElementById("corpo" + remetente + "_" + destinatario + "").scrollTop = document.getElementById("corpo" + remetente + "_" + destinatario + "").scrollHeight;
                }

                if (document.getElementById("corpo" + destinatario + "_" + remetente + "")) {
                    document.getElementById("corpo" + destinatario + "_" + remetente + "").scrollTop = document.getElementById("corpo" + destinatario + "_" + remetente + "").scrollHeight;
                }


            });

            socket.on('sendchat', function (data) {

                var remetente = data.remetente;
                var destinatario = data.destinatario;
                var msg = data.msg;
                var tipo = data.type;
                var windowChat_ = $("#window" + remetente + "_" + destinatario + ", #window" + destinatario + "_" + remetente + "");

                if (windowChat_.attr("id") == null || windowChat_.css("display") == "none") {
                    $.fn.interfaceChat.metodos.abreJanelaChat(remetente, destinatario, '');
                    $.fn.interfaceChat.metodos.registraSessaoChat(destinatario);
                    soundManager.play('chatbeep');
                }

                var windowChat = $("#window" + remetente + "_" + destinatario + ", #window" + destinatario + "_" + remetente + "");

                var titulo = $(".spantitulo");
                var item1 = $('.corpo .msgs');
                var chatmsg = $("#chatmsg");
                var itemstatus = $('.corpo .status');


                var titulospan = windowChat.find(titulo);
                var corpo = windowChat.find(item1);
                var txtbox = windowChat.find(chatmsg);
                var status = windowChat.find(itemstatus);

                if (tipo == "remetente") {

                    var img = "";
                    if (data.dataremetente.foto == null || data.dataremetente.foto == "") {
                        img = "/www/uploads/minichat.png";
                    } else {
                        img = "/www/uploads/perfis/minichat_" + data.dataremetente.foto;
                        //img = urlAplicacao + "uploads/minichat2.png";
                    }


                    var nome = data.dataremetente.nome;
                    nome = nome.split(" ");
                    nome = nome[0];

                    var novalinha = "<div class=\"linhaMsg\"><div class=\"avatar\"><img src=\"" + img + "\" title=\"" + data.dataremetente.nome + "\" /></div> <div class=\"msg\"> <i>" + dataHora() + "</i> - <b>" + nome + "</b> diz: <br>" + msg + "</div></div>";

                    corpo.append(novalinha);

                    titulospan.html(data.datadestinatario.nome);


                    socket.emit('registerlogChat', { remetente: remetente, destinatario: destinatario, html: novalinha, tipo: 'remet' });



                } else {

                    var img = "";
                    if (data.dataremetente.foto == null || data.dataremetente.foto == "") {
                        img = "/www/uploads/minichat.png";
                    } else {
                        img = "/www/uploads/perfis/minichat_" + data.dataremetente.foto;
                    }



                    var nome = data.dataremetente.nome;
                    nome = nome.split(" ");
                    nome = nome[0];


                    var novalinha = "<div class=\"linhaMsg\"><div class=\"avatar\"><img src=\"" + img + "\"  title=\"" + data.dataremetente.nome + "\" /></div><div class=\"msg\"> <i>" + dataHora() + "</i> - <b>" + nome + "</b> diz: <br>" + msg + "</div></div>"
                    corpo.append(novalinha);

                    titulospan.html(data.dataremetente.nome);
                    soundManager.play('chatbeep');

                    itemstatus.html("");

                    socket.emit('registerlogChat', { remetente: remetente, destinatario: destinatario, html: novalinha, tipo: 'dest' });



                    if (windowChat.find(".botoesMinimiza").attr("rel") == "minimizado") {

                       var corOriginal = windowChat.find(".topo").css("background-color");
                        var destt = destinatario;
                        var url = $("#urlVirtualPath").val();

                        if (!chatTimerMensagem['timer' + destt + '']) {
                            var exec = "var timer" + destt + " = $.timer(function() { ";
                            exec += "var cor = windowChat.find('.topo').css('background-color');";
                            exec += "if(cor == corOriginal)";
                            exec += "{ ";
                           	exec += "	windowChat.find('.topo').css('background-color', '#cccccc')";
                            exec += "}else";
                            exec += "{";
                            exec += "	windowChat.find('.topo').css('background-color', corOriginal)";
                            exec += "}});";
                            exec += "timer" + destt + ".set({ time : 1000, autostart : false });"
                            exec += "timer" + destt + ".play();";
                            exec += "chatTimerMensagem['timer" + destt + "'] = timer" + destt + "";
                            eval(exec);
                        }

                    }

                }

                if (document.getElementById("corpo" + remetente + "_" + destinatario + "")) {
                    document.getElementById("corpo" + remetente + "_" + destinatario + "").scrollTop = document.getElementById("corpo" + remetente + "_" + destinatario + "").scrollHeight;
                }

                if (document.getElementById("corpo" + destinatario + "_" + remetente + "")) {
                    document.getElementById("corpo" + destinatario + "_" + remetente + "").scrollTop = document.getElementById("corpo" + destinatario + "_" + remetente + "").scrollHeight;
                }



            });



            socket.on('SERVIDOR', function (data) {

                //console.log(data);

            });

            socket.on('message', function (msg) {

                //console.log(msg);
            });

            socket.on('CONVIDA', function (data) {

                //socket.emit('joinroom', { sala: data });
            });

        }

    } catch (err) {
        var ul = $("#amigos").append("<li>servidor de chat não encontrado...</li>");
    }

});



function dataHora() {
    var data = new Date();

    // Guarda cada pedaço em uma variável
    var dia = data.getDate();           // 1-31
    var dia_sem = data.getDay();            // 0-6 (zero=domingo)
    var mes = data.getMonth();          // 0-11 (zero=janeiro)
    var ano2 = data.getYear();           // 2 dígitos
    var ano4 = data.getFullYear();       // 4 dígitos
    var hora = data.getHours();          // 0-23
    var min = data.getMinutes();        // 0-59
    var seg = data.getSeconds();        // 0-59
    var mseg = data.getMilliseconds();   // 0-999
    var tz = data.getTimezoneOffset(); // em minutos

    // Formata a data e a hora (note o mês + 1)
    var str_data = dia + '/' + (mes + 1) + '/' + ano4;
    var str_hora = hora + ':' + min;
    return str_hora;
}
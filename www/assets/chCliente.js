
(function ($) {
    // jQuery plugin definition  
    $.fn.interfaceChat = function (params) {


        var defaults =
	 {
	     onPegaMeusDados: function () { },
	     onAbreJanela: function () { },
	     onEnviaMensagem: function () { },
	     onDigitandoMensagem: function () { },
	     onDigitandoMensagemLimpa: function () { },
	     onListaAmigos: function () { }
	 };

        var options = $.extend(defaults, params);


        var sessao =
	  {
	      meusdados: null,
	      amigos: null,

	      msgJanelaCarrega: false
	  };




        $.fn.interfaceChat.metodos =
     {

         BuscaAtualizacoes: function (id) {

             $.ajax({
                 type: "POST",
                 url: "/conta/buscaatualizacoes",
                 data: { id: id },
                 dataType: "json",
                 contentType: "application/json; charset=utf-8",
                 success: function (data) {
                     $("#contaAtualizacoes").html(data.total);

                 }
             });
         },
         getMeusDados: function () {
             return sessao.meusdados;
         },

         amigos: function () {
             return $.ajax({
                 type: "POST",
				 cache: false,
                  url: "/chat/amigos",
                 data: {},
                 dataType: "json",
                 contentType: "application/json; charset=utf-8",
                 success: function (data) {
                     sessao.amigos = data;

                 }
             });
         },

         me: function () {
          return ajax =  $.ajax({
                 type: "POST",
                 url: "/chat/me",
                 data: {},
                 dataType: "json",
                 contentType: "application/json; charset=utf-8",
                 success: function (data) {

                     sessao.meusdados = data;
                     options.onPegaMeusDados.call(this, data);
					  
                     //socket.emit('adduser', { usuario: data.d, namespace: namespace });
                 }
             });
         },


         buscaSessaoAtivas: function () {
            return  $.ajax({
                 type: "POST",
                 url: "/chat/buscasessoes",
                 data: {},
                 dataType: "json",
                 contentType: "application/json; charset=utf-8",
                 success: function (data) {
                     if (data != null) {

                         if (sessao.msgJanelaCarrega == false) {

                             $.each(data, function (index, value) {
                                 var titulo = $("#lista" + value[0] + "_" + index).text();
                                 if (titulo != "") {
                                     $.fn.interfaceChat.metodos.abreJanelaChat(value[0], index, titulo, value[1]);
									 $.fn.interfaceChat.metodos.getlogChatUsuario(value[0]);
                                 }

                             });


                             $.fn.interfaceChat.metodos.getlogChat();
                             sessao.msgJanelaCarrega = true;
                         }


					
						
                         /*if (!(window.location.toString().search("/Admin/Modulos/Default.aspx") != -1)) {

                             var janelas = $("#chatWindows").find(".window");

                             $.each(janelas, function (index, value) {

                                 var slimScrollDiv = $('.slimScrollDiv');
                                 var inputfieldchat = $(".inputfieldchat");
                                 var botoesMinimiza = $(".botoesMinimiza");
                                 var titulo = $(".titulo");
                                 var imgStatus = $(".imgStatus");
                                 var chatmsg = $(".chatmsg");
                                 var windowcontexto = $(value);
                                 windowcontexto.css("height", "28px");
                                 windowcontexto.find(slimScrollDiv).hide();
                                 windowcontexto.find(inputfieldchat).hide();
                                 windowcontexto.attr("rel", "minimizado");
                                 windowcontexto.find(botoesMinimiza).attr("rel", "minimizado");
                                 windowcontexto.css("bottom", "0");
                             });

                         }*/

                     }

                 }
             });
         },

         registraSessaoChat: function (destinatario) {
             $.ajax({
                 type: "POST",
                 url: "/chat/register",
                 data: { friend : destinatario },
			 	 dataType: "json",
				 ContentType:	"text/json",
                 success: function (data) {

                 }
             });
         },

 		unregistraSessaoChat: function (destinatario) {
             $.ajax({
                 type: "POST",
                 url: "/chat/unregister",
                 data: { friend : destinatario},
                 dataType: "json",
                 ContentType:	"text/json",
                 success: function (data) {

                 }
             });
         },

         windowstateChat: function (destinatario, state) {
             $.ajax({
                 type: "POST",
                 url: "/chat/windowstate",
                 data: { friend : destinatario, state: state},
                 dataType: "json",
                 ContentType:	"text/json",
                 success: function (data) {
					  
                 }
             });
         },

        


         replaceAll: function (string, token, newtoken) {

             if (string != null) {
                 while (string.indexOf(token) != -1) {
                     string = string.replace(token, newtoken);
                 }
             }
             return string;
         },

         getlogChat: function () {
             $.ajax({
                 type: "POST",
                 url: "/chat/getlog",
                 data: {},
                 dataType: "json",
                 contentType: "text/json",
                 success: function (data) {
                     if (data != null) {

                         $.each(data, function (index, value) {

                             var remetente = value.ID_USUARIO_REMETENTE;
                             var destinatario = value.ID_USUARIO_DESTINATARIO;
							

                             var windowChat = $("#window" + remetente + "_" + destinatario + ", #window" + destinatario + "_" + remetente + "");
                             var item1 = $('.corpo .msgs');
                             var corpo = windowChat.find(item1);
							 

							 
                             var html = value.MENSAGEM;

                             html = $.fn.interfaceChat.metodos.replaceAll(html, "\\\"", "\"");

							
							
							 
                             corpo.html(html);

                             var heightdepois = $("#corpo" + remetente + "_" + destinatario + ", #corpo" + destinatario + "_" + remetente + "").prop('scrollHeight');
                             var calcScroll = (parseInt(heightdepois) - 240);

                             if (document.getElementById("corpo" + remetente + "_" + destinatario + "")) {
                                 document.getElementById("corpo" + remetente + "_" + destinatario + "").scrollTop = document.getElementById("corpo" + remetente + "_" + destinatario + "").scrollHeight;
                             }

                             if (document.getElementById("corpo" + destinatario + "_" + remetente + "")) {
                                 document.getElementById("corpo" + destinatario + "_" + remetente + "").scrollTop = document.getElementById("corpo" + destinatario + "_" + remetente + "").scrollHeight;
                             }

								

                         });
                     }
                 }
             });
         },


			


         getlogChatUsuario: function (amigo) {
             $.ajax({
                 type: "POST",
                 url: "/chat/getlogusuario",
                 data: { amigo : amigo },
                 dataType: "json",
                 ContentType:	"text/json",
                 success: function (data) {
                     if (data != "") {
						
				
                         var value = data[0];
						
						
						


                         var remetente = value.ID_USUARIO_REMETENTE;
                         var destinatario = value.ID_USUARIO_DESTINATARIO;

                         var windowChat = $("#window" + remetente + "_" + destinatario + ", #window" + destinatario + "_" + remetente + "");
                         var item1 = $('.corpo .msgs');
                         var corpo = windowChat.find(item1);

                         var html = value.MENSAGEM;

                         html = $.fn.interfaceChat.metodos.replaceAll(html, "\\\"", "\"");
						
						
						
                         corpo.fadeIn('slow');

                         corpo.html(html);

                         var heightdepois = $("#corpo" + remetente + "_" + destinatario + ", #corpo" + destinatario + "_" + remetente + "").prop('scrollHeight');
                         var calcScroll = (parseInt(heightdepois) - 240);

                         if (document.getElementById("corpo" + remetente + "_" + destinatario + "")) {
                             document.getElementById("corpo" + remetente + "_" + destinatario + "").scrollTop = document.getElementById("corpo" + remetente + "_" + destinatario + "").scrollHeight;
                         }

                         if (document.getElementById("corpo" + destinatario + "_" + remetente + "")) {
                             document.getElementById("corpo" + destinatario + "_" + remetente + "").scrollTop = document.getElementById("corpo" + destinatario + "_" + remetente + "").scrollHeight;
                         }




                     }
                 }
             });
         },

         minimizaJanelaChat: function (obj, destinatario, remetente) {

             //maximizado
             var estado = $(obj).attr("rel");


             

             var windowcontexto = $("#" + $(obj).attr("id").replace("minimize", ""))
             var topo = $(".topo");
             var slimScrollDiv = $('.slimScrollDiv');
             var inputfieldchat = $(".inputfieldchat");
             var chatmsg = $(".chatmsg");

             if (estado == "maximizado") {

                 windowcontexto.css("height", "28px");
                 windowcontexto.find(slimScrollDiv).hide();
                 windowcontexto.find(inputfieldchat).hide();
                 $(obj).attr("rel", "minimizado");

             


                 windowcontexto.css("bottom", "0");

                 $.fn.interfaceChat.metodos.windowstateChat(destinatario, "minimizado");



             } else {

                 windowcontexto.css("height", "300px");
                 windowcontexto.find(slimScrollDiv).show();
                 windowcontexto.find(inputfieldchat).show();
                 $(obj).attr("rel", "maximizado");
                 windowcontexto.find(chatmsg).focus();

                 windowcontexto.css("margin-top", "0px");


                 $.fn.interfaceChat.metodos.windowstateChat(destinatario, "maximizado");


                 if (chatTimerMensagem['timer' + destinatario + '']) {
                     chatTimerMensagem['timer' + destinatario + ''].stop();
                     delete chatTimerMensagem['timer' + destinatario + ''];
                 }

                 var url = $("#urlVirtualPath").val();

                 $(obj).parent().parent().find('.topo').css('background-image', 'url(' + url + '/Admin/Content/Images/userbarbg.jpg)')


             }
         },

         abreJanelaChat: function (remetente, destinatario, titulo, windowState) {


             var windowChat_ = $("#window" + remetente + "_" + destinatario + ", #window" + destinatario + "_" + remetente + "");
			 

			 
             if (windowChat_.attr("id") != null) {


                 windowChat_.show();
                 var chatmsg = $(".chatmsg");
                 var input = windowChat_.find(chatmsg);
				 var imgStatus = windowChat_.find("imsg:first");
                 input.focus();
				 
				 var lista = $("#lista" + remetente + "_" + destinatario + ", #lista" + destinatario + "_" + remetente + "");
				 var statusLista = lista.attr("class");
				 if(statusLista == "conectado")
				 {
					imgStatus.attr("src", "/assets/portal/default/images/logado.png");
				 }else
				 {
					imgStatus.attr("src", "/assets/portal/default/images/naologado.png"); 				
				 }

				 

             }else
			 {
			
			
			
			

             var div = $("#chatWindows");
             var windows = $("<div/>");
             windows.attr("class", "window");
             windows.attr("id", "window" + remetente + "_" + destinatario);


             var url = $("#urlVirtualPath").val();
             var topo = $("<div/>"); topo.attr("class", "topo");
             var divtitulo = $("<div/>");
             var spantitulo = $("<span/>"); spantitulo.attr("class", "spantitulo"); spantitulo.attr("id", "spantitulo" + remetente + "_" + destinatario + "");
             spantitulo.html(titulo);
             divtitulo.attr("class", "titulo");
			 
			 var lista = $("#lista" + remetente + "_" + destinatario + ", #lista" + destinatario + "_" + remetente + "");
			 var statusLista = lista.attr("class");
			
			 if(statusLista == "conectado")
			 {
             	divtitulo.html("<img src='/assets/portal/default/images/logado.png' class='imgstatus' id='imgstatus" + remetente + "_" + destinatario + "' /> ");
			 }else
			 {
				divtitulo.html("<img src='/assets/portal/default/images/naologado.png' class='imgstatus' id='imgstatus" + remetente + "_" + destinatario + "' /> ");			 
			 }

             var divbotoes1 = $("<div/>"); divbotoes1.attr("class", "botoesMinimiza"); divbotoes1.attr("rel", "maximizado"); divbotoes1.html("[-]"); divbotoes1.attr("id", "minimizewindow" + remetente + "_" + destinatario);

             var divbotoes = $("<div/>"); divbotoes.attr("class", "botoes"); divbotoes.html("[x]"); divbotoes.attr("id", "closewindow" + remetente + "_" + destinatario);



             divbotoes1.bind("click", function () {
                 $.fn.interfaceChat.metodos.minimizaJanelaChat($(this), destinatario, remetente);
             });



             /*topo.bind("click", function () {
                 $.fn.interfaceChat.metodos.minimizaJanelaChat($(this), destinatario, remetente);
             });*/

             divbotoes.bind("click", function () {
                 $(this).parent().parent().hide();
                 $(this).parent().parent().remove();

                 var margin_right = 240;
                 $.each($(".window"), function (index, value) {
                     $(this).css("margin-left", "-" + margin_right + "px");
                     margin_right = margin_right + 260;
                 });

                 $.fn.interfaceChat.metodos.unregistraSessaoChat(destinatario);
             });

             divtitulo.append(spantitulo);
             topo.append(divtitulo);
             topo.append(divbotoes);
             topo.append(divbotoes1);
             var corpo = $("<div/>"); corpo.attr("class", "corpo"); corpo.attr("id", "corpo" + remetente + "_" + destinatario);
             var msgs = $("<div/>"); msgs.attr("class", "msgs"); msgs.attr("id", "msgs" + remetente + "_" + destinatario);
             var status = $("<div/>"); status.attr("class", "status"); status.attr("id", "status" + remetente + "_" + destinatario);
             status.html('');
             corpo.append(msgs);
             corpo.append(status);

             var field = $("<div/>"); field.attr("class", "inputfieldchat");
             var input = $("<input/>"); input.attr("type", "text"); input.attr("id", "chatmsg"); input.attr("class", "chatmsg");
             field.append(input);

             input.bind("keyup", function (event) {
                 if (event.which == 13) {

                     options.onEnviaMensagem.call(this, { msg: $(this).val(), remetente: remetente, destinatario: destinatario });
                     input.val("");

                 } else {

                     if (event.keyCode == 17 || event.keyCode == 18 || event.keyCode == 9) {

                     } else {


                         var cont = $(this).val().length;
                         if (cont == 1) {
                             options.onDigitandoMensagem.call(this, { msg: $(this).val(), remetente: remetente, destinatario: destinatario });
                         } else if (cont == 0) {
                             options.onDigitandoMensagemLimpa.call(this, { msg: $(this).val(), remetente: remetente, destinatario: destinatario });
                         }
                     }
                 }
             });






             windows.append(topo);
             windows.append(corpo);
             windows.append(field);
             windows.fadeIn('slow');
             div.append(windows);



             input.focus();


             if (windowState == "minimizado") {



                 var topo = $(".topo");
                 var slimScrollDiv = $('.slimScrollDiv');
                 var inputfieldchat = $(".inputfieldchat");

                 windows.css("height", "28px");
                 windows.find(topo).show();
                 windows.find(slimScrollDiv).hide();
                 windows.find(inputfieldchat).hide();
                 //windows.css("margin-top", "270px");
                 //windows.css("bottom", "0");
                 divbotoes1.attr("rel", "minimizado");


             }


             var dados = { remetente: remetente, destinatario: destinatario };
             options.onAbreJanela.call(this, dados);


             var margin_right = 240;
             $.each($(".window"), function (index, value) {
                 //alert(margin_right);
                 $(this).css("margin-left", "-" + margin_right + "px");
                 margin_right = margin_right + 260;
             });

			 }

         },



         listaamigos: function (data) {
             var ul = $("#amigos");

             ul.find("li").remove();

             $.each(data, function (index, value) {


                 if (sessao.meusdados.tb_sys_usuario != value.tb_sys_usuario) {
                     var li = $("<li/>"); li.attr("id", "lista" + sessao.meusdados.tb_sys_usuario + "_" + value.tb_sys_usuario);
                     li.attr("class", "naoconectado");
					 //li.css("display", "none");
                     //li.html("<div class='persons'><div class='fotoPerson'><img src='/thumbs/index/w/20/h/20/type/2/autocrop/true/file/" + value.ARQUIVO + "'/></div><div class='nomePerson'>" + value.NOME + "</div><div class='iconePerson'><img src='/assets/portal/default/images/naologado.png'></div>");
                     li.html("<div class='persons'><div class='fotoPerson'><img class='lazy' data-original='/thumbs/index/w/20/h/20/type/2/autocrop/true/file/" + value.ARQUIVO + "' src='/thumbs/index/w/20/h/20/type/2/autocrop/true/file/x'/></div><div class='nomePerson'>" + value.NOME + "</div><div class='iconePerson'><img src='/assets/portal/default/images/naologado.png'></div>");
                     ul.append(li);

                 }

             });
			
			
             options.onListaAmigos.call(this, "");
			 

			
         },

         minimizaAreaChat: function (obj) {

             var href = $(obj);
             var estado;
             if ($(href).attr("class") == null || $(href).attr("class") == "maximizado") {

                 $("#Areachat").css("height", "28px");
                 $("#Areachat #blocoPersons").hide();
                 href.attr("class", "minimizado");
                 estado = "minimizado";
             } else {
                 $("#Areachat").css("height", "400px");
                 $("#Areachat #blocoPersons").show();
                 href.attr("class", "maximizado");
                 estado = "maximizado";
             }

             $.ajax({
                 type: "POST",
                 url: "/chat/minimizeAreaChat",
                 data: "{'estado' : '" + estado + "'}",
                 dataType: "json",
                 contentType: "application/json; charset=utf-8",
                 success: function (data) {

                 }
             });
         },

         conectados: function (data) {





             var ul = $("#amigos");
             
             var ul = $("#amigos li");
             $.each(ul, function (index, value) {
                 $(this).unbind("click");
                 $(this).find("img:last").attr("src", '/assets/portal/default/images/naologado.png');
				 $(this).bind("click", function (e, ui) {
					  var sep = $(this).attr("id").split("_");
					  var id = sep[1];
                      $.fn.interfaceChat.metodos.abreJanelaChat(sessao.meusdados.tb_sys_usuario, id, $(this).text(), 'maximizado');
                      $.fn.interfaceChat.metodos.registraSessaoChat(id);
                      $.fn.interfaceChat.metodos.getlogChatUsuario(id);
                  });
				 
             });


             //if (data.data) {
             $.each(data.data, function (index, value) {



                 if (sessao.meusdados.tb_sys_usuario != value.id) {

					
                     var obj = $("#lista" + sessao.meusdados.tb_sys_usuario + "_" + value.id + "");
                     $("#imgstatus" + sessao.meusdados.tb_sys_usuario + "_" + value.id).attr("src", "/assets/portal/default/images/logado.png");					
                     obj.find("img:last").attr("src", '/assets/portal/default/images/logado.png');
					 obj.css("display", "block");
					
					 
                     obj.attr("class", "conectado");
                     /*obj.bind("click", function (e, ui) {

                         $.fn.interfaceChat.metodos.abreJanelaChat(sessao.meusdados.tb_sys_usuario, value.id, $(this).text(), 'maximizado');
                         $.fn.interfaceChat.metodos.registraSessaoChat(value.id);
                         //$.fn.interfaceChat.metodos.getlogChatUsuario(value.id);
                     });*/
                 }

             });
             //}

             $('ul#amigos>li').tsort({ attr: 'class' });

             $.fn.interfaceChat.metodos.buscaSessaoAtivas();


             /*if (data.data) {
                 var online = $.map(data.data, function (n, i) { return i; }).length;
                 if (online > 1) {
                     $("#qtdUsuariosOnline").html(online - 1);
                 } else {
                     $("#qtdUsuariosOnline").html(0);
                 }
             }*/
			
			
			    if($("img[src*='/logado.png']").length >= 1)
			   {
					$("#qtdUsuariosOnline").html($("img[src*='/logado.png']").length - 1)
			   }else
			   {
					$("#qtdUsuariosOnline").html(0)
			   }
			   
			if($("#PageLoaded").val() == "false")   
			{
				$("img").lazyload({  container: $("#blocoPersons")  });
			}
		   	
			setTimeout(function() {
				$("#blocoPersons").trigger('scroll');  
				$("#PageLoaded").val("true");
			}, 20000);
			

         }
     }




        this.each(function () {

            var $t = $(this);



        });

        // allow jQuery chaining  
        return this;
    };
})(jQuery); 


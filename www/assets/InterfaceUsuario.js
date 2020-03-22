
(function($) {  
    // jQuery plugin definition  
    $.fn.InterfaceUsuario = function(params) {  

	
	 var defaults =
	 {
		onAddComentario: function() {}
	 };
	 
	  var options = $.extend(defaults, params);
	
	
	 
	 

	  
	 $.fn.InterfaceUsuario.metodos =
     {
		
		
		BlocoStatusClick : function()
		{
			$("#BlocoStatus #options .optStatus");
		},
		 
		 
		 
		ComentariosPega : function(pagina, post, placeholder, placeholdertotal, remetente)
		{	
			$.ajax({
					 type: "GET",
					 url: "/conta/getcomentarios",
					 data: { post : post, pagina : pagina, remetente : remetente},
					 dataType: "json",
					 ContentType:	"text/json",
					 success: function (data) 
					 { 
					 	$(placeholder).html(data.html);
						$(placeholdertotal).html(data.total);

						$(".fancybox-iframe").contents().find(placeholder).html(data.html);
						$(".fancybox-iframe").contents().find(placeholdertotal).html(data.total);
												
					 }
				 }); 
		},
		
	  	ComentariosAdd : function(post, msg)
		{	
			$.ajax({
					 type: "POST",
					 url: "/conta/addcomentario",
					 data: { post : post, msg : msg},
					 dataType: "json",
					 ContentType:	"text/json",
					 success: function (data) 
					 { 
					 		if(data.error == "false")
							{
								options.onAddComentario.call(this, data);					
								
								if(socket != null)
								{
									socket.emit('notificacao', { tipo: 'ComentariosAdd', data : data });	
		
								}
								
							}else
							{
								alert('Houve um erro na tentativa de inserir seu comentário.');
							}
					 }
				 }); 
		},
		
		ComentariosDeletar : function (idComentario, idMensagem)
		{	
			jConfirm('Confirmar exclusão?', 'Confirmar', function(r) {
				if( r ){ 
					$.ajax({
					 type: "POST",
					 url: "/conta/excluircomentario",
					 data: {idComentario : idComentario, idMensagem : idMensagem},
					 dataType: "json",
					 ContentType:	"text/json",
					 success: function (data) 
					 { 
						
						//console.log(data);
						
						if(data.error == "false")
						{					
							//$.jGrowl(data.msg, { theme: 'ok'});
							window.parent.$("#comentario"+idComentario).remove();	
							$(".fancybox-iframe").contents().find("#comentario"+idComentario).remove();								
							
							
							
							var total = parseInt($("#totalComentarios"+idMensagem).html());
							
							if(!(total - 1 < 0))
							{
								window.parent.$("#totalComentarios"+idMensagem).html((total - 1).toString())
								$(".fancybox-iframe").contents().find("#totalComentarios"+idMensagem).html((total - 1).toString())								
								
							}
						
						}
						else if(data.error == "true")
						{
							
							 window.parent.parent.$().toastmessage('showToast', {
								text     : data.msg,
								stayTime   : 60000,
								type     : 'warning'
							});		
						}
						
					 }
				 }); 
			 };
			});
		},
		
		ComentariosGostei : function (id)
		{	
	
			$.ajax({
				 type: "POST",
				 url: "/conta/gosteicomentario",
				 data: {id : id},
				 dataType: "json",
				 ContentType:	"text/json",
				 success: function (data) 
				 { 
				 	
					if(data.error == "false")
					{					
						
						$("#txtGosteiComentario"+id).html(data.msg);
						$("#btnDesgostarComentario"+id).show();
						$("#btnGosteiComentario"+id).hide();
						
						$(".fancybox-iframe").contents().find("#txtGosteiComentario"+id).html(data.msg);
						$(".fancybox-iframe").contents().find("#btnDesgostarComentario"+id).show();
						$(".fancybox-iframe").contents().find("#btnGosteiComentario"+id).hide();						
						
						

						
						if(socket != null)
						{
							socket.emit('notificacao', { tipo: 'ComentarioGostei', data : data });	

						}
					
					}
					else if(data.error == "true")
					{
						
						 window.parent.parent.$().toastmessage('showToast', {
							text     : data.msg,
							stayTime   : 60000,
							type     : 'warning'
						});		
					}
					
				 }
			 }); 

		},
		
		ComentariosDesgostei : function (id)
		{	
		
					$.ajax({
					 type: "POST",
					 url: "/conta/comentariodesgostei",
					 data: {id : id},
					 dataType: "json",
					 ContentType:	"text/json",
					 success: function (data) 
					 { 
						
						if(data.error == "false")
						{					
							
							$("#txtGosteiComentario"+id).html(data.msg);
							$("#btnDesgostarComentario"+id).hide();
							$("#btnGosteiComentario"+id).show();
							
							
							$(".fancybox-iframe").contents().find("#txtGosteiComentario"+id).html(data.msg);
							$(".fancybox-iframe").contents().find("#btnDesgostarComentario"+id).hide();
							$(".fancybox-iframe").contents().find("#btnGosteiComentario"+id).show();													
							
							
						
						}	
						else if(data.error == "true")
						{
							
							 window.parent.parent.$().toastmessage('showToast', {
								text     : data.msg,
								stayTime   : 60000,
								type     : 'warning'
							});		
						}
						
					 }
				 }); 
	
		},
		
		MensagensAdd : function()
		{
			
			var editor_data = CKEDITOR.instances.message.getData();
			$("#message").val(editor_data);
			
			$.ajax({
				 type: "POST",
				 url: "/conta/addrecado",
				 data: $("#form1").serialize(),
				 dataType: "json",
				 ContentType:	"text/json",
				 success: function (data) 
				 { 
					
				
				
	 
					if(data.erro == "false")
					{					
						window.parent.$().toastmessage('showSuccessToast', data.msg);
						$("#message").val("");
						
							var editor = CKEDITOR.instances['message'];
							if (editor) 
							{ 
								editor.resize('98%', 90);
								editor.setData("");
							}
							
							window.parent.$("#messageStatus").val("");
							
							$.fn.InterfaceUsuario.metodos.MensagensPega(1);
							$("#legendaMidia").val("Digite aqui sua legenda...");
						
						
						if(socket != null)
						{
							socket.emit('notificacao', { tipo: 'MensagensAdd', data : data });	

						}
							
					}else if(data.erro == "valida")
					{
						
						window.parent.parent.$().toastmessage('showToast', {
							text     : 'Por favor, preencha os campos obrigatórios.',
							stayTime   : 60000,
							type     : 'notice'
						});					 
						
						$(".msg_validacao_input").remove();
						$.each(data.campos_erros, function(index, value) { 
						
							var input = $('#'+index).focus();				
							input.after("<div class=\"msg_validacao_input\"><div class=\"icone\"></div><div class=\"msg\">"+value+"</div></div>");
						 });
																	
					}
					else if(data.erro == "true")
					{
						window.parent.parent.$().toastmessage('showToast', {
							text     : data.msg,
							stayTime   : 60000,
							type     : 'warning'
						});				
					}
					
				 },
				 error: function (data) 
				 {
	
					
				 }
			 });
			
		},
		MensagensPega :	function (pagina, id)
		{
				  id = id || null;
				
				return $.ajax({
					 type: "POST",
					 url: "/conta/getmensagens/pagina/"+pagina + "/id_mensagem/"+id,
					 //l: "/conta/getmensagens/id_mensagem/"+id+"/pagina/1",
					 data: $("#form1").serialize(),
					 cache: false,
					 beforeSend : function (data) 
					 {
						 $('.loader').show();
					 },
					 success: function (data) 
					 { 
						 $('.loader').hide();
					 
					 	$("#recados").html(data);
										 
									
						
						$(".botaoOption").contextMenu({ menu: 'myMenu', leftButton: true  }, 
							function(action, el, pos) { contextMenuWork(action, el, pos); 		
						});
						
						
						
						$(".box").fancybox({
							  helpers: {
								  title : {
									  type : 'float'
								  }
							  }
						  });
						  
						  $(".addcomentariofield").bind("click", function(){  
							  if($(this).val() == "Escreva um comentário...")
							  {
								   $(this).val("");
							  }
						  });
						  
						  $(".addcomentariofield").bind("blur", function(){
							  if($(this).val() == "")
							  {
								  $(this).val("Escreva um comentário...");
							  }
						  });
						  
						  $(".addcomentariofield").bind("keyup", function(event)
							{
								if ( event.which == 13 ) {
									
									$.fn.InterfaceUsuario.metodos.ComentariosAdd($(this).attr("id"), $(this).val());
									$(this).val("");
								}
							});
							
							
							 //setTimeout(function(){ $.fbInit(); alert('aqui'); },5000)
							 
							 
							 
							 function facebookReady(){
							FB.init({
							  appId      : '519836588052109',                        // App ID from the app dashboard
							  channelUrl : 'http://www.glovebrasil.com.br/conta/face', // Channel file for x-domain comms
							  status     : true,                                 // Check Facebook Login status
							  xfbml      : true                                  // Look for social plugins on the page
							});
								$(document).trigger("facebook:ready");
							  }
							
							  if(window.FB) {
								facebookReady();
							  } else {
								window.fbAsyncInit = facebookReady;
							  }
							
							
							
							
							/*$("a[rel=grupo]").fancybox({
								'transitionIn'		: 'none',
								'transitionOut'		: 'none',
								'titlePosition' 	: 'over',
								centerOnScroll: true,
								'titleFormat'       : function(title, currentArray, currentIndex, currentOpts) {
									return '<span id="fancybox-title-over">Imagem ' +  (currentIndex + 1) + ' / ' + currentArray.length + ' ' + title + '</span>';
								}
							});
							
							
							$(".flexslider .slides > li, .flexslider-detail .slides > li").css("display", "block");*/
							
								chamasAjax();
								
								
									 
							 $(".linkCallPerfil").bind("click", function(ui, e){
								
								 //parent.jQuery.fancybox.isOpen
								 var url = $(this).attr("href");
								 if(parent.jQuery.fancybox.isOpen)
								 {
									 window.parent.location = url;
									 return false;
								 }

							 });
											
						 }
						});
						},
							
							MensagensDeletar : function (id)
							{	
								jConfirm('Confirmar exclusão?', 'Confirmar', function(r) {
									if( r ){ 
										$.ajax({
										 type: "POST",
										 url: "/conta/excluirmsg",
										 data: {id : id},
										 dataType: "json",
										 ContentType:	"text/json",
										 success: function (data) 
										 { 
											
											if(data.error == "false")
											{					
												//$.jGrowl(data.msg, { theme: 'ok'});
												$("#msg"+id).remove();	
											
											}
											else if(data.error == "true")
											{
												
												 window.parent.parent.$().toastmessage('showToast', {
													text     : data.msg,
													stayTime   : 60000,
													type     : 'warning'
												});		
											}
											
										 }
									 }); 
					 }
				});
		},
		
		MensagensPegaUnica :	function (id)
		{
				
				$.ajax({
					 type: "POST",
					 url: "/conta/getmensagens/id_mensagem/"+id+"/pagina/1",
					 data: $("#form1").serialize(),
					 success: function (data) 
					 { 
					 
					 
					 	
					 
				 
				 		$(".fancybox-iframe").contents().find("#recados").html(data);		
				 		$(".fancybox-iframe").contents().find(".table_paginacao").hide();								
						
						
						
							
							$(".fancybox-iframe").contents().find(".linkCallPerfil").bind("click", function(ui, e){
								 var url = $(this).attr("href");
								 window.parent.location = url;
								 return false;

							 });
						
						
						
						$(".fancybox-iframe").contents().find(".botaoOption").contextMenu({ menu: 'myMenu', leftButton: true  }, 
							function(action, el, pos) { contextMenuWork(action, el, pos); 		
						});					
						
						
						$(".fancybox-iframe").contents().find(".box").fancybox({
							  helpers: {
								  title : {
									  type : 'float'
								  }
							  }
						  });
						  
						  $(".fancybox-iframe").contents().find(".addcomentariofield").bind("click", function(){  
							  if($(this).val() == "Escreva um comentário...")
							  {
								   $(this).val("");
							  }
						  });
						  
						  $(".fancybox-iframe").contents().find(".addcomentariofield").bind("blur", function(){
							  if($(this).val() == "")
							  {
								  $(this).val("Escreva um comentário...");
							  }
						  });
						  
						  $(".fancybox-iframe").contents().find(".addcomentariofield").bind("keyup", function(event)
							{
								if ( event.which == 13 ) {
									
									$.fn.InterfaceUsuario.metodos.ComentariosAdd($(this).attr("id"), $(this).val());
									$(this).val("");
								}
							});
							
							
							
						
						
						  // Load the SDK asynchronously
						  (function(d, s, id){
							 var js, fjs = d.getElementsByTagName(s)[0];
							 if (d.getElementById(id)) {return;}
							 js = d.createElement(s); js.id = id;
							 js.src = "//connect.facebook.net/pt_BR/all.js";
							 fjs.parentNode.insertBefore(js, fjs);
						   }(document, 'script', 'facebook-jssdk'));
							
											
							}
							
							
						});
				
							

							
						
							
							
							
							},
							
							MensagensDeletar : function (id)
							{	
								jConfirm('Confirmar exclusão?', 'Confirmar', function(r) {
									if( r ){ 
										$.ajax({
										 type: "POST",
										 url: "/conta/excluirmsg",
										 data: {id : id},
										 dataType: "json",
										 ContentType:	"text/json",
										 success: function (data) 
										 { 
											
											if(data.error == "false")
											{					
												//$.jGrowl(data.msg, { theme: 'ok'});
												$("#msg"+id).remove();	
											
											}
											else if(data.error == "true")
											{
												
												 window.parent.parent.$().toastmessage('showToast', {
													text     : data.msg,
													stayTime   : 60000,
													type     : 'warning'
												});		
											}
											
										 }
									 }); 
								 };
								});
		},
		
		MensagensGostei : function (id, remetente)
		{	
	
			$.ajax({
				 type: "POST",
				 url: "/conta/gostei",
				 data: {id : id},
				 dataType: "json",
				 ContentType:	"text/json",
				 success: function (data) 
				 { 
				 	
					if(data.error == "false")
					{					
						
						$("#txtGostei"+id).html(data.msg);
						$("#btnDesgostar"+id).show();
						$("#btnGostei"+id).hide();
						
						
						$(".fancybox-iframe").contents().find("#txtGostei"+id).html(data.msg);						
						$(".fancybox-iframe").contents().find("#btnDesgostar"+id).show();		
						$(".fancybox-iframe").contents().find("#btnGostei"+id).hide();
						
						
						if(socket != null)
						{
							socket.emit('notificacao', { tipo: 'MensagemGostei', data : data });	
							//console.log(data);
						}
					
					}
					else if(data.error == "true")
					{
						
						 window.parent.parent.$().toastmessage('showToast', {
							text     : data.msg,
							stayTime   : 60000,
							type     : 'warning'
						});		
					}
					
				 }
			 }); 

		},
		
	
		
		MensagensDesgostei : function (id, remetente)
		{	
		
					$.ajax({
					 type: "POST",
					 url: "/conta/desgostei",
					 data: {id : id},
					 dataType: "json",
					 ContentType:	"text/json",
					 success: function (data) 
					 { 
						
						if(data.error == "false")
						{					
							
							$("#txtGostei"+id).html(data.msg);
							$("#btnDesgostar"+id).hide();
							$("#btnGostei"+id).show();						
						
						
							$(".fancybox-iframe").contents().find("#txtGostei"+id).html(data.msg);						
							$(".fancybox-iframe").contents().find("#btnDesgostar"+id).hide();		
							$(".fancybox-iframe").contents().find("#btnGostei"+id).show();						
						
						}
						else if(data.error == "true")
						{
							
							 window.parent.parent.$().toastmessage('showToast', {
								text     : data.msg,
								stayTime   : 60000,
								type     : 'warning'
							});		
						}
						
					 }
				 }); 
	
		},
		
		AlbumDeletar : function (id)
		{	
			window.parent.parent.jConfirm('Confirmar exclusão?', 'Confirmar', function(r) {
				if( r ){ 
					$.ajax({
					 type: "POST",
					 url: "/conta/excluiralbum",
					 data: {id : id},
					 dataType: "json",
					 ContentType:	"text/json",
					 success: function (data) 
					 { 
						
						
						
						if(data.error == "false")
						{					
							 window.parent.parent.$().toastmessage('showToast', {
								text     : data.msg,
								stayTime   : 600000,
								type   : 'success'
							});		
							
							resultado(1);
							window.parent.parent.resultado(1);
						
						}
						else if(data.error == "true")
						{
							
							 window.parent.parent.$().toastmessage('showToast', {
								text     : data.msg,
								stayTime   : 60000,
								type     : 'warning'
							});		
						}
						
					 }
				 }); 
			 };
			});
		},
		
		
			
		
		
		
		FotosDeletar : function (id)
		{	
		
			
						
		    var n = $("input:checked").length;						
			if(n == 0)
			{
				window.parent.parent.jAlert('Para excluir você deve selecionar pelo menos uma foto deste álbum.', 'Alerta');
				return;
			}
			
		
			window.parent.parent.jConfirm('Confirmar exclusão?', 'Confirmar', function(r) {
				if( r ){ 
					$.ajax({
					 type: "POST",
					 url: "/conta/excluirfotos",
					 data: $("#form1").serialize(),
					 dataType: "json",
					 ContentType:	"text/json",
					 success: function (data) 
					 { 
						
						if(data.error == "false")
						{					
							 window.parent.parent.$().toastmessage('showToast', {
								text     : data.msg,
								stayTime   : 600000,
								type   : 'success'
							});		
							
							$(data.jquerystr).remove();
							
						
						}
						else if(data.error == "true")
						{
							
							 window.parent.parent.$().toastmessage('showToast', {
								text     : data.msg,
								stayTime   : 60000,
								type     : 'warning'
							});		
						}
						
					 }
				 }); 
			 };
			});
		},
		
			
		FotosPerfil : function (id)
		{	
		
			
						
		    var n = $("input:checked").length;						
			if(n == 0)
			{
				window.parent.parent.jAlert('Para excluir você deve selecionar pelo menos uma foto deste álbum.', 'Alerta');
				return;
			}else if(n > 1)
			{
				window.parent.parent.jAlert('Selecione somente uma foto.', 'Alerta');
				return;
			}
			
		
			window.parent.parent.jConfirm('Aplicar foto no perfil?', 'Confirmar', function(r) {
				if( r ){ 
					$.ajax({
					 type: "POST",
					 url: "/conta/fotoperfil",
					 data: $("#form1").serialize(),
					 dataType: "json",
					 ContentType:	"text/json",
					 success: function (data) 
					 { 
						
						if(data.error == "false")
						{					
							 window.parent.parent.$().toastmessage('showToast', {
								text     : data.msg,
								stayTime   : 600000,
								type   : 'success'
							});		
							
						
						$("#iconepqn").attr("src", "/thumbs/index/w/190/h/200/type/2/file/"+data.file);
						window.parent.parent.$("#pictureMeuPerfil").attr("src", "/thumbs/index/w/150/h/150/type/2/file/"+data.file);
						
							
						
						}
						else if(data.error == "true")
						{
							
							 window.parent.parent.$().toastmessage('showToast', {
								text     : data.msg,
								stayTime   : 60000,
								type     : 'warning'
							});		
						}
						
					 }
				 }); 
			 };
			});
		},
		
		AtualizacoesPega :	function (pagina)
		{
				
				$.ajax({
					 type: "POST",
					 url: "/conta/atualizacoesajax/pagina/"+pagina,
					 data: $("#form1").serialize(),
					 success: function (data) 
					 { 

						$(".fancybox-iframe").contents().find("#atualizacoes").html(data);						
					
						
					 }
				});
		},
		
		SolicitacoesPega :	function ()
		{
				
				var ajax = $.ajax({
					 type: "POST",
					 url: "/conta/pegasolicitacoes",
					 data: $("#form1").serialize(),
 				     dataType: "json",
					 success: function (data) 
					 { 
				
						
					 }
				});
				
				return ajax;
		},
		
		ConversasPega :	function ()
		{
				
				var ajax = $.ajax({
					 type: "POST",
					 url: "/conta/pegaconversas",
					 data: $("#form1").serialize(),
 				     dataType: "json",
					 success: function (data) 
					 { 
				
						
					 }
				});
				
				return ajax;
		},
		
		ConversasPegaLog :	function (remetente)
		{
			
				$.ajax({
					 type: "POST",
					 url: "/conta/pegaconversaslog",
					 data: { remetente : remetente },
 				     dataType: "json",
					 success: function (data) 
					 { 
						$("#mainconversasright").html(data.html);					
						document.getElementById('mainconversasright').scrollTop = document.getElementById('mainconversasright').scrollHeight;
					 }
				});
				
		}
		
		
	 }
		 
		   
       this.each(function() {  
         
           var $t = $(this);
     
       });  
       
       // allow jQuery chaining  
       return this;  
    };  
})(jQuery); 



(function($) {  
    // jQuery plugin definition  
    $.fn.interfaceChat = function(params) {  

	
	 var defaults =
	 {
		 onPegaMeusDados: function() {},
		 onAbreJanela: function() {},
		 onEnviaMensagem: function() {},
 		 onDigitandoMensagem: function() {},
		 onDigitandoMensagemLimpa: function() {},
		 onListaAmigos : function() {}
	 };
	 
	  var options = $.extend(defaults, params);
		 
		 
	  var sessao =
	  {
		   meusdados : null,
		   amigos: null,
		   
		   msgJanelaCarrega : false
	  };
	
	 
	 
	 
	 $.fn.interfaceChat.metodos =
     {
		 
 		  BuscaAtualizacoes : function (id)
		{	
		
					$.ajax({
					 type: "POST",
					 url: "/conta/buscaatualizacoes",
					 data: {id : id},
					 dataType: "json",
					 ContentType:	"text/json",
					 success: function (data) 
					 { 
							$("#contaAtualizacoes").html(data.total);
						
					 }
				 }); 
		} , 
		getMeusDados  : function()
		{
			return sessao.meusdados;
		},
		
	  	amigos : function()
		{	
			$.ajax({
					 type: "POST",
					 url: "/chat/amigos",
					 data: {},
					 dataType: "json",
					 ContentType:	"text/json",
					 success: function (data) 
					 { 
						sessao.amigos = data;						
					 }
				 }).id = "buscaamigos"; 
		},
		
		me : function()
		{	
			$.ajax({
					 type: "POST",
					 url: "/chat/me",
					 data: {},
					 dataType: "json",
					 ContentType:	"text/json",
					 success: function (data) 
					 { 
						sessao.meusdados = data;	
						options.onPegaMeusDados.call(this, data);
					
					 }
				 }).id = "meusdados"; 
		},
		
		
		buscaSessaoAtivas : function()
		{
			$.ajax({
					 type: "POST",
					 url: "/chat/buscasessoes",
					 data: {},
					 dataType: "json",
					 ContentType:	"text/json",
					 success: function (data) 
					 { 
					 	if(data != null)
						{	
						
							if(sessao.msgJanelaCarrega == false)
							{
								
								$.each(data, function(index, value) 
								{ 
									var titulo = $("#lista"+value[0]+"_"+index).text();
									$.fn.interfaceChat.metodos.abreJanelaChat(value[0], index, titulo, value[1]);
									
								});
								
							/*if(sessao.msgJanelaCarrega == false)
							{*/
								$.fn.interfaceChat.metodos.getlogChat();
								sessao.msgJanelaCarrega = true;
							}
						}
					
					 }
				 }).id = "buscaSessaoAtivas"; 
		},
		
		registraSessaoChat : function(destinatario)
		{
				$.ajax({
					 type: "POST",
					 url: "/chat/register",
					 data: {friend : destinatario},
					 dataType: "json",
					 ContentType:	"text/json",
					 success: function (data) 
					 { 
											
					 }
				 }).id = "registraSessaoChat"; 
		},
		
		windowstateChat : function(destinatario, state)
		{
				$.ajax({
					 type: "POST",
					 url: "/chat/windowstate",
					 data: {friend : destinatario, state : state},
					 dataType: "json",
					 ContentType:	"text/json",
					 success: function (data) 
					 { 
											
					 }
				 }).id = "windowstateChat"; 
		},
		
		unregistraSessaoChat : function(destinatario)
		{
				$.ajax({
					 type: "POST",
					 url: "/chat/unregister",
					 data: {friend : destinatario},
					 dataType: "json",
					 ContentType:	"text/json",
					 success: function (data) 
					 { 
											
					 }
				 }).id = "unregistraSessaoChat"; 
		},
		
	
		replaceAll : function(string, token, newtoken) {
			while (string.indexOf(token) != -1) {
				string = string.replace(token, newtoken);
			}
			return string;
		},

		getlogChat : function()
		{
				$.ajax({
					 type: "POST",
					 url: "/chat/getlog",
					 data: {},
					 dataType: "json",
					 ContentType:	"text/json",
					 success: function (data) 
					 { 
						if(data != null)
						{
							
							
							$.each(data, function(index, value) 
							{ 
							
								var remetente = value.ID_USUARIO_REMETENTE;
								var destinatario = value.ID_USUARIO_DESTINATARIO;								
								
								
								var windowChat = $("#window"+remetente+"_"+destinatario+", #window"+destinatario+"_"+remetente+"");
								var item1 = $('.corpo .msgs');
								var corpo = windowChat.find(item1);
								
								var html = value.MENSAGEM;
								
								html = $.fn.interfaceChat.metodos.replaceAll(html, "\\\"", "\"");
							
								corpo.html(html);
								
							var heightdepois = $("#corpo"+remetente+"_"+destinatario+", #corpo"+destinatario+"_"+remetente+"").prop('scrollHeight');			
							var calcScroll = (parseInt(heightdepois) - 240);
							$("#corpo"+remetente+"_"+destinatario+", #corpo"+destinatario+"_"+remetente+"").slimScroll({ scroll: calcScroll+'px' });
								
								
							});
						}				
					 }
				 }).id = "registerlog"; 
		},
		
		minimizaJanelaChat : function(obj, destinatario, remetente)
		{
			
			//maximizado
			var estado = $(obj).attr("rel");
			
			
			var windowcontexto = $("#"+$(obj).attr("id").replace("minimize", ""))
			var topo = $(".topo");
			var slimScrollDiv = $('.slimScrollDiv');
			var inputfieldchat = $(".inputfieldchat");
			var chatmsg = $(".chatmsg");
			
			if(estado == "maximizado")
			{
						
				windowcontexto.css("height", "30px");
				windowcontexto.find(slimScrollDiv).hide();
				windowcontexto.find(inputfieldchat).hide();
				$(obj).attr("rel", "minimizado");
				
				
				windowcontexto.css("bottom", "0");
				
				$.fn.interfaceChat.metodos.windowstateChat(destinatario, "minimizado");
				
				
				
			}else
			{
			
				windowcontexto.css("height", "300px");
				windowcontexto.find(slimScrollDiv).show();
				windowcontexto.find(inputfieldchat).show();		
				$(obj).attr("rel", "maximizado");
				windowcontexto.find(chatmsg).focus();		
				
				windowcontexto.css("margin-top", "0px");
				
				
				$.fn.interfaceChat.metodos.windowstateChat(destinatario, "maximizado");
				
				
				if(chatTimerMensagem['timer'+destinatario+''])
				{
					chatTimerMensagem['timer'+destinatario+''].stop();
					delete chatTimerMensagem['timer'+destinatario+''];
				}
				
				$(obj).parent().parent().find('.topo').css('background-color', $("h2").css("color"))
		
				
			}
		},
		
		abreJanelaChat : function(remetente, destinatario, titulo, windowState)
		{

			
			var windowChat_ = $("#window"+remetente+"_"+destinatario+", #window"+destinatario+"_"+remetente+"");
			if(windowChat_.attr("id") != null)
			{
				
				
				windowChat_.show();
				var chatmsg = $(".chatmsg");
				var input = windowChat_.find(chatmsg);
				input.focus();
				
				return;
			}
					
			
			var div =  $("#chatWindows");	
			var windows = $("<div/>"); windows.attr("class", "window"); windows.attr("id", "window"+remetente+"_"+destinatario);
			var topo = $("<div/>"); topo.attr("class", "topo");
			var divtitulo = $("<div/>"); divtitulo.attr("class", "titulo"); divtitulo.html(titulo);
			
			var divbotoes1 = $("<div/>"); divbotoes1.attr("class", "botoesMinimiza");  divbotoes1.attr("rel", "maximizado"); divbotoes1.html("[-]"); divbotoes1.attr("id", "minimizewindow"+remetente+"_"+destinatario);
			
			var divbotoes = $("<div/>"); divbotoes.attr("class", "botoes"); divbotoes.html("[x]"); divbotoes.attr("id", "closewindow"+remetente+"_"+destinatario);
			
			
			
			divbotoes1.bind("click", function(){
				$.fn.interfaceChat.metodos.minimizaJanelaChat($(this), destinatario, remetente);				
			});
			
			divbotoes.bind("click", function(){
				$(this).parent().parent().hide();
				$.fn.interfaceChat.metodos.unregistraSessaoChat(destinatario);
			});
			
			topo.append(divtitulo);
			topo.append(divbotoes);
			topo.append(divbotoes1);
			var corpo = $("<div/>"); corpo.attr("class", "corpo");  corpo.attr("id", "corpo"+remetente+"_"+destinatario);
			var msgs = $("<div/>"); msgs.attr("class", "msgs");  msgs.attr("id", "msgs"+remetente+"_"+destinatario);
			var status = $("<div/>"); status.attr("class", "status");  status.attr("id", "status"+remetente+"_"+destinatario);
			status.html('');
			corpo.append(msgs);
			corpo.append(status);
			
			var field = $("<div/>");  field.attr("class", "inputfieldchat");
			var input = $("<input/>"); input.attr("type", "text"); input.attr("id", "chatmsg");  input.attr("class", "chatmsg"); 
			field.append(input);
			
			input.bind("keyup", function(event)
			{
				if ( event.which == 13 ) {
					
					options.onEnviaMensagem.call(this, {msg : $(this).val(), remetente : remetente, destinatario : destinatario});
					input.val("");
				}else
				{
					//if(event.ctrlKey || event.altKey || event.metaKey)
					if(event.keyCode == 17 || event.keyCode == 18 || event.keyCode == 9)
					{
					}else
					{	
					
								
						var cont = $(this).val().length;
						if(cont == 1)
						{
						 	options.onDigitandoMensagem.call(this, {msg : $(this).val(), remetente : remetente, destinatario : destinatario});
						}else if(cont == 0)
						{
							options.onDigitandoMensagemLimpa.call(this, {msg : $(this).val(), remetente : remetente, destinatario : destinatario});
						}
					}
				}
			});
			
			
			
			
			
			
			windows.append(topo);
			windows.append(corpo);			
			windows.append(field);	
			div.append(windows);
			
			input.focus();
			
			
			if(windowState == "minimizado")
			{
				
				var topo = $(".topo");
				var slimScrollDiv = $('.slimScrollDiv');
				var inputfieldchat = $(".inputfieldchat");
			
				windows.css("height", "30px");
				windows.find(topo).show();
				windows.find(slimScrollDiv).hide();
				windows.find(inputfieldchat).hide();
				//windows.css("margin-top", "270px");
				divbotoes1.attr("rel", "minimizado");
				windows.css("bottom", "0");
				
			}
			
			
			
			
			 $("#corpo"+remetente+"_"+destinatario).slimscroll({
                  color: '#000000',
                  size: '10px',
                  width: '250px',
                  height: '240px',
				  start: 'bottom',
				  railVisible: true,
				  allowPageScroll: true
              });
			
			
			var dados = { remetente : remetente, destinatario : destinatario};
			options.onAbreJanela.call(this, dados);
			
			
		},
		
		
		
		listaamigos : function (data)
		{
			var ul =  $("#amigos");	
			
			ul.find("li").remove();
						
			$.each(data, function(index, value) 
			{ 
				
			
				if(sessao.meusdados.tb_sys_usuario != value.tb_sys_usuario)
				{	
				var li = $("<li/>");  li.attr("id", "lista"+sessao.meusdados.tb_sys_usuario+"_"+value.tb_sys_usuario);
				li.attr("class", "naoconectado");
				li.html("<div style='width:20px; height:20px; float:left; overflow:hidden;'><img src='/thumbs/index/w/20/h/20/type/2/autocrop/true/file/"+value.ARQUIVO+"' style='border: 1px solid #cccccc;' /></div><div style='float:right; width:170px;'>" + value.NOME + " <img src='/assets/portal/default/images/naologado.png'></div>");
				ul.append(li);
							 
				}
				
			});
			
			options.onListaAmigos.call(this, "");
		},
		
		conectados : function (data)
		{
			
		
			var ul =  $("#amigos li");	
			$.each(ul, function(index, value){ 
				$(this).unbind("click");
				$(this).find("img:last").attr("src", '/assets/portal/default/images/naologado.png');
			});
			
			
			
			//$("#amigos li").remove();
			$.each(data.data, function(index, value) { 

				
				
				if(sessao.meusdados.tb_sys_usuario != value.id)
				{	
					
					//alert($("#lista"+sessao.meusdados.tb_sys_usuario+"_"+value.id+"").html());
					var obj = $("#lista"+sessao.meusdados.tb_sys_usuario+"_"+value.id+"");
					obj.find("img:last").attr("src", '/assets/portal/default/images/logado.png');
					obj.attr("class", "conectado");						
					obj.bind("click", function(e, ui){
									
						$.fn.interfaceChat.metodos.abreJanelaChat(sessao.meusdados.tb_sys_usuario, value.id, $(this).text(), 'maximizado');
						$.fn.interfaceChat.metodos.registraSessaoChat(value.id);
					});
				}
				
			});
			
			$('ul#amigos>li').tsort({attr:'class'});
			
			$.fn.interfaceChat.metodos.buscaSessaoAtivas();
		}		 
	 }
	
	
		 
		   
       this.each(function() {  
         
           var $t = $(this);
		   
		
             
       });  
       
       // allow jQuery chaining  
       return this;  
    };  
})(jQuery); 



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
		   meusdados : null
		   
	  };
	
	
	 $.fn.interfaceChat.metodos =
     {
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
		
		
		abreJanelaChat : function(remetente, destinatario, titulo)
		{

			
			var windowChat_ = $("#window"+remetente+"_"+destinatario+", #window"+destinatario+"_"+remetente+"");
			if(windowChat_.attr("id") != null)
			{
				windowChat_.show();
				var chatmsg = $("#chatmsg");
				var input = windowChat_.find(chatmsg);
				input.focus();
				return;
			}
					
			
			var div =  $("#chatWindows");	
			var windows = $("<div/>"); windows.attr("class", "window"); windows.attr("id", "window"+remetente+"_"+destinatario);
			var topo = $("<div/>"); topo.attr("class", "topo");
			var divtitulo = $("<div/>"); divtitulo.attr("class", "titulo"); divtitulo.html(titulo);
			var divbotoes = $("<div/>"); divbotoes.attr("class", "botoes"); divbotoes.html("[x]");
			
			divbotoes.bind("click", function(){
				$(this).parent().parent().hide();
			});
			
			topo.append(divtitulo);
			topo.append(divbotoes);
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
			
			
			 $("#corpo"+remetente+"_"+destinatario).slimscroll({
                  color: '#000000',
                  size: '10px',
                  width: '250px',
                  height: '240px',
				  start: 'bottom',
				  railVisible: true,
				  allowPageScroll: true
              })
			
			
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
				li.html("<img src='/thumbs/index/w/20/h/20/type/2/autocrop/true/file/"+value.ARQUIVO+"' style='border: 1px solid #cccccc;' /> " + value.NOME + " <img src='/assets/portal/default/images/naologado.png'>");
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
										
					obj.bind("click", function(e, ui){
									
						$.fn.interfaceChat.metodos.abreJanelaChat(sessao.meusdados.tb_sys_usuario, value.id, $(this).text());
						
					});
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


var socket = null;
var voce = null;
var url = 'http://localhost:9090';

$(document).ready(function() {
		
		
		try
		{	
		
			socket = io.connect(url);
			
			//socket.socket.reconnect(); para reconectar
			
			
			var interfaceChat = $("body").interfaceChat
				(	
					{
						onPegaMeusDados : function(data)
						{
							socket.emit('adduser', { usuario: data });
						},
						onAbreJanela : function(data)
						{	
							socket.emit('joinroom', 
							{
								 remetente : data.remetente,
								 destinatario : data.destinatario
								 
							});
						},
						onEnviaMensagem : function(data)
						{
							socket.emit('sendchat',  {  remetente : data.remetente, destinatario : data.destinatario, sala: data.sala, msg : data.msg });
						}
				}
				);
			
			
			socket.on('connect', function(){ 
				
				
				console.log("conectou..."); 
			
				$.fn.interfaceChat.metodos.me();
					
			
			});
			socket.on('connecting', function (){  console.log("conectando...");  }) 
			socket.on('connect_failed', function () { console.log("conexão falha...");  })
			socket.on('error', function () { console.log("erro ocorrido...");  })
			socket.on('reconnect_failed', function () { console.log("reconexão falhada...");  })		
			socket.on('reconnect', function () { console.log("reconectou...");  })				
			socket.on('reconnecting', function () { console.log("reconectando...");  })						
			socket.on('disconnect', function(){			
				console.log('desconectou');			
			});
			
			
			socket.on('listaconectados', function(data){
			
				$.fn.interfaceChat.metodos.conectados(data);
				console.log(data);
				
			});
			
			socket.on('sendchat', function (data) {
				
				
				
				var remetente = data.remetente;
				var destinatario = data.destinatario;
				var msg = data.msg;
				var tipo = data.type;
				
				
				
				var windowChat_ = $("#window"+remetente+"_"+destinatario+", #window"+destinatario+"_"+remetente+"");
				
				
				
				if(windowChat_.attr("id") == null)
				{				
					$.fn.interfaceChat.metodos.abreJanelaChat(remetente, destinatario, '');
				}
				
				var windowChat = $("#window"+remetente+"_"+destinatario+", #window"+destinatario+"_"+remetente+"");
				
				var titulo = $(".titulo");
				var item1 = $('.corpo');
				var chatmsg = $("#chatmsg");
				
				
				var titulospan = windowChat.find(titulo);
				var corpo = windowChat.find(item1);
				var txtbox = windowChat.find(chatmsg);
				
				
				if(tipo == "remetente")
				{
					
					corpo.append("<img src='/thumbs/index/w/20/h/20/type/2/autocrop/true/file/"+data.dataremetente.foto+"' style='border: 1px solid #cccccc;' title='"+data.dataremetente.nome+"' /> " + dataHora() + " - " + msg+"<br>");
					
					titulospan.html(data.datadestinatario.nome);
					
				}else{
					
					corpo.append("<img src='/thumbs/index/w/20/h/20/type/2/autocrop/true/file/"+data.dataremetente.foto+"' style='border: 1px solid #cccccc;' title='"+data.dataremetente.nome+"' /> " + dataHora() + " - " + msg+"<br>");
					
					titulospan.html(data.dataremetente.nome);
				}
				
				
				
				
				txtbox.val("");
			
	
				
				corpo.scrollTop(100000);
				
				
				console.log(data);
				console.log("enviou uma conversa");
			});
			
		
			
			socket.on('SERVIDOR', function(data){
				
				console.log(data);
				
			});
			
			socket.on('message', function(msg) 
			{
				
				console.log(msg);
			});
			
			socket.on('CONVIDA', function(data) 
			{
				console.log("aquuuuuuuuuuuuuuuuu");
				//socket.emit('joinroom', { sala: data });
			});
			
		
		
		
		}catch(err)
		{
			
		}
	
});



function dataHora()
{
	var data = new Date();
	
	// Guarda cada pedaço em uma variável
	var dia     = data.getDate();           // 1-31
	var dia_sem = data.getDay();            // 0-6 (zero=domingo)
	var mes     = data.getMonth();          // 0-11 (zero=janeiro)
	var ano2    = data.getYear();           // 2 dígitos
	var ano4    = data.getFullYear();       // 4 dígitos
	var hora    = data.getHours();          // 0-23
	var min     = data.getMinutes();        // 0-59
	var seg     = data.getSeconds();        // 0-59
	var mseg    = data.getMilliseconds();   // 0-999
	var tz      = data.getTimezoneOffset(); // em minutos
	
	// Formata a data e a hora (note o mês + 1)
	var str_data = dia + '/' + (mes+1) + '/' + ano4;
	var str_hora = hora + ':' + min;
	return str_hora;
}
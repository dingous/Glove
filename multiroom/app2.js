var express = require('express')
  , routes = require('routes')
  , http = require('http')
  , path = require('path')
  , url = require('url')
  , mysql = require('mysql');
  
  
var app = express();
var slug_aplicacao = "";




var database = 'glove';





/*var client = mysql.createClient({
	user : 'root',
	password : '',
	host : 'localhost'
});*/


var client = mysql.createClient({
	user : 'glovebra_glove2',
	password : 'inventoadmin',
	host : 'localhost'
});

client.query('USE ' + database);


function handleDisconnect(connection) {
  connection.on('error', function(err) {
    if (!err.fatal) {
      return;
    }

    if (err.code !== 'PROTOCOL_CONNECTION_LOST') {
      throw err;
    }

    console.log('Re-connecting lost connection: ' + err.stack);

    connection = mysql.createConnection(connection.config);
    handleDisconnect(connection);
    connection.connect();
  });
}

handleDisconnect(client);

/*app.configure(function(){
    app.use(express.methodOverride());
    app.use(express.bodyParser());
    app.use(app.router);
	app.use(express.static(__dirname + '/public'));
});*/


// routing
app.get('/chat', function (req, res) {
	
	
	var sql = "SELECT tb_sys_usuario.ID_USUARIO, tb_sys_usuario.NOME, tb_sys_usuario.SLUG, tb_social_foto.ARQUIVO ";
		sql += "FROM tb_social_amigos ";
		sql += "INNER JOIN tb_sys_usuario ON tb_social_amigos.ID_USUARIO = tb_sys_usuario.ID_USUARIO ";
		sql += "INNER JOIN tb_glove_perfil ON tb_sys_usuario.ID_USUARIO = tb_glove_perfil.ID_USUARIO ";
		sql += "LEFT JOIN tb_social_foto ON tb_glove_perfil.ID_FOTOPERFIL = tb_social_foto.ID_FOTO ";
		sql += "WHERE tb_social_amigos.ID_AMIGO = '23' ";
		sql += "AND FL_ACEITOU = 1 ";
		sql += "ORDER BY tb_social_amigos.DT_CADASTRO DESC";
	client.query(sql, 
	function(err, results, fields) {
		
	   if(results.length > 0)
       {	 
			//console.log(results);
			for(var i=0; i < results.length; i++)
			{
				console.log(results[i].NOME);
			}
	   }
		
	});
	
	//res.sendfile(__dirname + '/index.html');
	
	var html = "teste";
	res.writeHeader(200, {"Content-Type": "text/html"});
    res.write(html);
  	res.end();
	
	
});


if(slug_aplicacao == null)
{
	console.log("Não foi definido um namespace para a aplicação");
}


var server = app.listen(9090);  //use esta para local
//var server = app.listen(process.env.PORT || process.env.VCAP_APP_PORT); //esta para o Cloud Foundry
var io = require('socket.io').listen(server, { log: true });


	

//io.configure('production', function(){
	
 io.set('log level', 0);
 io.set('transports', [
        'websocket',
        'flashsocket',
        'htmlfile',
        'xhr-polling',
        'jsonp-polling'
    ]);

//});
  


/*Salvador da pátria*/
/*if(process.env.VMC_APP_PORT) {
    io.set('transports', [
        'websocket',
        'flashsocket',
        'htmlfile',
        'xhr-polling',
        'jsonp-polling'
    ]);
}*/


//console.log("aqui ultimo"); //{Object}

//process.exit(1);


//require('./public/js/classChar.js');




var conexao = new function() {
    
	
	this.namespace = null;
	this.conectados = {};
	
	
	this.SetaNamespace = function (namespace) {
		this.namespace = namespace;
	};
	
	
	this.PegaNamespace = function () {
		return this.namespace;
	};
	
    this.PegaConectados = function () {
		
		return this.conectados[this.namespace];        						
    };
	
	this.EstaConectado = function (id) {
		
			
			if(!this.conectados[this.namespace])
			{
				this.conectados[this.namespace] = {};
				return false;
			}
			
			if(this.conectados[this.namespace][id])
			{
				return true;
			}else
			{
				return false;
			}
			
		
	}
	
	this.AdicionaConectado = function (data, socketid) {
		
			data = data.usuario;
			

		
			if(!this.EstaConectado(data.tb_sys_usuario))
			{
				
				
				this.conectados[this.namespace][data.tb_sys_usuario] = 
									  { 
											"id" : data.tb_sys_usuario,
											"nome" : data.NOME,
											"slug" : data.SLUG,									
											"foto" : data.ARQUIVO,
											"socketid" : socketid,																		
											"conversas" : 
											 [
												{
													/*"nome":"João", 
													"provas":  [ { "nota":8 }, {"nota":6}, {"nota":10 }, {"nota":2 } ] */
												}
											 ]
									  };
				//console.log(data.NOME + " conectou...");
				return true;
						
			}else
			{
				//console.log(data.NOME + " já está conectado...");
				return false;
			}
			
		
    };
	
	this.BuscaConectado = function (id) 
	{
		if(this.EstaConectado(id))
		{
			return this.conectados[this.namespace][id];
		}else
		{
			return false;
		}
	}
	
	this.BuscaConversas = function (id) 
	{
		return this.conectados[this.namespace][id]["conversas"];
	}
	
	this.ExcluiConectado = function (id) {
		
		delete this.conectados[this.namespace][id];
		//console.log(id + " desconectou...");
    };
	
	
	/*Conversas*/
	this.AdicionaConversa = function (id, idConversa) {
		
		if(this.EstaConectado(id))
		{
			if(!this.EstaConversando(id, idConversa))
			{
				this.conectados[this.namespace][id]["conversas"].push({ nome : idConversa });
			}
			
		}else
		{
			//console.log(id + " não está conectado...");
		}
    };
	
	this.EstaConversando = function (id, idConversa) {
		
		var conversas = this.conectados[this.namespace][id]["conversas"];
		for(var i in conversas )
		{
			var x = conversas[this.namespace][i].nome;	
			if(idConversa == x)
			{
				//console.log(id + " já está conversando com " + idConversa);
				return true;
			}
			
		}
		
		return false;
	}
	
	this.PegaConversas = function (id)
	{	
		if(this.EstaConectado(id))
		{
			var conversas = this.conectados[this.namespace][id]["conversas"];
			return conversas;
		}
	}
	
}



//http://www.scottblaine.com/2012/using-socket-io-broadcast-one-some-sockets/




app.get('/:id', function (req, res) {
	
			
		slug_aplicacao = req.query["namespace"];
		res.writeHead(200, {'Content-Type': 'text/plain'});
		res.end('_testcb(\'{"message": "ok"}\')');
		
});


var chat = io		 
		  .sockets
		  .on('connection', function (socket) {
			
			
			
			socket.on('adduser', function(data){
				
				
				conexao.SetaNamespace(data.namespace);
		
				
				if(conexao.AdicionaConectado(data, socket.id))
				{
					
					socket.join(conexao.PegaNamespace());
					
					socket.username = data.usuario.tb_sys_usuario;
					socket.room = data.tb_sys_usuario; 
					
				   
					
					chat.to(conexao.PegaNamespace()).emit('SERVIDOR', { 
						msg: "SERVIDOR DIZ: " + data.usuario.NOME + ' conectou',
						salas : chat.manager.rooms,
						salasconectadas : chat.manager.roomClients[socket.id] ,
						namespace : data.namespace
					});
					
					//console.log(socket.id);
					
					
				}else
				{
					chat.to(conexao.PegaNamespace()).emit('SERVIDOR', { msg: "SERVIDOR DIZ: " + data.usuario.NOME + ' já está conectado' });
					
					var remetente = conexao.BuscaConectado(data.usuario.tb_sys_usuario);
					if(remetente != false)
					{
						chat.to(conexao.PegaNamespace()).socket(remetente.socketid).emit('fazlogoff',{});
						//conexao.ExcluiConectado(remetente.id);
						chat.to(conexao.PegaNamespace()).socket(socket.id).emit('reconecta',{});
					}
					
				}
				
					/*chat.emit('listaconectados', 
					{  
						data : conexao.PegaConectados(),
						total : chat.manager.server.connections
					})*/
		
			});
			
			
			socket.on('notificacao', function (data) 
			{
				var tipo = data.tipo;			
				var remetente = conexao.BuscaConectado(data.data.data.dono);
				
				
				
			
				switch(tipo)
				{
					case "MensagemGostei":
					
						if(remetente != false)
						{
							chat.to(conexao.PegaNamespace()).socket(remetente.socketid).emit('notificacao',
							{ 
								tipo : "MensagemGostei",
								data : data	
							});
						}					
					break;
					
					case "ComentarioGostei":
					
						if(remetente != false)
						{
							chat.to(conexao.PegaNamespace()).socket(remetente.socketid).emit('notificacao',
							{ 
								tipo : "ComentarioGostei",
								data : data	
							});
						}					
					break;
					
					case "MensagensAdd":
					
						if(remetente != false)
						{
							chat.to(conexao.PegaNamespace()).socket(remetente.socketid).emit('notificacao',
							{ 
								tipo : "MensagensAdd",
								data : data	
							});
						}					
					break;
					
					case "ComentariosAdd":
					
						if(remetente != false)
						{
							chat.to(conexao.PegaNamespace()).socket(remetente.socketid).emit('notificacao',
							{ 
								tipo : "ComentariosAdd",
								data : data	
							});
						}					
					break;
					
				}
			
				
			});
			
			
			
			socket.on('listaconectados', function (data) 
			{	
		
				chat.to(conexao.PegaNamespace()).emit('listaconectados', 
				{  
					data : conexao.PegaConectados(),
					total : chat.manager.server.connections
				})
			});
			
			socket.on('joinroom', function (data) 
			{
				
				
				//console.log(data);
				socket.join(data.remetente+"_"+data.destinatario);
				chat.emit('SERVIDOR', 
				{ 
				  msg: "SERVIDOR DIZ: " + socket.username + ' ' + socket.id + ' conectou na sala ' + data.sala ,
				  salas : chat.manager.rooms, 
				  salasconectadas : chat.manager.roomClients[socket.id] 
				});
				
				var remetente = conexao.BuscaConectado(data.remetente);
				var destinatario = conexao.BuscaConectado(data.destinatario);
				
				
				
				chat.socket(destinatario.socketid).emit('CONVIDA', 
				{
					remetente : data.remetente,
					destinatario : data.destinatario
				});
			
				
		
			});
			
			
			
			
			socket.on('salas', function (data) 
			{
				//console.log(data);
				socket.join(data.sala);
				chat.to(conexao.PegaNamespace()).emit('SERVIDOR', 
				{ 
				  msg: "SERVIDOR DIZ SALAS CONECTADAS" ,
				  salas : chat.manager.rooms, 
				  salasconectadas : chat.manager.roomClients[socket.id] 
				});
			
			});
			
		
			
			
			socket.on('sendchat', function (data) {
					/*console.log(data);
					chat.in(data.sala).emit('sendchat', {msg : socket.username + ' disse: ' + data.msg});*/
				
				var remetente = conexao.BuscaConectado(data.remetente);
				var destinatario = conexao.BuscaConectado(data.destinatario);
				var msg = data.msg;
				
				if(remetente != false)
				{
				
					chat.to(conexao.PegaNamespace()).socket(remetente.socketid).emit('sendchat', 
					{
						remetente : data.remetente,
						destinatario : data.destinatario,
						msg : data.msg,
						dataremetente : remetente,
						datadestinatario : destinatario,
						type : 'remetente'
					});
				
				}
				
				if(destinatario != false)
				{
					chat.to(conexao.PegaNamespace()).socket(destinatario.socketid).emit('sendchat', 
					{
						remetente : data.destinatario,
						destinatario : data.remetente,
						msg : data.msg,
						dataremetente : remetente,
						datadestinatario : destinatario,
						type : 'destinatario'			
					});
				}
				
			});
			
			
			socket.on('registerlogChat', function (data) 
			{
				
				/*var remetente = data.remetente;
				var destinatario = data.destinatario;
				var msg = data.html;
				
				hoje = new Date()
				dia = hoje.getDate()
				mes = hoje.getMonth()
				ano = hoje.getFullYear()
				if (dia < 10){ dia = "0" + dia}
				if ((mes + 1) < 10){ mes = "0" + (mes + 1) }else{ mes = mes + 1}
				if (ano < 2000){ ano = "19" + ano }
				var data = ano+"/"+mes+"/"+dia;
				
				var sql = "SELECT * FROM tb_social_chat AS MSG WHERE 1=1 AND MSG.ID_USUARIO_REMETENTE = '"+remetente+"' AND MSG.ID_USUARIO_DESTINATARIO = '"+destinatario+"' AND DATE_FORMAT(MSG.DATA_CADASTRO, '%Y/%m/%d') = '"+data+"'";
				
				//console.log(sql);
				
				var sql2 = "INSERT INTO  `tb_social_chat` (";
					sql2 += "`ID_CHAT` ,";
					sql2 += "`ID_USUARIO_REMETENTE` ,";
					sql2 += "`ID_USUARIO_DESTINATARIO` ,";
					sql2 += "`DATA_CADASTRO` ,";
					sql2 += "`MENSAGEM`";
					sql2 += ")";
					sql2 += "VALUES (";
					sql2 += "NULL ,  '"+remetente+"',  '"+destinatario+"',  '"+data+"',  '"+msg+"'";
					sql2 += ");";
				
				//console.log(sql2);
					
				var sql22 = "INSERT INTO  `tb_social_chat` (";
					sql22 += "`ID_CHAT` ,";
					sql22 += "`ID_USUARIO_REMETENTE` ,";
					sql22 += "`ID_USUARIO_DESTINATARIO` ,";
					sql22 += "`DATA_CADASTRO` ,";
					sql22 += "`MENSAGEM`";
					sql22 += ")";
					sql22 += "VALUES (";
					sql22 += "NULL ,  '"+destinatario+"',  '"+remetente+"',  '"+data+"',  '"+msg+"'";
					sql22 += ");";
				
				//console.log(sql22);
				
				
				client.query(sql, 
				function(err, results, fields) {
					
		
					//if (err) throw err;
					
					if(results.length == 0)
					{
						client.query(sql2, 
						function(err, results, fields) {
							
							if (err){ console.log("houve um erro: " + err); throw err };
						});
						
						client.query(sql22, 
						function(err, results, fields) {
							
							if (err){ console.log("houve um erro: " + err); throw err };
						});
					
					}else
					{
						
						
						var sql3 = "UPDATE  `tb_social_chat` SET  MENSAGEM = CONCAT(MENSAGEM, '"+msg+"'), DATA_MODIFICACAO = '"+data+"' WHERE 1=1 AND ID_USUARIO_REMETENTE = '"+remetente+"' AND ID_USUARIO_DESTINATARIO = '"+destinatario+"' AND DATE_FORMAT(DATA_CADASTRO, '%Y/%m/%d') = '"+data+"'";
						
						var sql4 = "UPDATE  `tb_social_chat` SET  MENSAGEM = CONCAT(MENSAGEM, '"+msg+"'), DATA_MODIFICACAO = '"+data+"' WHERE 1=1 AND ID_USUARIO_REMETENTE = '"+destinatario+"' AND ID_USUARIO_DESTINATARIO = '"+remetente+"' AND DATE_FORMAT(DATA_CADASTRO, '%Y/%m/%d') = '"+data+"'";
						
						
						
						client.query(sql3, function(err, results, fields) { if (err){ console.log("houve um erro: " + err); throw err }; });
						client.query(sql4, function(err, results, fields) { if (err){ console.log("houve um erro: " + err); throw err }; });						
						
						

						
					}
										
					//console.log(results);
					
				}
				); 	
				
				*/
				
			});
			
			socket.on('digitandomsg', function (data) {
		
				
				var remetente = conexao.BuscaConectado(data.remetente);
				var destinatario = conexao.BuscaConectado(data.destinatario);
				
				if(remetente != false)
				{
					chat.to(conexao.PegaNamespace()).socket(remetente.socketid).emit('digitandomsg', 
					{
						remetente : data.remetente,
						destinatario : data.destinatario,
						msg : data.msg,
						dataremetente : remetente,
						datadestinatario : destinatario,
						type : 'remetente'
					});
				}
				
				
				if(destinatario != false)
				{
					chat.to(conexao.PegaNamespace()).socket(destinatario.socketid).emit('digitandomsg', 
					{
						remetente : data.destinatario,
						destinatario : data.remetente,
						msg : data.msg,
						dataremetente : remetente,
						datadestinatario : destinatario,
						type : 'destinatario'			
					});
				}
			});
			
			socket.on('digitandomsglimpa', function (data) {
		
				
				var remetente = conexao.BuscaConectado(data.remetente);
				var destinatario = conexao.BuscaConectado(data.destinatario);
				
				if(remetente != false)
				{
					chat.to(conexao.PegaNamespace()).socket(remetente.socketid).emit('digitandomsglimpa', 
					{
						remetente : data.remetente,
						destinatario : data.destinatario,
						msg : data.msg,
						dataremetente : remetente,
						datadestinatario : destinatario,
						type : 'remetente'
					});
				}
				
				if(destinatario != false)
				{
					chat.to(conexao.PegaNamespace()).socket(destinatario.socketid).emit('digitandomsglimpa', 
					{
						remetente : data.destinatario,
						destinatario : data.remetente,
						msg : data.msg,
						dataremetente : remetente,
						datadestinatario : destinatario,
						type : 'destinatario'			
					});
				}

			});
			
			
			// when the user disconnects.. perform this
			socket.on('disconnect', function(){
				
				conexao.ExcluiConectado(socket.username);
				socket.leave(socket.room);
				chat.to(conexao.PegaNamespace()).emit('SERVIDOR', { msg: "SERVIDOR DIZ: " + socket.username + ' desconectou' });
				
				
				
				chat.to(conexao.PegaNamespace()).emit('listaconectados', 
				{  
					data : conexao.PegaConectados(),
					total : chat.manager.server.connections
				});
				
			});
			
			
			
			
		});
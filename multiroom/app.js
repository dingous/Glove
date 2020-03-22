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
	user : 'glovebra_glove2',
	password : 'inventoadmin',
	host : 'localhost'
});*/


var client = mysql.createClient({
	user : 'root',
	password : '',
	host : 'localhost'
});




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



if (slug_aplicacao == null) {
    console.log("Não foi definido um namespace para a aplicação");
}


var server = app.listen(9090);  //use esta para local
//var server = app.listen(process.env.PORT || process.env.VCAP_APP_PORT); //esta para o Cloud Foundry
var io = require('socket.io').listen(server, { log: false });


io.set('log level', 3);
io.set('transports', [
        'websocket',
        'flashsocket',
        'htmlfile',
        'xhr-polling',
        'jsonp-polling'
    ]);

var conexao = new function () {


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


        if (!this.conectados[this.namespace]) {
            this.conectados[this.namespace] = {};
            return false;
        }

        if (this.conectados[this.namespace][id]) {
            return true;
        } else {
            return false;
        }


    }

    this.AdicionaConectado = function (data, socketid) {
		

		
        data = data.usuario;

		
	
		
        if (!this.EstaConectado(data.tb_sys_usuario)) {


            this.conectados[this.namespace][data.tb_sys_usuario] =
									  {
									      "id": data.tb_sys_usuario,
									      "nome": data.NOME,
									      "slug": data.SLUG,
									      "foto": data.ARQUIVO,
									      "socketid": socketid,
									      "conversas":
											 [
												{
												    /* "nome":"fulano", 
												    "provas":  [ { "a":8 }, {"b":6}, {"c":10 }, {"d":2 } ] */
												}
											 ]
									  };
            //console.log(data.NOME + " conectou...");
            return true;

        } else {
            //console.log(data.NOME + " já está conectado...");
            return false;
        }


    };

    this.BuscaConectado = function (id) {
        if (this.EstaConectado(id)) {
            return this.conectados[this.namespace][id];
        } else {
            return false;
        }
    }

    this.BuscaConversas = function (id) {
        return this.conectados[this.namespace][id]["conversas"];
    }

    this.ExcluiConectado = function (id) {
        if (this.EstaConectado(id)) {
            delete this.conectados[this.namespace][id];
        }
        //console.log(id + " desconectou...");
    };


    /*Conversas*/
    this.AdicionaConversa = function (id, idConversa) {

        if (this.EstaConectado(id)) {
            if (!this.EstaConversando(id, idConversa)) {
                this.conectados[this.namespace][id]["conversas"].push({ nome: idConversa });
            }

        } else {
            //console.log(id + " não está conectado...");
        }
    };

    this.EstaConversando = function (id, idConversa) {

        var conversas = this.conectados[this.namespace][id]["conversas"];
        for (var i in conversas) {
            var x = conversas[this.namespace][i].nome;
            if (idConversa == x) {
                //console.log(id + " já está conversando com " + idConversa);
                return true;
            }

        }

        return false;
    }

    this.PegaConversas = function (id) {
        if (this.EstaConectado(id)) {
            var conversas = this.conectados[this.namespace][id]["conversas"];
            return conversas;
        }
    }

}



//http://www.scottblaine.com/2012/using-socket-io-broadcast-one-some-sockets/



var chat = io
		  .sockets
		  .on('connection', function (socket) {

		      socket.on('adduser', function (data) {

		          conexao.SetaNamespace(data.namespace);

		          if (conexao.AdicionaConectado(data, socket.id)) {

		              socket.join(conexao.PegaNamespace());

		              socket.username = data.usuario.tb_sys_usuario;
		              socket.room = data.tb_sys_usuario;



		              chat.to(conexao.PegaNamespace()).emit('SERVIDOR', {
		                  msg: "SERVIDOR DIZ: " + data.usuario.NOME + ' conectou',
		                  salas: chat.manager.rooms,
		                  salasconectadas: chat.manager.roomClients[socket.id],
		                  namespace: data.namespace
		              });


		              chat.to(conexao.PegaNamespace()).emit('listaconectados',
				        {
				            data: conexao.PegaConectados(),
				            total: chat.manager.server.connections
				        })

		              //console.log(socket.username + " conectou");


		          } else {
		              chat.to(conexao.PegaNamespace()).emit('SERVIDOR', { msg: "SERVIDOR DIZ: " + data.usuario.NOME + ' já está conectado' });




		              var remetente = conexao.BuscaConectado(data.usuario.tb_sys_usuario);
		              if (remetente != false) {
		                  chat.to(conexao.PegaNamespace()).socket(remetente.socketid).emit('fazlogoff', {});
		                  //conexao.ExcluiConectado(remetente.id);
		                  chat.to(conexao.PegaNamespace()).socket(socket.id).emit('reconecta', {});
		              }

		          }

		          /*chat.emit('listaconectados', 
		          {  
		          data : conexao.PegaConectados(),
		          total : chat.manager.server.connections
		          })*/

		      });

			 
			  
			 
			 socket.on('AmigoAdd', function (data) {
				 
				   
					
				    var remetente = conexao.BuscaConectado(data.data.data);
				   
				   if (remetente != false) {
					   chat.to(conexao.PegaNamespace()).socket(remetente.socketid).emit('AmigoAdd',
					   {
						   tipo: "AmigoAdd",
						   data: data
					   });
		           }
				 
			 }),
			  
		      socket.on('notificacao', function (data) {
				  
				  
				  
		          var tipo = data.tipo;
		          var remetente = conexao.BuscaConectado(data.data.data.dono);

		          switch (tipo) {
		              case "MensagemGostei":

		                  if (remetente != false) {
		                      chat.to(conexao.PegaNamespace()).socket(remetente.socketid).emit('notificacao',
							{
							    tipo: "MensagemGostei",
							    data: data
							});
		                  }
		                  break;

		              case "ComentarioGostei":

		                  if (remetente != false) {
		                      chat.to(conexao.PegaNamespace()).socket(remetente.socketid).emit('notificacao',
							{
							    tipo: "ComentarioGostei",
							    data: data
							});
		                  }
		                  break;

		              case "MensagensAdd":

		                  if (remetente != false) {
		                      chat.to(conexao.PegaNamespace()).socket(remetente.socketid).emit('notificacao',
							{
							    tipo: "MensagensAdd",
							    data: data
							});
		                  }
		                  break;

		              case "ComentariosAdd":

		                  if (remetente != false) {
		                      chat.to(conexao.PegaNamespace()).socket(remetente.socketid).emit('notificacao',
							{
							    tipo: "ComentariosAdd",
							    data: data
							});
		                  }
		                  break;


		          }


		      });

		      socket.on('listaconectados', function (data) {

		          chat.to(conexao.PegaNamespace()).emit('listaconectados',
				{
				    data: conexao.PegaConectados(),
				    total: chat.manager.server.connections
				})

		      });

		      socket.on('joinroom', function (data) {

		          //console.log(data);
		          socket.join(data.remetente + "_" + data.destinatario);
		          chat.emit('SERVIDOR',
				{
				    msg: "SERVIDOR DIZ: " + socket.username + ' ' + socket.id + ' conectou na sala ' + data.sala,
				    salas: chat.manager.rooms,
				    salasconectadas: chat.manager.roomClients[socket.id]
				});

		          var remetente = conexao.BuscaConectado(data.remetente);
		          var destinatario = conexao.BuscaConectado(data.destinatario);



		          chat.socket(destinatario.socketid).emit('CONVIDA',
				{
				    remetente: data.remetente,
				    destinatario: data.destinatario
				});



		      });

		      socket.on('salas', function (data) {
		          //console.log(data);
		          socket.join(data.sala);
		          chat.to(conexao.PegaNamespace()).emit('SERVIDOR',
				{
				    msg: "SERVIDOR DIZ SALAS CONECTADAS",
				    salas: chat.manager.rooms,
				    salasconectadas: chat.manager.roomClients[socket.id]
				});

		      });


		      socket.on('sendchat', function (data) {
		          /*console.log(data);
		          chat.in(data.sala).emit('sendchat', {msg : socket.username + ' disse: ' + data.msg});*/

		          var remetente = conexao.BuscaConectado(data.remetente);
		          var destinatario = conexao.BuscaConectado(data.destinatario);
		          var msg = data.msg;

		          if (remetente != false) {

		              chat.to(conexao.PegaNamespace()).socket(remetente.socketid).emit('sendchat',
					{
					    remetente: data.remetente,
					    destinatario: data.destinatario,
					    msg: data.msg,
					    dataremetente: remetente,
					    datadestinatario: destinatario,
					    type: 'remetente'
					});

		          }

		          if (destinatario != false) {
		              chat.to(conexao.PegaNamespace()).socket(destinatario.socketid).emit('sendchat',
					{
					    remetente: data.destinatario,
					    destinatario: data.remetente,
					    msg: data.msg,
					    dataremetente: remetente,
					    datadestinatario: destinatario,
					    type: 'destinatario'
					});
		          }

		      });


		      socket.on('registerlogChat', function (data) {

						
				  var remetente = data.remetente;
		          var destinatario = data.destinatario;

		          var msg = data.html;
		          var tipo = data.tipo;

		          hoje = new Date()
		          dia = hoje.getDate()
		          mes = hoje.getMonth()
		          ano = hoje.getFullYear()
		          if (dia < 10) { dia = "0" + dia }
		          if ((mes + 1) < 10) { mes = "0" + (mes + 1) } else { mes = mes + 1 }
		          if (ano < 2000) { ano = "19" + ano }
		          var data = ano + "-" + mes + "-" + dia;

				  
				  /*var conn = mysql.createClient({
					user : 'glovebra_glove2',
					password : 'inventoadmin',
					host : 'localhost',
					database : 'glove'
				  });


					var client = mysql.createClient({
					user : 'root',
					password : '',
					host : 'localhost',
				 	database : 'glove',
					});
					*/
		         
					client.query('USE ' + database);
					
					
		              var sql = "SELECT * FROM tb_social_chat AS MSG WHERE 1=1 AND MSG.ID_USUARIO_REMETENTE = " + remetente + " AND MSG.ID_USUARIO_DESTINATARIO = " + destinatario + " AND DATA_CADASTRO BETWEEN '" + data + " 00:00:00' AND '" + data + " 23:59:59'";
					  
					 
					  
		              var sql2 = "INSERT INTO  tb_social_chat (";
		              sql2 += "ID_USUARIO_REMETENTE ,";
		              sql2 += "ID_USUARIO_DESTINATARIO ,";
		              sql2 += "DATA_CADASTRO ,";
					  sql2 += "DATA_MODIFICACAO ,";
		              sql2 += "MENSAGEM";
		              sql2 += ")";
		              sql2 += "VALUES (";
		              sql2 += "'" + remetente + "',  '" + destinatario + "',  '" + data + "', NOW(), '" + msg + "'";
		              sql2 += ");";

					  
					
					 

		              var sql22 = "INSERT INTO  tb_social_chat (";
		              sql22 += "ID_USUARIO_REMETENTE ,";
		              sql22 += "ID_USUARIO_DESTINATARIO ,";
		              sql22 += "DATA_CADASTRO ,";
					  sql22 += "DATA_MODIFICACAO ,";
		              sql22 += "MENSAGEM";
		              sql22 += ")";
		              sql22 += "VALUES (";
		              sql22 += "'" + destinatario + "',  '" + remetente + "', '" + data + "', NOW(), '" + msg + "'";
		              sql22 += ");";

					  //console.log(sql22);
					 
					 
		              client.query(sql,
		              function (err, results, fields) {

		                   if (err) { console.log("houve um erro: " + err); throw err };

		                

		                  if (results.length == 0) {
								
							
							  if(tipo == "remet"){
								  client.query(sql2);
								  client.query(sql22);
							  }
							  
							  
		                  } else {
							  
							
							  
		                      var sql3 = "UPDATE  tb_social_chat SET  MENSAGEM = CONCAT(MENSAGEM, + '" + msg + "'), DATA_MODIFICACAO = NOW() WHERE 1=1 AND ID_USUARIO_REMETENTE = " + destinatario + " AND ID_USUARIO_DESTINATARIO = " + remetente + " AND DATA_CADASTRO BETWEEN '" + data + " 00:00:00' AND '" + data + " 23:59:59'";

		                     
							  
							       var sql333 = "UPDATE  tb_social_chat SET  MENSAGEM = CONCAT(MENSAGEM, + '" + msg + "'), DATA_MODIFICACAO = NOW() WHERE 1=1 AND ID_USUARIO_REMETENTE = " + remetente + " AND ID_USUARIO_DESTINATARIO = " + destinatario + " AND DATA_CADASTRO BETWEEN '" + data + " 00:00:00' AND '" + data + " 23:59:59'";
							  
							  if(tipo == "remet"){
							   client.query(sql3);
		                      	client.query(sql333);
								
							  }
							  
							
							  
		                      //conn.queryRaw(sql4, function (err, results, fields) { if (err) { console.log("houve um erro: " + err); throw err }; });
		                  }

		              }
		              );
						
						
						//client.end();
					

		      });

		      socket.on('digitandomsg', function (data) {

		          var remetente = conexao.BuscaConectado(data.remetente);
		          var destinatario = conexao.BuscaConectado(data.destinatario);

		          if (remetente != false) {
		              chat.to(conexao.PegaNamespace()).socket(remetente.socketid).emit('digitandomsg',
					{
					    remetente: data.remetente,
					    destinatario: data.destinatario,
					    msg: data.msg,
					    dataremetente: remetente,
					    datadestinatario: destinatario,
					    type: 'remetente'
					});
		          }


		          if (destinatario != false) {
		              chat.to(conexao.PegaNamespace()).socket(destinatario.socketid).emit('digitandomsg',
					{
					    remetente: data.destinatario,
					    destinatario: data.remetente,
					    msg: data.msg,
					    dataremetente: remetente,
					    datadestinatario: destinatario,
					    type: 'destinatario'
					});
		          }
		      });

		      socket.on('digitandomsglimpa', function (data) {
		          var remetente = conexao.BuscaConectado(data.remetente);
		          var destinatario = conexao.BuscaConectado(data.destinatario);

		          if (remetente != false) {
		              chat.to(conexao.PegaNamespace()).socket(remetente.socketid).emit('digitandomsglimpa',
					{
					    remetente: data.remetente,
					    destinatario: data.destinatario,
					    msg: data.msg,
					    dataremetente: remetente,
					    datadestinatario: destinatario,
					    type: 'remetente'
					});
		          }

		          if (destinatario != false) {
		              chat.to(conexao.PegaNamespace()).socket(destinatario.socketid).emit('digitandomsglimpa',
					{
					    remetente: data.destinatario,
					    destinatario: data.remetente,
					    msg: data.msg,
					    dataremetente: remetente,
					    datadestinatario: destinatario,
					    type: 'destinatario'
					});
		          }

		      });


		      // when the user disconnects.. perform this
		      socket.on('disconnect', function () {

		          conexao.ExcluiConectado(socket.username);
		          socket.leave(socket.room);
		          chat.to(conexao.PegaNamespace()).emit('SERVIDOR', { msg: "SERVIDOR DIZ: " + socket.username + ' desconectou' });

		          chat.to(conexao.PegaNamespace()).emit('usuariodesconectado',
				  {
				      data: socket.username
				  });

		          chat.to(conexao.PegaNamespace()).emit('listaconectados',
				 {
				     data: conexao.PegaConectados(),
				     total: chat.manager.server.connections

				 });

		      });
		  });
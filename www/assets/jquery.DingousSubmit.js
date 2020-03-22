(function($) {  
    // jQuery plugin definition  
    $.fn.DingousSubmit = function(params) {  
        
		 var defaults =
	     {
			 onCompletado: function() {} 		
	     };
    	 
       var options = $.extend(defaults, params);
        
       var retorno =
	   {
		   html : null,
		   modelo : null
		   
	   };
       
       
       //acessos externos a este plugin
       $.fn.DingousSubmit.metodos =
       {
    		pegaHtml : function()
    		{
    			if(retorno.html == null)
				{
    				alert('Nenhum html foi retornado nesta requisicao.');
    				return;
				}
    			return retorno.html;
    		},
       
	       pegaModelo : function()
			{
				if(retorno.modelo == null)
				{
					alert('Nenhum modelo foi retornado nesta requisicao.');
					return;
				}
				return retorno.modelo;
			}
       };
       
       
     
    	   
       
       //acessos interno
       var submitForm = function () {
    	   

    	   var dados = JSON.stringify(options);
    	  

    	   $.ajax({
		        async: true,
		        type: 'POST',
		        url: options.url,
		        dataType: "json",
		        contentType: "application/json; charset=utf-8",
		        data: dados,
		        success: function (r) {
		          
		        	var data = r;
		        	
					
		        	if(data.erro == null)
	        		{
		        		alert('O retorno da requisicao nao possui a propriedade error, ela e obrigatoria');
		        		return;
	        		}
					
					
		        	
		        	if(data.erro == "false")
					{					
						$.jGrowl(data.msg, { theme: 'ok'});	
						
						if(data.html != null)
						{
							retorno.html = data.html;
						
						}
						if(data.modelo != null)
						{
							retorno.modelo = data.modelo;
						
						}
						
						options.onCompletado.call(this, data);
						
					}else if(data.erro == "valida")
					{	
						if(data.campos_erros)
						{
						
							//lista erros
							$.each(data.campos_erros, function(index, value) { 

							 });
							
							$("#validacao").html(data.error_html);
							$("#validacao").css("display", "block").fadeIn(1000);;
							$("#validacao").focus();
						}
						
						$.jGrowl(data.msg, { theme: 'alerta'});												
					}
					else if(data.erro == "erro")
					{
												
						$("#validacao").css("display", "none");
		                 $.jGrowl(data.msg, { theme: 'alerta'});
					}
		        	
		        	
		        	
		        },
		        error: function (r)
		        {
		        	
		        	$("#validacao").css("display", "none");
		        	alert(r.responseText);
		        	//document.write(r.responseText);
		        	/*$.colorbox({
                        
                        width: "80%", 
                        html: r.responseText.toString(),
                        onComplete: function(){ }

                      });*/
		        	
		        }
		    });
    	   
    	 
       };
       
       this.each(function() {  
         
           var $t = $(this);  
           
           
           if(defaults.url == null)
    	   {
        	   alert("DingousSubmit: a propriedade url não pode ser nula ");
        	   return false;
    	   }
           
           submitForm();
       });  
       
       // allow jQuery chaining  
       return this;  
    };  
})(jQuery); 
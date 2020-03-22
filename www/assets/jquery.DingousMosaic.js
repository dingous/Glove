(function($) {  
    // jQuery plugin definition  
    $.fn.DingousMosaic = function(params) {  
        
		 var defaults =
	     {
			 onClick: function() {} ,  
			 url: "",
			 ImagensWidth: 0,
			 ImagensHeight: 0,			 		
	     };
    	 
       var options = $.extend(defaults, params);
        
       var vars =
	   {
			pai : null,
			totZindex : 0			   		   
	   };
	   
	   	var  randomFromTo = function(from, to){

			//original: return Math.floor(Math.random() * (to - from + 1) + from);
			return Math.floor(Math.random() * (to - from + 10) + from);
		}
		
		
		var moveRandom = function(id) {

			var cPos = $('#container').offset();
			var cHeight = $('#container').height();
			var cWidth = $('#container').width();
			
			// get box padding (assume all padding have same value)
			var pad = parseInt($('#container').css('padding-top').replace('px', ''));
			
			// get movable box size
			var bHeight = $('#'+id).height();
			var bWidth = $('#'+id).width();
			
			// set maximum position
			maxY = cPos.top + cHeight - bHeight - pad;
			maxX = cPos.left + cWidth - bWidth - pad;
			
			// set minimum position
			minY = cPos.top + pad;
			minX = cPos.left + pad;
			
			// set new position			
			newY = randomFromTo(minY, maxY);
			newX = randomFromTo(minX, maxX);
			
			$('#'+id).css("left", newX);
			$('#'+id).css("top", newY);
			
			$('#'+id).bind("mouseover", function(ui, e){
				$(this).css("border", "1px solid #F00");
			
			});
			
			
			$('#'+id).bind("mouseout", function(ui, e){
				$(this).css("border", "1px solid #666");
			});
			
			
			
			$('#'+id).bind("click", function(ui, e){
			
				var cons =  $(".box a");
				$(cons).each(function(index, value) 
				{
					var clone = $(value).html();
					$(this).replaceWith(clone);
			   	    

				});
			
				
				
				$(this).css("z-index", vars.totZindex);
				vars.totZindex = vars.totZindex + 1;
				
				var target = $(this).find("input:first");
				var url = $(this).find("input:last");
		
								
				var a = $("<a/>");
				a.attr("href", url.val());
				a.attr("target", target.val());				
				$(this).wrapInner(a);
				
				
				
			});
		}
		
	 	   
	   var pegaDados = function()
	   {
		   	
			$.ajax({
			 type: "POST",
			 url: defaults.URL,
			 data: { width : defaults.ImagensWidth, height : defaults.ImagensHeight, limiteFotos: defaults.limiteFotos},
			 success: function (json) 
			 { 	
			 	vars.totZindex = json.length;
				$(json).each(function(index, value) {

					var div = $("<div/>");
					div.attr("class", "box");
					div.attr("id", index);
					div.css("z-index", index);		
					div.width(value.w);
					div.height(value.h);

					var input = $('<input/>');
					input.attr("id", "inputUrl_" + index);
					input.attr("type", "hidden");													
					input.val(value.target);
					div.append(input);
					
					var input2 = $('<input/>');
					input2.attr("id", "inputTarget_" + index);
					input2.attr("type", "hidden");													
					input2.val(value.url);
					div.append(input2);
		
					
					var img = $('<img/>');
					img.attr("id", "foto_" + index);
					img.attr("src", value.src);
					
		
					div.append(img);
					div.fadeIn(2000);
					vars.pai.append(div);
				
					
					if(value.title != "")
					{
						//img.attr("title", value.title);
						$(div).qtip({
						   content: value.title,
						   style: { tip: { corner: 'rightBottom' } },
						   tip: true,
						   show: 'mouseover',
						   hide: 'mouseout',
						   style: 'cream',
						    position: {
									  corner: {
										 target: 'topRight',
										 tooltip: 'bottomLeft'
									  }
							  }
						});
					}
					
					moveRandom(index);

				});
				
				
				$('.box').draggable(
					{ 
					  containment: "parent",
					  start: function(event, ui) 
							{

								$(this).qtip("hide");
								var cons =  $(".box a");
								$(cons).each(function(index, value) 
								{
									var clone = $(value).html();
									$(this).replaceWith(clone);
				
								});
							
								$(this).css("z-index", vars.totZindex);
								vars.totZindex = vars.totZindex + 1;
												
							},
							 stop: function(event, ui)
							  { 
							   

							  	var target = $(this).find("input:first");
								var url = $(this).find("input:last");
						
												
								var a = $("<a/>");
								a.attr("href", url.val());
								a.attr("href", url.val());				
								$(this).wrapInner(a);
							  	
								 
							  }
							
							
					});
					
					
					
				
				
			 }
			
			});	
		
			
	   };
	   
            
       this.each(function() {  
         
           var $t = $(this);  
           
           vars.pai = $t;
		   
		   pegaDados();
          
       });  
       
       // allow jQuery chaining  
       return this;  
    };  
})(jQuery); 
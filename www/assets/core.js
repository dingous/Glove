function Popup(url, width, height, FunccloseCallback)
{
		
		$.fancybox({
				width				: width,
				//height			: height,
				height: 'auto',
				afterShow: function(){
					
					$.fancybox.update()
				},
				onStart: function(){},
				autoScale			: true,
				transitionIn		: 'elastic',
				transitionOut		: 'elastic',
				helpers : {
				overlay : {
					opacity : 0.1,
					css : 
					{
						'background-color' : '#fff'
					}
					}
				},
				type: 'iframe',
				href: url,
				centerOnScroll: true,
				onClosed : FunccloseCallback,
				
				 'transitionIn'        : 'elastic',
				'transitionOut'       : 'elastic',
				'onComplete' : function(){
					$('#fancybox-content').removeAttr('style').css({ 'height' : $(window).height()-100, 'margin' : '0 auto', 'width' : $(window).width()-150 });
					$('#fancybox-wrap').removeAttr('style').css({ 'height' : $(window).height()-100, 'margin' : '0 auto', 'width' : $(window).width()-150 }).show();
					$.fancybox.resize();
					$.fancybox.center();
					
				}
			});			
}



function chamasAjax()
{
	//return true; //para desabilitar resquisições em ajax descomente esta linha
	

	if(self==top)
	{
	
	
	window.parent.$("a").not('#change-pattern a, #change-color a, #tabs a').unbind();
	window.parent.$("a").not('#change-pattern a, #change-color a, #tabs a').bind("click", function(event)
	{
		
		
		var url = window.parent.$(this).attr('href');
		var type = window.parent.$(this).attr('rel');
		
		
		if(url == null)
		{
			return;
		}
		
		
		
		if(type == "normal_request")
		{
			
			return true;
		}
		
		
		if(url.indexOf(".jpg") != -1 || url.indexOf(".gif") != -1 || url.indexOf(".png") != -1 || url.indexOf(".JPG") != -1 || url.indexOf(".GIF") != -1 || url.indexOf(".PNG") != -1 || url.indexOf(".jpeg") != -1 || url.indexOf(".JPEG") != -1 || url.indexOf("void") != -1)
		{
			return true;
		}
		
		if(url.indexOf("#tabs") != -1)
		{
			//event.preventDefault();
			
			/*$('#tabs').tabs();
			var index = $('#tabs a[href="#tabs-2"]').parent().index();
			$('#tabs').tabs('select', index);*/

			
		}else
		{
		
			event.preventDefault();
			
				var browser_type=navigator.appName;
			if (browser_type != "Microsoft Internet Explorer")
			{
			window.history.pushState({url: "" + url + ""}, $(this).attr('title') , url);
			window.history.replaceState({ path: url }, '');
			}
			
			$.ajax({
			type: "GET",
			url : url,
			cache : false,
			beforeSend: function (data) 
			{
				$('.loader').show();
			},
			success : function (html) 
			{ 
				
				$('.loader').hide();
				
				var header1 = window.parent.$('header#header:first');
				header1.hide();
			
				
				window.parent.$("#content").html(html);
				
				
				var header2 = window.parent.$('header#header:last', window.parent.$("#content"));
				
				header1.replaceWith(header2);
				header1.show();
		
				chamasAjax();
				
				
				
			}});
			
		
				
			return false;	
		}
	});
	}
}



function chamasAjaxUrl(url)
{
	//return true; //para desabilitar resquisições em ajax descomente esta linha
	
		
		var url = url;
		

			var browser_type=navigator.appName;
			if (browser_type != "Microsoft Internet Explorer")
			{
				window.history.pushState({url: "" + url + ""}, '' , url);
				window.history.replaceState({ path: url }, '');
			}
			
			$.ajax({
			type: "GET",
			url : url,
			cache : false,
			beforeSend: function (data) 
			{
				$('.loader').show();
			},
			success : function (html) 
			{ 
				
				$('.loader').hide();
				
				var header1 = $('header#header:first');
				header1.hide();
			
				
				window.parent.$("#content").html(html);
				
				
				var header2 = window.parent.$('header#header:last', window.parent.$("#content"));
				
				header1.replaceWith(header2);
				header1.show();
		
				chamasAjax();
				
			}
		  });
			
		
				
			return false;	
}
	
var chatTimerMensagem = {};

$(document).ready(function(){
	
	
	chamasAjax();
	
	var browser_type = navigator.appName;
	

	
	if (browser_type != "Microsoft Internet Explorer")
	{
		window.history.replaceState({ path: window.location.href }, '');	
	}
	
	$(window).bind('popstate', function(event) {    	
		// if the event has our history data on it, load the page fragment with AJAX
		var state = event.originalEvent.state;
		if (state) {
			//$("#content").load(state.path);
			chamasAjaxUrl(state.path);
		}
	});
	
	
	
});
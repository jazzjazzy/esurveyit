$(document).ready(function(){
		
	$('#bg-color').live('click', function(){
		$('.color-tags').css({'font-weight':'normal'});
		$('#bg-color-tag').css({'font-weight':'bold'});
		var currcolor = rgbTohex($('.mainContent').css('background-color'));
		var label = $.farbtastic('#colorpicker');
		label.linkTo(function(color){
			$('.mainContent').css({'background-color':color});
			$('#bg-color').val(color);
			$('#show-color').css({'background-color':color});
		})
		label.setColor(currcolor);
	})
	
	$('#title-color').live('click', function(){
		$('.color-tags').css({'font-weight':'normal'});
		$('#title-color-tag').css({'font-weight':'bold'});
		var currcolor = rgbTohex($('.title').css('color'));
		var label = $.farbtastic('#colorpicker');
		label.linkTo(function(color){
			$('.title').css({'color':color});
			$('#title-color').val(color);
			$('#show-color').css({'background-color':color});
		})
		label.setColor(currcolor);
	})
	
	$('#details-color').live('click', function(){
		$('.color-tags').css({'font-weight':'normal'});
		$('#details-color-tag').css({'font-weight':'bold'});
		var currcolor = rgbTohex($('.details').css('color'));
		var label = $.farbtastic('#colorpicker');
		label.linkTo(function(color){
			$('.details').css({'color':color});
			$('#details-color').val(color);
			$('#show-color').css({'background-color':color});
		})
		label.setColor(currcolor);
	})
	
	$('#font-color').live('click', function(){
		$('.color-tags').css({'font-weight':'normal'});
		$('#font-color-tag').css({'font-weight':'bold'});
		var currcolor = rgbTohex($('.question-block').css('color'));
		var label = $.farbtastic('#colorpicker');
		label.linkTo(function(color){
			$('.question-block').css({'color':color});
			$('#font-color').val(color);
			$('#show-color').css({'background-color':color});
		})
		label.setColor(currcolor);
	})
	
	$('#label-bg').live('click', function(){
		$('.color-tags').css({'font-weight':'normal'});
		$('#label-bg-tag').css({'font-weight':'bold'});
		var currcolor = rgbTohex($('.question-label').css('background-color'));
		var label = $.farbtastic('#colorpicker');
		label.linkTo(function(color){
			$('.question-label').css({'background-color':color});
			$('#label-bg').val(color);
			$('#show-color').css({'background-color':color});
		})
		label.setColor(currcolor);
	})
	
	$('#label-color').live('click', function(){	
		$('.color-tags').css({'font-weight':'normal'});
		$('#label-color-tag').css({'font-weight':'bold'});
		var currcolor = rgbTohex($('.question-label').css('color'));
		var label = $.farbtastic('#colorpicker');
		label.linkTo(function(color){
			$('.question-label').css({'color':color});
			$('#label-color').val(color);
			$('#show-color').css({'background-color':color});
		})
		label.setColor(currcolor);
	})
});

function pageSetProp(id){
			
			$('#bg-color').val(rgbTohex($('.mainContent').css('background-color')));
			$('#title-color').val(rgbTohex($('.title').css('color')));
			$('#details-color').val(rgbTohex($('.details').css('color')));
			$('#font-color').val(rgbTohex($('.question-block').css('color')));
			$('#label-bg').val(rgbTohex($('.question-label').css('background-color')));
			$('#label-color').val(rgbTohex($('.question-label').css('color')));
	
			 var titlefont = $(".title").css("font-size").replace('px', '');
			$("#title-font-val").html(titlefont+'px');
			$("#title-font").slider({value:titlefont,
								min: 10,
								max: 30,
								step: 1,
								slide: function( event, ui ) {
									$(".title").css({"font-size":ui.value+'px', 'line-height': '105%'});
									$("#title-font-val").html(ui.value+'px');
								}
			});
			
			var detailsfont = $(".details").css("font-size").replace('px', '');

			$("#details-font-val").html(detailsfont+'px');
			$("#details-font").slider({value:detailsfont,
								min: 10,
								max: 30,
								step: 1,
								slide: function( event, ui ) {
									$(".details").css({"font-size":ui.value+'px', 'line-height': '105%'});
									$("#details-font-val").html(ui.value+'px');
								}
			});
			
			var labelfont = $(".question-label").css("font-size").replace('px', '');
			$("#label-font-val").html(labelfont+'px');
			$("#label-font").slider({value:labelfont,
								min: 10,
								max: 30,
								step: 1,
								slide: function( event, ui ) {
									$(".question-label").css({"font-size":ui.value+'px', 'line-height': '105%'});
									$("#label-font-val").html(ui.value+'px');
								}
			});
			
			var labelfont = $(".question-block").css("font-size").replace('px', '');
			$("#page-font-val").html(labelfont+'px');
			$("#page-font").slider({value:labelfont,
								min: 10,
								max: 30,
								step: 1,
								slide: function( event, ui ) {
									$(".question-block").css({"font-size":ui.value+'px', 'line-height': '105%'});
									$("#page-font-val").html(ui.value+'px');
									//$("#example div#value").exampleVal();	
								}
			});

};
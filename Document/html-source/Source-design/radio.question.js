
$(document).ready(function(){

});

var question_id;

function radioSetProp(id){
	
	question_id = "#q"+id;
	
	$(question_id+' li').each(function(id, strong){
		var field = id+1;
		$("#row-List").append(inputRow(field,$(this).text()));
		
	});
	
	$("#row-List").sortable({
			handle : '#handle',
			axis: 'y',
			update : function() { 
				$(question_id+'  option').each(function(id, strong){
					var field = (id+1);
					var value =  $('#row-List li:nth-child('+field+') input:text').val();
					$(question_id+' option:nth-child('+field+')').html(value);
				})
			},
			delay: 30
	});
	
	var inputfont = $(question_id).css("width").replace('px', '');
	
	$("#text-width-val").html(inputfont+'px');
	
	$("#text-width").slider({value:inputfont,
						min: 10,
						max: 400,
						step: 1,
						start: function(event, ui){
							$(question_id).css('border', '1px dashed #ccc');
						},
						stop: function(event, ui){
							$(question_id).css('border', 'none');
						},
						slide: function( event, ui ) {
							$(question_id).css({"width":ui.value+'px', 'line-height': '105%'});
							$("#text-width-val").html(ui.value+'px');
						}
	});
	
	$('#radio-row-add').click( function(){
		id = $("#row-List li").length+1;
		$("#row-List").append(inputRow(id,$('#radio-row').val()));
		$(question_id+' option:last').after('<option>'+$('#radio-row').val()+"</option>");
	})
	
	var inputfont = $(question_id).css("font-size").replace('px', '');
	$("#text-height-val").html(inputfont+'px');
	$("#text-height").slider({value:inputfont,
						min: 10,
						max: 100,
						step: 1,
						slide: function( event, ui ) {
							$(question_id).css({"font-size":ui.value+'px'});
							$("#text-height-val").html(ui.value+'px');
						}
	});
	
	

	
	$('.rowlabel').live('keyup', function(e){
		var id =$(this).attr('id');
		var num_id = id.split("-");
		$(question_id+' option:nth-child('+num_id[1]+')').html($('#'+id).val());
	})
	
	
	
	//$('#element-count').number();
	
	$('#add-element-count').click(function(){
			var size = (parseInt($('#element-count').text())+1);
			if(size <= 1){
				$(question_id).removeAttr("multiple");
			}else{
				$(question_id).attr("multiple", "multiple");	
			}
			$('#element-count').text((size)) ;
			$(question_id).attr("size", (size));
	});
	

	$('#remove-element-count').click(function(){
			var size = (parseInt($('#element-count').text())-1);
			if(size <= 1){
				$(question_id).removeAttr("multiple");
				size=1;
			}else{
				$(question_id).attr("multiple", "multiple");	
			}
			$('#element-count').text(size) ;
			$(question_id).attr("size", size);
	});
	
	if($(question_id).attr("multiple") == true){
		$('#element-count').text($(question_id).attr("size"));
	}else{
		$('#element-count').text('1');
	}
	/*
	$('#layout').live('change', function(){
			if ($('#layout:checked').val() == 'single'){
					$(question_id).removeAttr("multiple");
			}else{
					$(question_id).attr("multiple", 'multiple');
					$(question_id).attr("size", $('#element-count').val());
			}
	});*/
	
	$('#text-center').live('click', function(){
		$(question_id).parent('div').css({'text-align':'center'});
	})
	
	$('#text-left').live('click', function(){
		$(question_id).parent('div').css({'float':'left'});
	})
	
	$('#text-right').live('click', function(){
		$(question_id).parent('div').css({'text-align':'right'});
	})
	
	$('#text-caption').live('keyup', function(e){
		$(question_id+'_caption').html($(this).val());
	})
};

function radioGather(id) {
	
	var row = new Array();
	$("#"+id+' li').each(function(index, ui){
		var field = index+1;
		row[field] = '"'+field+'":"' +$(this).text()+'"';
	});
	
	row.shift();
	
	var a = id.split('_');
	var qid = "q"+a[1];
	
	var type = '"type":"'+$("#"+id).attr('class')+'"';
	var rows = '"rows":{'+row.join(',')+'}';
	var title = '"title":"'+$.trim(($("#"+id+' div.question-label').text()))+'"';
	var width = '"width":"'+$("#"+qid).css("width")+'"';
	var height = '"height":"'+$("#"+qid).css("font-size")+'"';
	var text = '"text":"'+$("#"+qid).css("text-align")+'"';
	var caption = '"caption":"'+$.trim(($("#"+qid+'_caption').text()))+'"';
	var options = '"options":{'+width+', '+height+', '+text+', '+caption+'}';
	
	//create array to assign to json 
	return '"'+id+'":{'+title+', '+type+', '+rows+', '+options+'}';
}

function inputRow(id, text){
	return '<li><div><input type="text" style="float:left;width:90%" id="rowlabel-'+id+'" class="rowlabel" name="rowlabel-'+id+'" value="'+text+'" /><div style="float:left;width:5%" id="handle"><img src="images/handle.png" /></div></div><br clear="all"></li>';	
}
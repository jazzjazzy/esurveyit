
$(document).ready(function(){

});

var question_id;

function checkboxSetProp(id){
	
	question_id = "#q"+id;
	
	$("#question-field").val($("#qid_"+id+" div.question-label").text());
	
	$('#question-field').live('keyup', function(e){
		$("#qid_"+id+" div.question-label").html($(this).val());
	})
	
	$('#text-caption').val($("#qid_"+id+" div.caption").text());
	
	$(question_id+' li').each(function(id, strong){
		var field = id+1;
		$("#row-List").append(inputRow(field,$(this).text()));
		
	});
	
	$("#row-List").sortable({
			handle : '#handle',
			axis: 'y',
			update : function() { 
				$(question_id+' li').each(function(id, strong){
					var field = (id+1);
					var value =  $('#row-List li:nth-child('+field+') input:text').val();
					alert(value);
					$(question_id+' li:nth-child('+field+')>label').html(value);
				})
			},
			delay: 30
	});
	
	var inputfont = $(question_id).outerWidth();
	
	$("#text-width-val").html(inputfont+'px');
	
	$("#text-width").slider({value:inputfont,
						min: 10,
						max: 571,
						step: 1,
						start: function(event, ui){
							$(question_id).css('border', '1px dashed #ccc');
						},
						stop: function(event, ui){
							$(question_id).css('border', 'none');
						},
						slide: function( event, ui ) {
							$(question_id).css({"width":ui.value+'px', 'line-height': '1.5em'});
							$("#text-width-val").html(ui.value+'px');
						}
	});
	
	$('#checkbox-row-add').click( function(){
		rowid = $("#row-List li").length+1;
		$("#row-List").append(inputRow(id,$('#checkbox-row').val()));
		$(question_id).append('<li><input type="checkbox" name="q'+id+'" id="q'+id+'" class="row" value="" ><label  for="q'+id+'">'+$('#checkbox-row').val()+'</label></li>');
	})
	
	var inputfont = $(question_id).css("font-size").replace('px', '');
	$("#text-height-val").html(inputfont+'px');
	$("#text-height").slider({value:inputfont,
						min: 10,
						max: 100,
						step: 1,
						start: function(event, ui){
							$(question_id).css('border', '1px dashed #ccc');
						},
						stop: function(event, ui){
							$(question_id).css('border', 'none');
						},
						slide: function( event, ui ) {
							$(question_id).css({"font-size":ui.value+'px', 'line-height': '1.5em'});
							$("#text-height-val").html(ui.value+'px');
						}
	});
	
	
	if($(question_id).attr("multiple") == true){
		$('#layout[value=multi]').attr("checked", "checked");
	}else{
		$('#layout[value=single]').attr("checked", "checked");
	}
	
	
	$('#layout').live('change', function(){
			if ($('#layout:checked').val() == 'single'){
					$(question_id).removeAttr("multiple");
			}else{
					$(question_id).attr("multiple", 'multiple');
			}
	});
	
	$('#text-center').live('click', function(){
		$(question_id).parent('div').css({'text-align':'center'});
	})
	
	$('#text-left').live('click', function(){
		$(question_id).parent('div').css({'text-align':'left'});
	})
	
	$('#text-right').live('click', function(){
		$(question_id).parent('div').css({'text-align':'right'});
	})
	
	$('#text-caption').live('keyup', function(e){
		$(question_id+'_caption').html($(this).val());
	})
};

function checkboxGather(id) {
	
	var row = new Array();
	$("#"+id+' li').each(function(id, ui){
		var field = id+1;
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
	return '<li><div><input type="text" id="rowlabel-'+id+'" class="rowlabel" name="rowlabel-'+id+'" value="'+text+'" /><div id="handle"><img src="images/handle.png" /></div><div id="handle"><img src="images/Close-icon-small.png"  id="remove-col-'+id+'" class="remove-col" /></div></div><br clear="all"></li>';	
}
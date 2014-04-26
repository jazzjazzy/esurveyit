$(document).ready(function(){
	

});

var question_id;

function arraySetProp(id){
	
	question_id = "#q"+id;
	
	$("#question-field").val($("#qid_"+id+" div.question-label").text());
	
	$('#question-field').live('keyup', function(e){
		$("#qid_"+id+" div.question-label").html($(this).val());
	})
	
	$('#array-caption').val($("#qid_"+id+" div.caption").text());
	
	$(question_id+' td:nth-child(1)').each(function(id, strong){
		var field = id+1;
		$("#row-List").append(inputRow(field,$(this).text()));
		
	});

	$(question_id+' th').each(function(id, strong){
		if($(this).attr('class') != "blank"){
			$("#column-List").append(inputColumn(id,$(this).text()));
		}
	});
	
	$("#column-List").sortable({
			handle : '#handle',
			axis: 'y',
			update : function(event, ui) { 
				$(question_id+' th').each(function(id, strong){
					var field = (id+1);
					var value =  $('#column-List li:nth-child('+field+') input:text').val();
					//$('#column-List li:nth-child('+field+') input:text').attr('id', 'colheader-'+field);
					$(question_id+' th.header-'+field).html(value);
				})
			},
			delay: 30
	});
	
	$("#row-List").sortable({
			handle : '#handle',
			axis: 'y',
			update : function() { 
				$(question_id+'  td:nth-child(1)').each(function(id, strong){
					var field = (id+1);
					var value =  $('#row-List li:nth-child('+field+') input:text').val();
					$('#row-List li:nth-child('+field+') input').attr('id', 'rowlabel-'+field);
					$(question_id+' #label-'+field).html(value);
				})
			},
			delay: 30
	});
		
	$('.colheader').live('keyup', function(e){
		var id =$(this).attr('id');
		var num_id = id.split("-");
		$(question_id+' th.header-'+num_id[1]).html($('#'+id).val());
	})
	
	$('.rowlabel').live('keyup', function(e){
		var id =$(this).attr('id');
		var num_id = id.split("-");
		$(question_id+' td#label-'+num_id[1]).html($('#'+id).val());
	})
	
	$('.columntype').live('change', function(e){

		var id =$(this).attr('id');
		var num_id = id.split("-");
		
		switch($(this).val()){ 
			case "Radio" : $('.col'+num_id[1]).html('<input type="radio">'); break;
			case "Checkbox" : $('.col'+num_id[1]).html('<input type="checkbox">'); break;
			case "yes no" : $('.col'+num_id[1]).html('<select><option>Yes</option><option>No</option>'); break;
		}
	})
	
	$('#array-heading-add').live('click', function(){
		id = $("#column-List li").length+1;
	
		$("#column-List").append(inputColumn(id,$('#array-heading').val()));
		$(question_id+' th:last').after('<th class="header-'+id+'">'+$('#array-heading').val()+"</th>");
		console.debug($(question_id+' tr:not(:first)'));
		$(question_id+' tr:not(:first)').each(function(){
			$(this).append('<td class="col'+id+'"><input type="radio"></td>');
		})
	})
	
	$('#array-row-add').live('click', function(){
		id = $("#row-List li").length+1;
		$("#row-List").append(inputRow(id,$('#array-row').val()));
		$(question_id+' tr:last').after('<tr><td id="label-'+id+'" class="label">'+$('#array-row').val()+"</td>");
		$("#column-List li").each(function(){
			$(question_id+' tr:last').append('<td class="col'+id+'"><input type="radio"></td>');
		})
	})
	
	$(".remove-col").live("click", function(){
		var id = $(this).attr("id").split('-'); 
		$(".header-"+id[2]).remove();
		$(".col"+id[2]).remove();
		$('#colheader-'+id[2]).parent('div').parent('li').remove();
	});
	
	$(".remove-row").live("click", function(){
		var rowid = $(this).attr("id").split('-'); 
		alert(rowid[2]);
		$("#label-"+rowid[2]).parent('tr').remove();
		//$(".col"+id[2]).remove();
		$('#rowlabel-'+rowid[2]).parent('div').parent('li').remove();
	});
	
	$('#array-caption').live('keyup', function(e){
		$(question_id+'_caption').html($(this).val());
	})
	
};

function arrayGather(id) {
	
	var type = '"type":"'+$("#"+id).attr('class')+'"';
	var title = '"title":"'+$.trim(($("#"+id+' div.question-label').text()))+'"';
	
	var row = new Array();
	$("#"+id+' td:nth-child(1)').each(function(id, strong){
		var field = id+1;
		row[field] = '"'+field+'":"' +$(this).text()+'"';
	});
	
	row.shift(); //TODO: find a better way to remove undefined

	var rows = '"rows":{'+row.join(',')+'}';
	
	var column = new Array();
	$("#"+id+' th').each(function(index, value){
		if($(this).attr('class') != "blank"){
			column[index] = '"'+index+'":"' +$(this).text()+'"';
		}
	});
	
	column.shift(); //TODO: find a better way to remove undefined
	
	var columns = '"columns":{'+column.join(',')+'}';
	
	var a = id.split('_');
	var qid = "q"+a[1];
	
	var caption = '"caption":"'+$.trim(($("#"+qid+'_caption').text()))+'"';
	var options = '"options":{'+caption+'}';
	//var options = '"options":""';
	
	//var caption = '"caption":"'+$.trim(($("#"+id+'_caption').text()))+'"';
	
	//return array to assign to json 
	return '"'+id+'":{'+title+', '+type+', '+rows+', '+columns+', '+options+'}';
	
}

function inputColumn(id, text){
	return '<li><div style="width:120px;float:left"><input type="text" id="colheader-'+id+'" class="colheader" name="colheader'+id+'" value="'+text+'" /></div> <div style="float:left"><select id="columntype-'+id+'" class="columntype"><option>Radio</option><option>Checkbox</option><option>yes no</option></select> </div><div id="handle" style="float:left"><img src="images/handle.png" /></div><div id="handle"><img src="images/Close-icon-small.png"  id="remove-col-'+id+'" class="remove-col" /></div><br clear="all"></li>';	
}


function inputRow(id, text){
	return '<li><div><input type="text" style="float:left;width:80%" id="rowlabel-'+id+'" class="rowlabel" name="rowlabel-'+id+'" value="'+text+'" /><div id="handle"><img src="images/handle.png" /></div><div id="handle"><img src="images/Close-icon-small.png" id="remove-row-'+id+'" class="remove-row" /></div></div><br clear="all"></li>';	
}
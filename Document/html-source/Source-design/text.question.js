
$(document).ready(function(){

});

var question_id;

function textSetProp(id){
	
	question_id = "#q"+id;
	
	var inputfont = $(question_id).css("width").replace('%', '');
	$("#text-width-val").html(inputfont+'%');
	$("#text-width").slider({value:inputfont,
						min: 10,
						max: 100,
						step: 1,
						slide: function( event, ui ) {
							$(question_id).css({"width":ui.value+'%', 'line-height': '105%'});
							$("#text-width-val").html(ui.value+'%');
						}
	});
	
	var inputfont = $(question_id).css("font-size").replace('px', '');
	$("#text-height-val").html(inputfont+'px');
	$("#text-height").slider({value:inputfont,
						min: 10,
						max: 100,
						step: 1,
						slide: function( event, ui ) {
							$(question_id).css({"font-size":ui.value+'px', "height":ui.value+'px'});
							$("#text-height-val").html(ui.value+'px');
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
};

function textGather(id) {
	//Qid_2
	var a = id.split('_');
	var qid = "q"+a[1];
	
	var type = '"type":"'+$("#"+id).attr('class')+'"';
	var title = '"title":"'+$.trim(($("#"+id+' div.question-label').text()))+'"';
	var width = '"width":"'+$("#"+qid).css("width")+'"';
	var height = '"height":"'+$("#"+qid).css("font-size")+'"';
	var text = '"text":"'+$("#"+qid).parent('div').css('text-align')+'"';
	var caption = '"caption":"'+$.trim(($("#"+id+'_caption').text()))+'"';
	var options = '"options":{'+width+', '+height+', '+text+', '+caption+'}';
	
	//create array to assign to json 
	return '"'+id+'":{'+title+', '+type+', '+options+'}';
}
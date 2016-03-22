function updateTableAfterAdd(top){
    return function(data,textStatus,jqXHR){
	if(top){
	    $("#table-header").after(data);
	}else{
	    $("#main-table-holder").append(data);
	}
    };
};
function closeTask(event){
    var id = event.target.id;
    var taskId = id.substr(5);
    $.post("query.php",{close:taskId});
    var parentrow = $("#"+id).parents(".row").first();
    parentrow.insertAfter("#closed-table-header");
    parentrow.children().addClass("closed strikethrough");
    parentrow.children().last().removeClass("strikethrough");
    $("#"+id).remove();
};

function addTask(task,tag,person,top){
    if(top){
	$.post("query.php",{increaseRank:""});
    }
    $.post("query.php",{task:task,tag:tag,person:person,top:top},updateTableAfterAdd(top));
};

function getTable(){
    $.get("query.php",{load: ""})
	.done(function(data){
	    $("#main-table-holder").append(data);
	});
};

function getClosedTable(){
    $.get("query.php",{closed:""})
	.done(function(data){
	    $("#closed-table-holder").append(data);
	});
}

function toggleTask(event){
    var formdiv = $(event.data.formsel);
    var buttondiv = $(event.data.buttonsel);
    if(formdiv.css('display') == 'none'){
	formdiv.css({
	    display:'inline',
	    width: '0'
	}).animate({
	    display:'inline',
	    width:'80%'
	});
	//buttondiv.animate({marginLeft:"+=300px"});
    }else{
	var taskObj = formdiv.find("[id^=taskDescription]").first();
	var tagObj = formdiv.find("[id^=taskTag]").first();
	var personObj = formdiv.find("[id^=taskPerson]").first();
	var taskVal = taskObj.val();
	var tagVal = tagObj.val();
	var personVal = personObj.val();
	var topTask = true;
	if(tagObj.attr('id') == "taskTagBottom"){
	    topTask = false;
	}
	if(taskVal != "" && tagVal != "" && personVal != "")addTask(taskVal,tagVal,personVal,topTask);
	formdiv.animate({
	    width:0
	},function(){
	    $(this).find("[id^=taskDescription]").first().val("");
	    $(this).find("[id^=taskTag]").first().val("");
	    $(this).find("[id^=taskPerson]").first().val("");
	    $(this).hide();
	});

/*
	buttondiv.animate({
	    marginLeft: "-=300px"
	});
*/
    }
};

$(document).ready(function() {
    $("#task-top").click({formsel:'#addform-top',buttonsel:'#task-top'},toggleTask);
    $("#task-bottom").click({formsel:'#addform-bottom',buttonsel:'#task-bottom'},toggleTask);
    getTable();
    getClosedTable();
    $("#main-table-holder").on('click','.close-button',closeTask);
});


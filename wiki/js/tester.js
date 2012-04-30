var tests = true;

function runAllTests(total){
	if (total <= 0){
		//TODO error case
		return;
	}
	
	$('#progress-total').html("Running tests...");
	$('#button-modal').removeClass("btn-primary");
	$('#button-modal').html("Cancel");

	$('#runTests').modal('toggle');
	$.get("runtest.php?total="+total, function(data){
		//alert(data);
		if (data <=-1){
			$('#progress-total').html("Error contacting server");
			$('#button-modal').addClass("btn-primary");
			$('#button-modal').html("Done");
			return;
		}
		checkStatus(data,total);
	});
	tests = true;
}

function checkStatus(data,total){
	$.get("runtest.php?status="+data,function (status){
		status = $.parseJSON(status);
		$('#progress-total').html(status.current + " of " + status.total);
		$('#modal-bar').width(+Math.round(status.current/status.total*100)+"%");
		if (status.current < total && tests){
			setTimeout("checkStatus('"+data+"',"+total+");",1000);
		} else if (status.current >= total){
			$('#progress-message').html("Tests finished!");
			$('#button-modal').addClass("btn-primary");
			$('#button-modal').html("Done");
		}
	});
}


function cancelTests(){
	$('#runTests').modal('hide');
	if ($('#button-modal').html()=="Done"){
		location.reload();
	}
	tests = false;
}
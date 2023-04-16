"use strict";
function loadAptAlerOrFollowUp(dataType,aptID){
		$('.loadAptAlerts').html('Loading...');
		$('.typeTitle').html('Loading...');
		if(dataType=='alerts')
			var cmd = 'load_apt_alerts';
		else
			var cmd = 'load_apt_followUp';
			
		$.post('server.php',{cmd:cmd,aptID:aptID},function(r){
			$('.loadAptAlerts').html(r);
			if(cmd=='load_apt_alerts')
				$('.typeTitle').html('Alerts');
			else
				$('.typeTitle').html('Follow Up');
		});
	}
	$('#aptTable').cardtable();
	function deleteApt(aptID){
		if(confirm("Are you sure you want to delete this appointment template?")){
			$.post('server.php',{aptID:aptID,"cmd":"delete_appt_temp"},function(r){
				window.location = 'view_apts.php';	
			});	
		}
	}
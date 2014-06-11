jQuery(document).ready(function($){
	if (typeof ga == 'function' && gaevents.length > 0) {
		function bindEventTrigger(eventNo) {
			if($(gaevents[i]['selector']).length) {
				var label = '';
				if (gaevents[i]['label']) {
					label = ', ' + gaevents[i]['label'];
				}
				var value = '';
				if (gaevents[i]['value'] != 0) {
					value = ', ' + parseInt(gaevents[i]['value']);
				}
				var gaevent = gaevents[i]['category'] + ', ' + gaevents[i]['action'] + label + value;
				$(gaevents[i]['selector']).click(function(){
					ga(['send', 'event', gaevent]);
				});
			}
		}
		for( var i = 0; i < gaevents.length; i++ ) {
			bindEventTrigger(i);
		}
		
	}
});
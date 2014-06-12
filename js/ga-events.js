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
				if ( gaevents[i]['handler'] == 'click' ){
					$(gaevents[i]['selector']).click(function(){
						ga(['send', 'event', gaevent]);
					});
				}
				if ( gaevents[i]['handler'] == 'submit' ){
					$(gaevents[i]['selector']).submit(function(){
						ga(['send', 'event', gaevent]);
					});
				}
				if ( gaevents[i]['handler'] == 'mouseover' ){
					$(gaevents[i]['selector']).mouseover(function(){
						ga(['send', 'event', gaevent]);
					});
				}
			}
		}
		for( var i = 0; i < gaevents.length; i++ ) {
			bindEventTrigger(i);
		}
		
	}
});
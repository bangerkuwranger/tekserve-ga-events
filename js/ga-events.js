jQuery(document).ready(function($){
	if (typeof ga == 'function' && gaevents.length > 0) {
		function bindEventTrigger(eventNo) {
			if($(gaevents[i]['selector']).length) {
				var label = '';
				if (gaevents[i]['label']) {
					label = gaevents[i]['label'];
				}
				var value = '';
				if (gaevents[i]['value'] != 0) {
					value = parseInt(gaevents[i]['value']);
				}
				var gamethod = 'send';
				var gatype = 'event';
				var gacategory = gaevents[i]['category'];
				var gaaction = gaevents[i]['action'];
				
				if ( gaevents[i]['handler'] == 'click' ){
					$(gaevents[i]['selector']).click(function(){
						if(value != '') {
							ga(gamethod, gatype, gacategory, gaaction, label, value);
						}
						else if(label != '') {
							ga(gamethod, gatype, gacategory, gaaction, label);
						}
						else {
							ga(gamethod, gatype, gacategory, gaaction);
						}
					});
				}
				if ( gaevents[i]['handler'] == 'submit' ){
					$(gaevents[i]['selector']).submit(function(){
						if(value != '') {
							ga(gamethod, gatype, gacategory, gaaction, label, value);
						}
						else if(label != '') {
							ga(gamethod, gatype, gacategory, gaaction, label);
						}
						else {
							ga(gamethod, gatype, gacategory, gaaction);
						}
					});
				}
				if ( gaevents[i]['handler'] == 'mouseover' ){
					$(gaevents[i]['selector']).mouseover(function(){
						if(value != '') {
							ga(gamethod, gatype, gacategory, gaaction, label, value);
						}
						else if(label != '') {
							ga(gamethod, gatype, gacategory, gaaction, label);
						}
						else {
							ga(gamethod, gatype, gacategory, gaaction);
						}
					});
				}
			}
		}
		for( var i = 0; i < gaevents.length; i++ ) {
			bindEventTrigger(i);
		}
		
	}
});
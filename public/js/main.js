(function($) {

	"use strict";

	$('#id_0,#id_1').datetimepicker({
    allowInputToggle: true,
    showClose: true,
    showClear: true,
    showTodayButton: true,
	
	locale:"fr",
    format: "DD/MM/YYYY",
    icons: {
		  time:'fas fa-clock',

		  date:'fa fa-calendar-o',

		  up:'fa fa-chevron-up',

		  down:'fa fa-chevron-down',

		  previous:'fa fa-chevron-left',

		  next:'fa fa-chevron-right',

		  today:'fa fa-chevron-up',

		  clear:'fa fa-trash',

		  close:'fas fa-window-close'
		},
	// seconds: false,
	});

})(jQuery);

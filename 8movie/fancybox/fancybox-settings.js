jQuery(document).ready(function() {

	$(".gateway").fancybox({
		ajax : {
		    type	: "POST",
		    data	: 'mydata=test'
		},
		'padding' : 0,
		'overlayOpacity' : 0.0,
		'overlayColor' : '#000',
		'modal' : true
		
	});

});
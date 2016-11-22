$(document).ready(function(){
	$('.carousel').carousel({indicators:"true"});

	$('#0').hover(
		function() {
			$('#pkdx0').show();
			$('#pkdx1').hide();
			$('#pkdx2').hide();
		},
		function(){
		}
	);
	$('#1').hover(
		function() {
			$('#pkdx1').show();
			$('#pkdx0').hide();
			$('#pkdx2').hide();
		}, 
		function(){
		}
	);
	$('#2').hover(
		function() {
			$('#pkdx2').show();
			$('#pkdx0').hide();
			$('#pkdx1').hide();	
		}, 
		function(){
		}
	);
});
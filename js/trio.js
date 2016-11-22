$(document).ready(function(){
	$('.carousel').carousel({indicators:"true"});

	$('#pkmn0').hover(
		function() {
			$('#pkdx0').show();
			$('#pkdx1').hide();
			$('#pkdx2').hide();
		},
		function(){
		}
	);
	$('#pkmn1').hover(
		function() {
			$('#pkdx1').show();
			$('#pkdx0').hide();
			$('#pkdx2').hide();
		}, 
		function(){
		}
	);
	$('#pkmn2').hover(
		function() {
			$('#pkdx2').show();
			$('#pkdx0').hide();
			$('#pkdx1').hide();	
		}, 
		function(){
		}
	);
	$('#pkbl0').click(
		function() {
			$('#pkdx0').show();
			$('#pkdx1').hide();
			$('#pkdx2').hide();
			$('#pkbl0').hide();
			$('#pkmn0').show();
		}
	);
	$('#pkbl1').click(
		function() {
			$('#pkdx1').show();
			$('#pkdx0').hide();
			$('#pkdx2').hide();
			$('#pkbl1').hide();
			$('#pkmn1').show();
		}
	);
	$('#pkbl2').click(
		function() {
			$('#pkdx2').show();
			$('#pkdx0').hide();
			$('#pkdx1').hide();
			$('#pkbl2').hide();
			$('#pkmn2').show();
		}
	);
});
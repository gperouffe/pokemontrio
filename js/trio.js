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
			$('#pkdx1').fadeOut();
			$('#pkdx2').fadeOut();
			$('#pkbl0').fadeOut("slow", function(){		
				$('#pkmn0').show();
				$('#pkdx0').fadeIn("slow");		
			});
		}
	);
	$('#pkbl1').click(
		function() {
			$('#pkdx0').fadeOut();
			$('#pkdx2').fadeOut();
			$('#pkbl1').fadeOut("slow", function(){		
				$('#pkmn1').show();		
				$('#pkdx1').fadeIn("slow");		
			});
		}
	);
	$('#pkbl2').click(
		function() {
			$('#pkdx0').fadeOut();
			$('#pkdx1').fadeOut();
			$('#pkbl2').fadeOut("slow", function(){		
				$('#pkmn2').show();	
				$('#pkdx2').fadeIn("slow");		
			});
		}
	);
});
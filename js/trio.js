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
	
	$('#pkbl0').one('click',function(){
		$('.pkdx:visible').fadeOut("slow");
		$('#pkbl0').fadeOut("slow", function(){		
			$('.pkmn:visible').removeClass('breath');
			$('#pkmn0').show();
			$('#pkdx0').fadeIn("slow");		
		});
	});
	$('#pkbl1').one('click',function(){
		$('.pkdx:visible').fadeOut("slow");
		$('#pkbl1').fadeOut("slow", function(){		
			$('.pkmn:visible').removeClass('breath');
			$('#pkmn1').show();		
			$('#pkdx1').fadeIn("slow");		
		});
	});
	$('#pkbl2').one('click',function(){
		$('.pkdx:visible').fadeOut("slow");
		$('#pkbl2').fadeOut("slow", function(){		
			$('.pkmn:visible').removeClass('breath');
			$('#pkmn2').show();	
			$('#pkdx2').fadeIn("slow");		
		});
	});
});
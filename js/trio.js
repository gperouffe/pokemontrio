$(document).ready(function(){
	
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
		$('.choice:not(:disabled)').attr("disabled", true);
		$('#choice0').attr("disabled", false);
		$('.pkdx:visible').fadeOut("slow");
		$('#pkbl0').fadeOut("slow", function(){		
			$('.pkmn:visible').removeClass('breath');
			$('#pkmn0').show();
			$('#pkdx0').fadeIn("slow");		
		});
	});
	$('#pkbl1').one('click',function(){
		$('.choice:not(:disabled)').attr("disabled", true);
		$('#choice1').attr("disabled", false);
		$('.pkdx:visible').fadeOut("slow");
		$('#pkbl1').fadeOut("slow", function(){		
			$('.pkmn:visible').removeClass('breath');
			$('#pkmn1').show();		
			$('#pkdx1').fadeIn("slow");		
		});
	});
	$('#pkbl2').one('click',function(){
		$('.choice:not(:disabled)').attr("disabled", true);
		$('#choice2').attr("disabled", false);
		$('.pkdx:visible').fadeOut("slow");
		$('#pkbl2').fadeOut("slow", function(){		
			$('.pkmn:visible').removeClass('breath');
			$('#pkmn2').show();	
			$('#pkdx2').fadeIn("slow");		
		});
	});
	
	$('#choice0').one('click',function(){
		$('.choice').attr("disabled",true);
		$('#pkmnBlock0').before($("#pkmnBlock1"));
		$('.pkbl:visible').fadeOut('slow',function(){
			$('.pkmn:not(:visible)').removeClass("breath");
			$('.pkmn').show();
		});
	});
	$('#choice1').one('click',function(){
		$('.choice').attr("disabled",true);
		$('.pkbl:visible').fadeOut('slow',function(){
			$('.pkmn:not(:visible)').removeClass("breath");
			$('.pkmn').show();
		});
	});
	$('#choice2').one('click',function(){
		$('.choice').attr("disabled",true);
		$('#pkmnBlock1').before($("#pkmnBlock2"));
		$('.pkbl:visible').fadeOut('slow',function(){
			$('.pkmn:not(:visible)').removeClass("breath");
			$('.pkmn').show();
		});
	});
});
/*---------------------------------------------------------
*	Filename : trio.js
*	Author : Guillaume Perouffe
*	Last Modified : Nov 23, 2016
*	
*	Description : 
*	Javascript for Pokémon Trio Webpage (jQuery)
*		
*---------------------------------------------------------*/

$(document).ready(function(){
	
	//Show the corresponding Pokédex entry when hovering a Pokémon
	$('#pkmn0').hover(function() {
			$('.pkdx').hide();
			$('#pkdx0').show();
		},
		function(){}
	);
	$('#pkmn1').hover(function() {
			$('.pkdx').hide();
			$('#pkdx1').show();
		}, 
		function(){}
	);
	$('#pkmn2').hover(function() {
			$('.pkdx').hide();
			$('#pkdx2').show();
		}, 
		function(){}
	);
	
	//Reveal a Pokémon when clicking on its Pokéball, prevent
	//already revealed Pokémons to be chosen
	$('#pkbl0').one('click',function(){
		$('.choice:not(:disabled)').attr("disabled", true);  //Disable enabled choice buttons
		$('#choice0').attr("disabled", false);               //Enable the corresponding choice button
		$('.pkdx:visible').fadeOut("slow");                  //Fade Out the visible Pokédex
		$('#pkbl0').fadeOut("slow", function(){              //Fade Out the clicked Pokéball
			$('.pkmn:visible').removeClass('breath');        //Disable breathing on visible Pokémon
			$('#pkmn0').show();                              //Show corresponding Pokémon
			$('#pkdx0').fadeIn("slow");		                 //Fade In the corresponding Pokédex entry
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
	
	//When a Pokémon is chosen, highlight it, open any remaining Pokéball
	//prevent the user to choose again, and offer him to replay the application.
	$('#choice0').one('click',function(){
		$('.choice').hide();                                       //Hide all choice buttons
		$('#pkmnBlock0').css({"transition":"all 1s",               //Move chosen Pokémon up, the other down
							  "transform":"translate(0,-17px)"});
		$('#pkmnBlock1').css({"transition":"all 1s",
							  "transform":"translate(0,17px)"});
		$('#pkmnBlock2').css({"transition":"all 1s",
							  "transform":"translate(0,17px)"});
		$('#pkmnBlock0').before($("#pkmnBlock1"));                 //Put the chosen Pokémon at the middle
		$('.pkbl:visible').fadeOut('slow',function(){              //Open the remaining Pokéballs
			$('.pkmn:not(:visible)').removeClass("breath");
			$('.pkmn').show();
		});
		$('#replay').show();                                       //Show replay button
	});
	$('#choice1').one('click',function(){
		$('.choice').hide();
		$('#pkmnBlock0').css({"transition":"all 1s",
							  "transform":"translate(0,17px)"});
		$('#pkmnBlock1').css({"transition":"all 1s",
							  "transform":"translate(0,-17px)"});
		$('#pkmnBlock2').css({"transition":"all 1s",
							  "transform":"translate(0,17px)"});
		$('.pkbl:visible').fadeOut('slow',function(){
			$('.pkmn:not(:visible)').removeClass("breath");
			$('.pkmn').show();
		});
		$('#replay').show();
	});
	$('#choice2').one('click',function(){
		$('.choice').hide();
		$('#pkmnBlock0').css({"transition":"all 1s",
							  "transform":"translate(0,17px)"});
		$('#pkmnBlock1').css({"transition":"all 1s",
							  "transform":"translate(0,17px)"});
		$('#pkmnBlock2').css({"transition":"all 1s",
							  "transform":"translate(0,-17px)"});
		$('#pkmnBlock2').after($("#pkmnBlock1"));
		$('.pkbl:visible').fadeOut('slow',function(){
			$('.pkmn:not(:visible)').removeClass("breath");
			$('.pkmn').show();
		});
		$('#replay').show();
	});
});
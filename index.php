<!---------------------------------------------------------
*	Filename : index.php
*	Author : Guillaume Perouffe
*	Last Modified : Nov 21, 2016
*	
*	Description : 
*	A small web app that displays 3 pokemon chosen at 
*	random from PokeAPI.co
*	Clicking on a Pokémon displays its informations 
*	underneath.
*	
*---------------------------------------------------------->

<?php
	
	//An object to manage API caching in order to improve response time
	class Cache
	{
		private $delay;
		
		function Cache($d){$this->delay = $d;}
		
		function getResource($url)
		{
			$cacheFile = "tmp/res/".md5($url).".json";
			if(file_exists($cacheFile) && filesize($cacheFile)!=0 && (time() - filemtime($cacheFile)) < $this->delay)
			{
				return file_get_contents($cacheFile);
			}
			else //We need to fetch a newer copy of the resource
			{
				$jsonStr = file_get_contents($url);
				file_put_contents($cacheFile, $jsonStr);
				return $jsonStr;
			}
		}
		
		function getImg($url)
		{
			$cacheFile = "tmp/img/".md5($url).".png";
			if( !file_exists($cacheFile) || filesize($cacheFile)==0 || (time() - filemtime($cacheFile)) >= $this->delay)
				//We need to fetch a newer copy of the image
			{
				file_put_contents($cacheFile, file_get_contents($url));
				chmod($cacheFile, 644);
			}
			return $cacheFile;
		}
	}
	
	//Finds in $array the string corresponding to $property, in language $lang
	function getLocale($array, $lang, $property)
	{
		foreach ($array as $value)
		{
			if($value["language"]["name"] == $lang)
				return $value[$property];
		}
	}
	
	//Cache with 30 days persistence
	//The v2 of the API does not include "modified" timestamps
	$cache = new Cache(30*24*3600);
	
	//Setting UI language
	$lang = 'fr';
	if(isset($_COOKIE["lang"]))
	{
		$lang = $_COOKIE["lang"];
	}
	if(isset($_GET["lang"]))
	{
		setcookie("lang",$_GET["lang"]);
		$lang = $_GET["lang"];
	}
	
	//Data to gather (only here as a reminder)
	$id=[];
	$name;
	$height;
	$weight;
	$spriteFile;
	$text;
	$genus;
	$color;
	$types;
	$habitat;
	$canEvolve;
	$evoToName;
	$evoToSprite;
	
	
	//Selecting 3 different IDs at random among the 151 first Pokémons (first Pokédex version)
	for($i=0; $i<3; $i++)
	{	
		$r = rand(1, 151);
		while(in_array($r, $id))
			$r = rand(1, 151);
		$id[$i]=$r;
	}
	
	//Getting data to fill-in the Pokédex pages of the 3 Pokémons
	for($i=0; $i<3; $i++)
	{
		//Pokemon
		$jsonStr = $cache->getResource("http://pokeapi.co/api/v2/pokemon/".$id[$i]);
		$pokemonData = json_decode($jsonStr, true);
		
		//Pokemon Species
		$jsonStr = $cache->getResource("http://pokeapi.co/api/v2/pokemon-species/".$id[$i]);
		$speciesData = json_decode($jsonStr, true);
		
		//Pokemon Habitat
		$habitatUrl = $speciesData["habitat"]["url"];
		$jsonStr = $cache->getResource($habitatUrl);
		$habitatData = json_decode($jsonStr, true);
		
		//Pokemon Evolution
		$evoChainUrl = $speciesData["evolution_chain"]["url"];
		$jsonStr = $cache->getResource($evoChainUrl);
		$evoChainData = json_decode($jsonStr, true);
		
		//Pokemon Types
		unset($typeList);
		foreach($pokemonData["types"] as $value)
		{
			$jsonStr = $cache->getResource($value["type"]["url"]);
			$typeData = json_decode($jsonStr, true);
			$typeList[$value["slot"]-1] = getLocale($typeData["names"], $lang, "name");
		}
		if(count($typeList)==1) $typeList[1]="N/A";
		
		//Common data
		$height[$i] = $pokemonData["height"];
		$weight[$i] = $pokemonData["weight"];
		$color[$i] = $speciesData["color"]["name"];
		if($color[$i] == 'black' || $color[$i] == 'white' || $color[$i] == 'gray') $color[$i]='grey';
		$spriteFile[$i] = $cache->getImg($pokemonData["sprites"]["front_default"]);
		$commonName = $speciesData["name"];
		//Local data
		$name[$i] = getLocale($speciesData["names"], $lang, "name");
		$genus[$i] = getLocale($speciesData["genera"], $lang, "genus");
		$habitat[$i] = getLocale($habitatData["names"], $lang, "name");
		$text[$i] = getLocale($speciesData["flavor_text_entries"], $lang, "flavor_text");
		$types[$i] = $typeList;
		
		//Going through the list of evolutions until we meet our pokemon;
		while($evoChainData["chain"]["species"]["name"] !=$commonName && !empty($evoChainData["chain"]["evolves_to"]))
		{
			$evoChainData["chain"] = $evoChainData["chain"]["evolves_to"][0];
		}
		$canEvolve[$i] = !empty($evoChainData["chain"]["evolves_to"]);
		if($canEvolve[$i])
		{
			$evoChainData["chain"] = $evoChainData["chain"]["evolves_to"][0];
			
			//Getting the species of the next evolution
			$jsonStr = $cache->getResource($evoChainData["chain"]["species"]["url"]);
			$evoSpeciesData = json_decode($jsonStr, true);
			//Getting the Pokemon of the next evolution
			$jsonStr = $cache->getResource($evoSpeciesData["varieties"][0]["pokemon"]["url"]);
			$evoData = json_decode($jsonStr, true);
			
			$evoToName[$i] = getLocale($evoSpeciesData["names"], $lang, "name");
			$evoToSprite[$i] = $cache->getImg($evoData["sprites"]["front_default"]);
		}
	}
	
	//Getting the poke-ball sprite
	$jsonStr = $cache->getResource("https://pokeapi.co/api/v2/item/poke-ball");
	$pokeballData = json_decode($jsonStr, true);
	$ballSprite = $cache->getImg($pokeballData["sprites"]["default"]);
	
?>
 
<!DOCTYPE html>
<html>
	<head>
		<link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
		<link type="text/css" rel="stylesheet" href="css/trio.css"  media="screen,projection"/>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		
		<meta http-equiv="content-type" content="text/html" charset="utf-8">
		<title>Pokémon Trio</title>
		<link rel="icon" type="image/png" href="<?php echo $ballSprite; ?>">
	</head>

	<body>
		<main>
			<div class="container">
				<section class="row valign-wrapper">
					<h1 class="col s11">Pokémon Trio</h1>		
					<a class='dropdown-button btn col s2 m1 grey' href='#' data-activates='dropdown1'><?php echo $lang; ?></a>
					<ul id='dropdown1' class='dropdown-content'>
						<li><a href="?lang=fr">fr</a></li>
						<li><a href="?lang=en">en</a></li>
						<li><a href="?lang=de">de</a></li>
						<li><a href="?lang=es">es</a></li>
					</ul>
				</section>
				<div class="divider"></div>
				<p class="flow-text"> <?php echo json_decode(file_get_contents("locale.json"), true)[$lang]; ?></p>  

				<div class="center-align">
				
				<?php
					for($i=0; $i<3; $i++)
					{
						echo "<span class='pkmnBlock'>";
							echo '<a id="pkbl'.$i.'" href="#null"><img class="pkbl" src="'.$ballSprite.'"></a>';
							echo '<a id="pkmn'.$i.'" href="#null'.$i.'" style="display:none"><img class="pkmn" src="'.$spriteFile[$i].'"></a>';
						echo "</span>";
					}
				?>
				
				</div>
				<div class="row">
				
				<?php
					for($i=0; $i<3; $i++)
					{
						echo 
						'<div class="card horizontal col s12 m6 offset-m3 '.$color[$i].' lighten-5" id="pkdx'.$i.'" style="display:none">
							<div class="card-image center-align">
								<p><img src="'.$spriteFile[$i].'"></p>';
						if($canEvolve[$i])
						{
							echo
								'<i class="material-icons '.$color[$i].'-text" style="font-size:3em">arrow_downward</i>
								<p><img src="'.$evoToSprite[$i].'">
								'.$evoToName[$i].'</p>';
						}
						echo
							'</div>
							<div class="card-stacked">
								<div class="card-content">
									<h4>'.$id[$i].': '.$name[$i].'</h4>
									<h5>'.$genus[$i].'</h5><br>
									<p>';
						if($types[$i][1] != "N/A") echo $types[$i][0]."/".$types[$i][1]; //If there are two types, separate them with a /
						else echo $types[$i][0];
						echo						
								'	('.$habitat[$i].')</p>
									<p>'.($height[$i]/10).'m, '.($weight[$i]/10).' kg</p>
									<blockquote style="border-left-color:'.$color[$i].'">'.$text[$i].'</blockquote>
									<a class="btn '.$color[$i].' darken-2" href="index.php">OK</a>
								</div>
							</div>
						</div>';
					}	
				?>
				
				</div>
			</div>
		</main>
		
		<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
		<script type="text/javascript" src="js/materialize.min.js"></script>
		<script type="text/javascript" src="js/trio.js"></script>
		
	</body>
</html>
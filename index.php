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
	
	$cache = new Cache(30*24*3600); //Cache with 30 days persistence
	
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
	
	//Data to gather
	$id;
	$name;
	$height;
	$weight;
	$spriteFile;
	$text;
	$genus;
	$color;
	$types;
	$habitat;
	
	
	//Selecting 3 IDs at random among the 151 first Pokémons (first Pokédex version)
	for($i=0; $i<3; $i++)
		$id[$i]=rand(1,151);
	
	//Getting data to fill in the Pokédex cards
	for($i=0; $i<3; $i++)
	{
		$jsonStr = $cache->getResource("http://pokeapi.co/api/v2/pokemon/".$id[$i]);
		$pokemonData = json_decode($jsonStr, true);
		
		$jsonStr = $cache->getResource("http://pokeapi.co/api/v2/pokemon-species/".$id[$i]);
		$speciesData = json_decode($jsonStr, true);
		
		$habitatUrl = $speciesData["habitat"]["url"];
		$jsonStr = $cache->getResource($habitatUrl);
		$habitatData = json_decode($jsonStr, true);
		
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
		if($color[$i] == 'black') $color[$i]='grey';
		$spriteFile[$i] = $cache->getImg($pokemonData["sprites"]["front_default"]);
		//Local data
		$name[$i] = getLocale($speciesData["names"], $lang, "name");
		$genus[$i] = getLocale($speciesData["genera"], $lang, "genus");
		$habitat[$i] = getLocale($habitatData["names"], $lang, "name");
		$text[$i] = getLocale($speciesData["flavor_text_entries"], $lang, "flavor_text");
		$types[$i] = $typeList;
		
	}
	
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
		<link rel="icon" href="img/poele.ico" type="image/x-icon">
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
						<li><a href="?lang=es">es</a></li>
					</ul>
				</section>
				<div class="divider"></div>
				<p class="flow-text"> <?php echo json_decode(file_get_contents("locale.json"), true)[$lang]; ?></p>  

				<div class="center-align">
				<?php
					for($i=0; $i<3; $i++)
						echo '<a id='.$i.' href="#pkdx'.$i.'"><img class="poke" src="'.$spriteFile[$i].'"></a>';
				?>
				</div>
				<div class="row">
				<?php
					for($i=0; $i<3; $i++)
					{
						echo 
						'<div class="card horizontal col s12 m6 offset-m3 '.$color[$i].' lighten-2" id="pkdx'.$i.'" style="display:none">
							<div class="card-image">
								<img src="'.$spriteFile[$i].'">
							</div>
							<div class="card-stacked">
								<div class="card-content">
									<h4>'.$id[$i].': '.$name[$i].'</h4>
									<h5>'.$genus[$i].'</h5><br>
									<p>';
						if($types[$i][1] != "N/A") echo $types[$i][0]."/".$types[$i][1];
						else echo $types[$i][0];
						echo						
								'	('.$habitat[$i].')</p>
									<p>'.($height[$i]/10).'m, '.($weight[$i]/10).' kg</p>
									<blockquote style="border-left-color:'.$color[$i].'">'.$text[$i].'</blockquote>
									<a class="btn '.$color[$i].'" href="index.php">OK</a>
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
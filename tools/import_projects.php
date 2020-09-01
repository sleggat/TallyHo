<?php

// import Projects (and Clients) from Kimai CSV
// need a way to generate the rates for clients and projects too

$array = array_map("str_getcsv", preg_split('/\r*\n+|\r+/', "16,A5 Brochure,MacLymph,,,,,#d2d6de,TRUE,
22,Australia vs Taiwan map,CampingTaiwan,,,,,#d2d6de,TRUE,
27,Bleeders - Darkness Falls MV,ElevenFiftySeven,,,,,#d2d6de,TRUE,
15,Bleeders Album / CD artwork,ElevenFiftySeven,,,,,#d2d6de,TRUE,
17,Business Cards,MacLymph,,,,,#d2d6de,TRUE,
8,Cap Design,Magrette,,,,,#d2d6de,TRUE,
20,Email Newsletters,JWHenley,,,,,#d2d6de,TRUE,
29,Glossy Six60 brochure,Notable,,,,,#d2d6de,TRUE,
28,GMT Instruction PDF,Magrette,,,,,#d2d6de,TRUE,
2,Labels,Mighty Mighty,,,,,#d2d6de,TRUE,
30,Loading Docs prop,Notable,,,,,#d2d6de,TRUE,
18,Logo,MacLymph,,,,,#d2d6de,TRUE,
26,Logo,The BrickShop,,,,,#d2d6de,TRUE,
11,NZSAS,Magrette,,,,,#d2d6de,TRUE,
19,Posters,ElevenFiftySeven,,,,,#d2d6de,TRUE,
25,Print,The BrickShop,,,,,#d2d6de,TRUE,
5,Print Ads,Magrette,,,,,#d2d6de,TRUE,
6,Social Media / Promo,MacLymph,,,,,#d2d6de,TRUE,
7,Social Media / Promo,Magrette,,,,,#d2d6de,TRUE,
12,South Pacific Marine Park,Magrette,,,,,#d2d6de,TRUE,
4,Website,Magrette,,,,,#d2d6de,TRUE,
13,Website,JWHenley,,,,,#d2d6de,TRUE,
21,Website,GoInside,,,,,#d2d6de,TRUE,
14,Website,SolaRosa,,,,,#d2d6de,TRUE,
23,Website,PetBuddy,,,,,#d2d6de,TRUE,
24,Website,The BrickShop,,,,,#d2d6de,TRUE,
9,Website,Black,,,,,#d2d6de,TRUE,
10,Website,CampingTaiwan,,,,,#d2d6de,TRUE,
3,Website,MacLymph,,,,,#d2d6de,TRUE,"));

foreach ($array as $part) {
	$client = urlencode(trim($part[2]));
	$project = urlencode(trim($part[1]));
	$folder = "./".$client."/".$project;
	echo "<br>Making ".$folder;
	if (!mkdir($folder, 0777, true)) {
	    die('Failed to create folders...');
	}

}
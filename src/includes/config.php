<?php

	if ( ! defined( 'SKDBM' ) ) { die( 'Nope' ); }

	$sql = array(
		'username' => '',
		'password' => '',
		'database' => '',
		'hostname' => ''
	);
	$menus = array(
		'skills' => array(
			'agility',
			'construction',
			'cooking',
			'crafting',
            'divination',
			'farming',
            'fighting',
			'firemaking',
			'fishing',
			'fletching',
			'herblore',
			'hunter',
			'invention',
			'magic',
            'magic_lunar',
			'mining',
			'prayer',
			'runecrafting',
			'slayer',
			'smithing',
			'summoning',
			'thieving',
			'woodcutting'
		),
        'atlas' => array(
            'search',
            'transport'
        )
	);

    //  Export filenames
    $export = array(
        'agility'      => 'Agility',
        'construction' => 'Construction',
        'cooking'      => 'Cooking',
        'crafting'     => 'Crafting',
        'divination'   => 'za-15-Divination',
        'farming'      => 'za-10-Farming',
        'firemaking'   => 'Firemaking',
        'fishing'      => 'Fishing',
        'fletching'    => 'Fletching',
        'herblore'     => 'Herblore',
        'hunter'       => 'Hunter',
	    'invention'    => 'Invention',
        'magic'        => 'Magic',
        'magic_lunar'  => 'z-9-MagicLunar',
        'mining'       => 'Mining',
        'prayer'       => 'Prayer',
        'runecrafting' => 'Runecrafting',
        'slayer'       => 'z0-Slayer',
        'smithing'     => 'Smithing',
        'summoning'    => 'z6-Summoning',
        'thieving'     => 'Thieving',
        'woodcutting'  => 'Woodcutting',
        'quests'       => 'z5-QuestData',
        'tasks'        => 'za-11-AchievementDiaryData',
        'fairyrings'   => 'z3-FairRings',
        'fighting'     => 'z1-Fighting',
        'search'       => 'z-7-AtlasSearch',
        'location'     => 'z-8-AtlasTransport'
    );

    //  We need a second set of skills arrays for use with other areas that require combat and the likes.
    $skill_list = array(
        'quest' => array (
            "ag" => "Agility",
            "at" => "Attack",
            "aa" => "Combat",
            "hp" => "Constitution",
            "co" => "Construction",
            "ck" => "Cooking",
            "cr" => "Crafting",
            "de" => "Defence",
            'di' => 'Divination',
            "du" => "Dungeoneering",
            "fr" => "Farming",
            "fm" => "Firemaking",
            "fi" => "Fishing",
            "fl" => "Fletching",
            "hl" => "Herblore",
            "hu" => "Hunter",
            "ma" => "Magic",
            "mi" => "Mining",
            "pr" => "Prayer",
            "rn" => "Ranged",
            "rc" => "Runecrafting",
            "sl" => "Slayer",
            "sm" => "Smithing",
            "st" => "Strength",
            "su" => "Summoning",
            "th" => "Thieving",
            "wc" => "Woodcutting",
	        "in" => "Invention"
        ),
        'diary' => array (
            'agi' => 'Agility',
            'att' => 'Attack',
            'com' => 'Combat',
            'hit' => 'Constitution',
            'con' => 'Construction',
            'coo' => 'Cooking',
            'cra' => 'Crafting',
            'def' => 'Defence',
            'div' => 'Divination',
            'dun' => 'Dungeoneering',
            'far' => 'Farming',
            'fir' => 'Firemaking',
            'fis' => 'Fishing',
            'fle' => 'Fletching',
            'her' => 'Herblore',
            'hun' => 'Hunter',
            'mag' => 'Magic',
            'min' => 'Mining',
            'pra' => 'Prayer',
            'ran' => 'Ranged',
            'run' => 'Runecrafting',
            'sla' => 'Slayer',
            'smi' => 'Smithing',
            'str' => 'Strength',
            'sum' => 'Summoning',
            'thi' => 'Thieving',
            'woo' => 'Woodcutting',
	        'inv' => 'Invention'
        )
    );

	$db = new PDO('mysql:host=' . $sql['hostname'] . ';dbname=' . $sql['database'], $sql['username'], $sql['password']);

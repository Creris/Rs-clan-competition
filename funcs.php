<?php

$xpArray = array(
	0,
	83,
	174,
	276,
	388,
	512,
	650,
	801,
	969,
	1154,
	1358,
	1584,
	1833,
	2107,
	2411,
	2746,
	3115,
	3523,
	3973,
	4470,
	5018,
	5624,
	6291,
	7842,
	8740,
	9730,
	7028,
	10824,
	12031,
	13363,
	14833,
	16456,
	18247,
	20224,
	22406,
	24815,
	27473,
	30408,
	33648,
	37224,
	41171,
	45529,
	50339,
	55649,
	61512,
	67983,
	75124,
	83014,
	91721,
	101333,
	111945,
	123660,
	136594,
	150872,
	166636,
	184040,
	203254,
	224466,
	247886,
	273742,
	302288,
	333804,
	368599,
	407015,
	449428,
	496254,
	547953,
	605032,
	668051,
	747627,
	814445,
	899257,
	992895,
	1096278,
	1210421,
	1336443,
	1475581,
	1629200,
	1798808,
	1986068,
	2192818,
	2421087,
	2673114,
	2951373,
	3258594,
	3597792,
	3972294,
	4385776,
	4842295,
	5346332,
	5902831,
	6517253,
	7195629,
	7944614,
	8771588,
	9684577,
	10692629,
	11805606,
	13034431,
	14391160,
	15889109,
	17542976,
	19368992,
	21385073,
	23611006,
	26068632,
	28782069,
	31777943,
	35085654,
	38737661,
	42769801,
	47221641,
	52136869,
	57563718,
	63555443,
	70170840,
	77474828,
	85539082,
	94442737,
	104273167,
	115126838,
	127110260,
	140341028,
	154948977,
	171077457,
	188884740,
	200000000
);

$inventionXpArray = array(
	0,
	830,
	1861,
	2902,
	3980,
	5126,
	6390,
	7787,
	9400,
	11275,
	13605,
	16372,
	19656,
	23546,
	28138,
	33520,
	39809,
	47109,
	55535,
	64802,
	77190,
	90811,
	106221,
	123573,
	143025,
	164742,
	188893,
	215651,
	245196,
	277713,
	316311,
	358547,
	404634,
	454796,
	509259,
	568254,
	632019,
	700797,
	774834,
	854383,
	946227,
	1044569,
	1149696,
	1261903,
	1381488,
	1508756,
	1644015,
	1787581,
	1939773,
	2100917,
	2283490,
	2476369,
	2679907,
	2894505,
	3120508,
	3358307,
	3608290,
	3870846,
	4146374,
	4435275,
	4758122,
	5096111,
	5449685,
	5819299,
	6205407,
	6608473,
	7028964,
	7467354,
	7924122,
	8399751,
	8925664,
	9472665,
	10041285,
	10632061,
	11245538,
	11882262,
	12542789,
	13227679,
	13937496,
	14672812,
	15478994,
	16313404,
	17176661,
	18069395,
	18992239,
	19945833,
	20930821,
	21947856,
	22997593,
	24080695,
	25259906,
	26475754,
	27728955,
	29020233,
	30350318,
	31719944,
	33129852,
	34580790,
	36073511,
	37608773,
	39270442,
	40978509,
	42733789,
	44537107,
	46389292,
	48291180,
	50243611,
	52247435,
	54303504,
	56412678,
	58575823,
	60793812,
	63067521,
	65397835,
	67785643,
	70231841,
	72737330,
	75303019,
	77929820,
	80618654,
	200000000,
);

$skillList = array(
	"Total_Level", "Attack", "Defence",
	"Strength", "Constitution", "Ranged",
	"Prayer", "Magic", "Cooking",
	"Woodcutting", "Fletching", "Fishing",
	"Firemaking", "Crafting", "Smithing",
	"Mining", "Herblore", "Agility",
	"Thieving", "Slayer", "Farming",
	"Runecrafting", "Hunter", "Construction",
	"Summoning", "Dungeoneering", "Divination",
	"Invention"
);

function skill2Index($skillName)
{
	global $skillList;
	for($i = 0, $j = count($skillList); $i < $j; ++$i)
	{
		if ($skillList[$i] === $skillName)
			return $i;
	}
	
	return -1;
}

function index2Skill($skill)
{
	global $skillList;
	return $skillList[$skill];
}

function getVirtualLevel($exps, $arr, $ceiling)
{
	if ($exps == 0)
		return 1;	/* 1 lvl */
	
	for($i = 0; $i < count($arr); ++$i)
	{
		if ($arr[$i] > $exps)
			return $i;
	}
	
	if ($exps == 200000000)
		return $ceiling;
	
	return -2;	/* error */
}

$ranks;
$levels;
$exps;

$readSkillsSoFar = 0;
$readPos = 0;

$skillsCount = 28;	/* includes Total Level */

function addReadLine($contents)
{
	global $readSkillsSoFar, $ranks, $levels, $exps, $skillsCount, $readPos, $xpArray;
	if ($readSkillsSoFar >= $skillsCount)
		return false;
	
	$endReadPos = $readPos;
	
	while($contents[$endReadPos] != ",")
		$endReadPos++;
	
	$ranks[] = (int)substr($contents, $readPos, $endReadPos - $readPos);
	
	$readPos = ++$endReadPos;
	
	while($contents[$endReadPos] != ",")
		$endReadPos++;
	
	$levels[] = (int)substr($contents, $readPos, $endReadPos - $readPos);
	
	$readPos = ++$endReadPos;
	
	while($contents[$endReadPos] != "\n")
		$endReadPos++;
	
	$exps[] = substr($contents, $readPos, $endReadPos - $readPos);
	
	$readPos = ++$endReadPos;
	$readSkillsSoFar++;
	
	return true;
}

function getUserAvatar($userName)
{
	return "http://services.runescape.com/m=avatar-rs/"."$userName"."/chat.png";
}

function loadStats($contents)
{
	while(addReadLine($contents))
		;
}
?>
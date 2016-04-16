<?php

/*
	returns Array of arrays of strings.
	First array is unsued, and represents the basic string "Clanmate, Clan rank, Total xp, Kills".
	Format:
		[0] = User name
		[1] = Clan rank
		[2] = xp gained in clan
		[3] = kills in pvp while in clan(useless)
	
	Order guarantees:
		The arrays are ordered by rank, but in ranks there is no order, so person with name
		starting with A and with 40m xp can appear after person with name starting with W and with 32m xp. 
*/
function loadClan($clanName)
{
	$clanInfo = file_get_contents("http://services.runescape.com/m=clan-hiscores/members_lite.ws?clanName=".$clanName);
	$clanArray = explode("\n", $clanInfo);
	for($i = 1; $i < count($clanArray); $i++)
	{
		$clanArray[$i] = explode(",", $clanArray[$i]);
	}
	
	return $clanArray;
}


function loadStats()
{
	
}

function storeStartingXP()
{
	
}

function getHistoricalCompetitions()
{
	
}

loadClan("Reclusion");

?>
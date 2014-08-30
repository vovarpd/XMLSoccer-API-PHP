XMLSoccer-API-PHP
=================

PHP wrapper for [XMLSoccer](http://XMLSoccer.com) API

Full API Documentation here: [http://xmlsoccer.wikia.com/wiki/API_Documentation](http://xmlsoccer.wikia.com/wiki/API_Documentation)

Requirements:
=================

PHP5, CURL extension.

How to use:
=================

Go to [Getting_started](http://xmlsoccer.wikia.com/wiki/Getting_started) and receive API key for access to XMLSoccer.com API.

Include the XMLSoccer.php file, and call one of available methods.
	

Methods Available
-------------------

Currently available methods:

	CheckApiKey()
	GetAllGroupsByLeagueAndSeason(array("league"=>"","seasonDateString"=>""))
	GetAllLeagues()
	GetAllTeams()
	GetAllTeamsByLeagueAndSeason(array("league"=>"","seasonDateString"=>""))
	GetCupStandingsByGroupId(array("group_id"=>""))
	GetEarliestMatchDatePerLeague(array("league"=>""))
	GetFixturesByDateInterval(array("startDateString"=>"","endDateString"=>""))
	GetFixturesByDateIntervalAndLeague(array("league"=>"","startDateString"=>"","endDateString"=>""))
	GetFixturesByDateIntervalAndTeam(array("teamId"=>"","startDateString"=>"","endDateString"=>""))
	GetFixturesByLeagueAndSeason$arr(array("league"=>"","seasonDateString"=>"1415"));
	GetHistoricMatchesByFixtureMatchID(array("Id"=>""))
	GetHistoricMatchesByID(array("Id"=>""))
	GetHistoricMatchesByLeagueAndDateInterval(array("league"=>"","startDateString"=>"","endDateString"=>""))
	GetHistoricMatchesByLeagueAndSeason(array("league"=>"","seasonDateString"=>""))
	GetHistoricMatchesByTeamAndDateInterval(array("teamId"=>"","startDateString"=>"","endDateString"=>""))
	GetHistoricMatchesByTeamsAndDateInterval(array("team1Id"=>"","team2Id"=>"","startDateString"=>"","endDateString"=>""))
	GetLeagueStandingsBySeason(array("league"=>"","seasonDateString"=>""))
	GetLiveScore()
	GetLiveScoreByLeague(array("league"=>""))
	GetNextMatchOddsByLeague(array("league"=>""))
	GetOddsByFixtureMatchId(array("fixtureMatch_Id"=>""))
	GetOddsByFixtureMatchId2(array("fixtureMatch_Id"=>""))
	GetPlayerById(array("playerId"=>""))
	GetPlayersByTeam(array("teamId"=>""))
	GetTeam(array("team_name"=>"")))
	GetTopScorersByLeagueAndSeason(array("league"=>"","seasonDateString"=>""))

Go to [http://www.xmlsoccer.com/FootballData.asmx](http://www.xmlsoccer.com/FootballData.asmx) for more info.


Examples:
==================

List players for team with id 49
--------------------------------
	include("XMLSoccer.php");
	try{
		$soccer=new XMLSoccer("your_api_key");
		$players=$soccer->GetPlayersByTeam(array("teamid"=>49));
		echo "Players List:<br>";
		foreach($players as $key=>$value){
			echo "<b>".$value->Name."</b> ".$value->Position."<br>";
		}
	}
	catch(Exception $e){
		echo "Exception: ".$e->getMessage();
	}


If you server has multiple IP available you can set any IP for request:
---------------------------------------------
	include("XMLSoccer.php");
	try{
		$soccer=new XMLSoccer("your_api_key");
		$soccer->setServerIp("ip_for_request");
		$result=$soccer->GetLeagueStandingsBySeason(array("league"=>3,"seasonDateString"=>"1314"));
		var_dump($result);
	}
	catch(Exception $e){
		echo "Exception: ".$e->getMessage();
	}

If you have try a free demo feed, use some like this:
------------------------------------------------------
	include("XMLSoccer.php");
	try{
		$soccer=new XMLSoccer("your_api_key");
		$soccer->setServiceUrl("http://www.xmlsoccer.com/FootballDataDemo.asmx");
		$result=$soccer->GetAllTeams();
		var_dump($result);
	}
	catch(Exception $e){
		echo "Exception: ".$e->getMessage();
	}


That's all!
-----------

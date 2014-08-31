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

Go to [http://www.xmlsoccer.com/FootballData.asmx](http://www.xmlsoccer.com/FootballData.asmx) for more info about methods and parameters.

[Input data formats](http://xmlsoccer.wikia.com/wiki/Input_data_formats)

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
	catch(XMLSoccerException $e){
		echo "XMLSoccerException: ".$e->getMessage();
	}


If you server has multiple IP available you can set any IP for request:
---------------------------------------------
	include("XMLSoccer.php");
	try{
		$soccer=new XMLSoccer("your_api_key");
		$soccer->setRequestIp("ip_for_request");
		$result=$soccer->GetLeagueStandingsBySeason(array("league"=>3,"seasonDateString"=>"1314"));
		var_dump($result);
	}
	catch(XMLSoccerException $e){
		echo "XMLSoccerException: ".$e->getMessage();
	}

If you have try a free demo feed, use some like this:
------------------------------------------------------
	include("XMLSoccer.php");
	try{
		$soccer=new XMLSoccer("your_api_key");
		$soccer->setServiceUrl("http://www.xmlsoccer.com/FootballDataDemo.asmx");
		$fixtures=$soccer->GetFixturesByDateIntervalAndLeague(array("league"=>3,"startDateString"=>"2014-08-01 00:00","endDateString"=>"2014-09-30 00:00"));
		var_dump($result);
	}
	catch(XMLSoccerException $e){
		echo "XMLSoccerException: ".$e->getMessage();
	}



That's all!
-----------

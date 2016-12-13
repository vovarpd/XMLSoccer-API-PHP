XMLSoccer-API-PHP
=================

PHP client for [XMLSoccer](http://XMLSoccer.com) API

Full API Documentation here: [https://xmlsoccer.zendesk.com/hc/en-us](https://xmlsoccer.zendesk.com/hc/en-us)

Requirements:
=================

PHP5.5+, CURL, SimpleXML extensions.

How to use:
=================

Go to [Getting_started](https://xmlsoccer.zendesk.com/hc/en-us/articles/202838171-Getting-started) and receive API key.

Include the XMLSoccer.php file, and call one of available methods.
	

Methods Available
-------------------

Go to [http://www.xmlsoccer.com/FootballData.asmx](http://www.xmlsoccer.com/FootballData.asmx) for more info about methods and parameters.

[Input data formats](https://xmlsoccer.zendesk.com/hc/en-us/articles/202784172-Input-data-formats)

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


If your server has multiple IP, then you can set any available IP for your request:
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

If you want try a free demo feed, use php code like this:
------------------------------------------------------
	include("XMLSoccer.php");
	try{
		$soccer=new XMLSoccer("your_api_key");
		$soccer->setServiceUrl("http://www.xmlsoccer.com/FootballDataDemo.asmx");
		$result=$soccer->GetFixturesByDateIntervalAndLeague(array("league"=>3,"startDateString"=>"2014-08-01 00:00","endDateString"=>"2014-09-30 00:00"));
		var_dump($result);
	}
	catch(XMLSoccerException $e){
		echo "XMLSoccerException: ".$e->getMessage();
	}

If your connection is very slow then you can set curl timeout using this method:
-----------------------------------------------------------
    $soccer->setTimeout(60);


That's all!
-----------

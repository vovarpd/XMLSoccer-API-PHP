Yii2-xmlsoccer-client
=================

Yii2 client for [XMLSoccer](http://XMLSoccer.com) API

Full API Documentation here: [http://xmlsoccer.wikia.com/wiki/API_Documentation](http://xmlsoccer.wikia.com/wiki/API_Documentation)

Requirements:
=================

PHP5 with CURL, SimpleXML extensions.

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
composer require --prefer-dist drsdre/yii2-xmlsoccer-client "*"
```

or add

```json
"drsdre/yii2-xmlsoccer-client": "*"
```

to the `require` section of your `composer.json` file.

Usage
-----

You need to setup the client as application component:

```php
'components' => [
    'xmlsoccerApi' => [
        'class' => 'drsdre\xmlsoccer\Client',
        'api_key' => 'xxx',
        'url' => 'http://www.xmlsoccer.com/FootballData.asmx',
    ]
    ...
]
```

or define the client directly in the code:

```php
$client = new \drsdre\xmlsoccer\Client([
    'api_key' => 'xxx',
    'url' => 'http://www.xmlsoccer.com/FootballData.asmx',
]);
```

By default client url is setup to demo API (http://www.xmlsoccer.com/FootballData.asmx). For demo access use http://www.xmlsoccer.com/FootballDataDemo.asmx as url.

How to use:
=================

Go to [Getting_started](http://xmlsoccer.wikia.com/wiki/Getting_started) and receive API key for access to XMLSoccer.com API.

Include the module, and call one of available methods.
	

Methods Available
-------------------

Go to [http://www.xmlsoccer.com/FootballData.asmx](http://www.xmlsoccer.com/FootballData.asmx) for more info about methods and parameters.

[Input data formats](http://xmlsoccer.wikia.com/wiki/Input_data_formats)

Examples:
==================

List players for team with id 49
--------------------------------
	try {
		$client = new \drsdre\xmlsoccer\Client([
            'api_key' => 'xxx',
        ]);
		$players=$soccer->GetPlayersByTeam(array("teamid"=>49));
		echo "Players List:<br>";
		foreach($players as $key=>$value){
			echo "<b>".$value->Name."</b> ".$value->Position."<br>";
		}
	}
	catch(Exception $e) {
		echo "XMLSoccer Exception: ".$e->getMessage();
	}

If your server has multiple IP's available, you can set any IP for request:
---------------------------------------------
	try {
		$client = new \drsdre\xmlsoccer\Client([
            'api_key' => 'xxx',
        ]);
		$soccer->setRequestIp("ip_for_request");
		$result=$soccer->GetLeagueStandingsBySeason(array("league"=>3,"seasonDateString"=>"1314"));
		var_dump($result);
	}
	catch(XMLSoccerException $e) {
		echo "XMLSoccerException: ".$e->getMessage();
	}

If you have a trial/free demo feed, use it like this:
------------------------------------------------------
	try{
		$client = new \drsdre\xmlsoccer\Client([
            'api_key' => 'xxx',
            'url' => 'http://www.xmlsoccer.com/FootballDataDemo.asmx',
            'request_ip' => '
        ]);
		$fixtures=$soccer->GetFixturesByDateIntervalAndLeague(array("league"=>3,"startDateString"=>"2014-08-01 00:00","endDateString"=>"2014-09-30 00:00"));
		var_dump($result);
	}
	catch(XMLSoccerException $e){
		echo "XMLSoccerException: ".$e->getMessage();
	}



That's all!
-----------

<?php
/**
* XMLSoccer.com API PHP Class
*
* @see http://xmlsoccer.wikia.com/wiki/API_Documentation
* @see http://promo.lviv.ua
* @author Volodymyr Chukh <vova@promo.lviv.ua>
* @copyright 2014 Volodymyr Chukh
* @license MIT License
*/

class XMLSoccer{
	protected $service_url="http://www.xmlsoccer.com/FootballData.asmx";
	protected $api_key,$server_ip;
	protected $availableMethods=array("CheckApiKey","GetAllGroupsByLeagueAndSeason","GetAllLeagues","GetAllTeams","GetAllTeamsByLeagueAndSeason","GetCupStandingsByGroupId","GetEarliestMatchDatePerLeague","GetFixturesByDateInterval","GetFixturesByDateIntervalAndLeague","GetFixturesByDateIntervalAndTeam","GetFixturesByLeagueAndSeason","GetHistoricMatchesByFixtureMatchID","GetHistoricMatchesByID","GetHistoricMatchesByLeagueAndDateInterval","GetHistoricMatchesByLeagueAndSeason","GetHistoricMatchesByTeamAndDateInterval","GetHistoricMatchesByTeamsAndDateInterval","GetLeagueStandingsBySeason","GetLiveScore","GetLiveScoreByLeague","GetNextMatchOddsByLeague","GetOddsByFixtureMatchId","GetOddsByFixtureMatchId2","GetPlayerById","GetPlayersByTeam","GetTeam","GetTopScorersByLeagueAndSeason");

	const TIMEOUT_GetLiveScore=25;
	const TIMEOUT_GetLiveScoreByLeague=25;
	const TIMEOUT_GetOddsByFixtureMatchID=3600;
	const TIMEOUT_GetHistoricMatchesByLeagueAndSeason=3600;
	const TIMEOUT_GetAllTeams=3600;
	const TIMEOUT_GetAllTeamsByLeagueAndSeason=3600;
	const TIMEOUT_Others=300;
	const TIMEOUT_CURL=30;

	public function __construct($api_key=""){
		$this->server_ip=gethostbyname(gethostname());
		if(empty($this->service_url)) throw new XMLSoccerException("service_url cannot be empty. Please setup");
		if(!empty($api_key)) $this->api_key=$api_key;
		if(empty($this->api_key)) throw new XMLSoccerException("api_key cannot be empty. Please setup.");
	}


	/*
		list available methods with params.
	*/
	public function __call($name,$params){
		if(!in_array($name,$this->availableMethods)) throw new XMLSoccerException("Unsupported method: ".$name);
		$data=$this->request($this->buildUrl($name,$params));
		if(false===($xml = simplexml_load_string($data))) throw new XMLSoccerException("Invalid XML");
		if(strstr($xml[0],"To avoid misuse of the service")){
			switch($name){
				case "GetLiveScore":
				case "GetLiveScoreByLeague":
				case "GetOddsByFixtureMatchID":
				case "GetHistoricMatchesByLeagueAndSeason":
				case "GetAllTeams":
				case "GetAllTeamsByLeagueAndSeason":
					throw new XMLSoccerException($xml[0],constant("self::TIMEOUT_".$name));
				default:
					throw new XMLSoccerException($xml[0],self::TIMEOUT_Others);
			}
		}
		return $xml;
	}


	protected function buildUrl($method,$params){
		$url=$this->service_url."/".$method."?apikey=".$this->api_key;
		for($i=0;$i<count($params);$i++){
			if(is_array($params[$i])){
				foreach($params[$i] as $key=>$value){
					$url.="&".strtolower($key)."=".rawurlencode($value);
				}
			}
			else{
				throw new XMLSoccerException("Arguments must be an array");
			}
		}
		return $url;
	}


	protected function request($url){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, self::TIMEOUT_CURL);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, self::TIMEOUT_CURL);
		curl_setopt($curl, CURLOPT_INTERFACE,$this->server_ip);
		$data = curl_exec($curl);
		$cerror=curl_error($curl);
		$cerrno=curl_errno($curl);
		$http_code=curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);

		if($cerrno!=0) throw new XMSoccerException($cerror,E_USER_WARNING);

		if($http_code == 200 ) return $data;
		throw new XMLSoccerException($http_code .' - '. $data . "\nURL: " . $url);
	}

	public function setServerIp($ip){
		if(empty($ip)) throw new XMLSoccerException("IP parameter cannot be empty",E_USER_WARNING);
		$this->server_ip=$ip;
	}

	public function setServiceUrl($service_url){
		if(empty($service_url)) throw new XMLSoccerException("service_url parameter cannot be empty",E_USER_WARNING);
		$this->service_url=$service_url;
	}


}
class XMLSoccerException extends Exception{

}
?>

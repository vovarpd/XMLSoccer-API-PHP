<?php
/**
* XMLSoccer.com API PHP Class
*
* @see https://xmlsoccer.zendesk.com/hc/en-us
* @see http://promo.lviv.ua
* @author Volodymyr Chukh <vova@promo.lviv.ua>
* @copyright 2014-2016 Volodymyr Chukh
* @license MIT License
*/

class XMLSoccer{
	protected $serviceUrl="http://www.xmlsoccer.com/FootballData.asmx";
	protected $apiKey=null;
	protected $requestIp=null; // optional server ip.
	protected $timeout=30; // curl timeout

    /**
     * XMLSoccer constructor.
     * @param null $apiKey
     * @throws XMLSoccerException
     */
	public function __construct($apiKey=null){
		if(empty($this->serviceUrl)) throw new XMLSoccerException("serviceUrl can't be empty.",E_USER_WARNING);
		if(!empty($apiKey)) $this->apiKey=$apiKey;
	}

    /**
     * @param $ip
     * @throws XMLSoccerException
     */
	public function setRequestIp($ip){
		if(empty($ip)) throw new XMLSoccerException("IP can't be empty",E_USER_WARNING);
		$this->requestIp=$ip;
	}

    /**
     * @param $serviceUrl
     * @throws XMLSoccerException
     */
	public function setServiceUrl($serviceUrl){
		if(empty($serviceUrl)) throw new XMLSoccerException("serviceUrl can't be empty",E_USER_WARNING);
		$this->serviceUrl=$serviceUrl;
	}

    /**
     * @param $apiKey
     * @throws XMLSoccerException
     */
	public function setApiKey($apiKey){
		if(!empty($apiKey)) $this->apiKey=$apiKey;
		else throw new XMLSoccerException("ApiKey can't be empty",E_USER_WARNING);
	}

    /**
     * @param $timeout
     */
	public function setTimeout($timeout){
		$this->timeout=(int)$timeout;
	}

    /**
     * @param $name
     * @param $params
     * @return SimpleXMLElement
     * @throws XMLSoccerException
     */
	public function __call($name,$params){
		$data=$this->request($this->buildUrl($name,$params));
		if(false===($xml = simplexml_load_string($data))) throw new XMLSoccerException("Invalid XML",E_USER_WARNING);
		if(strstr($xml[0],"To avoid misuse of the service")){
			throw new XMLSoccerException($xml[0],E_USER_WARNING);
		}
		elseif(strstr($xml[0],"We were unable to verify your API-key")){
			throw new XMLSoccerException($xml[0],E_USER_WARNING);
		}
		else{
			return $xml;
		}
	}

    /**
     * @param $method
     * @param $params
     * @return string
     * @throws XMLSoccerException
     */
	protected function buildUrl($method,$params){
		$url=$this->serviceUrl."/".$method."?apikey=".$this->apiKey;
		for($i=0;$i<count($params);$i++){
			if(is_array($params[$i])){
				foreach($params[$i] as $key=>$value){
					$url.="&".strtolower($key)."=".rawurlencode($value);
				}
			}
			else{
				throw new XMLSoccerException("Arguments must be an array",E_USER_WARNING);
			}
		}
		return $url;
	}

    /**
     * @param $url
     * @return mixed
     * @throws XMLSoccerException
     */
	protected function request($url){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $this->timeout);
		if(!empty($this->requestIp)){
			curl_setopt($curl, CURLOPT_INTERFACE,$this->requestIp);
		}
		$data = curl_exec($curl);
		$cerror=curl_error($curl);
		$cerrno=curl_errno($curl);
		$http_code=curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);

		if($cerrno!=0) throw new XMLSoccerException($cerror,E_USER_WARNING);
		if($http_code == 200 ){

			return $data;
		}
		throw new XMLSoccerException($http_code .' - '. $data . "\nURL: " . $url);
	}
}

/**
 * Class XMLSoccerException
 */
class XMLSoccerException extends Exception{

}
?>
<?php
/**
 * XMLSoccer.com API Yii2 Client Component
 *
 * @see http://xmlsoccer.wikia.com/wiki/API_Documentation
 * @see http://promo.lviv.ua
 * @author Volodymyr Chukh <vova@promo.lviv.ua>
 * @author Andre Schuurman <andre.schuurman@gmail.com>
 * @copyright 2014 Volodymyr Chukh
 * @license MIT License
 */

namespace drsdre\xmlsoccer;

use yii\base\Component;
use yii\base\InvalidConfigException;

class Client extends Component {
	public $url = "http://www.xmlsoccer.com/FootballData.asmx";

	public $api_key;

	public $request_ip;

	const TIMEOUT_GetLiveScore = 25;
	const TIMEOUT_GetLiveScoreByLeague = 25;
	const TIMEOUT_GetOddsByFixtureMatchID = 3600;
	const TIMEOUT_GetHistoricMatchesByLeagueAndSeason = 3600;
	const TIMEOUT_GetAllTeams = 3600;
	const TIMEOUT_GetAllTeamsByLeagueAndSeason = 3600;
	const TIMEOUT_Others = 300;
	const TIMEOUT_CURL = 30;

	/**
	 * Initialize component
	 *
	 * @throws Exception
	 */
	public function init() {
		if (empty($this->request_ip)) {
			$this->request_ip = gethostbyname( gethostname() );
		}
		if ( empty( $this->service_url ) ) {
			throw new Exception( "service_url cannot be empty. Please configure." );
		}
		if ( empty( $this->api_key ) ) {
			throw new Exception( "api_key cannot be empty. Please configure." );
		}
	}

	/**
	 *	list available methods with params.
	 */
	public function __call( $name, $params ) {
		$data = $this->request( $this->buildUrl( $name, $params ) );
		if ( false === ( $xml = simplexml_load_string( $data ) ) ) {
			throw new Exception( "Invalid XML" );
		}
		if ( strstr( $xml[0], "To avoid misuse of the service" ) ) {
			switch ( $name ) {
				case "GetLiveScore":
				case "GetLiveScoreByLeague":
				case "GetOddsByFixtureMatchID":
				case "GetHistoricMatchesByLeagueAndSeason":
				case "GetAllTeams":
				case "GetAllTeamsByLeagueAndSeason":
					throw new Exception( $xml[0], constant( "self::TIMEOUT_" . $name ) );
				default:
					throw new Exception( $xml[0], self::TIMEOUT_Others );
			}
		}

		return $xml;
	}

	/**
	 * Build URL for API call
	 *
	 * @param $method
	 * @param $params
	 *
	 * @return string
	 * @throws Exception
	 */
	protected function buildUrl( $method, $params ) {
		$url = $this->service_url . "/" . $method . "?apikey=" . $this->api_key;
		for ( $i = 0; $i < count( $params ); $i ++ ) {
			if ( is_array( $params[ $i ] ) ) {
				foreach ( $params[ $i ] as $key => $value ) {
					$url .= "&" . strtolower( $key ) . "=" . rawurlencode( $value );
				}
			} else {
				throw new Exception( "Arguments must be an array" );
			}
		}

		return $url;
	}

	/**
	 * Execute API request
	 *
	 * @param $url
	 *
	 * @return mixed
	 * @throws Exception
	 */
	protected function request( $url ) {
		$curl = curl_init();
		curl_setopt( $curl, CURLOPT_URL, $url );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $curl, CURLOPT_TIMEOUT, self::TIMEOUT_CURL );
		curl_setopt( $curl, CURLOPT_CONNECTTIMEOUT, self::TIMEOUT_CURL );
		curl_setopt( $curl, CURLOPT_INTERFACE, $this->request_ip );
		$data      = curl_exec( $curl );
		$cerror    = curl_error( $curl );
		$cerrno    = curl_errno( $curl );
		$http_code = curl_getinfo( $curl, CURLINFO_HTTP_CODE );
		curl_close( $curl );

		if ( $cerrno != 0 ) {
			throw new Exception( $cerror, E_USER_WARNING );
		}

		if ( $http_code == 200 ) {
			return $data;
		}
		throw new Exception( $http_code . ' - ' . $data . "\nURL: " . $url );
	}

	public function setRequestIp( $ip ) {
		if ( empty( $ip ) ) {
			throw new Exception( "IP parameter cannot be empty.", E_USER_WARNING );
		}
		$this->request_ip = $ip;
	}
}

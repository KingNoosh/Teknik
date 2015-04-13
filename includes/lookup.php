<?php

/** -

Donations welcome:
	BTC: 122MeuyZpYz4GSHNrF98e6dnQCXZfHJeGS
	LTC: LY1L6M6yG26b4sRkLv4BbkmHhPn8GR5fFm
		~ Thank you!

MIT License (MIT)

Copyright (c) 2013 http://coinwidget.com/ 
Copyright (c) 2013 http://scotty.cc/

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

*/

	header("Content-type: text/javascript");
	/*
		you should server side cache this response, especially if your site is active
	*/
try
{
	$data = isset($_GET['data'])?$_GET['data']:'';
	if (!empty($data)) {
		$data = explode("|", $data);
		$responses = array();
		if (!empty($data)) {
			foreach ($data as $key) {
				list($instance,$currency,$address) = explode('_',$key);
				switch ($currency) {
					case 'bitcoin': 
						$response = get_bitcoin($address);
						break;
					case 'litecoin': 
						$response = get_litecoin($address);
						break;
					case 'dogecoin': 
						$response = get_dogecoin($address);
						break;
				}
				$responses[$instance] = $response;
			}
		}
		echo 'var COINWIDGETCOM_DATA = '.json_encode($responses).';';
	}
}
catch (Exception $e)
{
    echo 'Caught Exception: ', $e->getMessage();
}

	function get_bitcoin($address) {
		$return = array();
		$data = get_request('https://blockchain.info/address/'.$address.'?format=json');
		if (!empty($data)) {
			$data = json_decode($data);
			$return += array(
				'count' => (int) $data->n_tx,
				'amount' => (float) $data->total_received/100000000
			);
			return $return;
		}
	}

	function get_litecoin($address) {
		$return = array();
		$data = get_request('http://explorer.litecoin.net/address/'.$address);
		if (!empty($data) 
		  && strstr($data, 'Transactions in: ') 
		  && strstr($data, 'Received: ')) {
		  	$return += array(
				'count' => (int) parse($data,'Transactions in: ','<br />'),
				'amount' => (float) parse($data,'Received: ','<br />')
			);
		  	return $return;
		}
	}

	function get_dogecoin($address) {
		$return = array();
		$recieved_data = get_request('https://chain.so/api/v2/get_address_received/DOGE/'.$address);
		$tx_data = get_request('https://chain.so/api/v2/get_tx_received/DOGE/'.$address);
		if (!empty($recieved_data) && !empty($tx_data)) {
        $recieved_data = json_decode($recieved_data);
        $tx_data = json_decode($tx_data);
		  	$return += array(
				'count' => (int) count($tx_data->data->txs),
				'amount' => (float) $recieved_data->data->confirmed_received_value
        );
		  	return $return;
		}
	}

	function get_request($url,$timeout=4) {
		if (function_exists('curl_version')) {
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
			curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13');
			$return = curl_exec($curl);
			curl_close($curl);
			return $return;
		} else {
			return @file_get_contents($url);
		}
	}

	function parse($string,$start,$stop) {
		if (!strstr($string, $start)) return;
		if (!strstr($string, $stop)) return;
		$string = substr($string, strpos($string,$start)+strlen($start));
		$string = substr($string, 0, strpos($string,$stop));
		return $string;
	}

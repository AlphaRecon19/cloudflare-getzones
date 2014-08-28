<?php
//======================================================================
//   Copyright 2014 @AlphaRecon19
//
//   Licensed under the Apache License, Version 2.0 (the "License");
//   you may not use this file except in compliance with the License.
//   You may obtain a copy of the License at
//
//       http://www.apache.org/licenses/LICENSE-2.0
//
//   Unless required by applicable law or agreed to in writing, software
//   distributed under the License is distributed on an "AS IS" BASIS,
//   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
//   See the License for the specific language governing permissions and
//   limitations under the License.
//
//======================================================================
//	More information: https://www.cloudflare.com/docs/client-api.html

//Test for cURL
$curltest = function_exists('curl_version') ? 'Enabled' : 'Disabled';
if ($curltest == 'Disabled'){
	echo 'cURL not installed or available';
	exit();
}

$cloudflare_a = 'zone_load_multi';
// To define which request is being made
$cloudflare_tkn = '8afbe6dea02407989af4dd4c97bb6e25';
// This is the API key made available on your Account page.
$cloudflare_email = 'sample@example.com';
// The e-mail address associated with the API key.

$zones = array();

function toArray($data) {
	if (is_object($data)) {$data = get_object_vars($data);
	}
	return is_array($data) ? array_map(__FUNCTION__, $data) : $data;
}

//Location of cloudflare's client gateway interface
$host = 'https://www.cloudflare.com/api_json.html?';
//Set up the get data with the information provided above
$data = 'a=' . $cloudflare_a . '&tkn=' . $cloudflare_tkn . '&email=' . $cloudflare_email;

// Get cURL resource
$curl = curl_init();
// Set some options - we are passing in a useragent too here
curl_setopt_array($curl, array(CURLOPT_RETURNTRANSFER => true, CURLOPT_URL => $host . $data, CURLOPT_USERAGENT => 'CloudFlare get zone_load_multi'));
// Send the request & save response to $resp
$resp = curl_exec($curl);
// Close request to clear up some resources
curl_close($curl);
// Decode the json given to use by cloudflare
$fulla = json_decode($resp);
// New
$full = toArray($fulla);

//We only need the zone information so need a counter to get the second array
$n = 0;

//Mine the data
foreach ($full as $key) {
	if ($n == 0) {//If API status
		if ($full['result'] == 'error'){//Add some error handling
			die('ERROR '.$full['err_code'] .': '. $full['msg']);
		}
		
	}
	if ($n == 1) {//If is the zone information

		foreach ($key["zones"]["objs"] as $keya) {//For each zone in your account

			$name = $keya["zone_name"];
			// Get the domain / name of this zone

			foreach ($keya as $a) {// Push the data to the $zones array();
				$key = array_search($a, $keya);
				//Search the for the key in the array
				$zones[$name][$key] = $a;
				//Push to the $zones array();
			}
		}
	}
	$n++;
}
//Debug - Show the data that is now in this array.
echo '<pre>
';
//var_dump($full); 
echo '
</pre>';
	
//Below is not needed but shows the domains and their data in a neat format
//You can now use 
//	$zones['(domain)']['variable']
//to retrieve the stored data

echo '<select id="domains" style="display:block;">';
echo '<option>Select Domain</option>';
foreach ($zones as $key => $value) {
	echo '<option value="' . $zones[$key]['zone_id'] . '" >' . $key . '</option>';
}
echo '</select><br />';

foreach ($zones as $key => $value) {
	echo '<span id="' . $zones[$key]['zone_id'] . '" style="display:none;">';
	foreach ($value as $key => $value) {echo $key . '>';
		print_r($value);
		echo '<br />';
	}
	echo '</span><br />';
}
?>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script>
	$('#domains').change(function() {
		var id = $(this).val();
		$('#' + id).siblings().hide()
		$('#' + id).parents().siblings().hide()
		$('#domains').css('display', 'block');
		$('#' + id).css('display', 'block');
	});
</script>


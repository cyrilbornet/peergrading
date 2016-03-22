<?php
session_start();
include('_db/_db.php');
require_once "defines.php";

if ( isset( $_GET[ 'code' ] ) ) {
	if ( false == isset( $_GET[ 'state' ] ) )
		die( 'Warning! State variable missing after authentication' );
	session_start();
	if ( $_GET[ 'state' ] != $_SESSION[ 'wpcc_state' ] )
		die( 'Warning! State mismatch. Authentication attempt may have been compromised.' );

	$curl = curl_init( REQUEST_TOKEN_URL );
	curl_setopt( $curl, CURLOPT_POST, true );
	curl_setopt( $curl, CURLOPT_POSTFIELDS, array(
		'client_id' => CLIENT_ID,
		'redirect_uri' => REDIRECT_URL,
		'client_secret' => CLIENT_SECRET,
		'code' => $_GET[ 'code' ], // The code from the previous request
		'grant_type' => 'authorization_code'
		)
	);

	curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
	$auth = curl_exec( $curl );
	$secret = json_decode( $auth );

	$access_token = $secret->access_token;
	$curl = curl_init( "https://public-api.wordpress.com/rest/v1/me/" );
	curl_setopt( $curl, CURLOPT_HTTPHEADER, array( 'Authorization: Bearer '.$access_token ) );
	curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
	$me = json_decode( curl_exec( $curl ) );

	if ($user = db_fetch(db_s('users', array('username' => $me->username)))) {
		db_u('users', array('username' => $me->username), array('wp_access_token' => $access_token));
		$_SESSION['wp_username'] = $me->username;
		header("Location: index.php");
		echo '<a href="/">Poll</a>';
	}
	else {
		header("HTTP/1.0 403 Forbidden");
		die('<h1>403</h1>');
	}
}

//redirect errors or cancelled requests back to login page
header( "Location: ".LOGIN_URL );
die();


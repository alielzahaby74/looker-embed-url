<?php



date_default_timezone_set('America/Los_Angeles');

$secret = "";

$embedpath= "";

$host = "";
$path = "/login/embed/" . urlencode($embedpath);

$json_nonce = json_encode(md5(uniqid()));
$json_current_time = json_encode(time());
$json_session_length = json_encode(3600);
$json_external_user_id = json_encode("test-id-123");
$json_first_name = json_encode("Data");
$json_last_name = json_encode("Team Cloudypedia");
$json_permissions = json_encode( array ( "see_user_dashboards", "see_lookml_dashboards", "access_data", "see_looks" ) );
$json_models = json_encode( array ( "future_x" ) );
$json_group_ids = json_encode( array ( ) );  // just some example group ids
$json_external_group_id = json_encode("");
// $json_user_attributes = json_encode( array ( ) ) ?? "{}";  // just some example attributes
$json_user_attributes = "{}";  // just some example attributes
// NOTE: accessfilters must be present and be a json hash. If you don't need access filters then the php
// way to make an empty json hash as an alternative to the below seems to be:
// $accessfilters = new stdClass()
$accessfilters = array ( "future_x" => array ( "Instituton" => 55830, "Year" => 2023 ));//default value is []
$json_accessfilters = json_encode($accessfilters); //its still wrong but the problem now is with the signature

var_dump($json_accessfilters);
$stringtosign = "";
$stringtosign .= $host . "\n";
$stringtosign .= $path . "\n";
$stringtosign .= $json_nonce . "\n";
$stringtosign .= $json_current_time . "\n";
$stringtosign .= $json_session_length . "\n";
$stringtosign .= $json_external_user_id . "\n";
$stringtosign .= $json_permissions . "\n";
$stringtosign .= $json_models . "\n";
$stringtosign .= $json_group_ids . "\n";
$stringtosign .= $json_external_group_id . "\n";
$stringtosign .= $json_user_attributes . "\n";
$stringtosign .= $json_accessfilters;

$signature = trim(base64_encode(hash_hmac("sha1", utf8_encode($stringtosign), $secret, $raw_output = true)));

$queryparams = array (
  // all fields are required and must be in the shown order, so don't sort them alphabetically or something
    'nonce' =>  $json_nonce,
    'time'  =>  $json_current_time,
    'session_length'  =>  $json_session_length,
    'external_user_id'  =>  $json_external_user_id,
    'permissions' =>  $json_permissions,
    'models'  =>  $json_models,
    'group_ids' => $json_group_ids,
    'external_group_id' => $json_external_group_id, 
    'user_attributes' => $json_user_attributes,
    'access_filters'  =>  $json_accessfilters,
    'first_name'  =>  $json_first_name,
    'last_name' =>  $json_last_name,
    'force_logout_login'  =>  false,
    'signature' =>  $signature
);

$querystring = "";
foreach ($queryparams as $key => $value) {
  if (strlen($querystring) > 0) {
    $querystring .= "&";
  }
  if ($key == "force_logout_login") {
    $value = "true";
  }
  $querystring .= "$key=" . urlencode($value);
}

$final = "https://" . $host . $path . "?" . $querystring;
echo $final;
echo "\n";
?>
<?php



date_default_timezone_set('America/Los_Angeles');

$secret = "";

$embedpath= "";

$host = "";
$path = "/login/embed/" . urlencode($embedpath);

$json_nonce = json_encode(md5(uniqid()));
$json_current_time = json_encode(time());
$json_session_length = json_encode(3600);
$json_external_user_id = json_encode("test-id-123"); //create a unique id for each user
$json_first_name = json_encode("Data"); //first name of the user
$json_last_name = json_encode("Team Cloudypedia"); //last name of the user
$json_permissions = json_encode([
  //permissions for the user
  "see_user_dashboards",
  "see_lookml_dashboards",
  "access_data",
  "see_looks"
]);
$json_models = json_encode(array ("future_x"));
$json_group_ids = json_encode( array ( ) );  //default value is []
$json_external_group_id = json_encode(""); //default value is ""
$json_user_attributes = json_encode( array(), JSON_FORCE_OBJECT);  // default value is {}
$accessfilters = json_encode( array(), JSON_FORCE_OBJECT); //default value is {}

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
$stringtosign .= $accessfilters;

$iso = $stringtosign;
$stringtosign = mb_convert_encoding($iso, 'UTF-8', 'ISO-8859-1'); //just a utf-8 encoding but the php utf-8 function is deprecated

$signature = trim(base64_encode(hash_hmac("sha1", $stringtosign, $secret, $raw_output = true)));

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
    'access_filters'  =>  $accessfilters,
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
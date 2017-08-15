
<?php 
ini_set('display_errors', 'On');



$service_url = 'http://api.kivaws.org/graphql';

$curl = curl_init($service_url);

$curl_post_data = array("query" => '{loans (filters: {expiringSoon:true, status:fundRaising}, sortBy: newest, limit: 20) {totalCount values { name  status plannedExpirationDate }}}');
$data_string =  json_encode($curl_post_data);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($data_string))
);

$curl_response = curl_exec($curl);
$json_a = json_decode($curl_response, true);
if ($curl_response === false) {
    $info = curl_getinfo($curl);
    curl_close($curl);
    die('error occured during curl exec. Additioanl info: ' . var_export($info));
}
curl_close($curl);
// echo $json_a['data'][loans];

// if (!isset($json_a['data']['loans']['values'][0]['loanAmount'])) $json_a['data']['loans']['values'][0]['loanAmount'] = '';

  $amount= $json_a['data']['loans']['values'][0]['loanAmount'];
  echo floatval($amount);

$loans= $json_a['data']['loans']['values'];
for($i=0; $i < count($loans); $i++){
  $loan= $json_a['data']['loans']['values'][$i];
  echo $loan['name'];
  // echo $loan['loanAmount'];
  // echo $loan['loanAmount'];
  // $date = new DateTime($loan['plannedExpirationDate']);
  // $now =  (new DateTime());
  // $datetime1 = date_create($date->format('Y-m-d'));
  // $datetime2 = date_create($now->format('Y-m-d'));
  // $interval = date_diff($datetime2, $datetime1);
  // echo $interval->format('%R%a').'<br>';
  // if (echo intval($interval->format('%R%a')) < 5){
  //   echo $loan['name'];
  // }

};

?>




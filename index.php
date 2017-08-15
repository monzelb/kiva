
<?php 
ini_set('display_errors', 'On');



$service_url = 'http://api.kivaws.org/graphql';

$curl = curl_init($service_url);

$curl_post_data = array("query" => '{loans (filters: {expiringSoon:true, status:fundRaising}, sortBy: expiringSoon, limit: 300) {totalCount values { name id loanAmount plannedExpirationDate }}}');
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

$loans= $json_a['data']['loans']['values'];
echo count($json_a['data']['loans']['values']) . '<br>';
for($i=0; $i < count($loans); $i++){
  $loan= $json_a['data']['loans']['values'][$i];
  // echo $loan['name'];
  
  $date = new DateTime($loan['plannedExpirationDate']);
  $now =  (new DateTime());
  $datetime1 = date_create($date->format('Y-m-d'));
  $datetime2 = date_create($now->format('Y-m-d'));
  $interval = date_diff($datetime2, $datetime1);
  $link = "https://www.kiva.org/lend/" . $loan['id'];
  if ($interval->format('%R%a') < 1){
    echo "name: " . $loan['name'] . '<br>';
    echo "loan amount: " . $loan['loanAmount'] . '<br>';
    echo "expires: " . $loan['plannedExpirationDate'] . '<br>';
    echo "link: " . $link . '<br>' . '<br>';
  }

};

?>




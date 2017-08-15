<!DOCTYPE html>
<html>
<head>
  <title>Projects</title>
  <!-- meta -->
  <meta charset="utf-8" />
  <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
  <!-- mobile -->
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <!-- styles -->
  <link rel="stylesheet" type="text/css" href="vendor/css/normalize.css">
  <link rel="stylesheet" type="text/css" href="assets/css/main.css">
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
  <!-- scripts -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
</head>
<body>
<?php 
ini_set('display_errors', 'On');



$service_url = 'http://api.kivaws.org/graphql';

$curl = curl_init($service_url);

$curl_post_data = array("query" => '{loans (filters: {expiringSoon:true, status:fundRaising}, sortBy: expiringSoon, limit: 1000) {totalCount values { name id loanAmount fundedAmount plannedExpirationDate }}}');
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

date_default_timezone_set('Africa/Accra');
//Global variables
$loans = $json_a['data']['loans']['values'];
$totalAmountLeft = 0;
$totalLoanAmount = 0;
$values = $json_a['data']['loans']['values'];
$expiringToday = array();
$now =  (new DateTime());

?>

 <div class="container">
   <h2>Striped Rows</h2>
   <p>The .table-striped class adds zebra-stripes to a table:</p>            
   <table class="table table-striped">
     <thead>
       <tr>
         <th>Id</th>
         <th>Link</th>
         <th>Loan amount</th>
         <th>Amount Funded</th>
         <th>Amount left</th>
       </tr>
     </thead>
     <tbody>
       <?php

      for($i=0; $i < count($loans); $i++){
        $loan= $values[$i];  
        $date = new DateTime($loan['plannedExpirationDate']);
        $datetime1 = date_create($date->format('Y-m-d'));
        $datetime2 = date_create($now->format('Y-m-d'));
        $interval = date_diff($datetime2, $datetime1);
        $link = "https://www.kiva.org/lend/" . $loan['id'];
        $amountLeft = $loan['loanAmount'] - $loan['fundedAmount'];
        if ($interval->format('%R%a') <= 1){
          $totalAmountLeft += $amountLeft;
          $totalLoanAmount += $loan['loanAmount'];
          echo '<tr>';
          echo '<th>' . $loan['id'] . '</th>';
          echo '<th>' . "<a href={$link}>link</a></th>" ;
          echo '<th>' . $loan['loanAmount'] . '</th>';
          echo '<th>' . $loan['fundedAmount'] . '</th>';
          echo '<th>' . $amountLeft . '</th>';
          echo '</tr>';
          echo $totalAmountLeft;
          echo $totalLoanAmount;

        }
      };
      echo '<tr>';
      echo '<th>' . '- </th>';
      echo '<th>' . '- </th>';
      echo '<th>' . '- </th>';
      echo '<th>' . $totalLoanAmount . '</th>';
      echo '<th>' . $totalAmountLeft . '</th>';
      echo '</tr>';
       ?>
       <tr>
         <th> - </th>
         <th> - </th>
         <th> - </th>
         <th> Total amount </th>
         <th>Amount left</th>
       </tr>
     </tbody>
   </table>
 </div>


</body>
</html>
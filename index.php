<!DOCTYPE html>
<html>
  <head>
    <title>Soon-to-expire Loans</title>
    <!-- meta -->
    <meta charset="utf-8" />
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <!-- mobile -->
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- styles -->
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- scripts -->
  </head>
  <?php 

//API call
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

//Set Time zone
  date_default_timezone_set('Africa/Accra');

  ?>
  <body>
    <div class="container">
      <h2>Kiva loans expiring soon</h2>
      <p>A table of all loans with a status of fundRaising and an expiration date within the next 24 hours.</p>            
      <table class="table table-striped ">
        <thead>
          <tr>
           <th>Id</th>
           <th>Expiration date</th>
           <th>Link</th>
           <th>Loan amount</th>
           <th>Amount Funded</th>
           <th>Amount left</th>
          </tr>
        </thead>
        <tbody>

        <?php

      //Global variables
        $loans = $json_a['data']['loans']['values'];
        $totalAmountLeft = 0;
        $totalLoanAmount = 0;
        $values = $json_a['data']['loans']['values'];
        $now =  (new DateTime());

      //loop over response, and set variables.
        for($i=0; $i < count($loans); $i++){
          $loan= $values[$i];  
          $date = new DateTime($loan['plannedExpirationDate']);
          $datetime1 = date_create($date->format('Y-m-d H:i:s'));
          $datetime2 = date_create($now->format('Y-m-d H:i:s'));
          $interval = date_diff($datetime2, $datetime1);
          $link = "https://www.kiva.org/lend/" . $loan['id'];
          $amountLeft = $loan['loanAmount'] - $loan['fundedAmount'];

        //filter for plannedExpirationDate in next 24hours and create table with results.
          if ($interval->format('%R%a') < 1){
            $totalAmountLeft += $amountLeft;
            $totalLoanAmount += $loan['loanAmount'];
            echo '<tr>';
            echo '<th>' . $loan['id'] . '</th>';
            echo '<th>' . $loan['plannedExpirationDate'] . '</th>';
            echo '<th>' . "<a href={$link}>link</a></th>" ;
            echo '<th>' . $loan['loanAmount'] . '</th>';
            echo '<th>' . $loan['fundedAmount'] . '</th>';
            echo '<th>' . $amountLeft . '</th>';
            echo '</tr>';
          }
        };

      //Totals row of table
        echo '<tr class="success">';
        echo '<th>' . '- </th>';
        echo '<th>' . '- </th>';
        echo '<th>' . '- </th>';
        echo '<th>' . 'Totals: </th>';
        echo '<th>' . $totalLoanAmount . '</th>';
        echo '<th>' . $totalAmountLeft . '</th>';
        echo '</tr>';

         ?>

       </tbody>
      </table>
    </div>
  </body>
</html>
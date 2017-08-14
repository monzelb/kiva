<?php 
// ini_set('display_errors', 'On');


// $opts = array(
//   'http'=>array(
//     'method'=>"GET",
//     'header'=>"Accept-language: en\r\n" .
//               "Cookie: foo=bar\r\n"
//   )
// );

// $context = stream_context_create($opts);

// // Open the file using the HTTP headers set above
// $file = file_get_contents('http://www.example.com/', false, $context);

// $response = file_get_contents('http://api.kivaws.org/graphql');


// echo $response







$service_url = 'http://api.kivaws.org/graphql';

$curl = curl_init($service_url);

$curl_post_data = array("query" => '{loans (filters: {gender: male, status:funded, country: ["KE", "US"]}, sortBy: newest, limit: 2) {totalCount values { name  status loanAmount }}}');
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
if ($curl_response === false) {
    $info = curl_getinfo($curl);
    curl_close($curl);
    die('error occured during curl exec. Additioanl info: ' . var_export($info));
}
curl_close($curl);
echo $curl_response;
echo $info;
// $curl_post_data = array(
//         'Pragma' => 'no-cache',
//         'Origin' => 'http://api.kivaws.org', 
//         'Accept-Encoding' => 'gzip, deflate', 
//         'Accept-Language' => 'en-US,en;q=0.8', 
//         'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.115 Safari/537.36', 
//         'content-type' => 'application/json', 
//         'accept' => 'application/json', 
//         'Cache-Control' => 'no-cache', 
//         'Connection' => 'keep-alive', 
//         'data' => '{"query":"{
//   loans (filters: {gender: male, status:funded, country: [\"KE\", \"US\"]}, sortBy: newest, limit: 2) {
//     totalCount
//     values {
//       name
//       status
//       loanAmount
//     }
//   }
//   }","variables":null,"operationName":null}'
// );
// curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data);

// $result = curl_exec($curl, $cmd);
// if ($result === false) {
//     $info = curl_getinfo($curl);
//     curl_close($curl);
//     die('error occured during curl exec. Additioanl info: ' . var_export($info));
// }

// curl_close($curl);

// echo $result


?>




<!-- curl 'http://api.kivaws.org/graphql/?' -H 'Pragma: no-cache' -H 'Origin: http://api.kivaws.org' -H 'Accept-Encoding: gzip, deflate' -H 'Accept-Language: en-US,en;q=0.8' -H 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.115 Safari/537.36' -H 'content-type: application/json' -H 'accept: application/json' -H 'Cache-Control: no-cache' -H 'Connection: keep-alive' --data-binary '{"query":"{
  loans (filters: {gender: male, status:funded, country: [\"KE\", \"US\"]}, sortBy: newest, limit: 2) {
    totalCount
    values {
      name
      status
      loanAmount
    }
  }
  }","variables":null,"operationName":null}' --compressed -->
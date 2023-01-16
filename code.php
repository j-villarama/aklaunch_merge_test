<?php

/*

Information:
email_names.json -- has first name, last name, email
    Example: {"id":1,"first_name":"Patricia","last_name":"Mackiewicz","email":"pmackiewicz0@alexa.com"}
email_numbers.json -- has email, cc_number
    Example: {"id":1,"email":"pholt0@ftc.gov","cc_number":"3555060285229115"},

Objective:
For all matching emails that exist ONCE in EACH list, return first_name, last_name, cc_number and email.


*/
$start = microtime(true);
if (!file_exists('email_names_large.json')) {
    echo 'Missing email_names_large.json';
    return;
}

if (!file_exists('email_numbers_large.json')) {
    echo 'Missing email_numbers_large.json';
    return;
}

$namesList = json_decode(file_get_contents('email_names_large.json'), true);
$numbersList = json_decode(file_get_contents('email_numbers_large.json'), true);

$list1_emails = array_column($namesList, 'email');
$list2_emails = array_column($numbersList, 'email');

$matching_emails = array_intersect($list1_emails, $list2_emails);


$list1_email_count = array_count_values($list1_emails);
$list2_email_count = array_count_values($list2_emails);

$results = array();
foreach($matching_emails as $email){
    if($list1_email_count[$email] == 1 && $list2_email_count[$email] == 1){
            $nameData = array_filter($namesList, function($i) use ($email) {
                return $i['email'] == $email;
            });
            $key = array_keys($nameData)[0];
            $numberData =  array_filter($numbersList, function($i) use ($email) {
                return $i['email'] == $email;
            });
            $key2 = array_keys($numberData)[0];

            $results[] = array("email" => $email, "first_name" => $nameData[$key]["first_name"], "last_name" => $nameData[$key]["last_name"], "cc_number" => $numberData[$key2]["cc_number"]);
    }
}

$end = microtime(true);
$time = $end - $start;

echo "Finished execution in " . $time . " seconds";
$output_json = json_encode($results, JSON_PRETTY_PRINT);
file_put_contents('output.json', $output_json);
file_put_contents('matching_emails.csv',join(",\n", $matching_emails));

?>

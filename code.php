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

if (!file_exists('email_names.json')) {
    echo 'Missing email_names.json';
    return;
}

if (!file_exists('email_numbers.json')) {
    echo 'Missing email_numbers.json';
    return;
}

$namesList = json_decode(file_get_contents('email_names.json'), true);
$numbersList = json_decode(file_get_contents('email_numbers.json'), true);

$matches = array_filter($namesList, function ($nameData) use ($numbersList) {
    $matchCount = 0;
    $ccNumber = '';

    foreach ($numbersList as $numberData) {
        if ($nameData['email'] === $numberData['email']) {
            $ccNumber = $numberData['cc_number'];
            $matchCount++;
        }
    }

    if ($matchCount === 1) {
        $match = new stdClass();
        $match->first_name = $nameData['first_name'];
        $match->last_name = $nameData['last_name'];
        $match->email = $nameData['email'];
        $match->cc_number = $ccNumber;

        return $match;
    }
});

$output_json = json_encode($matches, JSON_PRETTY_PRINT);
file_put_contents('output.json', $output_json);

?>

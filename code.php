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
    echo "Missing email_names.json";
    return;
}

if (!file_exists('email_numbers.json')) {
    echo "Missing email_numbers.json";
    return;
}


$names_list = json_decode(file_get_contents('email_names.json'), true); 
$numbers_list = json_decode(file_get_contents('email_numbers.json'), true);  

$matches = [];


foreach ($numbers_list as $number_data) {    
    $filteredArray = array_filter($names_list, function ($obj) use ($number_data) {
        return $obj["email"] === $number_data["email"];
    });
    
    if (count($filteredArray) > 0) {
        $keys = array_keys($filteredArray);

        foreach ($keys as $key) {
            $matches[] = [
                'first_name' => $filteredArray[$key]["first_name"],
                'last_name' => $filteredArray[$key]["last_name"],
                'cc_number' => $number_data['cc_number'],
                'email' => $number_data['email']
            ];;
        }
    }
}

$output_json = json_encode($matches, JSON_PRETTY_PRINT);
file_put_contents('output.json', $output_json);

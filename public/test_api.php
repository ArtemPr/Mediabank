<?php

echo '<h3>TEST API</h3>';

$url = "http://127.0.0.1:8002/api/media_content/1";

echo CallAPI(
    "POST",
    $url,
    [
        'name' => 'test_name',
        'file_name' => 'file_name',
        'file' => 'file',
        'directory' => '1'
    ]
);

function CallAPI($method, $url, $data = false)
{
    $curl = curl_init();

    switch ($method)
    {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);

            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    // Optional Authentication:
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_USERPWD, "username:password");
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'token: 519ec8d1fa23e0ffdabf1a6b44c0c595d736ebc30ab00e62f4ba438b317bdd32c15f54a84d6f7171c3f73718b56896d6713e37664213c18dbf31eb26'
    ]);

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);

    curl_close($curl);

    return $result;
}
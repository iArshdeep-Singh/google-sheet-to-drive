<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"])) {
    $accessToken = "ya29.a0ARW5m75svXvZbeCHSEQSUN0EDTwdf0y6ADEr6R25uvFrmb65NfUMosmMJW5pLHrTrqbnFn6fAd5Ugn4GNXKIqSeR9OvqHA7zT7gu-ozLnXmL2DLw-fsRydwRHZO4ANUJ9rtbc4_BCezbG3bxwYzjYXrxObK4_dspkJC8FBqKaCgYKAaASARESFQHGX2Mi7I1smiDnr6NsdSJmKwkncg0175";
    $fileName = $_FILES["file"]["name"];
    $filePath = $_FILES["file"]["tmp_name"];
    $mimeType = mime_content_type($filePath);

    $fileData = file_get_contents($filePath);
    $url = "https://www.googleapis.com/upload/drive/v3/files?uploadType=multipart";

    $metadata = [
        "name" => $fileName
    ];

    $boundary = uniqid();
    $delimiter = "--" . $boundary;
    $eol = "\r\n";

    $body = "";
    $body .= $delimiter . $eol;
    $body .= "Content-Disposition: form-data; name=\"metadata\"; filename=\"metadata.json\"" . $eol;
    $body .= "Content-Type: application/json; charset=UTF-8" . $eol . $eol;
    $body .= json_encode($metadata) . $eol;
    $body .= $delimiter . $eol;
    $body .= "Content-Disposition: form-data; name=\"file\"; filename=\"$fileName\"" . $eol;
    $body .= "Content-Type: $mimeType" . $eol . $eol;
    $body .= $fileData . $eol;
    $body .= $delimiter . "--";

    $headers = [
        "Authorization: Bearer $accessToken",
        "Content-Type: multipart/related; boundary=$boundary",
        "Content-Length: " . strlen($body)
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    $responseArray = json_decode($response, true);

    if (isset($responseArray["id"])) {
        echo "File uploaded successfully! <br>";
        echo "File ID: " . $responseArray["id"] . "<br>";
        echo "View File: <a href='https://drive.google.com/file/d/" . $responseArray["id"] . "' target='_blank'>Open in Google Drive</a>";
    } else {
        echo "Upload failed! " . $response;
    }
} else {
    echo "No file uploaded!";
}

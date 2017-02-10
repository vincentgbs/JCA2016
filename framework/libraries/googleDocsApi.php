<?php
class googleDocsApi
{
    public function __construct($credentials='framework/libraries/credentials/googledocs.json')
    {
        require_once FILE . 'framework/libraries/google-api-php-client-2.1.1/vendor/autoload.php';
        if (is_file(FILE . $credentials)) {
            putenv('GOOGLE_APPLICATION_CREDENTIALS=/' . FILE . $credentials);
        } else {
            exit('Missing google credentials.');
        }
    }

    public function writeToGoogleSpreadsheet($spreadsheetId, $range, $values, $params = ['valueInputOption'=>'USER_ENTERED'])
    {
        $client = new Google_Client();
        $client->useApplicationDefaultCredentials();
        $client->addScope(Google_Service_Drive::DRIVE);
        $service = new Google_Service_Sheets($client);
        $body = new Google_Service_Sheets_ValueRange(['values'=>$values]);
        return $service->spreadsheets_values->append($spreadsheetId, $range, $body, $params);
    }
}
?>

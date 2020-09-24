<?php


class OnaApi
{

    static $URL = 'https://api.ona.io/api/v1/';
    static $USERPWD = "mdcmali:azerty12346";

    /**
     * @return mixed
     */
    public static function get(array $params = [])
    {

        if (isset($params['type'], $params['type'])) {
            // Initiate curl session in a variable (resource)
            $curl_handle = curl_init();
            // Set the curl URL option
            $typeParam = '';
            if (isset($params['param'])) {
                $typeParam = $params['param'];
            }
            curl_setopt($curl_handle, CURLOPT_URL, "https://api.ona.io/api/v1/${params['type']}$typeParam");
            //Specify the username and password using the CURLOPT_USERPWD option.
            curl_setopt($curl_handle, CURLOPT_USERPWD, self::$USERPWD);
            // This option will return data as a string instead of direct output
            curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
            // Execute curl & store data in a variable
            $curl_data = curl_exec($curl_handle);
            curl_close($curl_handle);
            // Decode JSON into PHP array
            return json_decode($curl_data);
        }
    }

    /**
     * @param array $params
     * @return array
     */
    public static function post(array $params = [])
    {
        self::$URL = 'https://api.ona.io/api/v1/';
        $typeParam = '';

        if (isset($params['param'])) {
            $typeParam = $params['param'];
        }
        $data = '';
        $curl_handle = curl_init();

        if (isset($params['file'])) {
            $file = $params['file'];
            $cfile = new CURLFile($file->getTempName(), $file->getType(), $file->getName());
            $data = array('xls_file' => $cfile);

        } else if (isset($params['data'])) {
            $data = $params['data'];
        }
        $url = "https://api.ona.io/api/v1/${params['type']}$typeParam";

        curl_setopt($curl_handle, CURLOPT_URL, $url);
        curl_setopt($curl_handle, CURLOPT_USERPWD, self::$USERPWD);
        if (isset($params['update'])) {
            curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, "PUT");
        } else {
            curl_setopt($curl_handle, CURLOPT_POST, true);
        }
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($curl_handle);
        $error = curl_error($curl_handle);
        curl_close($curl_handle);
        return ['response' => json_encode($response), 'error' => $error];
    }

    public static function delete(array $params = [])
    {
        $data = array();
        $data_json = json_encode($data);
        $typeParam = '';
        if (isset($params['param'])) {
            $typeParam = $params['param'];
        }
        $url = "https://api.ona.io/api/v1/${params['type']}$typeParam";
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $url);
        curl_setopt($curl_handle, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($data_json)));
        curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($curl_handle, CURLOPT_USERPWD, self::$USERPWD);
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $data_json);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl_handle);
        $error = curl_error($curl_handle);
        curl_close($curl_handle);
        return ['response' => json_encode($response), 'error' => $error];
    }

    public static function fileMaker($id)
    {
        //The resource that we want to download.
        $fileUrl = 'https://api.ona.io/api/v1/forms/' . $id . '.xls';

        //The path & filename to save to.
        $saveTo = PUBLIC_DIR . '/files/xls/' . $id . '.xls';

        //Open file handler.
        $fp = fopen($saveTo, 'w+');

        //If $fp is FALSE, something went wrong.
        if ($fp === false) {
            throw new Exception('Could not open: ' . $saveTo);
        }

        //Create a cURL handle.
        $ch = curl_init($fileUrl);

        //Pass our file handle to cURL.
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_USERPWD, self::$USERPWD);

        //Timeout if the file doesn't download after 20 seconds.
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);

        //Execute the request.
        curl_exec($ch);

        //If there was an error, throw an Exception
        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch));
        }

        //Get the HTTP status code.
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        //Close the cURL handler.
        curl_close($ch);

        //Close the file handler.
        fclose($fp);
        if ($statusCode == 200) {
            echo 'Downloaded!';
        } else {
            echo "Status Code: " . $statusCode;
        }

    }

    public static function formatterOnaObject($data)
    {
        $currentFormData = [];
        foreach (array_reverse(json_decode(json_encode($data), true)) as $key2 => $value) {
            if (($key2[0] !== "_" && $key2 !== 'formhub/uuid' && $key2 !== 'meta/instanceID') || $key2 = '_id') {
                $currentFormData[$key2] = $value;
            }
        }
        return $currentFormData;
    }

//    $test = OnaApi::query($id,'query={"Votre_age":"28"}');
    public static function query($formId, $query)
    {
        $response = [];
        foreach (self::get(['type' => 'data', 'param' => '/' . $formId . '?' . $query]) as $key => $data) {
            $response[$key] = self::formatterOnaObject($data);
        }
        return $response;
    }

    public static function createCsv($head, $body)
    {
        $file = fopen(PUBLIC_DIR . 'files/csv/csv_to_add.csv', 'w');
//        head:array('Column 1', 'Column 2', 'Column 3', 'Column 4', 'Column 5');
        fputcsv($file, $head);

//        body:array(
//        array('Data 11', 'Data 12', 'Data 13', 'Data 14', 'Data 15'),
//        array('Data 21', 'Data 22', 'Data 23', 'Data 24', 'Data 25'),
//        array('Data 31', 'Data 32', 'Data 33', 'Data 34', 'Data 35'),
//        array('Data 41', 'Data 42', 'Data 43', 'Data 44', 'Data 45'),
//        array('Data 51', 'Data 52', 'Data 53', 'Data 54', 'Data 55')
//    );
        foreach ($body as $row) {
            fputcsv($file, $row);
        }
        fclose($file);
    }

    public static function addCsvFile($formId, $dataCsv = [])
    {

//        if (isset($dataCsv['csv_data'])){
//            self::createCsv();
//        }
        $cfile = new CurlFile(PUBLIC_DIR . 'files/csv/antecedant.csv', 'text/csv');
        $data = array('data_file' => $cfile, 'data_type' => 'media', 'xform' => $formId, 'data_value' => 'mycsv.csv');
        $url = "https://api.ona.io/api/v1/metadata.json";
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $url);
        curl_setopt($curl_handle, CURLOPT_POST, true);
        curl_setopt($curl_handle, CURLOPT_USERPWD, self::$USERPWD);
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl_handle);
        $error = curl_error($curl_handle);
        curl_close($curl_handle);
        return ['response' => json_encode($response), 'error' => $error];
    }


}
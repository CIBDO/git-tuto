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
        $error = curl_error($curl_handle) === "" ? false : true;
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
        $error = curl_error($curl_handle) === "" ? false : true;
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

    /*
    * $test = OnaApi::query($id,'query={"Votre_age":"28"}');
    */
    public static function query($formId, $query)
    {
        $response = [];
        foreach (self::get(['type' => 'data', 'param' => '/' . $formId . '?' . $query]) as $key => $data) {
            $response[$key] = self::formatterOnaObject($data);
        }
        return $response;
    }

    public static function createCsv($head, $body, $name)
    {
        $file = fopen(PUBLIC_DIR . 'files/csv/' . $name . '.csv', 'w');
        fputcsv($file, $head);
        foreach ($body as $row) {
            fputcsv($file, $row);
        }
        fclose($file);
    }

    public static function postCsvMedia($name, $formId = 544798)
    {

        $cfile = new CurlFile(PUBLIC_DIR . 'files/csv/' . $name, 'text/csv');
        $data = array('data_file' => $cfile, 'data_type' => 'media', 'xform' => $formId, 'data_value' => $name);
        $url = "https://api.ona.io/api/v1/metadata.json";
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $url);
        curl_setopt($curl_handle, CURLOPT_POST, true);
        curl_setopt($curl_handle, CURLOPT_USERPWD, self::$USERPWD);
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl_handle);
        $error = curl_error($curl_handle) === "" ? false : true;
        curl_close($curl_handle);
        return ['response' => json_encode($response), 'error' => $error];
    }

    public static function getFormData($formId)
    {
        $response = [];
        foreach (self::get(['type' => 'data', 'param' => '/' . $formId]) as $key => $data) {
            $response[$key] = self::formatterOnaObject($data);
        }
        return $response;
    }

    public static function getFormSuivi($formId, $filter = [])
    {
        $response = [];
        foreach (self::get(['type' => 'data', 'param' => '/' . $formId]) as $key => $data) {

            $currentFormData = [];
            $submission_time = '';
            if ($data!=="Not found."){
                foreach (array_reverse(json_decode(json_encode($data), true)) as $key2 => $value) {
                    if ($key2 === 'suivi') {
                        $currentFormData[$key2] = $value[0];
                    }
                    if ($key2 === '_submission_time') {
                        $submission_time = date('d-m-Y', strtotime($value));
                    }
                }
                $currentFormData['suivi']['_submission_time'] = $submission_time;
                if (isset($filter['id_p'])) {
                    if ($currentFormData['suivi']['suivi/id_p'] == $filter['id_p']) {
                        $response[$key] = $currentFormData['suivi'];
                    }

                } else {
                    $response[$key] = $currentFormData['suivi'];
                }
            }

        }
        return $response;
    }


    public static function getMetaData($filter = [])
    {
        $xform = '';
        if (isset($filter['xform'])) {
            $xform = '?xform=' . $filter['xform'];
        }
        $metadata = self::get(['type' => 'metadata', 'param' => $xform]);
        $response = [];
        $i = 0;
        foreach (json_decode(json_encode($metadata), true) as $data) {
            if (isset($filter['data_type'])) {
                if ($filter['data_type'] === $data['data_type']) {
                    if (isset($filter['data_value'])) {
                        if ($filter['data_value'] === $data['data_value']) {
                            $response[$i] = $data;
                            $i++;
                        }
                    } else {
                        $response[$i] = $data;
                        $i++;
                    }

                }
            } else {
                $response[$i] = $data;
                $i++;
            }

        }

        return $response;
    }

    public static function deleteCsvMedia($name, $formId = 544798)
    {
        $media = self::getMetaData(['data_type' => 'media', 'data_value' => $name, 'xform' => $formId]);
        if (!(count($media) > 0)) {
            return ['response' => json_encode(""), 'error' => false];
        }
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, "https://api.ona.io/api/v1/metadata/" . $media[0]['id']);
        curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($curl_handle, CURLOPT_USERPWD, self::$USERPWD);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl_handle);
        $error = curl_error($curl_handle) === "" ? false : true;
        curl_close($curl_handle);
        return ['response' => json_encode($response), 'error' => $error];
    }

    public static function updateSuiviCsv()
    {
        $csvBody = [];
        foreach (DonneesHopital::find() as $k => $dh) {
            $patient = $dh->getPatients();
            $csvBody[$k][] = $k+1;
            $csvBody[$k][] = $dh->code_asc;
            $csvBody[$k][] = $patient->id_technique;
            $csvBody[$k][] = $patient->nom;
            $csvBody[$k][] = $patient->prenom;
            $csvBody[$k][] = $dh->commentaire;
            $csvBody[$k][] = date('Y-m-d', strtotime($dh->date_rdv));
            $csvBody[$k][] = date('Y-m-d', strtotime($dh->created));
            $csvBody[$k][] = "ASC:$dh->code_asc / $patient->prenom $patient->nom ID: $patient->id_technique / $dh->commentaire / Date RDV: $dh->date_rdv";
        }

        self::createCsv(
            ['id', 'id_asc', 'id_technique', 'nom_patient', 'prenom_patient', 'commentaire_dtc', 'date_rdv', 'date_creation', 'print'],
            $csvBody,
            'csv_suivi');

        self::deleteCsvMedia('csv_suivi.csv');
        return self::postCsvMedia('csv_suivi.csv');

    }
    public static function updateAscCsv()
    {
        $csvBody = [];
        foreach (Asc::find() as $k => $asc) {
            $csvBody[$k][] = $asc->code_asc;
            $csvBody[$k][] = $asc->nom;
            $csvBody[$k][] = $asc->prenom;
            $csvBody[$k][] = $asc->telephone;
        }

        self::createCsv(
            ['id_asc', 'nom_asc', 'prenom_asc', 'telephone_asc'],
            $csvBody,
            'liste_asc');

        self::deleteCsvMedia('liste_asc.csv');
        return self::postCsvMedia('liste_asc.csv');

    }
    public static function updatePatientsCsv()
    {
        $csvBody = [];
        foreach (Patients::find() as $k => $patient) {
            $csvBody[$k][] = $k+1;
            $csvBody[$k][] = $patient->id_technique;
            $csvBody[$k][] = $patient->nom;
            $csvBody[$k][] = $patient->prenom;
            $csvBody[$k][] = ($tmp = $patient->getAsc()) ? $tmp->code_asc : "";
        }

        self::createCsv(
            ['id','id_technique','nom_patient','prenom_patient','id_asc'],
            $csvBody,
            'liste_patient');

        self::deleteCsvMedia('liste_patient.csv');
        return self::postCsvMedia('liste_patient.csv');

    }

}
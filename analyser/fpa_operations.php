<?php

function _log($str) {
    // Log to the output
    $log_str = date('d.m.Y H:i:s') . ": {$str}\r\n";
    //echo $log_str;
    // Log to file
    if (($fp = @fopen('upload_log.txt', 'a+')) !== false) {
        @fputs($fp, $log_str);
        @fclose($fp);
    }
}

function SubmitJob($ClientId, $FileNameRealLocation, $FileName, $AssetNumber, $Subtitle, $Extra, $order_ofcom, $order_id) {

    $clientsDir = dirname(__FILE__) . "/../orders/".FillZero($order_id, 6)."/Clients/";
    $outputDir = dirname(__FILE__) . "/../orders/".FillZero($order_id, 6)."/output";

    $proxyhost = isset($_POST['proxyhost']) ? $_POST['proxyhost'] : '';
    $proxyport = isset($_POST['proxyport']) ? $_POST['proxyport'] : '';
    $proxyusername = isset($_POST['proxyusername']) ? $_POST['proxyusername'] : '';
    $proxypassword = isset($_POST['proxypassword']) ? $_POST['proxypassword'] : '';
    $useCURL = isset($_POST['usecurl']) ? $_POST['usecurl'] : '0';
    $client = new nusoap_client("http://hardingtest.com/hfpax/services/WSDL/hfpax", true, $proxyhost, $proxyport, $proxyusername, $proxypassword);
    _log("proxyhost:" . $proxyhost . "proxyusername:" . $proxyusername . "proxypassword:" . $proxypassword . "usecurl:" . $useCURL . "server:" . siteURL());
    _log("New client:" . $client);
    $err = $client->getError();

    _log("client error:" . $client->getError());
    if ($err) {
        return -1;
    }
    $curl = $client->setUseCurl($useCURL);

    _log("client setUseCurl: " . $curl);

    $outputPassedDir = $outputDir . '/';
    $outputFailedDir = $outputDir . '/';

    $params = array(
        'Name' => $FileName,
        'ResultsName' => $FileName,
        'UseJobID' => true,
        'Submitter' => "automat@websystem",
        'Locations' => array(
            'Movie' => $FileNameRealLocation,
            'Failed' => $outputFailedDir,
            'Passed' => $outputPassedDir,
            'Csv' => $clientsDir . 'csv/',
            'PapPar' => $clientsDir . 'par/',
            'Pdf' => $clientsDir . 'pdf/',
            'Xml' => $clientsDir . 'xml/'
        ),

        'AssetNumber' => $AssetNumber,
        'Subtitle' => $Subtitle,
        'Extra' => $Extra,
        'AnaysisStandard' => $order_ofcom,

    );
    
    


    $result = $client->call('SubmitJob', $params, 'http://hardingtest.com/hfpax/', 'http://hardingtest.com/hfpax/');
//    echo '<pre>';
//    print_r(siteURL() . '../hfpax/');
    _log("client WSDL api call:" . $result);
    _log("order_ofcom:" . $order_ofcom);
    if ($client->fault) {
        return -1;
    } else {
        $err = $client->getError();

        _log(" client WSDL Error msg:" . $err);
        _log("client WSDL result:" . $result);

        if ($err)
            return -1;
        else
            return $result;
    }
}

?>

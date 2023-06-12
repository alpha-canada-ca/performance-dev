<?php

try {
    $d = json_decode(file_get_contents('php://input'));

    $data = include ('data.php');

    $url = $d->oUrl;
    $range = $d->oRange;
    $endDate = $d->oEndDate;
    $date = $d->dates;
    $type = $d->type;
    $field =  $d->field;
    $start = $date[0];
    $end = $date[1];
    $lang = $d->lang;

    $mode = (empty($_REQUEST["mode"])) ? "update" : $_REQUEST["mode"];

    $today = new DateTime("today");
    $today = $today->modify('-' . $endDate . ' day');
    // $today = $today->modify('-3 day');
    // modify $today by subtracting the $range value
    // $today = $today->modify('-' . $range . ' day');

    if ($field == "aa") {
        $iso = 'Y-m-d\TH:i:s.v';
        $end = $today->format($iso);
    } else {
        $iso = 'Y-m-d';
        $end = (new DateTime($end))->format($iso);
    }
    $start = (new DateTime($start))->format($iso);    

    /*$yesterday = $today->modify('-6 day')
    ->format($iso);
    $week = $today->modify('-6 day')
    ->format($iso);
    $month = $today->modify('-6 day')
    ->format($iso);*/

    $yesterday = $today->modify('-' . $range . ' day')
        ->format($iso);
    $week = $today->modify('-' . $range . ' day')
        ->format($iso);
    $month = $today->modify('-' . $range . ' day')
        ->format($iso);

    $dates2 = [$month, $week, $yesterday];

    $dates = [$start];

    if ((isset($url) && !empty($url))) {

        require_once('mongodb_get.php');
        require_once('mongodb_delete.php');
        $origUrl = $url;

        if (substr($url, 0, 8) == "https://") {
            $url = substr($url, 8, strlen($url));
        }
        $pUrl = substr($url, 0, 255 - 8);

        $url = substr($url, -255);
        $oUrl = $url;

        if ($url == "www.canada.ca") {
            $oUrl = "www.canada.ca/home.html";
            $pUrl = "www.canada.ca/home.html";
        }

        foreach ($dates as $key => $start) {
            if ($field == "aa") {
                $oDate = "$start/$end";
            } else {
                $oDate = "$start/$end";
            }

            if ( $type == "activityMap" || $type == "metrics-new" || $type == "srchAll" || $type == "refType" || $type == "snmAll" || $type == "srchLeftAll" || $type == "fwylf" || $type == "prvs" || $type == "trnd") {
                $oDate = $dates2[0] . "/" . $end;
                $sm = "multi";
            } else {
                $sm = "single";
            }
            if ($mode == "update") {
                if ($field == "aa") {
                    $md = mongoGet($oUrl, $oDate, $type, $sm, "search", $lang);
                } else {
                    $md = mongoGet($origUrl, $oDate, $type, "multi", "search","bi");
                }
                if ($md) {
                    //$md = json_encode( array('bypol' => 'fgfg', 'errorCode' => 'yaaaaydfgy' ));
                    $oMd = $md;
                    $md = json_decode( $md, true );

                    if ( array_key_exists('error', $md) ) {
                        $error = 1;
                        $md = json_encode( array('error' => $md['error'], 'message' => $md['message'] ));
                    } else if ( array_key_exists('errorCode', $md) ) {
                        $error = 1;
                        $md = json_encode( array('error' => ( $md['errorId'] . ' - ' . $md['errorCode'] ), 'message' => $md['errorDescription'] ));
                    } else if ( array_key_exists('error_code', $md) ) {
                        $error = 1;
                        $md = json_encode( array('error' => $md['error_code'], 'message' => $md['message'] ));
                    } else {
                        $error = 0;
                        $md = json_encode( $md );
                    }

                    // TODO:
                    // Create a function to run the api call from another service (one up)
                    // Parameters needed, such as the type (call), url, date, lang
                    // include api_post.php
                    // i.e. $result = api_post($config[0]['ADOBE_API_KEY'], $config[0]['COMPANY_ID'], $_SESSION['token'], $api);
                    
                    if ( $error ) {
                        if ($field == "aa") {
                            mongoDelete($oUrl, "search", $type, $oMd, $sm, $oDate, $lang);
                        } else {
                            mongoDelete($oUrl, "search", $type, $oMd, "multi", $oDate, "bi");
                        }
                    }

                    echo $md;
                    return;
                } else {
                    echo json_encode(array('error' => "No data", 'message' => 'Could not fetch data from database'));
                    return;
                }
            }
        }
    }
}
catch(Exception $ex) {
    echo json_encode(array('error' => $ex));
}

?>
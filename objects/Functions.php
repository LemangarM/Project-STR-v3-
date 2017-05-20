<?php

class functions
{

    public function createJsonFileBrometer($data)
    {
        $fp = fopen('./tables/barometerData.json', 'w');
        fwrite($fp, json_encode($data));
        fclose($fp);

        return $fp;
    }

    public function createJsonFileReview($data)
    {
        $fp = fopen('./tables/reviewData.json', 'w');
        fwrite($fp, json_encode($data));
        fclose($fp);
        return $fp;
    }

    public function checkDateTime($data)
    {
        if (date('Y-m-d', strtotime($data)) == $data) {
            return true;
        } else {
            return false;
        }
    }

}

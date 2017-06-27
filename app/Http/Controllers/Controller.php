<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Aloha\Twilio\Twilio;
use Billplz\Client;
use Http\Client\Common\HttpMethodsClient;
use Http\Adapter\Guzzle6\Client as GuzzleHttpClient;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use App\Jobs\SendRequestPaymentNotification;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function sendSMS($mobile_number, $message)
    {
        $sid = "AC82d41c29d48f8b91b58ba4796b9c7813";
        $token = "42646e4d02734a30f00d62e342cead1d";
        $twilioPhoneNumber = "+601117223168";

        $client = new Twilio($sid, $token, $twilioPhoneNumber);
        $message = $client->message($mobile_number, $message);

        return $message;
    }

    protected function createBillPlz()
    {
        $billplz = Client::make('6afaaa17-1425-43be-9f27-bcd258aaab4b');
        //$billplz->useVersion('v3');
        $billplz->useSandbox();

        return $billplz;
    }

    protected function getExtension($fileName)
    {
        $arr = explode('.', $fileName);
        $extension = array_last($arr);

        return $extension;
    }

    protected function hasExtension($fileName)
    {
        $arr = explode('.', $fileName);
        if (sizeof($arr) > 1) {
            return true;
        }

        return false;
    }

    protected function getExtensionType($fileExtension)
    {
        if ($fileExtension == "pdf" || $fileExtension == "PDF") {
            return "pdf";
        } else if ($fileExtension == "doc" || $fileExtension == "docx") {
            return "word";
        }

        return "word";

    }

    /**
     * Format bytes to kb, mb, gb, tb
     *
     * @param  integer $size
     * @param  integer $precision
     * @return integer
     */
    public function formatBytes($size, $precision = 0)
    {
        if ($size > 0) {
            $size = (int) $size;
            $base = log($size) / log(1024);
            $suffixes = array(' bytes', ' KB', ' MB', ' GB', ' TB');

            return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
        } else {
            return $size;
        }
    }


    /**
     * Send Queueing Email
     */
    public function sendPaymentRequest()
    {
        $job = (new SendRequestPaymentNotification());
        dispatch($job);
    }

}

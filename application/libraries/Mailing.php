<?php

/*
 * Project: Cleanbox
 * Document: Mailing
 * 
 * @author Manevi Matias Alejandro 
 * Informatic Engineer - Web Developer
 * 
 * Contact: manevimatias@gmail.com
 * 
 * All rights reserved ®
 */

defined('BASEPATH') OR exit('No direct script access allowed');

require_once("./vendor/dompdf/dompdf/autoload.inc.php");

use Dompdf\Dompdf;

class Mailing {

    public static function send($params) {
       try {
                
            $email = new Mailer(true);

            $email->From = 'noreply@cleanbox.com';
            $email->FromName = 'Cleanbox';
            $email->Subject = $params['subject'];
            $email->Body = $params['body'];
            $email->IsHTML($params['is_html']);
            $email->AddAddress($params['address']);
            if(isset($params['report_root']) && isset($params['report_file_name'])){
                $email->AddAttachment($params['report_root'], $params['report_file_name']);
            }
            return $email->Send();
        } catch (Exception $e) {
            print_r('email->ErrorInfo: '.$email->ErrorInfo);
            print_r('e: '.$e);
        }

    }

    public static function generateAttachPdf($html) {
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        return $dompdf->output();
    }

    /**
     * https://selectpdf.com/web-html-to-pdf-rest-api-for-php-samples/
     * @param  [type] $html [description]
     * @return [type]       [description]
     */
    public static function generateAttachPdf4($html){
        $api_endpoint = "http://selectpdf.com/api2/convert/";
        $key = '28e18878-c99c-42c8-a9b2-903469160ab4';

        $parameters = [
            'key' => $key,
            'html' => $html,
            'use_css_print' => true
        ];

        // for options use key 'http' even if you send the request to https://...
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($parameters),
            ),
        );

        $context  = stream_context_create($options);
        $result = @file_get_contents($api_endpoint, false, $context);

        if (!$result) { 
            echo "HTTP Response: " . $http_response_header[0] . "<br/>";

            $error = error_get_last();
            return false;
        } else {

            return $result;
        }
    }

}
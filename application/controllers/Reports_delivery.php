<?php

/*
 * Project: Cleanbox
 * Document: Reports_delivery
 * 
 * @author Manevi Matias Alejandro 
 * Informatic Engineer - Web Developer
 * 
 * Contact: manevimatias@gmail.com
 * 
 * All rights reserved ®
 */

class Reports_delivery extends CI_Controller {

    public function index() {
        $month = General::getStringMonth(date('m')) . ' ' . date('Y');
        $month = 'Febrero 2017';
        $this->sendAccountsBalanceReport($month);
        $this->sendHonoraryDuesReport($month);
    }

    public function sendAccountsBalanceReport($month) {

        if(General::isLastDayInMonth() && !Report::wasDelivered('accounts_balance_report', $month)) {
            $html = Report::buildAccountsBalanceReport($month);

            $pdf_report = Mailing::generateAttachPdf($html);

            $report_file_name = "Reporte mensual de saldos " . $month . ".pdf";

            $report_root = "documents/reports/" . $report_file_name;

            file_put_contents($report_root, $pdf_report);

            $status = Mailing::send(array(
                'subject' => "Reporte mensual de saldos: " . $month,
                'body' => 'Cleanbox le envia el reporte mensual de balances. No responda este email',
                'report_root' => $report_root,
                'report_file_name' => $report_file_name,
                'is_html' => false,
                'address' => User::getBussinesEmail()
            ));

            if ($status) {
                Report::saveDelivery('accounts_balance_report', $month);
            }
        }
    }

    public function sendHonoraryDuesReport($month) {

        if(General::isLastDayInMonth() && !Report::wasDelivered('honorary_payments_report', $month)) {
            $html = Report::buildHonoraryPaymentsReport();

            $pdf_report = Mailing::generateAttachPdf($html);
            
            $report_file_name = "Reporte de Pago de Honorarios " . $month . ".pdf";
            $report_root = "documents/reports/" . $report_file_name;

            file_put_contents($report_root, $pdf_report);

            $status = Mailing::send(array(
                'subject' => 'Reporte Pago de Honorarios',
                'body' => 'Cleanbox le envia el Reporte de Pago de Honorarios. Por favor, no responda este email',
                'report_root' => $report_root,
                'report_file_name' => $report_file_name,
                'is_html' => false,
                'address' => User::getBussinesEmail()
            ));

            if ($status) {
                Report::saveDelivery('honorary_payments_report', $month);
            }
        }
    }

    public function emailReceiveRenter() {
        $response['status'] = false;

        $credits_info = json_decode(stripslashes(get_cookie('credits_receive')), true);

        if($credits_info){

            $renter = General::getRenterClientByCredit($credits_info['credits'][0]);

            if($renter && strlen($renter['client_email']) > 0){ 

                $report_file_name = "Recibo_".$renter['client_id'].".jpeg";

                $report_root = "documents/" . $report_file_name;

                $image = @file_put_contents($report_root, base64_decode(explode(",", $_POST['data'])[1]));

                if($image){

                    $response['status'] = Mailing::send(array(
                        'subject' => "Recibo pago alquiler | Inmobiliaria " . User::getBussinesName(),
                        'body' => 'Hola!, recientemente usted pago su Alquiler, aqui le enviamos el recibo en formato digital, muchas gracias!. No responda este email',
                        'report_root' => $report_root,
                        'report_file_name' => $report_file_name,
                        'is_html' => false,
                        'address' => $renter['client_email']
                    )); 

                    if($response['status']){
                        unlink($report_root);
                    }
                }
            }
        }

        echo json_encode($response);
    } 


}

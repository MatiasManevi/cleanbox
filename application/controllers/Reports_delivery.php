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
        $reports_config = $this->basic->get_all('reports_config')->result_array();

        ini_set('memory_limit', '256M');

        foreach ($reports_config as $report_config) {
            if($report_config['frequency'] != 'no_send'){
                switch ($report_config['report_name']) {
                    case 'Reporte mensual de balances':
                        $month = General::getStringMonth(date('m')) . ' ' . date('Y');
                        $this->sendAccountsBalanceReport($month);
                        break;
                    case 'Reporte Pago de Honorarios':
                        $month = General::getStringMonth(date('m')) . ' ' . date('Y');
                        $this->sendHonoraryDuesReport($month);
                        break;
                    case 'Reporte de Cuentas Corrientes especificas':
                        $month = General::getStringMonth(date('m')) . ' ' . date('Y');
                        $this->sendCurrentAccountsReport($month, unserialize($report_config['data']));
                        break;    
                }
            }    
        }
    }

    public function sendCurrentAccountsReport($month, $current_accounts) {
        $month_string = trim(preg_replace("/[^^A-Za-z (),.]/", "", $month));
        $month_number = General::getMonthNumber($month_string);
        $year = trim(preg_replace("/[^0-9 (),.]/", "", $month));

        $from = '01-'.$month_number.'-'.$year;
        $to = '31-'.$month_number.'-'.$year;

        foreach ($current_accounts as $current_account) {
            $account = $this->basic->get_where('cuentas_corrientes', array('cc_id' => $current_account))->row_array();
        
            if($account && General::isLastDayInMonth() && !Report::wasDelivered('accounts_balance_report_'.$current_account, $month)) {
            
                $html = Report::buildAccountReport($from, $to, $account);

                $pdf_report = Mailing::generateAttachPdf($html);

                $report_file_name = "Reporte cuenta de  ". $account['cc_prop']. " " . $month . ".pdf";

                $report_root = "documents/reports/" . $report_file_name;

                file_put_contents($report_root, $pdf_report);

                $status = Mailing::send(array(
                    'subject' => "Reporte cuenta de  ". $account['cc_prop']. " " . $month,
                    'body' => 'Cleanbox le envia un reporte automatico. No responda este email',
                    'report_root' => $report_root,
                    'report_file_name' => $report_file_name,
                    'is_html' => false,
                    'address' => User::getReportsEmail()
                ));

                if ($status) {
                    Report::saveDelivery('accounts_balance_report_'.$current_account, $month);
                }
            }
        }
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

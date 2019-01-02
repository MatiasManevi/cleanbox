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
        $reports_email = User::getReportsEmail();

        $response = array();

        if (filter_var($reports_email, FILTER_VALIDATE_EMAIL)) {
 
            $reports_config = $this->basic->get_all('reports_config')->result_array();

            ini_set('memory_limit', '256M');
            
            foreach ($reports_config as $report_config) {

                if($report_config['frequency'] != 'no_send'){
                    switch ($report_config['report_name']) {
                        case 'Reporte mensual de balances':
                            $month = General::getStringMonth(date('m')) . ' ' . date('Y');
                            $res = $this->sendAccountsBalanceReport($reports_email, $month);
                            array_push($response, $res);
                            break;
                        case 'Reporte Pago de Honorarios':
                            $month = General::getStringMonth(date('m')) . ' ' . date('Y');
                            $res = $this->sendHonoraryDuesReport($reports_email, $month);
                            array_push($response, $res);
                            break;
                        case 'Reporte de Cuentas Corrientes especificas':
                            $month = General::getStringMonth(date('m')) . ' ' . date('Y');
                            $res = $this->sendCurrentAccountsReport($reports_email, $month, unserialize($report_config['data']));
                            array_push($response, $res);
                            break;    
                    }
                }    
            }
        }

        echo json_encode($response);
    }

    public function sendCurrentAccountsReport($reports_email, $month, $current_accounts) {
        $month_string = trim(preg_replace("/[^^A-Za-z (),.]/", "", $month));
        $month_number = General::getMonthNumber($month_string);
        $year = trim(preg_replace("/[^0-9 (),.]/", "", $month));

        $from = '01-'.$month_number.'-'.$year;
        $to = '31-'.$month_number.'-'.$year;

        $response = array();

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
                    'body' => 'No responda este email. Gracias',
                    'report_root' => $report_root,
                    'report_file_name' => $report_file_name,
                    'is_html' => false,
                    'address' => $reports_email
                ));

                if ($status) {
                    Report::saveDelivery('accounts_balance_report_'.$current_account, $month);
                }

                array_push($response, array(
                    'report' => $report_file_name,
                    'status' => $status
                ));
            }
        }

        return $response;
    }

    public function sendAccountsBalanceReport($reports_email, $month) {
        $response = array();

        if(General::isLastDayInMonth() && !Report::wasDelivered('accounts_balance_report', $month)) {
            $html = Report::buildAccountsBalanceReport($month);

            $pdf_report = Mailing::generateAttachPdf($html);

            $report_file_name = "Reporte mensual de saldos " . $month . ".pdf";

            $report_root = "documents/reports/" . $report_file_name;

            file_put_contents($report_root, $pdf_report);

            $status = Mailing::send(array(
                'subject' => "Reporte mensual de saldos: " . $month,
                'body' => 'Le enviamos el reporte mensual de balances. No responda este email',
                'report_root' => $report_root,
                'report_file_name' => $report_file_name,
                'is_html' => false,
                'address' => $reports_email
            ));

            if ($status) {
                Report::saveDelivery('accounts_balance_report', $month);
            }

            $response = array(
                'report' => $report_file_name,
                'status' => $status
            );
        }

        return $response;
    }

    public function sendHonoraryDuesReport($reports_email, $month) {
        $response = array();

        if(General::isLastDayInMonth() && !Report::wasDelivered('honorary_payments_report', $month)) {
            $html = Report::buildHonoraryPaymentsReport();

            $pdf_report = Mailing::generateAttachPdf($html);
            
            $report_file_name = "Reporte de Pago de Honorarios " . $month . ".pdf";
            $report_root = "documents/reports/" . $report_file_name;

            file_put_contents($report_root, $pdf_report);

            $status = Mailing::send(array(
                'subject' => 'Reporte Pago de Honorarios',
                'body' => 'Le enviamos el Reporte de Pago de Honorarios. Por favor, no responda este email',
                'report_root' => $report_root,
                'report_file_name' => $report_file_name,
                'is_html' => false,
                'address' => $reports_email
            ));

            if ($status) {
                Report::saveDelivery('honorary_payments_report', $month);
            }

            $response = array(
                'report' => $report_file_name,
                'status' => $status
            );

        }

        return $response;
    }

    public function emailReceiveRenter() {
        $response['status'] = false;

        $credits_info = json_decode(stripslashes(get_cookie('credits_receive')), true);

        if($credits_info){
            $transaction_id = $credits_info['transaction_id'];
            $credits = $this->basic->get_where('creditos', array('trans' => $transaction_id))->result_array();

            foreach ($credits as $credit) {
                if($credit['cred_concepto'] != "Gestion de Cobro" && $credit['cred_concepto'] != "Gestion de Cobro Sobre Intereses"){
                    $renter = General::getRenterClientByCredit($credit);
                    break;
                }
            }

            if($renter && strlen($renter['client_email']) > 0 && filter_var($renter['client_email'], FILTER_VALIDATE_EMAIL)){ 

                $report_file_name = "Recibo_".$renter['client_id'].".jpeg";

                $report_root = "documents/" . $report_file_name;

                $image = @file_put_contents($report_root, base64_decode(explode(",", $_POST['data'])[1]));

                if($image){
                    $response['status'] = Mailing::send(array(
                        'subject' => "Recibo pago ".$credits_info['credits'][0]['cred_concepto']." | Inmobiliaria " . User::getBussinesName(),
                        'body' => 'Hola! '.$renter['client_name'].', recientemente usted pago su '.$credits_info['credits'][0]['cred_concepto'].', le adjuntamos el recibo en formato digital, gracias por ayudarnos a proteger el medio ambiente!',
                        'report_root' => $report_root,
                        'report_file_name' => $report_file_name,
                        'is_html' => false,
                        'address' => $renter['client_email']
                    )); 

                    unlink($report_root);
                }
            }
        }

        echo json_encode($response);
    } 


}

<?php

/*
 * Project: Cleanbox
 * Document: Transfers
 * 
 * @author Manevi Matias Alejandro 
 * Informatic Engineer - Web Developer
 * 
 * Contact: manevimatias@gmail.com
 * 
 * All rights reserved ®
 */


class Transfers extends CI_Controller {

    public function index() {
        $this->data['transfers_to_cash'] = $this->basic->get_where('debitos', array('is_transfer' => 1), 'deb_id', 'desc')->result_array();
        $this->data['transfers_to_safe'] = $this->basic->get_where('creditos', array('is_transfer' => 1), 'cred_id', 'desc')->result_array();
        
        $this->data['content'] = $this->load->view('transfers/transfers', $this->data, TRUE);

        $this->load->view('layout', $this->data);
    }

    public function transferToCash() {
        $response = array();

        try {
            $amount = $this->input->post('amount');

            if ($amount) {
                $safe_box = $this->basic->get_where('cuentas_corrientes', array('cc_prop' => 'CAJA FUERTE'))->row_array();
                $current_monthly_cash = $this->basic->get_where('mensuales', array('men_mes' => date('m'), 'men_ano' => date('Y')))->row_array();

                if ($amount <= $safe_box['cc_saldo']) {

                    $current_monthly_cash['men_creditos'] += $amount;
                    $safe_box['cc_saldo'] -= $amount;
                    
                    $debit = array(
                        'deb_cc' => $safe_box['cc_prop'],
                        'cc_id' => $safe_box['cc_id'],
                        'deb_concepto' => 'Transferencia a CAJA FISICA',
                        'deb_monto' => $amount,
                        'deb_mes' => General::getStringMonth(date('m')) . ' ' . Date('Y'),
                        'deb_fecha' => Date('d-m-Y'),
                        'is_transfer' => 1,
                        'trans' => Transaction::getLastTransactionId()
                    );

                    Transaction::incrementTransactionId();
                    
                    $this->basic->save('debitos', 'deb_id', $debit);
                    $this->basic->save('cuentas_corrientes', 'cc_id', $safe_box);
                    $this->basic->save('mensuales', 'men_id', $current_monthly_cash);

                    $response['status'] = true;
                    $response['success'] = 'Transferencia realizada!!';
                    $response['cash'] = Cash::getBalance('Caja');
                    $response['safebox'] = $safe_box['cc_saldo'];
                } else {
                    $response['status'] = false;
                    $response['error'] = 'El monto a transferir no puede superar la caja fuerte';
                }
            } else {
                $response['status'] = false;
                $response['error'] = 'No ingresaste ningun valor!';
            }
        } catch (Exception $exc) {
            $response['status'] = false;
            $response['error'] = 'Ups! Ocurrio un error, intente nuevamente por favor. Detalle: ' . $exc->getMessage();
        }

        echo json_encode($response);
    }

    public function transferToSafeBox() {
        $response = array();

        try {
            $amount = $this->input->post('amount');
            if ($amount) {
                $safe_box = $this->basic->get_where('cuentas_corrientes', array('cc_prop' => 'CAJA FUERTE'))->row_array();
                $current_monthly_cash = $this->basic->get_where('mensuales', array('men_mes' => date('m'), 'men_ano' => date('Y')))->row_array();

                $actual_balance = Cash::getBalance('Caja');

                if ($amount <= $actual_balance) {
                    $current_monthly_cash['men_creditos'] -= $amount;
                    $safe_box['cc_saldo'] += $amount;

                    $credit = array(
                        'cred_depositante' => 'CAJA FISICA',
                        'cred_cc' => $safe_box['cc_prop'],
                        'cc_id' => $safe_box['cc_id'],
                        'cred_mes_alq' => General::getStringMonth(date('m')) . ' ' . Date('Y'),
                        'cred_fecha' => Date('d-m-Y'),
                        'cred_concepto' => 'Transferencia a CAJA FUERTE',
                        'cred_monto' => $amount,
                        'is_transfer' => 1,
                        'trans' => Transaction::getLastTransactionId()
                    );

                    Transaction::incrementTransactionId();
                    
                    $this->basic->save('creditos', 'cred_id', $credit);
                    $this->basic->save('cuentas_corrientes', 'cc_id', $safe_box);
                    $this->basic->save('mensuales', 'men_id', $current_monthly_cash);

                    $response['status'] = true;
                    $response['success'] = 'Transferencia realizada!!';
                    $response['cash'] = Cash::getBalance('Caja');
                    $response['safebox'] = $safe_box['cc_saldo'];
                } else {
                    $response['status'] = false;
                    $response['error'] = 'El monto a transferir no puede superar el balance actual de la caja';
                }
            } else {
                $response['status'] = false;
                $response['error'] = 'No ingresaste ningun valor!';
            }
        } catch (Exception $exc) {
            $response['status'] = false;
            $response['error'] = 'Ups! Ocurrio un error, intente nuevamente por favor. Detalle: ' . $exc->getMessage();
        }

        echo json_encode($response);
    }

}

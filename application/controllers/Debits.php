<?php

/*
 * Project: Cleanbox
 * Document: Debits
 * 
 * @author Manevi Matias Alejandro 
 * Informatic Engineer - Web Developer
 * 
 * Contact: manevimatias@gmail.com
 * 
 * All rights reserved ®
 */


class Debits extends CI_Controller {

    public function index() {
        $this->data['particular_head'] = $this->load->view('particular_heads/transaction', '', TRUE);

        $this->data['debits'] = $this->basic->get_where('debitos', array('is_transfer' => 0), 'deb_id', 'desc', '30')->result_array();
        $this->data['row_count'] = count($this->data['debits']);
        $this->data['list'] = $this->load->view('debits/list', $this->data, TRUE);
        $this->data['content'] = $this->load->view('debits/debits', $this->data, TRUE);

        $this->load->view('layout', $this->data);
    }

    public function save() {
        $response = array();

        try {
            $response['debits'] = array();
            $acumulated_impacted_debits = array();

            $debits = $this->input->post('debits') ? $this->input->post('debits') : false;

            if ($debits) {
                // si no tiene fondos no podra hacer el debito
                // pero para aquellos que empiezan con cajas en cero si
                // ya que les imposibilitara hacer debitos cuando recien arrancan el mes
                if (Cash::sufficientFounds($debits) || User::beginCashZero()) {
                    $transaction_id = Transaction::getLastTransactionId();

                    foreach ($debits as $debit) {
                        $debit['trans'] = $transaction_id;
                        $debit['deb_fecha'] = date('d-m-Y');
                        $debit = Transaction::impactDebit($debit);
                        array_push($acumulated_impacted_debits, $debit);
                    }

                    foreach ($acumulated_impacted_debits as $debit) {
                        array_push($response['debits'], General::parseEntityForList($debit, 'debitos'));
                    }

                    // Se aumenta el id de transaccion
                    Transaction::incrementTransactionId();

                    $response['status'] = true;
                    $response['transaction_id'] = $transaction_id;
                    $response['print_report'] = $response['keep_loading'] = User::printDebit();
                    $response['table'] = array(
                        'table' => 'debitos',
                        'table_pk' => 'deb_id',
                        'entity_name' => 'debito',
                    );
                    $response['success'] = 'El debito fue guardado!';
                } else {
                    $response['status'] = false;
                    $response['error'] = 'No tienes los fondos suficientes en tu caja fisica para realizar estos debitos';
                }
            } else {
                $response['status'] = false;
                $response['error'] = 'No se han creado debitos para ser guardados';
            }
        } catch (Exception $exc) {
            $response['status'] = false;
            $response['error'] = 'Ups! Ocurrio un error, intente nuevamente por favor. Detalle: ' . $exc->getMessage();
        }

        echo json_encode($response);
    }

    public function printDebitReceive(){
        $transaction_id = json_decode(stripslashes(get_cookie('debits_receive')), true);

        if ($transaction_id) {
            delete_cookie('debits_receive');
            $this->printDebitReceiveList($transaction_id);
        } else {
            // Error no existe la info.
            redirect(site_url('debits'));
        }
    }

    public function printDebitReceiveList($transaction_id) {
        $debits = $this->basic->get_where('debitos', array('trans' => $transaction_id))->result_array();
        
        if ($debits[0]['cc_id']) {
            $account = $this->basic->get_where('cuentas_corrientes', array('cc_id' => $debits[0]['cc_id']))->row_array();
        } else {
            $account = $this->basic->get_where('cuentas_corrientes', array('cc_prop' => $debits[0]['deb_cc']))->row_array();
        }

        $receive_elements = array(
            'debits' => $debits,
            'total' => 0,
            'account' => $account
        );

        $this->data['receives'] = Transaction::parseForDebitReceives($receive_elements);
        $this->data['settings'] = User::getUserSettings();

        $this->data['content'] = $this->load->view('reports/debit_receive', $this->data, TRUE);

        $this->load->view('layout', $this->data);
    }

    public function showDebitReportList($id) {
        $rendition = $this->basic->get_where('debitos', array('deb_id' => $id))->row_array();

        $rendition_date = explode('-', $rendition['deb_fecha']);
        $from = '01-' . $rendition_date[1] . '-' . $rendition_date[2];
        $to = $rendition_date[0] . '-' . $rendition_date[1] . '-' . $rendition_date[2];

        $account = $this->basic->get_where('cuentas_corrientes', array('cc_id' => $rendition['cc_id']))->row_array();
        if (empty($account)) {
            $account = $this->basic->get_where('cuentas_corrientes', array('cc_prop' => $rendition['deb_cc']))->row_array();
        }
        
        $report = Report::buildAccountReport($from, $to, $account);
        
        $this->data['bussines_name'] = User::getBussinesName();
        
        $this->data['content'] = $this->load->view('reports/account_report', $report, TRUE);

        $this->load->view('layout',  $this->data);
    }

}

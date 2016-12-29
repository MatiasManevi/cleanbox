<?php

/*
 * Project: Cleanbox
 * Document: Migrations
 * 
 * @author Manevi Matias Alejandro 
 * Informatic Engineer - Web Developer
 * 
 * Contact: manevimatias@gmail.com
 * 
 * All rights reserved ®
 */


class Migrations extends CI_Controller {

    public function index() {
        $this->data['particular_head'] = $this->load->view('particular_heads/transaction', '', TRUE);

        $this->data['content'] = $this->load->view('migrations/migrations', '', TRUE);
        
        $this->load->view('layout', $this->data);
    }

    public function save() {
        $response = array();

        try {
            $this->form_validation->set_rules('cc_from_id', 'Cuenta Origen', "required");
            $this->form_validation->set_rules('cc_to_id', 'Cuenta Destino', "required");
            $this->form_validation->set_rules('concept', 'Concepto', "required");
            $this->form_validation->set_rules('amount', 'Monto', "required");
            $this->form_validation->set_rules('month', 'Mes', "required");

            if ($this->form_validation->run()) {

                if ($this->input->post('concept') != 'Rendicion') {
                    if (Transaction::conceptExistsInAndOut($this->input->post('concept'))) {
                        $transaction_id = Transaction::getLastTransactionId();

                        $cc_from = $this->basic->get_where('cuentas_corrientes', array('cc_id' => $this->input->post('cc_from_id')))->row_array();
                        $cc_to = $this->basic->get_where('cuentas_corrientes', array('cc_id' => $this->input->post('cc_to_id')))->row_array();

                        $depositante_client = $this->basic->get_where('clientes', array('client_id' => $cc_from['client_id']))->row_array();
                        if (empty($depositante_client)) {
                            $depositante_client = $this->basic->get_where('clientes', array('client_name' => $this->input->post('cc_from')))->row_array();
                        }

                        $credit = array(
                            'cred_depositante' => $this->input->post('cc_from'),
                            'cred_cc' => $this->input->post('cc_to'),
                            'cc_id' => $this->input->post('cc_to_id'),
                            'client_id' => $depositante_client['client_id'],
                            'cred_forma' => $this->input->post('forma'),
                            'cred_tipo_trans' => $this->input->post('tipo_trans'),
                            'cred_banco' => $this->input->post('banco'),
                            'cred_nro_cheque' => $this->input->post('nro_cheque'),
                            'cred_mes_alq' => $this->input->post('month'),
                            'cred_concepto' => $this->input->post('concept'),
                            'cred_domicilio' => $this->input->post('address'),
                            'cred_monto' => $this->input->post('amount'),
                            'cred_fecha' => Date('d-m-Y'),
                            'cred_interes' => '',
                            'trans' => $transaction_id
                        );

                        $debit = array(
                            'deb_tipo_trans' => $this->input->post('tipo_trans'),
                            'cc_id' => $this->input->post('cc_from_id'),
                            'deb_cc' => $this->input->post('cc_from'),
                            'deb_mes' => $this->input->post('month'),
                            'deb_concepto' => $this->input->post('concept'),
                            'deb_domicilio' => $this->input->post('address'),
                            'deb_monto' => $this->input->post('amount'),
                            'deb_fecha' => Date('d-m-Y'),
                            'trans' => $transaction_id
                        );

                        $account_type = General::getAccountType($credit, 'Entrada', 'cred_concepto');

                        if ($account_type) {

                            if ($cc_to['cc_id'] != $cc_from['cc_id']) {
                                // acreditamos la cuenta destino
                                $cc_to[$account_type] += $credit['cred_monto'];

                                // Vemos antes de debitarle si sera necesario un prestamo
                                Transaction::createLoan($debit, $account_type, $cc_from);

                                // debitamos la cuenta origen
                                $cc_from[$account_type] -= $credit['cred_monto'];

                                $this->basic->save('creditos', 'cred_id', $credit);
                                $this->basic->save('debitos', 'deb_id', $debit);

                                $this->basic->save('cuentas_corrientes', 'cc_id', $cc_from);
                                $this->basic->save('cuentas_corrientes', 'cc_id', $cc_to);

                                Transaction::incrementTransactionId();

                                $response['status'] = true;
                                $response['table'] = array(
                                    'table' => 'migrar',
                                );
                                $response['success'] = 'La migracion fue efectuada exitosamente!!';
                            } else {
                                $response['status'] = false;
                                $response['error'] = 'No puedes crear una migracion con la misma cuenta!';
                            }
                        }
                    } else {
                        $response['status'] = false;
                        $response['error'] = 'Ups! El concepto debe existir tanto de Entrada como de Salida';
                    }
                } else {
                    $response['status'] = false;
                    $response['error'] = 'Ups! No pueden hacerse migraciones de Rendiciones';
                }
            } else {
                $response['status'] = false;
                $response['error'] = validation_errors();
            }
        } catch (Exception $exc) {
            $response['status'] = false;
            $response['error'] = 'Ups! Ocurrio un error, intente nuevamente por favor. Detalle: ' . $exc->getMessage();
        }

        echo json_encode($response);
    }

}

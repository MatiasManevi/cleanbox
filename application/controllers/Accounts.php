<?php

/*
 * Project: Cleanbox
 * Document: Accounts
 * 
 * @author Manevi Matias Alejandro 
 * Informatic Engineer - Web Developer
 * 
 * Contact: manevimatias@gmail.com
 * 
 * All rights reserved ®
 */

class Accounts extends CI_Controller {

    public function index() {
        $this->data['particular_head'] = $this->load->view('particular_heads/propietary', '', TRUE);

        $this->data['accounts'] = $this->basic->get_where('cuentas_corrientes', array(), 'cc_prop', '', '30')->result_array();
        $this->data['row_count'] = count($this->data['accounts']);
        $this->data['list'] = $this->load->view('accounts/list', $this->data, TRUE);
        $this->data['content'] = $this->load->view('accounts/accounts', $this->data, TRUE);

        $this->load->view('layout', $this->data);
    }

    public function save() {
        $response = array();

        try {
            $this->form_validation->set_rules('cc_prop', 'Propietario', "required|trim");
            if (!$this->input->post('cc_id')) {
                $this->form_validation->set_rules('cc_prop', 'Cuenta', "callback_noExistsAccount");
            }

            if ($this->form_validation->run()) {
                $new_account = array_map("strtoupper", $this->input->post());
                if (!$this->input->post('cc_id')) {
                    $new_account['cc_date_created'] = date('d-m-Y');
                }

                $account = $this->basic->get_where('cuentas_corrientes', array('cc_id' => $this->input->post('cc_id')))->row_array();

                $new_account['client_id'] = General::impactEditAccount($account, $new_account['cc_prop']);

                $new_account['cc_id'] = $this->basic->save('cuentas_corrientes', 'cc_id', $new_account);

                $response['status'] = true;
                $response['entity'] = General::parseEntityForList($new_account, 'cuentas_corrientes');
                $response['table'] = array(
                    'table' => 'cuentas_corrientes',
                    'table_pk' => 'cc_id',
                    'entity_name' => 'cuenta corriente',
                );
                $response['success'] = 'La cuenta corriente fue guardada!';
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

    public function noExistsAccount($account_name) {
        $account = $this->basic->get_where('cuentas_corrientes', array('cc_prop' => $account_name))->row();

        if ($account) {
            $this->form_validation->set_message('noExistsAccount', 'La %s ya existe en el sistema, no pueden existir dos iguales');
            return false;
        } else {
            return true;
        }
    }

}

<?php

/*
 * Project: Cleanbox
 * Document: Clients
 * 
 * @author Manevi Matias Alejandro 
 * Informatic Engineer - Web Developer
 * 
 * Contact: manevimatias@gmail.com
 * 
 * All rights reserved ®
 */

class Clients extends CI_Controller {

    public function index() {
        $this->data['particular_head'] = $this->load->view('particular_heads/client', '', TRUE);

        $this->data['clients'] = $this->basic->get_where('clientes', array(), 'client_name', '', '30')->result_array();
        $this->data['row_count'] = count($this->data['clients']);
        $this->data['list'] = $this->load->view('clients/list', $this->data, TRUE);
        $this->data['content'] = $this->load->view('clients/clients', $this->data, TRUE);

        $this->load->view('layout', $this->data);
    }

    public function save() {
        $response = array();

        try {

            $this->form_validation->set_rules('client_name', 'Nombre', "required|trim");
            $this->form_validation->set_rules('client_nro_calle', 'Nro. Calle', "numeric");
            $this->form_validation->set_rules('client_postal', 'Código Postal', "numeric");
            $this->form_validation->set_rules('client_tel', 'Teléfono Fijo', "numeric");
            $this->form_validation->set_rules('client_celular', 'Celular', "numeric");
            if (!$this->input->post('client_id')) {
                $this->form_validation->set_rules('client_name', 'Cliente', "callback_noExistsClient");
            }

            if ($this->form_validation->run()) {
                $new_client = array_map("strtoupper", $this->input->post());
                $new_client['client_email'] = strtolower($new_client['client_email']);

                $client = $this->basic->get_where('clientes', array('client_id' => $this->input->post('client_id')))->row_array();
                if (!empty($client)) {
                    General::impactEditClient($client, $new_client['client_name']);
                }

                $new_client['client_id'] = $this->basic->save('clientes', 'client_id', $new_client);

                $response['status'] = true;
                $response['entity'] = General::parseEntityForList($new_client, 'clientes');
                $response['table'] = array(
                    'table' => 'clientes',
                    'table_pk' => 'client_id',
                    'entity_name' => 'cliente',
                );
                $response['success'] = 'El cliente fue guardado!';
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

    public function noExistsClient($client_name) {
        $account = $this->basic->get_where('clientes', array('client_name' => $client_name))->row();

        if ($account) {
            $this->form_validation->set_message('noExistsClient', 'El %s ya existe en el sistema, no pueden existir dos iguales');
            return false;
        } else {
            return true;
        }
    }

}

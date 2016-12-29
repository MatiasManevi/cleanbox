<?php

/*
 * Project: Cleanbox
 * Document: Providers_rols
 * 
 * @author Manevi Matias Alejandro 
 * Informatic Engineer - Web Developer
 * 
 * Contact: manevimatias@gmail.com
 * 
 * All rights reserved ®
 */

class Providers_rols extends CI_Controller {

    public function index() {
        $this->data['particular_head'] = $this->load->view('particular_heads/maintenance', '', TRUE);

        $this->data['providers_rols'] = $this->basic->get_where('providers_rols', array(), 'rol')->result_array();
        $this->data['list'] = $this->load->view('providers_rols/list', $this->data, TRUE);
        $this->data['content'] = $this->load->view('providers_rols/providers_rols', $this->data, TRUE);

        $this->load->view('layout', $this->data);
    }

    public function save() {
        $response = array();

        try {

            $this->form_validation->set_rules('rol', 'Area', "required|trim");
            if (!$this->input->post('id')) {
                $this->form_validation->set_rules('rol', 'Area', "callback_noExistsProviderRol");
            }

            if ($this->form_validation->run()) {
                $new_provider_rol = $this->input->post();
                
                $provider_rol = $this->basic->get_where('providers_rols', array('id' => $this->input->post('id')))->row_array();
                if (!empty($provider_rol)) {
                    General::impactEditProviderRol($provider_rol, $new_provider_rol['rol']);
                }
                
                $new_provider_rol['id'] = $this->basic->save('providers_rols', 'id', $new_provider_rol);

                $response['status'] = true;
                $response['entity'] = General::parseEntityForList($new_provider_rol, 'providers_rols');
                $response['table'] = array(
                    'table' => 'providers_rols',
                    'table_pk' => 'id',
                    'entity_name' => 'area',
                );
                $response['success'] = 'El area fue guardada!';
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

    public function noExistsProviderRol($rol) {
        $provider_rol = $this->basic->get_where('providers_rols', array('rol' => $rol))->row();

        if ($provider_rol) {
            $this->form_validation->set_message('noExistsProviderRol', 'El %s ya existe en el sistema, no pueden existir dos iguales');
            return false;
        } else {
            return true;
        }
    }

}

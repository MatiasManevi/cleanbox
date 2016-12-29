<?php

/*
 * Project: Cleanbox
 * Document: Users
 * 
 * @author Manevi Matias Alejandro 
 * Informatic Engineer - Web Developer
 * 
 * Contact: manevimatias@gmail.com
 * 
 * All rights reserved ®
 */


class Users extends CI_Controller {

    public function index() {
        $this->data['users'] = $this->basic->get_all('man_users')->result_array();
        $this->data['list'] = $this->load->view('users/list', $this->data, TRUE);
        $this->data['content'] = $this->load->view('users/users', $this->data, TRUE);
        
        $this->load->view('layout', $this->data);
    }

    public function save() {
        $response = array();

        try {
            $this->form_validation->set_rules('username', 'Nombre Usuario', "required|trim");
            $this->form_validation->set_rules('password', 'Clave', "required|trim");

            if ($this->form_validation->run()) {
                $user = $this->input->post();
                $user['id'] = $this->basic->save('man_users', 'id', $user);
                $this->session->set_userdata('username', $user['username']);

                $response['status'] = true;
                $response['entity'] = General::parseEntityForList($user, 'man_users');
                $response['table'] = array(
                    'table' => 'man_users',
                    'table_pk' => 'id',
                    'entity_name' => 'usuario',
                );
                $response['success'] = 'El usuario fue guardado!';
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

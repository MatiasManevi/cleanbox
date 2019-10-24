<?php

/*
 * Project: Cleanbox
 * Document: Settings
 * 
 * @author Manevi Matias Alejandro 
 * Informatic Engineer - Web Developer
 * 
 * Contact: manevimatias@gmail.com
 * 
 * All rights reserved ®
 */

class Settings extends CI_Controller {

    public function index() {
        $this->data['particular_head'] = $this->load->view('particular_heads/settings', '', TRUE);
        $this->data['settings'] = User::getUserSettings();
        $this->data['reports_config'] = $this->basic->get_all('reports_config')->result_array();
        $this->data['current_accounts'] = $this->basic->get_where('cuentas_corrientes', array(), 'cc_prop', '')->result_array();
        $this->data['content'] = $this->load->view('settings/settings', $this->data, TRUE);
        
        $this->load->view('layout', $this->data);
    }

    public function save() {
        $response = array();

        try {
            $settings = $this->basic->get_where('settings', array('id' => $this->input->post('id')))->row_array();
            $reports_config = $this->input->post('reports_config');

            switch ($this->input->post('setting_section')) {
                case 'bussines':
                    $success = 'Configuracion de la empresa guardada!';
                    break;
                case 'system':
                    $success = 'Configuracion de sistema guardada!';
                    break;
                case 'tax':
                    $success = 'Configuracion de porcentajes impositivos guardada!';
                    break;
                case 'report_delivery':
                    $success = 'Configuracion de envio de reportes guardada!';
                    break;
            }

            $updated_settings = [];

            foreach ($settings as $key => $setting_value) {
                if(array_key_exists($key, $this->input->post())){
                    $updated_settings[$key] = $this->input->post($key);
                }
            }

            $this->basic->save('settings', 'id', $updated_settings);

            if ($reports_config) {
                foreach ($reports_config as $report_config) {
                    if(isset($report_config['current_accounts'])){
                        $report_config['data'] = serialize($report_config['current_accounts']);
                        unset($report_config['current_accounts']);
                    }
                    $this->basic->save('reports_config', 'id', $report_config);
                }
            }

            $response['status'] = true;
            $response['success'] = $success;
        } catch (Exception $exc) {
            $response['status'] = false;
            $response['error'] = 'Ups! Ocurrio un error, intente nuevamente por favor. Detalle: ' . $exc->getMessage();
        }

        echo json_encode($response);
    }

}

<?php

/*
 * Project: Cleanbox
 * Document: Maintenances
 * 
 * @author Manevi Matias Alejandro 
 * Informatic Engineer - Web Developer
 * 
 * Contact: manevimatias@gmail.com
 * 
 * All rights reserved ®
 */

class Maintenances extends CI_Controller {

    public function index() {
        $this->data['particular_head'] = $this->load->view('particular_heads/maintenance', '', TRUE);

        $this->data['maintenances'] = $this->basic->get_where('mantenimientos', array(), 'mant_id', 'desc', '30')->result_array();
        $this->data['row_count'] = count($this->data['maintenances']);
        $this->data['list'] = $this->load->view('maintenances/list', $this->data, TRUE);
        $this->data['content'] = $this->load->view('maintenances/maintenances', $this->data, TRUE);

        $this->load->view('layout', $this->data);
    }

    public function save() {
        $response = array();

        try {
            $this->form_validation->set_rules('mant_domicilio', 'Domicilio', "required|trim");
            $this->form_validation->set_rules('mant_desc', 'Descripcion detallada', "required");

            if ($this->form_validation->run()) {
                $maintenance = array_map("strtoupper", $this->input->post());
                $maintenance['mant_id'] = $this->basic->save('mantenimientos', 'mant_id', $maintenance);

                $response['status'] = true;
                $response['entity'] = General::parseEntityForList($maintenance, 'mantenimientos');
                $response['table'] = array(
                    'table' => 'mantenimientos',
                    'table_pk' => 'mant_id',
                    'entity_name' => 'mantenimiento',
                );
                $response['success'] = 'El mantenimiento fue guardado!';
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

    public function maintenanceReport($mant_id) {
        $this->data['maintenance'] = $this->basic->get_where('mantenimientos', array('mant_id' => $mant_id))->row_array();
        $this->data['content'] = $this->load->view('reports/maintenance_report', $this->data, TRUE);

        $this->load->view('layout', $this->data);
    }

}
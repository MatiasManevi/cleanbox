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
                if(!$this->input->post('mant_monto')){
                    $_POST['mant_monto'] = 0;
                }
                if(!$this->input->post('mant_calif')){
                    $_POST['mant_calif'] = 0;
                }

                $maintenance = array_map("strtoupper", $_POST);
                $property = $this->basic->get_where('propiedades',array('prop_dom' => $maintenance['mant_domicilio']))->row_array();

                switch ($maintenance['mant_status']) {
                    case '1':
                        $name = 'Se crea mantenimiento en '.$maintenance['mant_domicilio'];
                        break;
                    case '2':
                        $name = 'Se procesa mantenimiento en '.$maintenance['mant_domicilio'];
                        break;
                    case '3':
                        $name = 'Se terminó mantenimiento en '.$maintenance['mant_domicilio'];
                        break;
                }

                $maintenance['mant_id'] = $this->basic->save('mantenimientos', 'mant_id', $maintenance);

                TimelineService::createEvent([
                    'timeline_id' => $property['timeline_id'],
                    'name' => $name,
                    'description' => 'La tarea a realizar en la propiedad es: '.$maintenance['mant_desc']. '. Solicitada por el inquilino '. $maintenance['mant_inq']
                ]);
            
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

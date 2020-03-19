<?php

/*
 * Project: Cleanbox
 * Document: Inspections
 * 
 * @author Manevi Matias Alejandro 
 * Informatic Engineer - Web Developer
 * 
 * Contact: manevimatias@gmail.com
 * 
 * All rights reserved ®
 */

class Inspections extends CI_Controller {

    public function index() {
        $this->data['inspections'] = $this->basic->get_where('inspections', array(), 'id', 'desc', '30')->result_array();

        $this->data['row_count'] = count($this->data['inspections']);
        $this->data['list'] = $this->load->view('inspections/list', $this->data, TRUE);
        $this->data['content'] = $this->load->view('inspections/inspections', $this->data, TRUE);

        $this->load->view('layout', $this->data);
    }

    public function save() {
        $response = array();

        try {
            $this->form_validation->set_rules('property_id', 'Domicilio', "required|trim");
            $this->form_validation->set_rules('description', 'Descripcion detallada', "required");
            $this->form_validation->set_rules('date', 'Fecha', "required");
            $this->form_validation->set_rules('momentum', 'Momento de inspección', "required");

            if ($this->form_validation->run()) {
                $inspection = $this->input->post();
                if(!$inspection['renter_id']){
                    unset($inspection['renter_id']);
                }                                
                switch ($inspection['momentum']) {
                    case '1':
                        $momentum = '<strong>previo a contrato</strong>';
                        break;
                    case '2':
                        $momentum = '<strong>durante contrato</strong>';
                        break;
                    case '3':
                        $momentum = '<strong>post contrato</strong>';
                        break;
                }

                $pictures = isset($inspection['pictures']) ? explode(',', $inspection['pictures']) : [];
                unset($inspection['pictures']);

                $inspection['id'] = $this->basic->save('inspections', 'id', $inspection);

                $property = $this->basic->get_where('propiedades', ['prop_id' => $inspection['property_id']])->row_array();

                if(!empty($pictures)){
                    General::savePictures($inspection, 'inspection_pictures', 'inspection_id', 'id', $pictures);
                }

                $renter = isset($inspection['renter_id']) ? '. Inquilino: '. $inspection['renter'] : '';
                $event_data = [
                    'timeline_id' => $property['timeline_id'],
                    'name' => 'Inspección en '.$inspection['address'].' ('.$property['prop_prop'].')',
                    'description' => 'La inspección de la propiedad se realiza '.$momentum.'' . $renter. ', para revisar lo siguiente: '.$inspection['description']
                ];

                if(!$this->input->post('id')){
                    $inspection['event_id'] = TimelineService::createEvent($event_data, $pictures, $property['prop_id']);
                    $this->basic->save('inspections', 'id', $inspection);
                }else{
                    $inspection = $this->basic->get_where('inspections', ['id' => $inspection['id']])->row_array();
                    TimelineService::updateEvent($event_data, $inspection['event_id'], $pictures);
                }

                $response['status'] = true;
                $response['entity'] = General::parseEntityForList($inspection, 'inspections');
                $response['table'] = array(
                    'table' => 'inspections',
                    'table_pk' => 'id',
                    'entity_name' => 'inspección',
                );
                $response['success'] = 'La inspección fue guardada!';
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

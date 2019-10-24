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
            $this->form_validation->set_rules('renter_id', 'Inquilino', "required");
            $this->form_validation->set_rules('description', 'Descripcion detallada', "required");
            $this->form_validation->set_rules('date', 'Fecha', "required");
            $this->form_validation->set_rules('momentum', 'Momento de inspección', "required");

            if ($this->form_validation->run()) {
                $inspection = $this->input->post();
                                
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

                $pictures = isset($inspection['pictures']) ? $inspection['pictures'] : [];
                unset($inspection['pictures']);

                $inspection['id'] = $this->basic->save('inspections', 'id', $inspection);

                $property = $this->basic->get_where('propiedades', ['prop_id' => $inspection['property_id']])->row_array();

                if(!$this->input->post('id')){
                    $name = 'Se crea inspección en '.$inspection['address'];
                }else{
                    $name = 'Se actualiza inspección en '.$inspection['address'];
                }
                
                $old_pics = $this->basic->get_where('inspection_pictures', array('inspection_id' => $inspection['id']), 'id')->result_array();
                foreach ($old_pics as $old_pic) {
                    if(isset($old_pic['url']) && $old_pic['url']){
                        unlink('img/'.$old_pic['url']);
                    }
                }
                $this->basic->del('inspection_pictures', 'inspection_id', $inspection['id']);

                if(strlen($pictures)){
                    
                    $pictures = explode(',', $pictures);
                    
                    if(!empty($pictures)){

                        foreach ($pictures as $picture) {
                            if(strlen($picture) > 1){
                                $this->basic->save('inspection_pictures', 'id', [
                                    'inspection_id' => $inspection['id'],
                                    'url' => $picture
                                ]);
                            }
                        }
                    }
                }

                TimelineService::createEvent([
                    'timeline_id' => $property['timeline_id'],
                    'name' => $name,
                    'description' => 'La inspección de la propiedad se realiza '.$momentum.'. Solicitada por el inquilino '. $inspection['renter']. ', para revisar lo siguiente: '.$inspection['description']
                ], $pictures);

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

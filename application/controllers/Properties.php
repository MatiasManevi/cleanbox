<?php

/*
 * Project: Cleanbox
 * Document: Properties
 * 
 * @author Manevi Matias Alejandro 
 * Informatic Engineer - Web Developer
 * 
 * Contact: manevimatias@gmail.com
 * 
 * All rights reserved ®
 */

class Properties extends CI_Controller {

    public function index() {
        $this->data['particular_head'] = $this->load->view('particular_heads/propietary', '', TRUE);

        $this->data['properties'] = $this->basic->get_where('propiedades', array(), 'prop_prop', '','30')->result_array();
        $this->data['row_count'] = count($this->data['properties']);
        $this->data['list'] = $this->load->view('properties/list', $this->data, TRUE);
        $this->data['content'] = $this->load->view('properties/properties', $this->data, TRUE);

        $this->load->view('layout', $this->data);
    }

    public function save() {
        $response = array();

        try {
            if ($this->input->post('prop_id')) {
                $this->form_validation->set_rules('prop_dom', 'Domicilio de propiedad', "required|trim");
            } else {
                $this->form_validation->set_rules('prop_dom', 'Domicilio de propiedad', "required|trim|callback_noExistsProperty");
            }
            $this->form_validation->set_rules('cc_id', 'Propietario', "required|trim");
            $this->form_validation->set_rules('prop_contrato_vigente', 'Inquilino', "trim");

            if ($this->form_validation->run()) {

                $new_property = $this->input->post();

                $pictures = isset($new_property['pictures']) ? explode(',', $new_property['pictures']) : [];
                unset($new_property['pictures']);

                $new_property = array_map("strtoupper", $new_property);

                if (!$this->input->post('prop_id')) {
                    $new_property['prop_date_created'] = date('d-m-Y');
                }

                $property = $this->basic->get_where('propiedades', array('prop_id' => $this->input->post('prop_id')))->row_array();
                if (!empty($property)) {
                    General::impactEditProperty($property, $new_property['prop_dom']);
                }

                $new_property['prop_id'] = $this->basic->save('propiedades', 'prop_id', $new_property);

                $response['status'] = true;
                $response['entity'] = General::parseEntityForList($new_property, 'propiedades');
                $response['table'] = array(
                    'table' => 'propiedades',
                    'table_pk' => 'prop_id',
                    'entity_name' => 'propiedad',
                );
                $response['success'] = 'La propiedad fue guardada!';

                if(!empty($pictures)){
                    General::savePictures($new_property, 'property_pictures', 'property_id', 'prop_id', $pictures);
                }

                if (!$this->input->post('prop_id')) {
                    $timeline_id = TimelineService::createTimeline($new_property['prop_id']);
                    $this->basic->save('propiedades', 'prop_id', [
                        'prop_id' => $new_property['prop_id'],
                        'timeline_id' => $timeline_id
                    ]);
                    TimelineService::createEvent([
                        'timeline_id' => $timeline_id,
                        'name' => 'Propiedad creada',
                        'description' => 'En el domicilio <strong>'.$new_property['prop_dom'].'</strong>, a nombre de <strong>' . $new_property['prop_prop'].'</strong>'
                    ], $pictures);
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

    public function noExistsProperty($prop_dom) {
        $property = $this->basic->get_where('propiedades', array('prop_dom' => $prop_dom))->row();

        if ($property) {
            $this->form_validation->set_message('noExistsProperty', 'El %s ya existe en el sistema, no pueden existir dos domicilios iguales');
            return false;
        } else {
            return true;
        }
    }

}

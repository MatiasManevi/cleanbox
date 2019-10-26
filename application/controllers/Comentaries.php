<?php

/*
 * Project: Cleanbox
 * Document: Comentaries
 * 
 * @author Manevi Matias Alejandro 
 * Informatic Engineer - Web Developer
 * 
 * Contact: manevimatias@gmail.com
 * 
 * All rights reserved ®
 */

class Comentaries extends CI_Controller {

    public function index() {
        $this->data['particular_head'] = $this->load->view('particular_heads/propietary', '', TRUE);

        $this->data['comentaries'] = $this->basic->get_where('comentarios', array(), 'com_prop', '', '30')->result_array();
        $this->data['list'] = $this->load->view('comentaries/list', $this->data, TRUE);
        $this->data['row_count'] = count($this->data['comentaries']);
        $this->data['content'] = $this->load->view('comentaries/comentaries', $this->data, TRUE);
        
        $this->load->view('layout', $this->data);
    }

    public function save() {
        $response = array();

        try {
            $this->form_validation->set_rules('cc_id', 'Propietario', "required|trim");
            $this->form_validation->set_rules('prop_id', 'Domicilio de Propiedad', "required|trim");
            $this->form_validation->set_rules('com_com', 'Comentarios', "required|trim");

            if ($this->form_validation->run()) {
                $comentary = array_map("strtoupper", $this->input->post());
                $property = $this->basic->get_where('propiedades', ['prop_id' => $comentary['prop_id']])->row_array();

                if (!$this->input->post('com_id')) {
                    $comentary['com_date'] = Date('d-m-Y');
                    $comentary['com_mes'] = Date('m');
                    $comentary['com_ano'] = Date('Y');
                    $name = 'Se crea comentario en '.$property['prop_dom'];
                } else {
                    $name = 'Se actualiza comentario en '.$property['prop_dom'];
                }

                $comentary['com_id'] = $this->basic->save('comentarios', 'com_id', $comentary);

                TimelineService::createEvent([
                    'timeline_id' => $property['timeline_id'],
                    'name' => $name.' ('.$comentary['com_prop'].')',
                    'description' => 'Comentario: ' .$this->input->post('com_com')
                ], [], $property['prop_id']);

                $response['status'] = true;
                $response['entity'] = General::parseEntityForList($comentary, 'comentarios');
                $response['table'] = array(
                    'table' => 'comentarios',
                    'table_pk' => 'com_id',
                    'entity_name' => 'comentario',
                );
                $response['success'] = 'El comentario fue guardado!';
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

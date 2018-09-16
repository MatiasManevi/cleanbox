<?php

/*
 * Project: Cleanbox
 * Document: Concepts
 * 
 * @author Manevi Matias Alejandro 
 * Informatic Engineer - Web Developer
 * 
 * Contact: manevimatias@gmail.com
 * 
 * All rights reserved ®
 */


class Concepts extends CI_Controller {

    public function index() {
        $this->data['particular_head'] = $this->load->view('particular_heads/transaction', '', TRUE);

        $this->data['concepts'] = $this->basic->get_where('conceptos', array(), 'conc_desc', '', '30')->result_array();
        $this->data['row_count'] = count($this->data['concepts']);
        $this->data['list'] = $this->load->view('concepts/list', $this->data, TRUE);
        $this->data['content'] = $this->load->view('concepts/concepts', $this->data, TRUE);

        $this->load->view('layout', $this->data);
    }

    public function save() {
        $response = array();

        try {

            $this->form_validation->set_rules('conc_desc', 'Concepto', "required|trim");
            $this->form_validation->set_rules('conc_tipo', 'Tipo de Concepto', "required|trim");

            if ($this->form_validation->run()) {
                
                $concept = $this->input->post();
                if (!$this->conceptExist($this->input->post()) || $this->input->post('conc_id') != '') {
                    $concept['conc_id'] = $this->basic->save('conceptos', 'conc_id', array_map('strtoupper', $this->input->post()));

                    $response['status'] = true;
                    $response['entity'] = General::parseEntityForList($concept, 'conceptos');
                    $response['table'] = array(
                        'table' => 'conceptos',
                        'table_pk' => 'conc_id',
                        'entity_name' => 'concepto',
                    );
                    $response['success'] = 'El concepto fue guardado!';
                } else {
                    $response['status'] = false;
                    $response['error'] = 'El concepto ' . $concept['conc_desc'] . ' ya existe como ' . $concept['conc_tipo'] . ' para la cuenta seleccionada';
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

    public function conceptExist($new_concept) {
        $concept = $this->basic->get_where('conceptos', array('conc_desc' => $new_concept['conc_desc'], 'conc_tipo' => $new_concept['conc_tipo']))->row_array();

        if (!empty($concept)) {
            return true;
        } else {
            return false;
        }
    }

}

<?php

/*
 * Project: Cleanbox
 * Document: Providers
 * 
 * @author Manevi Matias Alejandro 
 * Informatic Engineer - Web Developer
 * 
 * Contact: manevimatias@gmail.com
 * 
 * All rights reserved ®
 */

class Providers extends CI_Controller {

    public function index() {
        $this->data['particular_head'] = $this->load->view('particular_heads/maintenance', '', TRUE);

        $this->data['providers_rols'] = $this->basic->get_all('providers_rols', array(), 'rol')->result_array();
        $this->data['providers'] = $this->basic->get_where('proveedores', array(), 'prov_name', '', '30')->result_array();
        $this->data['list'] = $this->load->view('providers/list', $this->data, TRUE);
        $this->data['row_count'] = count($this->data['providers']);
        $this->data['content'] = $this->load->view('providers/providers', $this->data, TRUE);

        $this->load->view('layout', $this->data);
    }

    public function save() {
        $response = array();

        try {
            $this->form_validation->set_rules('prov_name', 'Nombre', "required|trim");
            $this->form_validation->set_rules('prov_tel', 'Teléfono', "required|numeric");
            if (!$this->input->post('prov_id')) {
                $this->form_validation->set_rules('prov_name', 'Proveedor', "callback_noExistsProvider");
            }

            if ($this->form_validation->run()) {

                $areas = explode('#', trim($this->input->post('areas')));

                $new_provider = array(
                    'prov_name' => strtoupper($this->input->post('prov_name')),
                    'prov_tel' => $this->input->post('prov_tel'),
                    'prov_domicilio' => strtoupper($this->input->post('prov_domicilio')),
                    'prov_email' => strtoupper($this->input->post('prov_email')),
                    'prov_nota' => $this->input->post('nota_total') ? $this->input->post('nota_total') : 0,
                    'prov_id' => $this->input->post('prov_id')
                );

                $provider = $this->basic->get_where('proveedores', array('prov_id' => $this->input->post('prov_id')))->row_array();
                if (!empty($provider)) {
                    General::impactEditProvider($provider, $new_provider['prov_name']);
                }

                $new_provider['prov_id'] = $this->basic->save('proveedores', 'prov_id', $new_provider);

                if ($new_provider['prov_id']) {
                    $this->saveProveedorAreas($new_provider['prov_id'], $areas);
                    $this->saveProveedorNota($new_provider['prov_id'], $this->input->post());

                    $response['status'] = true;
                    $response['entity'] = General::parseEntityForList($new_provider, 'proveedores');
                    $response['table'] = array(
                        'table' => 'proveedores',
                        'table_pk' => 'prov_id',
                        'entity_name' => 'proveedor',
                    );
                    $response['success'] = 'El proveedor fue guardado!';
                } else {
                    $response['status'] = false;
                    $response['error'] = 'Ups! Ocurrio un error, intente nuevamente por favor. Detalle: ' . $exc->getMessage();
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

    public function noExistsProvider($provider_name) {
        $account = $this->basic->get_where('proveedores', array('prov_name' => $provider_name))->row();

        if ($account) {
            $this->form_validation->set_message('noExistsProvider', 'El %s ya existe en el sistema, no pueden existir dos iguales');
            return false;
        } else {
            return true;
        }
    }

    public function saveProveedorNota($prov_id, $post) {
        $notas = array(
            'nota_id' => $post['nota_id'] ? $post['nota_id'] : 0,
            'nota_prov_id' => $prov_id,
            'nota_garantia' => $post['nota_garantia'] ? $post['nota_garantia'] : 0,
            'nota_exp' => $post['nota_exp'] ? $post['nota_exp'] : 0,
            'nota_timing' => $post['nota_timing'] ? $post['nota_timing'] : 0,
            'nota_presup' => $post['nota_presup'] ? $post['nota_presup'] : 0,
            'nota_trust' => $post['nota_trust'] ? $post['nota_trust'] : 0,
            'nota_calidad' => $post['nota_calidad'] ? $post['nota_calidad'] : 0,
            'nota_total' => $post['nota_total'] ? $post['nota_total'] : 0,
        );

        $this->basic->save('proveedores_nota', 'nota_id', $notas);
    }

    public function saveProveedorAreas($prov_id, $areas) {

        $this->basic->del('areas_proveedores', 'area_prov', $prov_id);

        foreach ($areas as $area) {
            if (isset($area)) {
                if (strlen($area) > 0) {
                    $new_area = array(
                        'area_prov' => $prov_id,
                        'area_area' => trim($area),
                    );
                    $this->basic->save('areas_proveedores', 'area_id', $new_area);
                }
            }
        }
    }

    public function getProviders() {
        try {
            $response['status'] = true;
            $providers_to_check = $this->basic->get_where('proveedores', array(), 'prov_id', 'desc')->result_array();
            $response['providers'] = array();
            $providers = array();

            foreach ($providers_to_check as $row) {
                $row['prov_bussy'] = 0;
                if ($this->isBussy($row['prov_name'])) {
                    $row['prov_bussy'] = 1;
                }
                array_push($providers, $row);
            }

            foreach ($providers as $provider) {
                array_push($response['providers'], General::parseEntityForList($provider, 'proveedores'));
            }
        } catch (Exception $exc) {
            $response['status'] = false;
            $response['error'] = 'Ups! Ocurrio un error, intente nuevamente por favor. Detalle: ' . $exc->getMessage();
        }

        echo json_encode($response);
    }

    public function isBussy($prov_name) {
        $bussy = false;
        $maintenances = $this->basic->get_all('mantenimientos')->result_array();

        foreach ($maintenances as $row) {
            if ($row['mant_status'] != 3 && $row['mant_prov_1'] == $prov_name || $row['mant_prov_2'] == $prov_name || $row['mant_prov_3'] == $prov_name) {
                $bussy = true;
                break;
            }
        }

        return $bussy;
    }

}

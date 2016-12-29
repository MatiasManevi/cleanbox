<?php

/*
 * Project: Cleanbox
 * Document: Contracts
 * 
 * @author Manevi Matias Alejandro 
 * Informatic Engineer - Web Developer
 * 
 * Contact: manevimatias@gmail.com
 * 
 * All rights reserved ®
 */

class Contracts extends CI_Controller {

    public function index() {
        $this->data['particular_head'] = $this->load->view('particular_heads/propietary', '', TRUE);
     
        $this->data['contracts'] = $this->basic->get_where('contratos', array(), 'con_prop', '','30')->result_array();
        $this->data['alive_contracts'] = Contract::getCountAliveContracts();
        $this->data['row_count'] = count($this->data['contracts']);
        $this->data['list'] = $this->load->view('contracts/list', $this->data, TRUE);
        $this->data['content'] = $this->load->view('contracts/contracts', $this->data, TRUE);

        $this->load->view('layout', $this->data);
    }

    public function save() {
        $response = array();

        try {
            $this->form_validation->set_rules('con_prop', 'Propietario', "required|trim");
            $this->form_validation->set_rules('con_inq', 'Inquilino', "required|trim");
            $this->form_validation->set_rules('con_venc', 'Fecha Vencimiento', "required|trim");
            $this->form_validation->set_rules('con_tipo', 'Tipo de Contrato', "required|trim");
            $this->form_validation->set_rules('con_tolerancia', 'Tolerancia', "required|trim");
            $this->form_validation->set_rules('con_porc', 'Porcentaje Gestion de Cobro', "required|trim");
            $this->form_validation->set_rules('con_punitorio', 'Porcentaje Interes Punitorio', "required|trim");
            $this->form_validation->set_rules('con_domi', 'Domicilio Inmueble', "required|trim");
            $this->form_validation->set_rules('con_gar1', 'Garante (al menos uno)', "required|trim");

            if ($this->form_validation->run()) {
                $services = $this->input->post('services');
                $periods = $this->input->post('periods');

                // Controlo que esten bien cargados los conceptos de los servicios
                $services_ok = true;
                $no_load = array();

                if (!empty($services)) {
                    foreach ($services as $service) {
                        if ($service['serv_control'] == 0) {
                            $services_ok = false;
                            $no_load[] = $service['serv_concepto'];
                        }
                    }
                }

                if ($services_ok) {
                    $contract = array(
                        'con_prop' => strtoupper($this->input->post('con_prop')),
                        'con_inq' => strtoupper($this->input->post('con_inq')),
                        'con_id' => $this->input->post('con_id'),
                        'cc_id' => $this->input->post('cc_id'),
                        'client_id' => $this->input->post('client_id'),
                        'gar1_id' => $this->input->post('gar1_id'),
                        'gar2_id' => $this->input->post('gar2_id'),
                        'prop_id' => $this->input->post('prop_id'),
                        'con_venc' => $this->input->post('con_venc'),
                        'con_tipo' => $this->input->post('con_tipo'),
                        'con_iva' => $this->input->post('con_iva'),
                        'con_domi' => strtoupper($this->input->post('con_domi')),
                        'con_iva_alq' => $this->input->post('con_iva_alq'),
                        'con_porc' => $this->input->post('con_porc'),
                        'con_gar1' => strtoupper($this->input->post('con_gar1')),
                        'con_gar2' => strtoupper($this->input->post('con_gar2')),
                        'con_motivo' => $this->input->post('con_motivo'),
                        'con_punitorio' => $this->input->post('con_punitorio'),
                        'con_tolerancia' => $this->input->post('con_tolerancia'),
                        'honorary_cuotes' => $this->input->post('honorary_cuotes'),
                        'honorary_cuotes_payed' => $this->input->post('honorary_cuotes_payed'),
                        'warranty_cuotes' => $this->input->post('warranty_cuotes'),
                        'warranty_cuotes_payed' => $this->input->post('warranty_cuotes_payed'),
                        'con_enabled' => $this->input->post('con_enabled'),
                        'con_date_created' => $this->input->post('con_date_created'),
                        'con_date_declined' => $this->input->post('con_date_declined'),
                        'con_date_renovated' => $this->input->post('con_date_renovated')
                    );

                    $contract = $this->createContractParts($contract);

                    if ($contract['con_motivo'] == 'Rescindido') {
                        $contract['con_enabled'] = 0;
                    } elseif ($contract['con_motivo'] == 'Prorrogado') {
                        $contract['con_enabled'] = 1;
                    }

                    $contract['con_id'] = $this->basic->save('contratos', 'con_id', $contract);

                    $this->createContractPeriods($periods, $contract['con_id']);
                    $this->createContractServices($services, $contract['con_id']);
//                    $this->transferSign($contract);

                    $response['status'] = true;
                    $response['count_alive_contracts'] = Contract::getCountAliveContracts();
                    $response['entity'] = General::parseEntityForList($contract, 'contratos');
                    $response['table'] = array(
                        'table' => 'contratos',
                        'table_pk' => 'con_id',
                        'entity_name' => 'contrato',
                    );
                    $response['success'] = 'El contrato fue guardado!';
                } else {
                    $response['status'] = false;
                    $response['error'] = 'Los conceptos: ';
                    for ($x = 0; $x < count($no_load); $x++) {
                        $response['error'] .= $no_load[$x] . ', ';
                    }
                    $response['error'] .= ' no existen aun, debe darlos de alta en el sistema en la seccion Conceptos.';
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

    /**
     * Si no existen las partes dle contrato, inq, prop e inmueble, se crean
     * @param type $contract 
     */
    public function createContractParts($contract) {
        /* solo para davinia y rima */
        $inq = $this->basic->get_where('clientes', array('client_id' => $contract['client_id']))->row_array();
        if (empty($inq)) {
            $inq = $this->basic->get_where('clientes', array('client_name' => $contract['con_inq']))->row_array();
        }
        $gar1 = $this->basic->get_where('clientes', array('client_id' => $contract['gar1_id']))->row_array();
        if (empty($gar1)) {
            $gar1 = $this->basic->get_where('clientes', array('client_name' => $contract['con_gar1']))->row_array();
        }
        $gar2 = $this->basic->get_where('clientes', array('client_id' => $contract['gar2_id']))->row_array();
        if (empty($gar2)) {
            $gar2 = $this->basic->get_where('clientes', array('client_name' => $contract['con_gar2']))->row_array();
        }
        $prop_client = $this->basic->get_where('clientes', array('client_id' => $contract['cc_id']))->row_array();
        if (empty($prop_client)) {
            $prop_client = $this->basic->get_where('clientes', array('client_name' => $contract['con_prop']))->row_array();
        }
        $prop_cc = $this->basic->get_where('cuentas_corrientes', array('client_id' => $contract['cc_id']))->row_array();
        if (empty($prop_cc)) {
            $prop_cc = $this->basic->get_where('cuentas_corrientes', array('cc_prop' => $contract['con_prop']))->row_array();
        }
        $property = $this->basic->get_where('propiedades', array('prop_id' => $contract['prop_id']))->row_array();
        if (empty($property)) {
            $property = $this->basic->get_where('propiedades', array('prop_dom' => $contract['con_domi']))->row_array();
        }
        /* solo para davinia y rima */

        if (empty($gar1) && strlen($contract['con_gar1']) > 0) {
            $gar1 = array(
                'client_name' => $contract['con_gar1'],
                'client_categoria' => 'Garante',
            );
            $contract['gar1_id'] = $this->basic->save('clientes', 'client_id', $gar1);
        } else if (strlen($contract['con_gar1']) > 0) {
            $contract['gar1_id'] = $gar1['client_id'];
        }

        if (empty($gar2) && strlen($contract['con_gar2']) > 0) {
            $gar2 = array(
                'client_name' => $contract['con_gar2'],
                'client_categoria' => 'Garante',
            );
            $contract['gar2_id'] = $this->basic->save('clientes', 'client_id', $gar2);
        } else if (strlen($contract['con_gar2']) > 0) {
            $contract['gar2_id'] = $gar2['client_id'];
        }

        if (empty($inq)) {
            $inq = array(
                'client_name' => $contract['con_inq'],
                'client_categoria' => 'Inquilino',
            );
            $contract['client_id'] = $this->basic->save('clientes', 'client_id', $inq);
        } else {
            $contract['client_id'] = $inq['client_id'];
        }

        if (empty($prop_client)) {
            $prop_client = array(
                'client_name' => $contract['con_prop'],
                'client_categoria' => 'Propietario',
            );
            $prop_client_id = $this->basic->save('clientes', 'client_id', $prop_client);
        } else {
            $prop_client_id = $prop_client['client_id'];
        }

        if (empty($prop_cc)) {
            $prop_cc = array(
                'cc_prop' => $contract['con_prop'],
                'cc_saldo' => 0,
                'cc_varios' => 0,
                'cc_date_created' => date('d-m-Y'),
                'client_id' => $prop_client_id
            );
            $contract['cc_id'] = $this->basic->save('cuentas_corrientes', 'cc_id', $prop_cc);
        } else {
            $contract['cc_id'] = $prop_cc['cc_id'];
        }

        if (empty($property)) {
            $property = array(
                'prop_dom' => $contract['con_domi'],
                'prop_prop' => $contract['con_prop'],
                'prop_contrato_vigente' => $contract['con_inq'],
                'cc_id' => $contract['cc_id'],
                'prop_enabled' => 1,
                'prop_date_created' => date('d-m-Y')
            );
            $contract['prop_id'] = $this->basic->save('propiedades', 'prop_id', $property);
        } else {
            $contract['prop_id'] = $property['prop_id'];
        }

        return $contract;
    }

    public function createContractPeriods($periods, $contract_id) {
        if (!empty($periods)) {
            foreach ($periods as $period) {
                $this->basic->save('periodos', 'per_id', array(
                    'per_id' => $period['per_id'],
                    'per_contrato' => $contract_id,
                    'per_inicio' => $period['per_inicio'],
                    'per_fin' => $period['per_fin'],
                    'per_monto' => $period['per_monto']
                ));
            }
        }
    }

    public function createContractServices($services, $contract_id) {
        if (!empty($services)) {
            foreach ($services as $service) {
                $this->basic->save('servicios', 'serv_id', array(
                    'serv_id' => $service['serv_id'],
                    'serv_contrato' => $contract_id,
                    'serv_concepto' => $service['serv_concepto'],
                    'serv_accion' => $service['serv_accion']
                ));
            }
        }
    }

    /**
     * Si existe una seña depositada previa a la creacion del contrato, esta es pasada a almacenarse
     * como credito de Alquiler en la cuenta del propietario
     * @param array $contract
     * @deprecated: al parecer davinia usa deposito de garantia y no seña para este concepto y no lo usa
     */
    public function transferSign($contract) {
        $credit = $this->basic->get_where('creditos', array('cred_concepto' => 'Seña', 'client_id' => $contract['client_id'], 'cc_id' => $contract['cc_id']))->row_array();

        if (empty($credit)) {
            $credit = $this->basic->get_where('creditos', array('cred_concepto' => 'Seña', 'cred_depositante' => $contract['con_inq'], 'cred_cc' => $contract['con_prop']))->row_array();
        }

        if (!empty($credit)) {
            $id = $credit['trans'];
            $cc_prop = $this->basic->get_where('cuentas_corrientes', array('cc_id' => $contract['cc_id']))->row_array();

            if (empty($cc_prop)) {
                $cc_prop = $this->basic->get_where('cuentas_corrientes', array('cc_prop' => $contract['con_prop']))->row_array();
            }

            $debit_tranf = array(
                'deb_cc' => $contract['con_prop'],
                'cc_id' => $contract['cc_id'],
                'deb_concepto' => 'Transferencia Seña-Alquiler',
                'deb_monto' => $credit['cred_monto'],
                'deb_fecha' => Date('d-m-Y'),
                'trans' => $id
            );

            $credit_tranf = array(
                'cred_depositante' => $contract['con_inq'],
                'cred_cc' => $contract['con_prop'],
                'con_id' => $contract['con_id'],
                'client_id' => $contract['client_id'],
                'cc_id' => $contract['cc_id'],
                'cred_forma' => $credit['cred_forma'],
                'cred_banco' => $credit['cred_banco'],
                'cred_nro_cheque' => $credit['cred_nro_cheque'],
                'cred_concepto' => 'Alquiler',
                'cred_monto' => $credit['cred_monto'],
                'cred_fecha' => Date('d-m-Y'),
                'cred_interes' => $credit['cred_interes'],
                'trans' => $id
            );

            $cc_prop['cc_varios'] -= $credit_tranf['cred_monto'];
            $cc_prop['cc_saldo'] += $credit_tranf['cred_monto'];

            $this->basic->save('debitos', 'deb_id', $debit_tranf);
            $this->basic->save('creditos', 'cred_id', $credit_tranf);
            $this->basic->save('cuentas_corrientes', 'cc_id', $cc_prop);
        }
    }

    public function validateContractParts() {
        $response = array();

        try {
            $client_id = $this->input->post('client_id');

            $client = $this->basic->get_where('clientes', array('client_id' => $client_id))->row_array();
            $contract = $this->basic->get_where('contratos', array('client_id' => $client['client_id'], 'con_enabled' => 1))->row_array();

            // En el futuro agregar opcion de traer varios contratos, ya que tal vez, un mismo
            // inquilino va a estar en varios contratos.
            if (empty($contract)) {
                $contract = $this->basic->get_where('contratos', array('con_inq' => $client['client_name'], 'con_enabled' => 1))->row_array();
            }

            if (!empty($contract)) {
                if ($contract['cc_id']) {
                    $cc_id = $contract['cc_id'];
                } else {
                    $cc_prop = $this->basic->get_where('cuentas_corrientes', array('cc_prop' => $contract['con_prop']))->row_array();
                    $cc_id = $cc_prop['cc_id'];
                }
                $response['cc_id'] = $cc_id;
            }

            $response['client_id'] = $client_id;

            if ($contract) {
                $response['status'] = true;
                $response['prop_name'] = $contract['con_prop'];
                $response['con_id'] = $contract['con_id'];
            } else {
                $response['status'] = false;
                $response['error'] = 'No existe contrato que vincule a ' . $client['client_name'] . ', o el mismo esta VENCIDO';
            }
        } catch (Exception $exc) {
            $response['status'] = false;
            $response['error'] = 'Ups! Ocurrio un error, intente nuevamente por favor. Detalle: ' . $exc->getMessage();
        }

        echo json_encode($response);
    }

}

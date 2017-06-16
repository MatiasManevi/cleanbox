<?php

/*
 * Project: Cleanbox
 * Document: Credits
 * 
 * @author Manevi Matias Alejandro 
 * Informatic Engineer - Web Developer
 * 
 * Contact: manevimatias@gmail.com
 * 
 * All rights reserved ®
 */

class Credits extends CI_Controller {

    public function index() {
        $this->data['particular_head'] = $this->load->view('particular_heads/transaction', '', TRUE);

        $this->data['credits'] = $this->basic->get_where('creditos', array('is_transfer' => 0), 'cred_id', 'desc', '30')->result_array();
        $this->data['row_count'] = count($this->data['credits']);
        $this->data['list'] = $this->load->view('credits/list', $this->data, TRUE);
        $this->data['content'] = $this->load->view('credits/credits', $this->data, TRUE);

        $this->load->view('layout', $this->data);
    }

    public function save() {
        $response = array();

        try {
            $use_contract = false;
            $print_report = false;
            $response['credits'] = array();
            $acumulated_impacted_credits = array();
            $bank_transaction_amount = 0;
            $bank_transaction_month = '';

            $credits = $this->input->post('credits') ? $this->input->post('credits') : false;
            $services_controls = $this->input->post('services_control') ? $this->input->post('services_control') : false;

            if ($credits || $services_controls) {
                $contract_id = $this->input->post('con_id') ? $this->input->post('con_id') : false;
                $transaction_id = Transaction::getLastTransactionId();

                if ($contract_id) {
                    $contract = $this->basic->get_where('contratos', array('con_id' => $contract_id))->row_array();
                }

                if ($services_controls) {
                    foreach ($services_controls as $service_control) {
                        if ($service_control['status']) {
                            Contract::saveServiceControl($service_control, $transaction_id);
                        }
                    }
                }

                if ($credits) {
                    foreach ($credits as $credit) {
                        $impacted_credits = Transaction::impactCredit($credit, $contract, $transaction_id);

                        if ($impacted_credits) {
                            $use_contract = Contract::contractMustBeMarkedUsed($credit, $use_contract);
                            $print_report = Report::mustPrintReport($credit['cred_concepto'], $print_report);
                            $acumulated_impacted_credits = array_merge($acumulated_impacted_credits, $impacted_credits);
                        }

                        if ($credit['cred_tipo_trans'] == 'Bancaria' && Transaction::isImpactableCredit($credit)) {
                            $bank_transaction_month = $credit['cred_mes_alq'];
                            $bank_transaction_amount += Transaction::calculateCreditAmount($credit);
                        }
                    }
                }

                if ($bank_transaction_amount > 0) {
                    Transaction::generateTaxDebit($bank_transaction_amount, $bank_transaction_month, $transaction_id);
                }

                // Se aumenta el id de transaccion
                Transaction::incrementTransactionId();

                if ($contract) {
                    // Se cambia el status del contrato a usado de ser la primera vez que se use
                    // Es decir si es el primer credito creado bajo dicho contrato.
                    if ($use_contract) {
                        Contract::useContract($contract);
                    }

                    $this->basic->save('contratos', 'con_id', $contract);
                }

                foreach ($acumulated_impacted_credits as $credit) {
                    array_push($response['credits'], General::parseEntityForList($credit, 'creditos'));
                }

                $response['status'] = true;
                $response['keep_loading'] = $print_report;
                $response['print_report'] = $print_report;
                $response['table'] = array(
                    'table' => 'creditos',
                    'table_pk' => 'cred_id',
                    'entity_name' => 'credito',
                );
                $response['success'] = 'El credito fue guardado!';
            } else {
                $response['status'] = false;
                $response['error'] = 'No se han creado creditos para ser guardados';
            }
        } catch (Exception $exc) {
            $response['status'] = false;
            $response['error'] = 'Ups! Ocurrio un error, intente nuevamente por favor. Detalle: ' . $exc->getMessage();
        }

        echo json_encode($response);
    }

    public function searchCreditConcept() {
        $conc_id = $this->input->post('conc_id');
        $con_prop = $this->input->post('con_prop');
        $con_inq = $this->input->post('con_inq');
        $con_id = $this->input->post('con_id');

        $contract_debts = array();
        $services_debts = array();
        $services_control_debts = array();
        $total_intereses = 0;
        $total_iva = 0;
        $total_rent = 0;
        $response['exist_contract'] = false;
        $response['show_send_mail'] = false;

        $concept = $this->basic->get_where('conceptos', array('conc_id' => $conc_id))->row_array();

        $client_propietary = $this->basic->get_where('clientes', array('client_name' => $con_prop))->row_array();

        $response['iva_percibe'] = json_decode($concept['iva_percibe']);
        $response['interes_percibe'] = json_decode($concept['interes_percibe']);

        $services_array = $this->basic->getAllServicesArray();

        if ($con_id) {
            $contract = $this->basic->get_where('contratos', array('con_id' => $con_id))->row_array();
        } else {
            $contract = Contract::searchContractForPayment($concept, $con_prop, $con_inq);
        }

        $is_contract_concept = Contract::isContractConcept($concept['conc_desc']);

        if (!empty($contract) || !$is_contract_concept) {
            $response['exist_contract'] = true;
            $response['print_report'] = Report::mustPrintReport($concept['conc_desc']);

            if (!empty($contract)) {
                $response['show_send_mail'] = true;
                if (!empty($client_propietary['client_email']) && filter_var($client_propietary['client_email'], FILTER_VALIDATE_EMAIL)) {
                    $response['enable_send_mail'] = true;
                } else {
                    $response['enable_send_mail'] = false;
                }

                $this->data['porc'] = $contract['con_porc'];
                $this->data['iva'] = $contract['con_iva'];
                $this->data['iva_alq'] = $contract['con_iva_alq'];
                $this->data['punitorio'] = $contract['con_punitorio'];
                $this->data['honorary_cuotes'] = $contract['honorary_cuotes'];
                $this->data['honorary_cuotes_payed'] = $contract['honorary_cuotes_payed'];
                $this->data['warranty_cuotes'] = $contract['warranty_cuotes'];
                $this->data['warranty_cuotes_payed'] = $contract['warranty_cuotes_payed'];
                $this->data['con_id'] = $contract['con_id'];
                $this->data['periods'] = $this->basic->get_where('periodos', array('per_contrato' => $contract['con_id']), 'per_id')->result_array();
                $this->data['services'] = $this->basic->get_where('servicios', array('serv_contrato' => $contract['con_id']))->result_array();
                $this->data['painted'] = Contract::getCurrentPeriodDate($this->data['periods']);
                $response['contract_info'] = $this->load->view('credits/contract_info', $this->data, TRUE);
            }

            if (!empty($contract) && $contract['con_usado']) {
                // Unicamente buscaremos las deudas si el contrato ya fue usado
                // deudas de alquileres o loteo
                if (stripos($concept['conc_desc'], 'Loteo') !== FALSE || stripos($concept['conc_desc'], 'Alquiler') !== FALSE) {
                    $last_payment_contract = Transaction::getLastPayment($contract, $concept['conc_desc']);

                    $contract_debts = Contract::getContractDebts($concept['conc_desc'], $contract);
                    if (!empty($contract_debts)) {
                        $total_rent += Transaction::getTotalAlquileres($contract_debts);
                        $total_intereses += Transaction::getTotalIntereses($contract_debts);
                    }
                }

                // deudas de servicios
                $loading_service_debt = in_array($concept['conc_desc'], $services_array);
                $loading_contract_debt = strpos($concept['conc_desc'], 'Loteo') !== false || strpos($concept['conc_desc'], 'Alquiler') !== false;

                // Agregamos la comprobacion de $last_payment_contract porque si el contrato
                // esta usado pero no existe registro de su uso esto va tirar error
                if ($loading_service_debt || $loading_contract_debt && is_array($last_payment_contract)) {

                    if ($loading_contract_debt) {
                        $services = $this->basic->get_where('servicios', array('serv_contrato' => $contract['con_id']))->result_array();
                    }

                    if ($loading_service_debt) {
                        $services = $this->basic->get_where('servicios', array('serv_contrato' => $contract['con_id'], 'serv_concepto' => $concept['conc_desc']))->result_array();
                    }

                    foreach ($services as $row) {
                        // Obtendre los ultimos pagos de los servicios del contrato
                        if ($row['serv_accion'] == 'Pagar') {
                            $service_debt = Contract::getContractServicesDebts($row['serv_concepto'], $contract);
                            if (!empty($service_debt)) {
                                $services_debts = array_merge($services_debts, $service_debt);
                            }
                        } else {
                            $last_control_serv = Transaction::getLastControl($contract, $row['serv_concepto']);
                            if ($last_control_serv) {
                                $service_control_debt = Contract::getContractServicesControls($last_control_serv);
                                if (!empty($service_control_debt)) {
                                    $services_control_debts = array_merge($services_control_debts, $service_control_debt);
                                }
                            } else {
                                // First control of services for this contract
                                array_push($services_control_debts, array(
                                    'concept' => $row['serv_concepto'],
                                    'month' => ''
                                ));
                            }
                        }
                    }
                }
            }

            $response['status'] = true;
            $response['data']['concept'] = $concept['conc_desc'];
            $response['data']['total_rent'] = $total_rent;
            $response['data']['interes'] = $total_intereses;
            $response['data']['iva'] = $total_iva;
            $response['data']['total'] = $total_rent + $total_intereses + $total_iva;
            $response['data']['contract_debts'] = array_merge($contract_debts, $services_debts);
            $response['data']['services_control_debts'] = $services_control_debts;
            $response['data']['contract'] = $contract;
        } else {
            if ($is_contract_concept) {
                $response['status'] = false;
                $response['error'] = 'No existe contrato que vincule a ' . $con_prop . ' y ' . $con_inq . ', o el mismo esta VENCIDO';
            } else {
                $response['data']['concepto'] = $concept['conc_desc'];
                $response['status'] = true;
                $response['contrato'] = false;
            }
        }

        echo json_encode($response);
    }

    public function showCreditReport() {

        $credits_info = json_decode(stripslashes(get_cookie('credits_receive')), true);

        if ($credits_info) {

            if ($credits_info['con_id']) {
                $contract = $this->basic->get_where('contratos', array('con_id' => $credits_info['con_id']))->row_array();
            } else {
                $contract = Contract::getContract($credits_info['credits']);
            }


            $receive_elements = array(
                'credits' => $credits_info['credits'],
                'services_control' => $credits_info['services_control']
            );

            if ($credits_info['send_notification']) {
                Transaction::sendNotification($credits_info['credits']);
            }

            $this->data['receives'] = Transaction::parseForReceives($receive_elements, $contract);
            $this->data['contract'] = $contract;
            $this->data['settings'] = User::getUserSettings();
            
            if (!empty($contract)) {
                $this->data['propietary'] = $this->basic->get_where('clientes', array('client_name' => $contract['con_prop']))->row_array();
            } else {
                // Cuando es un recibo por reserva no hay contrato aun
                $this->data['propietary'] = General::getPropietaryClientByCredit($credits_info['credits'][0]);
            }

            $this->data['content'] = $this->load->view('reports/receive', $this->data, TRUE);

            delete_cookie('credits_receive');

            $this->load->view('layout', $this->data);
        } else {
            // Error no existe la info.
            redirect(site_url('credits'));
        }
    }

    public function showCreditReportList($transaction_id) {
        $credits = $this->basic->get_where('creditos', array('trans' => $transaction_id))->result_array();
        $services_control = $this->basic->get_where('services_control', array('trans' => $transaction_id))->result_array();

        $contract = Contract::getContract($credits);

        $credits = Transaction::cleanCalculatedCredits($credits);
        $credits = Transaction::calculateReceiveIVA($credits, $contract);
        $credits = Transaction::calculateReceiveInteres($credits, $contract);

        $receive_elements = array(
            'credits' => $credits,
            'services_control' => $services_control
        );

        $this->data['receives'] = Transaction::parseForReceives($receive_elements, $contract);
        $this->data['contract'] = $contract;
        $this->data['settings'] = User::getUserSettings();

        if (!empty($contract)) {
            $this->data['propietary'] = $this->basic->get_where('clientes', array('client_name' => $contract['con_prop']))->row_array();
        } else {
            // Cuando es un recibo por reserva no hay contrato aun
            $this->data['propietary'] = General::getPropietaryClientByCredit($credits[0]);
        }

        $this->data['content'] = $this->load->view('reports/receive', $this->data, TRUE);

        $this->load->view('layout', $this->data);
    }

}

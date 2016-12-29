<?php

/*
 * Project: Cleanbox
 * Document: Reports
 * 
 * @author Manevi Matias Alejandro 
 * Informatic Engineer - Web Developer
 * 
 * Contact: manevimatias@gmail.com
 * 
 * All rights reserved ®
 */


class Reports extends CI_Controller {

    public function cashReport() {
        $this->data['content'] = $this->load->view('reports/cash', '', TRUE);
        $this->load->view('layout', $this->data);
    }

    public function buildCashReport() {
        $date = $this->input->post('date');
        $cash_type = $this->input->post('type');
        try {
            if ($date) {
                $response['status'] = true;
                $response['html'] = Report::buildCashReport($date, $cash_type);
            } else {
                $response['status'] = false;
                $response['error'] = 'Elegi una fecha :)';
            }
        } catch (Exception $exc) {
            $response['status'] = false;
            $response['error'] = 'Ups! Ocurrio un error, intente nuevamente por favor. Detalle: ' . $exc->getMessage();
        }

        echo json_encode($response);
    }

    public function accountReport() {
        $this->data['content'] = $this->load->view('reports/account', '', TRUE);
        $this->load->view('layout', $this->data);
    }

    public function buildAccountReport() {
        $response = array();

        try {
            $from = $this->input->post('from');
            $to = $this->input->post('to');
            $cc_id = $this->input->post('cc_id');
            $account = $this->basic->get_where('cuentas_corrientes', array('cc_id' => $cc_id))->row_array();

            if ($from && $to && $account) {
                $response['status'] = true;
                $response['html'] = Report::buildAccountReport($from, $to, $account);
            } else {
                $response['status'] = false;
                $response['error'] = 'Ups!, completa todos los campos correctamente!';
            }
        } catch (Exception $exc) {
            $response['status'] = false;
            $response['error'] = 'Ups! Ocurrio un error, intente nuevamente por favor. Detalle: ' . $exc->getMessage();
        }

        echo json_encode($response);
    }

    public function propietaryRenditionsReport() {
        $this->data['content'] = $this->load->view('reports/propietary_renditions', '', TRUE);
        $this->load->view('layout', $this->data);
    }

    public function buildPropietaryRenditionsReport() {
        $response = array();

        try {
            $from = $this->input->post('from');
            $to = $this->input->post('to');

            if ($from && $to) {
                $response['status'] = true;
                $response['html'] = Report::buildPropietaryRenditionsReport($from, $to);
            } else {
                $response['status'] = false;
                $response['error'] = 'Ups!, completa todos los campos correctamente!';
            }
        } catch (Exception $exc) {
            $response['status'] = false;
            $response['error'] = 'Ups! Ocurrio un error, intente nuevamente por favor. Detalle: ' . $exc->getMessage();
        }

        echo json_encode($response);
    }

    public function propietaryLoansReport() {
        $this->data['content'] = $this->load->view('reports/propietary_loans', '', TRUE);
        $this->load->view('layout', $this->data);
    }

    public function buildpropietaryLoansReport() {
        $response = array();

        try {
            $from = $this->input->post('from');
            $to = $this->input->post('to');

            if ($from && $to) {
                $response['status'] = true;
                $response['html'] = Report::buildpropietaryLoansReport($from, $to);
            } else {
                $response['status'] = false;
                $response['error'] = 'Ups!, completa todos los campos correctamente!';
            }
        } catch (Exception $exc) {
            $response['status'] = false;
            $response['error'] = 'Ups! Ocurrio un error, intente nuevamente por favor. Detalle: ' . $exc->getMessage();
        }

        echo json_encode($response);
    }

    public function renterPaymentHistorialReport() {
        $this->data['content'] = $this->load->view('reports/renter_payment_historial', '', TRUE);
        $this->load->view('layout', $this->data);
    }

    public function buildRenterPaymentHistorialReport() {
        $response = array();

        try {
            $year = $this->input->post('year');
            $client_id = $this->input->post('client_id');

            if ($year && $client_id) {
                $response['status'] = true;
                $response['html'] = Report::buildRenterPaymentHistorialReport($year, $client_id);
            } else {
                $response['status'] = false;
                $response['error'] = 'Ups!, completa todos los campos correctamente!';
            }
        } catch (Exception $exc) {
            $response['status'] = false;
            $response['error'] = 'Ups! Ocurrio un error, intente nuevamente por favor. Detalle: ' . $exc->getMessage();
        }

        echo json_encode($response);
    }

    public function rentersInDefaultReport() {
        $this->data['content'] = $this->load->view('reports/renters_in_default', '', TRUE);
        $this->load->view('layout', $this->data);
    }

    public function buildRentersInDefaultReport() {
        $response = array();

        try {
            $date = $this->input->post('date');

            if ($date) {
                $response['status'] = true;
                $response['html'] = Report::buildRentersInDefaultReport($date);
            } else {
                $response['status'] = false;
                $response['error'] = 'Ups!, completa todos los campos correctamente!';
            }
        } catch (Exception $exc) {
            $response['status'] = false;
            $response['error'] = 'Ups! Ocurrio un error, intente nuevamente por favor. Detalle: ' . $exc->getMessage();
        }

        echo json_encode($response);
    }

    public function pendingRenditionsReport() {
        $this->data['content'] = $this->load->view('reports/pending_renditions', '', TRUE);
        $this->load->view('layout', $this->data);
    }

    public function buildPendingRenditionsReport() {
        $response = array();

        try {
            $from = $this->input->post('from');
            $to = $this->input->post('to');

            if ($from && $to) {
                $response['status'] = true;
                $response['html'] = Report::buildPendingRenditionsReport($from, $to);
            } else {
                $response['status'] = false;
                $response['error'] = 'Ups!, completa todos los campos correctamente!';
            }
        } catch (Exception $exc) {
            $response['status'] = false;
            $response['error'] = 'Ups! Ocurrio un error, intente nuevamente por favor. Detalle: ' . $exc->getMessage();
        }

        echo json_encode($response);
    }

    public function renditionsPercentReport() {
        $this->data['content'] = $this->load->view('reports/renditions_percent', '', TRUE);
        $this->load->view('layout', $this->data);
    }

    public function buildRenditionsPercentReport() {
        $response = array();

        try {
            $from = $this->input->post('from');
            $to = $this->input->post('to');

            if ($from && $to) {
                $response['status'] = true;
                $response['html'] = Report::buildRenditionsPercentReport($from, $to);
            } else {
                $response['status'] = false;
                $response['error'] = 'Ups!, completa todos los campos correctamente!';
            }
        } catch (Exception $exc) {
            $response['status'] = false;
            $response['error'] = 'Ups! Ocurrio un error, intente nuevamente por favor. Detalle: ' . $exc->getMessage();
        }

        echo json_encode($response);
    }

    public function contractsDeclinationReport() {
        $this->data['content'] = $this->load->view('reports/contracts_declination', '$this->data', TRUE);
        $this->load->view('layout', $this->data);
    }

    public function buildContractsDeclinationReport() {
        $response = array();

        try {
            $from = $this->input->post('from');

            if ($from) {
                $response['status'] = true;
                $response['html'] = Report::buildContractsDeclinationReport($from);
            } else {
                $response['status'] = false;
                $response['error'] = 'Ups!, completa todos los campos correctamente!';
            }
        } catch (Exception $exc) {
            $response['status'] = false;
            $response['error'] = 'Ups! Ocurrio un error, intente nuevamente por favor. Detalle: ' . $exc->getMessage();
        }

        echo json_encode($response);
    }

    public function allConceptsMovementsReport() {
        $this->data['content'] = $this->load->view('reports/all_concepts_movements', '', TRUE);
        $this->load->view('layout', $this->data);
    }

    public function buildAllConceptsMovementsReport() {
        $response = array();

        try {
            $from = $this->input->post('from');
            $to = $this->input->post('to');

            if ($from && $to) {
                $response['status'] = true;
                $response['html'] = Report::buildAllConceptsMovementsReport($from, $to);
            } else {
                $response['status'] = false;
                $response['error'] = 'Ups!, completa todos los campos correctamente!';
            }
        } catch (Exception $exc) {
            $response['status'] = false;
            $response['error'] = 'Ups! Ocurrio un error, intente nuevamente por favor. Detalle: ' . $exc->getMessage();
        }

        echo json_encode($response);
    }

    public function bankTransactionsReport() {
        $this->data['content'] = $this->load->view('reports/bank_transactions', '', TRUE);
        $this->load->view('layout', $this->data);
    }

    public function buildBankTransactionsReport() {
        $response = array();

        try {
            $from = $this->input->post('from');
            $to = $this->input->post('to');

            if ($from && $to) {
                $response['status'] = true;
                $response['html'] = Report::buildBankTransactionsReport($from, $to);
            } else {
                $response['status'] = false;
                $response['error'] = 'Ups!, completa todos los campos correctamente!';
            }
        } catch (Exception $exc) {
            $response['status'] = false;
            $response['error'] = 'Ups! Ocurrio un error, intente nuevamente por favor. Detalle: ' . $exc->getMessage();
        }

        echo json_encode($response);
    }

    public function generalBalanceReport() {
        $this->data['content'] = $this->load->view('reports/general_balance', '', TRUE);
        $this->load->view('layout', $this->data);
    }

    public function buildGeneralBalanceReport() {
        $response = array();

        try {
            $from = $this->input->post('from');
            $to = $this->input->post('to');

            if ($from && $to) {
                $response['status'] = true;
                $response['html'] = Report::buildGeneralBalanceReport($from, $to);
            } else {
                $response['status'] = false;
                $response['error'] = 'Ups!, completa todos los campos correctamente!';
            }
        } catch (Exception $exc) {
            $response['status'] = false;
            $response['error'] = 'Ups! Ocurrio un error, intente nuevamente por favor. Detalle: ' . $exc->getMessage();
        }

        echo json_encode($response);
    }

    public function endedMaintenancesReport() {
        $this->data['content'] = $this->load->view('reports/ended_maintenances', '', TRUE);
        $this->load->view('layout', $this->data);
    }

    public function buildEndedMaintenancesReport() {
        $response = array();

        try {
            $from = $this->input->post('from');
            $to = $this->input->post('to');

            if ($from && $to) {
                $response['status'] = true;
                $response['html'] = Report::buildEndedMaintenancesReport($from, $to);
            } else {
                $response['status'] = false;
                $response['error'] = 'Ups!, completa todos los campos correctamente!';
            }
        } catch (Exception $exc) {
            $response['status'] = false;
            $response['error'] = 'Ups! Ocurrio un error, intente nuevamente por favor. Detalle: ' . $exc->getMessage();
        }

        echo json_encode($response);
    }

    public function rentersPaymentPercentReport() {
        $this->data['content'] = $this->load->view('reports/renters_payment_percent', '', TRUE);
        $this->load->view('layout', $this->data);
    }

    public function buildRentersPaymentPercentReport() {
        $response = array();

        try {
            $month = $this->input->post('month');

            if ($month) {
                $response['status'] = true;
                $response['html'] = Report::buildRentersPaymentPercentReport($month);
            } else {
                $response['status'] = false;
                $response['error'] = 'Ups!, completa todos los campos correctamente!';
            }
        } catch (Exception $exc) {
            $response['status'] = false;
            $response['error'] = 'Ups! Ocurrio un error, intente nuevamente por favor. Detalle: ' . $exc->getMessage();
        }

        echo json_encode($response);
    }

}

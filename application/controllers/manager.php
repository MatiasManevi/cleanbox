<?php

/*
 * Project: Cleanbox
 * Document: Manager
 * 
 * @author Manevi Matias Alejandro 
 * Informatic Engineer - Web Developer
 * 
 * Contact: manevimatias@gmail.com
 * 
 * All rights reserved ®
 */

class Manager extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    /* INICIO */

    public function index() {
        // $cuentas = $this->basic->get_all('cuentas_corrientes')->result_array();
        //  foreach ($cuentas as $cuenta) {
        //      $loans = Transaction::getAccountLoans($cuenta);
        //      $loan_sum = 0;
        //      foreach ($loans as $row) {
        //          $loan_sum += $row['cred_monto'];
        //      }
        //      if($loan_sum > 0){
        //         echo '<pre>';
        //         echo $cuenta['cc_prop'] . ' tiene que devolver $ ' . $loan_sum . '<br><br>';
        //         echo '<br>';
        //         echo 'devuelto';
        //         echo '<br>';
        //         $cuenta['loans'] = $loan_sum;
        //         $this->basic->save('cuentas_corrientes', 'cc_id', $cuenta);
        //         print_r($cuenta);
        //      }
        //  }
        //  die;


        // $this->basic->repairTables();

        $this->data['row_count'] = 0;

        $this->loadHomeData();

        $this->load->view('layout', $this->data);
    }

    public function loadHomeData() {
        $safe_box = $this->basic->get_where('cuentas_corrientes', array('cc_prop' => 'CAJA FUERTE'))->row_array();
        $this->data['safe_box'] = $safe_box ? $safe_box['cc_saldo'] : '0.00';

        $this->data['daily_cash'] = $this->load->view('daily_cash', $this->data, TRUE);

        if (User::codeControl()) {
            $this->data['codes_generator'] = $this->load->view('codes_generator', $this->data, TRUE);
        }

        $this->data['home_maintenances'] = $this->getHomeMaintenances();
    }

    public function calculateBeginCash() {
        $response['status'] = true;
        $response['amount'] = round(Cash::getBeginCash(date('d-m-Y')), 2);

        echo json_encode($response);
    }

    public function calculateProgressiveCash() {
        $response['status'] = true;
        $monthly_progressive = Cash::getBalance('Caja');
        $monthly_progressive = $monthly_progressive > 0 ? $monthly_progressive : '0.00';
        $response['amount'] = round($monthly_progressive, 2);

        echo json_encode($response);   
    }

    public function getHomeMaintenances() {
        $this->data['maintenances'] = array();

        $maintenances = $this->basic->get_where('mantenimientos', array('mant_status' => 2))->result_array();

        if (count($maintenances) > 0) {

            foreach ($maintenances as $maintenance) {

                if ($maintenance['mant_date_deadline']) {
                    $deadline_date = strtotime($maintenance['mant_date_deadline']);
                } else {
                    $deadline_date = false;
                }

                $providers = strlen($maintenance['mant_prov_1']) > 0 ? $maintenance['mant_prov_1'] : '';
                $providers .= (strlen($providers) > 0 ? ',' : '') . strlen($maintenance['mant_prov_2']) > 0 ? $maintenance['mant_prov_2'] . ',' : '';
                $providers .= (strlen($providers) > 0 ? ',' : '') . strlen($maintenance['mant_prov_3']) > 0 ? $maintenance['mant_prov_3'] . ',' : '';

                array_push($this->data['maintenances'], array(
                    'id' => $maintenance['mant_id'],
                    'address' => $maintenance['mant_domicilio'],
                    'provider' => $providers,
                    'prop' => $maintenance['mant_prop'],
                    'inq' => $maintenance['mant_inq'],
                    'priority' => $maintenance['mant_prioridad'],
                    'desc' => $maintenance['mant_desc'],
                    'deadline_date' => $deadline_date,
                ));
            }

            $this->data['maintenances'] = General::msort($this->data['maintenances'], 'deadline_date');
        }

        return $this->load->view('home_maintenances', $this->data, TRUE);
    }

    /* END INICIO */

    public function login($message = '') {
        $this->data['head'] = $this->load->view('head', '', TRUE);
        $this->data['message'] = $message;
        $this->load->view('users/login', $this->data);
    }

    public function logout() {
        $this->session->sess_destroy();
        $this->emptyCodes();
        $this->login();
    }

    public function make_login() {
        $this->CI = & get_instance();

//        if (date('d-m-Y') == '10-02-2015') {
//            $error_message = 'El periodo de prueba del sistema ha caducado!';
//            $this->login($error_message);
//            return false;
//        }

        if (trim($_POST['user']) == '' OR trim($_POST['password']) == '') {
            $error_message = 'Escriba su usuario y contraseña';
            $this->login($error_message);
            return false;
        }

        if ($this->session->userdata('username') == $_POST['user']) {
            redirect('home', 'location', 301);
            return true;
        }

        $this->CI->db->where('username', $_POST['user']);
        $user = $this->CI->db->get_where('man_users')->row_array();

        if (!empty($user)) {

            if ($_POST['password'] != $user['password']) {
                $error_message = 'Error de contraseña';
                $this->login($error_message);
                return false;
            }

            $this->session->sess_destroy();
            $this->session->sess_create();
            unset($user['password']);
            $this->session->set_userdata($user);
            $this->session->set_userdata(array('logged_in' => true));
            $this->emptyCodes();
            redirect('home', 'location', 301);
        } else {
            $error_message = 'El usuario no existe';
            $this->login($error_message);
            return false;
        }
    }

    public function emptyCodes() {
        $codes = $this->basic->get_all('codes')->result_array();
        foreach ($codes as $code) {
            $this->basic->del('codes', 'code_id', $code['code_id']);
        }
    }

    // START SECTION FILTERS

    public function refresh() {
        $response = array();

        try {
            $table = $this->input->post('table');
            $table_pk = $this->input->post('table_pk');
            $table_order = $this->input->post('table_order');
            $entity_name = $this->input->post('entity_name');

            $response['entities'] = array();
            $response['status'] = true;

            $limit = 30;

            if ($table == 'creditos' || $table == 'debitos') {
                $entities = $this->basic->get_where($table, array('is_transfer' => 0), $table_order, 'desc', $limit)->result_array();
            } else {
                if ($table == 'transferencias_to_safe') {
                    $entities = $this->basic->get_where('creditos', array('is_transfer' => 1), 'cred_id', 'desc', $limit)->result_array();
                } else if ($table == 'transferencias_to_cash') {
                    $entities = $this->basic->get_where('debitos', array('is_transfer' => 1), 'deb_id', 'desc', $limit)->result_array();
                } else {
                    if ($table == 'mantenimientos') {
                        $or = 'desc';
                    } else {
                        $or = 'asc';
                    }
                    $entities = $this->basic->get_where($table, array(), $table_order, $or, $limit)->result_array();
                }
            }

            foreach ($entities as $entity) {
                array_push($response['entities'], General::parseEntityForList($entity, $table));
            }

            $response['table'] = array(
                'table' => $table,
                'table_pk' => $table_pk,
                'entity_name' => $entity_name,
            );
        } catch (Exception $exc) {
            $response['status'] = false;
            $response['error'] = 'Ups! Ocurrio un error, intente nuevamente por favor. Detalle: ' . $exc->getMessage();
        }

        echo json_encode($response);
    }

    /**
     * Busca todos aquellas filas que sean de una tabla y coincidan con un id determinado
     */
    public function searchAllOfId() {
        $response = array();

        try {
            $value_searched = $this->input->post('value_searched');
            $id = $this->input->post('id');
            $response['entities'] = array();

            if ($id != false) {
                switch ($value_searched) {
                    case 'cc_id':
                        // Devuelve los contratos de un propietario
                    $account = $this->basic->get_where('cuentas_corrientes', array($value_searched => $id))->row_array();

                    $entities = $this->basic->get_where('contratos', array($value_searched => $account['cc_id']))->result_array();
                    /* solo para davinia y rima */
                    $entities2 = $this->basic->get_where('contratos', array('con_prop' => $account['cc_prop']))->result_array();
                    foreach ($entities2 as $entity2) {
                        if (!in_array($entity2, $entities))
                            array_push($entities, $entity2);
                    }
                    /* solo para davinia y rima */

                    foreach ($entities as $entity) {
                        array_push($response['entities'], General::parseEntityForList($entity, 'contratos'));
                    }

                    $response['table'] = array(
                        'table' => 'contratos',
                        'table_pk' => 'con_id',
                        'entity_name' => 'contrato'
                    );
                    break;
                    case 'prop_prop':

                        // Devuelve las propiedades de un cliente
                    $account = $this->basic->get_where('cuentas_corrientes', array('cc_id' => $id))->row_array();

                    $entities = $this->basic->get_where('propiedades', array('cc_id' => $account['cc_id']))->result_array();
                    /* solo para davinia y rima */
                    $entities2 = $this->basic->get_where('propiedades', array($value_searched => $account['cc_prop']))->result_array();
                    foreach ($entities2 as $entity2) {
                        if (!in_array($entity2, $entities))
                            array_push($entities, $entity2);
                    }
                    /* solo para davinia y rima */

                    $response['table'] = array(
                        'table' => 'propiedades',
                        'table_pk' => 'prop_id',
                        'entity_name' => 'propiedad'
                    );

                    foreach ($entities as $entity) {
                        array_push($response['entities'], General::parseEntityForList($entity, 'propiedades'));
                    }
                    break;
                }
            }

            $response['status'] = true;
        } catch (Exception $exc) {
            $response['status'] = false;
            $response['error'] = 'Ups! Ocurrio un error, intente nuevamente por favor. Detalle: ' . $exc->getMessage();
        }

        echo json_encode($response);
    }

    /**
     * Busca una fila que sea de una tabla y coincida con un id determinado
     */
    public function searchRow() {
        $response = array();

        try {
            $table = $this->input->post('table');
            $table_pk = $this->input->post('table_pk');
            $id = $this->input->post('id');
            $entity_name = $this->input->post('entity_name');

            $response['status'] = true;
            $response['entity'] = $this->basic->get_where($table, array($table_pk => $id))->row_array();
            $response['entity'] = General::parseEntityForList($response['entity'], $table);
            $response['table'] = array(
                'table' => $table,
                'table_pk' => $table_pk,
                'entity_name' => $entity_name,
            );
        } catch (Exception $exc) {
            $response['status'] = false;
            $response['error'] = 'Ups! Ocurrio un error, intente nuevamente por favor. Detalle: ' . $exc->getMessage();
        }

        echo json_encode($response);
    }

    /**
     * Busca un valor determinado de una fila que sea de una tabla y coincida con un id determinado
     */
    public function searchValue() {
        $response = array();

        try {
            $table = $this->input->post('table');
            $table_pk = $this->input->post('table_pk');
            $id = $this->input->post('id');
            $value_searched = $this->input->post('value_searched');

            $entity = $this->basic->get_where($table, array($table_pk => $id))->row_array();

            if ($table == 'cuentas_corrientes') {
                $response['account_amount'] = $entity['cc_saldo'] + $entity['cc_varios'];
            }

            $response['status'] = true;
            $response['value'] = $entity[$value_searched];
            $response['id'] = $entity[$table_pk];
        } catch (Exception $exc) {
            $response['status'] = false;
            $response['error'] = 'Ups! Ocurrio un error, intente nuevamente por favor. Detalle: ' . $exc->getMessage();
        }

        echo json_encode($response);
    }

    public function getMaintenancesFiltered($prop, $inq, $prov) {
        if ($prop && $inq && $prov) {
            $maintenances = $this->basic->get_where('mantenimientos', array('mant_prop' => $prop, 'mant_inq' => $inq, 'mant_prov_1' => $prov), 'mant_id', 'desc')->result_array();
            if (empty($maintenances)) {
                $maintenances = $this->basic->get_where('mantenimientos', array('mant_prop' => $prop, 'mant_inq' => $inq, 'mant_prov_2' => $prov), 'mant_id', 'desc')->result_array();
                if (empty($maintenances)) {
                    $maintenances = $this->basic->get_where('mantenimientos', array('mant_prop' => $prop, 'mant_inq' => $inq, 'mant_prov_3' => $prov), 'mant_id', 'desc')->result_array();
                }
            }
        }
        if ($prop && $inq && !$prov) {
            $maintenances = $this->basic->get_where('mantenimientos', array('mant_prop' => $prop, 'mant_inq' => $inq), 'mant_id', 'desc')->result_array();
        }
        if ($prop && !$inq && $prov) {
            $maintenances = $this->basic->get_where('mantenimientos', array('mant_prop' => $prop, 'mant_prov_1' => $prov), 'mant_id', 'desc')->result_array();
            if (empty($maintenances)) {
                $maintenances = $this->basic->get_where('mantenimientos', array('mant_prop' => $prop, 'mant_prov_2' => $prov), 'mant_id', 'desc')->result_array();
                if (empty($maintenances)) {
                    $maintenances = $this->basic->get_where('mantenimientos', array('mant_prop' => $prop, 'mant_prov_3' => $prov), 'mant_id', 'desc')->result_array();
                }
            }
        }
        if ($prop && !$inq && !$prov) {
            $maintenances = $this->basic->get_where('mantenimientos', array('mant_prop' => $prop), 'mant_id', 'desc')->result_array();
        }
        if (!$prop && $inq && $prov) {
            $maintenances = $this->basic->get_where('mantenimientos', array('mant_inq' => $inq, 'mant_prov_1' => $prov), 'mant_id', 'desc')->result_array();
            if (empty($maintenances)) {
                $maintenances = $this->basic->get_where('mantenimientos', array('mant_inq' => $inq, 'mant_prov_2' => $prov), 'mant_id', 'desc')->result_array();
                if (empty($maintenances)) {
                    $maintenances = $this->basic->get_where('mantenimientos', array('mant_inq' => $inq, 'mant_prov_3' => $prov), 'mant_id', 'desc')->result_array();
                }
            }
        }
        if (!$prop && $inq && !$prov) {
            $maintenances = $this->basic->get_where('mantenimientos', array('mant_inq' => $inq), 'mant_id', 'desc')->result_array();
        }
        if (!$prop && !$inq && $prov) {
            $maintenances = $this->basic->get_where('mantenimientos', array('mant_prov_1' => $prov), 'mant_id', 'desc')->result_array();
            if (empty($maintenances)) {
                $maintenances = $this->basic->get_where('mantenimientos', array('mant_prov_2' => $prov), 'mant_id', 'desc')->result_array();
                if (empty($maintenances)) {
                    $maintenances = $this->basic->get_where('mantenimientos', array('mant_prov_3' => $prov), 'mant_id', 'desc')->result_array();
                }
            }
        }
        if (!$prop && !$inq && !$prov) {
            $maintenances = $this->basic->get_where('mantenimientos', array(), 'mant_id', 'desc')->result_array();
        }

        return $maintenances;
    }

    public function getDebitsFiltered($account, $concept, $month) {
        return $this->basic->like_and_where('debitos', array(
            'deb_cc' => $account,
            'deb_mes' => $month,
            'deb_concepto' => $concept
        ), array(
            'is_transfer' => 0
        ))->result_array();

        if ($account && $concept)
            return $this->basic->get_where('debitos', array('deb_cc' => $account, 'deb_concepto' => $concept, 'is_transfer' => 0), 'deb_id')->result_array();
        if ($account && !$concept)
            return $this->basic->get_where('debitos', array('deb_cc' => $account, 'is_transfer' => 0), 'deb_id')->result_array();
        if (!$account && $concept)
            return $this->basic->get_where('debitos', array('deb_concepto' => $concept, 'is_transfer' => 0), 'deb_id')->result_array();
        if (!$account && !$concept)
            return $this->basic->get_where('debitos', array('is_transfer' => 0), 'deb_id')->result_array();
    }

    public function getCreditsFiltered($account, $renter, $concept, $month) {
        return $this->basic->like_and_where('creditos', array(
            'cred_cc' => $account,
            'cred_depositante' => $renter,
            'cred_mes_alq' => $month,
            'cred_concepto' => $concept
        ), array(
            'is_transfer' => 0
        ))->result_array();

        if ($account && $renter && $concept)
            return $this->basic->get_where('creditos', array('cred_cc' => $account, 'is_transfer' => 0, 'cred_depositante' => $renter, 'cred_concepto' => $concept), 'cred_id')->result_array();
        if ($account && $renter && !$concept)
            return $this->basic->get_where('creditos', array('cred_cc' => $account, 'is_transfer' => 0, 'cred_depositante' => $renter), 'cred_id')->result_array();
        if ($account && !$renter && $concept)
            return $this->basic->get_where('creditos', array('cred_cc' => $account, 'is_transfer' => 0, 'cred_concepto' => $concept), 'cred_id')->result_array();
        if ($account && !$renter && !$concept)
            return $this->basic->get_where('creditos', array('cred_cc' => $account, 'is_transfer' => 0), 'cred_id')->result_array();
        if (!$account && $renter && $concept)
            return $this->basic->get_where('creditos', array('cred_depositante' => $renter, 'is_transfer' => 0, 'cred_concepto' => $concept), 'cred_id')->result_array();
        if (!$account && $renter && !$concept)
            return $this->basic->get_where('creditos', array('cred_depositante' => $renter, 'is_transfer' => 0), 'cred_id')->result_array();
        if (!$account && !$renter && $concept)
            return $this->basic->get_where('creditos', array('cred_concepto' => $concept, 'is_transfer' => 0), 'cred_id')->result_array();
        if (!$account && !$renter && !$concept)
            return $this->basic->get_where('creditos', array('is_transfer' => 0), 'cred_id')->result_array();
    }

    public function filterByValues() {
        try {
            $table = $this->input->post('table');
            $from = $this->input->post('from');
            $to = $this->input->post('to');
            $propietary = $this->input->post('propietary');
            $response['entities'] = array();
            $entities = array();

            switch ($table) {
                case 'comentarios':
                if ($propietary) {
                    $comentaries = $this->basic->get_where($table, array('com_prop' => $propietary))->result_array();
                    if ($from && $to) {
                        foreach ($comentaries as $row) {
                            if (General::isBetweenDates($row['com_date'], $from, $to)) {
                                array_push($entities, $row);
                            }
                        }
                    } else {
                        $entities = $comentaries;
                    }
                } else if ($from && $to) {
                    if ($propietary) {
                        $comentaries = $this->basic->get_where($table, array('com_prop' => $propietary))->result_array();
                    } else {
                        $comentaries = $this->basic->get_all($table)->result_array();
                    }
                    foreach ($comentaries as $row) {
                        if (General::isBetweenDates($row['com_date'], $from, $to)) {
                            array_push($entities, $row);
                        }
                    }
                }

                foreach ($entities as $entity) {
                    array_push($response['entities'], General::parseEntityForList($entity, $table));
                }

                $response['table'] = array(
                    'table' => $table,
                    'table_pk' => 'com_id',
                    'entity_name' => 'comentario',
                );
                break;
                case 'mantenimientos':
                $propietary = $this->input->post('propietary');
                $renter = $this->input->post('renter');
                $provider = $this->input->post('provider');

                $maintenances = $this->getMaintenancesFiltered($propietary, $renter, $provider);

                if ($from && $to) {
                    foreach ($maintenances as $maintenance) {
                        if ($maintenance['mant_date_deadline']) {
                            if (General::isBetweenDates($maintenance['mant_date_deadline'], $from, $to)) {
                                array_push($entities, $maintenance);
                            }
                        }
                    }
                } else {
                    $entities = $maintenances;
                }

                foreach ($entities as $entity) {
                    array_push($response['entities'], General::parseEntityForList($entity, $table));
                }

                $response['table'] = array(
                    'table' => $table,
                    'table_pk' => 'mant_id',
                    'entity_name' => 'mantenimiento',
                );
                break;
                case 'creditos':
                $propietary = $this->input->post('propietary');
                $renter = $this->input->post('renter');
                $concept = $this->input->post('concept');
                $month = $this->input->post('month');

                $credits = $this->getCreditsFiltered($propietary, $renter, $concept, $month);

                if ($from && $to) {
                    foreach ($credits as $row) {
                        if (General::isBetweenDates($row['cred_fecha'], $from, $to)) {
                            array_push($entities, $row);
                        }
                    }
                } else {
                    $entities = $credits;
                }

                foreach ($entities as $entity) {
                    array_push($response['entities'], General::parseEntityForList($entity, $table));
                }

                $response['table'] = array(
                    'table' => $table,
                    'table_pk' => 'cred_id',
                    'entity_name' => 'credito',
                );
                break;
                case 'debitos':
                $propietary = $this->input->post('propietary');
                $concept = $this->input->post('concept');
                $month = $this->input->post('month');

                $debits = $this->getDebitsFiltered($propietary, $concept, $month);

                if ($from && $to) {
                    foreach ($debits as $row) {
                        if (General::isBetweenDates($row['deb_fecha'], $from, $to)) {
                            array_push($entities, $row);
                        }
                    }
                } else {
                    $entities = $debits;
                }

                foreach ($entities as $entity) {
                    array_push($response['entities'], General::parseEntityForList($entity, $table));
                }

                $response['table'] = array(
                    'table' => $table,
                    'table_pk' => 'deb_id',
                    'entity_name' => 'debito',
                );
                break;
                case 'transferencias_to_safe':
                if ($from && $to) {
                    $transfers_to_safe = $this->basic->get_where('creditos', array('is_transfer' => 1), 'cred_id')->result_array();

                    foreach ($transfers_to_safe as $row) {
                        if (General::isBetweenDates($row['cred_fecha'], $from, $to)) {
                            array_push($entities, $row);
                        }
                    }
                }

                foreach ($entities as $entity) {
                    array_push($response['entities'], General::parseEntityForList($entity, $table));
                }

                $response['table'] = array(
                    'table' => $table,
                    'table_pk' => 'transf_id',
                    'entity_name' => 'transferencia',
                );
                break;
                case 'transferencias_to_cash':
                if ($from && $to) {
                    $transfers_to_cash = $this->basic->get_where('debitos', array('is_transfer' => 1), 'deb_id')->result_array();

                    foreach ($transfers_to_cash as $row) {
                        if (General::isBetweenDates($row['deb_fecha'], $from, $to)) {
                            array_push($entities, $row);
                        }
                    }
                }

                foreach ($entities as $entity) {
                    array_push($response['entities'], General::parseEntityForList($entity, $table));
                }

                $response['table'] = array(
                    'table' => $table,
                    'table_pk' => 'transf_id',
                    'entity_name' => 'transferencia',
                );
                break;
            }

            $response['status'] = true;
        } catch (Exception $exc) {
            $response['status'] = false;
            $response['error'] = 'Ocurrio un error al eliminar el registro, intente nuevamente por favor';
        }

        echo json_encode($response);
    }

    public function filterByValue() {
        $response = array();

        try {
            $table = $this->input->post('table');
            $key = $this->input->post('key');
            $value = $this->input->post('value');

            $response['status'] = true;

            switch ($table) {
                case 'proveedores':
                $response['entities'] = array();
                $entities = array();
                $providers = $this->basic->get_all('proveedores')->result_array();
                $areas = $this->basic->get_all('areas_proveedores')->result_array();

                foreach ($providers as $provider) {
                    foreach ($areas as $area) {
                        if ($area[$key] == $value && $area['area_prov'] == $provider['prov_id']) {
                            array_push($entities, $provider);
                        }
                    }
                }

                foreach ($entities as $entity) {
                    array_push($response['entities'], General::parseEntityForList($entity, 'proveedores'));
                }

                $response['table'] = array(
                    'table' => 'proveedores',
                    'table_pk' => 'prov_id',
                    'entity_name' => 'proveedor',
                );
                break;
            }
        } catch (Exception $exc) {
            $response['status'] = false;
            $response['error'] = 'Ups! Ocurrio un error, intente nuevamente por favor. Detalle: ' . $exc->getMessage();
        }

        echo json_encode($response);
    }

    public function autocomplete($table, $type = false) {
        $term = $this->input->get('term');

        if ($term) {
            $response = array();
            $data = array();
            $items = $this->basic->get_auto($term, $table, $type);
            if ($items->num_rows() > 0) {
                foreach ($items->result_array() as $item) {
                    if ($table == 'clientes') {
                        $data['id'] = $item['client_id'];
                        $data['label'] = $item['client_name'];
                        $data['value'] = $item['client_name'];
                        array_push($response, $data);
                    }
                    if ($table == 'cuentas_corrientes') {
                        $data['id'] = $item['cc_id'];
                        $data['label'] = $item['cc_prop'];
                        $data['value'] = $item['cc_prop'];
                        array_push($response, $data);
                    }
                    if ($table == 'contratos') {
                        $data['id'] = $item['con_id'];
                        $data['label'] = $item['con_prop'];
                        $data['value'] = $item['con_prop'];
                        array_push($response, $data);
                    }
                    if ($table == 'conceptos') {
                        $data['id'] = $item['conc_id'];
                        $data['label'] = $item['conc_desc'];
                        $data['value'] = $item['conc_desc'];
                        array_push($response, $data);
                    }
                    if ($table == 'propiedades') {
                        $data['id'] = $item['prop_id'];
                        $data['label'] = $item['prop_dom'];
                        $data['value'] = $item['prop_dom'];
                        array_push($response, $data);
                    }
                    if ($table == 'proveedores') {
                        $data['id'] = $item['prov_id'];
                        $data['label'] = $item['prov_name'];
                        $data['value'] = $item['prov_name'];
                        array_push($response, $data);
                    }
                    if ($table == 'providers_rols') {
                        $data['id'] = $item['id'];
                        $data['label'] = $item['rol'];
                        $data['value'] = $item['rol'];
                        array_push($response, $data);
                    }
                }
            }
            echo json_encode($response);
        }
    }

    // END SECTION FILTERS
    // ENTITY COMMON FUNCTIONS

    public function searchProvAreas($id) {
        return $this->basic->get_where('areas_proveedores', array('area_prov' => $id), 'area_id')->result_array();
    }

    public function searchProvNota($id) {
        return $this->basic->get_where('proveedores_nota', array('nota_prov_id' => $id))->row_array();
    }

    public function searchContractPeriods($id) {
        return $this->basic->get_where('periodos', array('per_contrato' => $id), 'per_id')->result_array();
    }

    public function searchInspectionPictures($id) {
        return $this->basic->get_where('inspection_pictures', array('inspection_id' => $id), 'id')->result_array();
    }

    public function searchContractServices($id) {
        return $this->basic->get_where('servicios', array('serv_contrato' => $id), 'serv_id')->result_array();
    }

    public function getEntitiesOnScrollDown() {
        $response = array();

        try {
            $response['entities'] = array();

            $per_page = 30; // en cada recarga se muestran las siguientes 30 entidades

            $row_count = $this->input->post('row_count') - 1;
            $page = $this->input->post('page') + 1;

            $start = ($page - 1) * $per_page;
            $limit = $row_count + $per_page;

            $table = $this->input->post('table');
            $table_pk = $this->input->post('table_pk');
            $entity_name = $this->input->post('entity_name');
            $table_order = $this->input->post('table_order');

            if ($table == 'creditos' || $table == 'debitos') {
                $entities = $this->basic->get_where($table, array('is_transfer' => 0), $table_order, 'desc')->result_array();
            } else {
                if ($table == 'mantenimientos') {
                    $or = 'desc';
                } else {
                    $or = 'asc';
                }
                $entities = $this->basic->get_where($table, array(), $table_order, $or)->result_array();
            }

            $entities = array_slice($entities, $start, $limit);

            foreach ($entities as $entity) {
                array_push($response['entities'], General::parseEntityForList($entity, $table));
            }

            $response['table'] = array(
                'table' => $table,
                'table_pk' => $table_pk,
                'entity_name' => $entity_name,
            );

            if (empty($entities)) {
                $response['info'] = 'No quedan registros por mostrar!';
            }
            
            $response['page'] = $page;
            $response['row_count'] = $limit;
            $response['status'] = true;
        } catch (Exception $exc) {
            $response['status'] = false;
            $response['error'] = 'Ocurrio un error al cargar el registro, intente nuevamente por favor';
        }

        echo json_encode($response);
    }

    public function loadEntityToEdit() {
        $response = array();

        try {
            $id = $this->input->post('id');
            $table = $this->input->post('table');
            $table_pk = $this->input->post('table_pk');
            $entity_name = $this->input->post('entity_name');

            $response['status'] = true;
            $response['entity'] = $this->basic->get_where($table, array($table_pk => $id))->row_array();

            if ($table == 'contratos') {
                $response['periods'] = $this->searchContractPeriods($id);
                $response['services'] = $this->searchContractServices($id);
            }
            if ($table == 'inspections') {
                $response['entity']['pictures'] = $this->searchInspectionPictures($id);
            }
            if ($table == 'proveedores') {
                $response['areas'] = $this->searchProvAreas($id);
                $response['nota'] = $this->searchProvNota($id);
            }

            $response['table'] = $table;
            $response['entity_name'] = $entity_name;
        } catch (Exception $exc) {
            $response['status'] = false;
            $response['error'] = 'Ocurrio un error al cargar el registro, intente nuevamente por favor';
        }

        echo json_encode($response);
    }

    public function deleteEntity() {
        $response = array();

        try {
            $id = $this->input->post('id');
            $table = $this->input->post('table');
            $table_pk = $this->input->post('table_pk');
            $force_delete = json_decode($this->input->post('force_delete'));

            $entity = $this->basic->get_where($table, array($table_pk => $id))->row_array();
            if ($entity) {

                if ($table != 'debitos' && $table != 'creditos') {
                    $response['status'] = $this->basic->del($table, $table_pk, $id);
                } else {
                    $response['status'] = true;
                }

                if ($response['status']) {
                    switch ($table) {
                        case 'contratos':
                        $this->basic->del('servicios', 'serv_contrato', $id);
                        $this->basic->del('periodos', 'per_contrato', $id);
                        break;
                        case 'creditos':
                        if ($entity['cred_tipo_trans'] == 'Caja') {
                            if (Cash::canDeleteCredits($entity['cred_monto']) || $force_delete) {
                                Transaction::removeCreditAndDecreaseAccount(array($entity));
                            } else {
                                $response['status'] = false;
                                $response['error_type'] = 'delete_credit';
                                $response['id'] = $entity['cred_id'];
                                $response['error'] = 'Eliminar este credito dejara tu caja con saldo negativo, estas seguro?';
                            }
                        } else {
                            Transaction::removeCreditAndDecreaseAccount(array($entity));
                        }
                        break;
                        case 'debitos':
                        Transaction::removeDebitAndIncreaseAccount(array($entity));
                        break;
                        case 'proveedores':
                        $this->basic->del('proveedores_nota', 'nota_prov_id', $id);
                        $this->basic->del('areas_proveedores', 'area_prov', $id);
                        break;
                    }
                } else {
                    $response['error'] = 'Ocurrio un error al eliminar el registro, intente nuevamente por favor';
                }
            }
        } catch (Exception $exc) {
            $response['status'] = false;
            $response['error'] = 'Ocurrio un error al eliminar el registro, intente nuevamente por favor';
        }

        echo json_encode($response);
    }

    // END ENTITY COMMON FUNCTIONS

    public function deleteTransaction() {
        $response = array();

        try {
            $transaction_id = $this->input->post('transaction_id');

            $debits = $this->basic->get_where('debitos', array('trans' => $transaction_id, 'is_transfer' => 0))->result_array();
            $credits = $this->basic->get_where('creditos', array('trans' => $transaction_id, 'is_transfer' => 0))->result_array();

            // elimino creditos de la transaccion
            Transaction::removeCreditAndDecreaseAccount($credits);
            // elimino debitos de la transaccion
            Transaction::removeDebitAndIncreaseAccount($debits);
            // elimino control de servicios de la transaccion, si es que los tiene
            $this->basic->del('services_control', 'trans', $transaction_id);

            $response['entities'] = array();
            foreach ($credits as $credit) {
                array_push($response['entities'], General::parseEntityForList($credit, 'creditos'));
            }

            foreach ($debits as $debit) {
                array_push($response['entities'], General::parseEntityForList($debit, 'debitos'));
            }
            $response['status'] = true;
        } catch (Exception $exc) {
            $response['status'] = false;
            $response['error'] = 'Ups! Ocurrio un error, intente nuevamente por favor. Detalle: ' . $exc->getMessage();
        }

        echo json_encode($response);
    }

    public function getTransactionItems() {
        $response = array();

        try {
            $response['credits'] = array();
            $response['debits'] = array();
            $response['services_control'] = array();
            $transaction_id = $this->input->post('transaction_id');

            $response['status'] = true;

            $credits = $this->basic->get_where('creditos', array('trans' => $transaction_id))->result_array();
            $debits = $this->basic->get_where('debitos', array('trans' => $transaction_id))->result_array();
            $services_control = $this->basic->get_where('services_control', array('trans' => $transaction_id))->result_array();

            foreach ($credits as $credit) {
                array_push($response['credits'], General::parseEntityForList($credit, 'creditos'));
            }

            foreach ($services_control as $service_control) {
                array_push($response['services_control'], General::parseEntityForList($service_control, 'services_control'));
            }

            foreach ($debits as $debit) {
                array_push($response['debits'], General::parseEntityForList($debit, 'debitos'));
            }
        } catch (Exception $exc) {
            $response['status'] = false;
            $response['error'] = 'Ups! Ocurrio un error, intente nuevamente por favor. Detalle: ' . $exc->getMessage();
        }

        echo json_encode($response);
    }

    public function authorizeCode() {
        $auth_code = $this->input->post('auth_code');

        if ($auth_code) {
            $code = $this->basic->get_where('codes', array('code_code' => $auth_code))->row_array();
            if ($code) {
                $response['status'] = true;
                $this->basic->del('codes', 'code_id', $code['code_id']);
            } else {
                $response['status'] = false;
                $response['error'] = 'El Código de autorización ingresado no es válido';
            }
        } else {
            $response['status'] = false;
            $response['error'] = 'Ingrese un código';
        }

        echo json_encode($response);
    }

    public function generateCode() {
        $long = 8;
        $code = '';
        $pattern = '1234567890abcdefghijklmnopqrstuvwxyz';
        $max = strlen($pattern) - 1;

        for ($i = 0; $i < $long; $i++) {
            $code .= $pattern{mt_rand(0, $max)};
        }

        if ($code) {
            $response['status'] = true;
            $response['code'] = $code;

            $this->basic->save('codes', 'code_id', array(
                'code_code' => $code
            ));
        } else {
            $response['status'] = false;
            $response['error'] = 'Ups! Ocurrio un error, intente nuevamente por favor.';
        }

        echo json_encode($response);
    }

    public function deleteImage() {
        try {
            $image = $this->input->post('image');
            $folder = $this->input->post('folder');

            if (is_file('./img/' . $folder . '/' . $image)) {
                unlink('./img/' . $folder . '/' . $image);

                if ($folder == 'bussines_logos') {
                    $settings = User::getUserSettings();
                    $settings['logo'] = '';
                    $this->basic->save('settings', 'id', $settings);
                }
            }

            $response['status'] = true;
        } catch (Exception $exc) {
            $response['status'] = false;
            $response['error'] = 'Ups! Ocurrio un error, intente nuevamente por favor. Detalle: ' . $exc->getMessage();
        }

        echo json_encode($response);
    }

    public function savePictures() {
        print_r($_POST);die;
    }

    public function uploadImageFromFile() {
        $response = array();

        try {
            if($this->input->post('folder') == 'inspections/'){
                $file_name = 'inspection_'.bin2hex(random_bytes(50));
            }
            if($this->input->post('folder') == 'properties/'){
                $file_name = 'property_'.bin2hex(random_bytes(50));
            }
            if($this->input->post('folder') == 'manteinments/'){
                $file_name = 'manteinment_'.bin2hex(random_bytes(50));
            }

            $config['file_name']            = $file_name;
            $config['upload_path']          = 'img/'.$this->input->post('folder');
            $config['allowed_types']        = 'jpg|jpeg|png';
            $config['max_size']             = 10000;
            $config['max_width']            = 3000;
            $config['max_height']           = 3000;

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('file')){
                $response['error'] = array('error' => $this->upload->display_errors());
                $response['status'] = false;
            }else{
                $data = $this->upload->data();
          
                $response['status'] = true;
                $response['image'] = array(
                    'value' => $data['file_name'],
                    'src' => $data['full_path'],
                    'full_path' => img_url().$this->input->post('folder').$data['raw_name'].$data['file_ext'],
                    'path' => $this->input->post('folder').$data['raw_name'].$data['file_ext'],
                    'delete_url' => $data['full_path'],
                );

                if ($this->input->post('folder') == 'bussines_logos/') {
                    $settings = User::getUserSettings();
                    $settings['logo'] = $data['file_name'];
                    $this->basic->save('settings', 'id', $settings);
                }
                
            }
        } catch (Exception $exc) {
            $response['status'] = false;
            $response['error'] = 'Ups! Ocurrio un error, intente nuevamente por favor. Detalle: ' . $exc->getMessage();
        }

        echo json_encode($response);
    }

    public function ImageCR($source, $crop = null, $scale = null, $destination = null) {
        $source = @ImageCreateFromString(@file_get_contents($source));

        if (is_resource($source) === true) {
            $size = array(ImageSX($source), ImageSY($source));

            if (isset($crop) === true) {
                $crop = array_filter(explode('/', $crop), 'is_numeric');

                if (count($crop) == 2) {
                    $crop = array($size[0] / $size[1], $crop[0] / $crop[1]);

                    if ($crop[0] > $crop[1]) {
                        $size[0] = $size[1] * $crop[1];
                    } else if ($crop[0] < $crop[1]) {
                        $size[1] = $size[0] / $crop[1];
                    }

                    $crop = array(ImageSX($source) - $size[0], ImageSY($source) - $size[1]);
                } else {
                    $crop = array(0, 0);
                }
            } else {
                $crop = array(0, 0);
            }

            if (isset($scale) === true) {
                $scale = array_filter(explode('*', $scale), 'is_numeric');

                if (count($scale) >= 1) {
                    if (empty($scale[0]) === true) {
                        $scale[0] = $scale[1] * $size[0] / $size[1];
                    } else if (empty($scale[1]) === true) {
                        $scale[1] = $scale[0] * $size[1] / $size[0];
                    }
                } else {
                    $scale = array($size[0], $size[1]);
                }
            } else {
                $scale = array($size[0], $size[1]);
            }

            $result = ImageCreateTrueColor($scale[0], $scale[1]);

            if (is_resource($result) === true) {
                ImageFill($result, 0, 0, IMG_COLOR_TRANSPARENT);
                ImageSaveAlpha($result, true);
                ImageAlphaBlending($result, true);

                if (ImageCopyResampled($result, $source, 0, 0, $crop[0] / 2, $crop[1] / 2, $scale[0], $scale[1], $size[0], $size[1]) === true) {
                    if (preg_match('~gif$~i', $destination) >= 1) {
                        return ImageGIF($result, $destination);
                    } else if (preg_match('~png$~i', $destination) >= 1) {
                        return ImagePNG($result, $destination, 9);
                    } else if (preg_match('~jpe?g$~i', $destination) >= 1) {
                        return ImageJPEG($result, $destination, 90);
                    }
                }
            }
        }

        return false;
    }

}
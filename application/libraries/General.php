<?php

/*
 * Project: Cleanbox
 * Document: General
 * 
 * @author Manevi Matias Alejandro 
 * Informatic Engineer - Web Developer
 * 
 * Contact: manevimatias@gmail.com
 * 
 * All rights reserved ®
 */

class General {

    public static function isLastDayInMonth() {
        return true;
        return date('d') == date('t');
    }

    public static function loadModels($instance) {
        $instance->load->model('basic');
    }

    public static function isSameYear($date, $year) {
        $date = explode('-', $date);
        if ($date[2] == $year) {
            return true;
        }
        return false;
    }

    /**
     * Devuelve el tipo de cuenta a la que afecta una trasaccion, ya sea credito o debito
     * sus valores 'cc_saldo' o 'cc_varios'
     * @param array $transaction
     * @param string $type_concept
     * @param string $key_concept
     * @return string 
     */
    public static function getAccountType($transaction, $type_concept, $key_concept) {
        $instance = &get_instance();
        self::loadModels($instance);

        $concepts = $instance->basic->get_all('conceptos')->result_array();

        foreach ($concepts as $concept) {
            if (strpos($transaction[$key_concept], $concept['conc_desc']) !== FALSE && $concept['conc_tipo'] == $type_concept) {
                return $concept['conc_cc'];
            }
        }
    }

    /**
     * Parsea las entidades de la DB segun el formato en que cada una
     * se muestra en su lista
     * @param array $entity
     * @param string $table
     * @return array $entity
     */
    public static function parseEntityForList($entity, $table) {

        switch ($table) {
            case 'clientes':
                $entity = array(
                    'id' => $entity['client_id'],
                    'client_name' => $entity['client_name'],
                    'client_email' => $entity['client_email'],
                    'client_tel' => $entity['client_tel'],
                    'client_celular' => $entity['client_celular'],
                    'client_cuit' => $entity['client_cuit'],
                    'client_domicilio' => $entity['client_calle'] . ' ' . $entity['client_nro_calle'],
                );
                break;
            case 'providers_rols':
                $entity = array(
                    'id' => $entity['id'],
                    'rol' => $entity['rol'],
                );
                break;
            case 'creditos':
                $print = Report::mustPrintReport($entity['cred_concepto']);
                if (isset($entity['cred_tipo_pago']) && $entity['cred_tipo_pago'] == 'A Cuenta') {
                    $type = ' a cuenta ';
                } else {
                    $type = '';
                }
                $entity = array(
                    'id' => $entity['cred_id'],
                    'transaction_id' => $entity['trans'],
                    'cred_depositante' => $entity['cred_depositante'],
                    'cred_cc' => $entity['cred_cc'],
                    'cred_concepto' => $entity['cred_concepto'] . $type . ' (' . $entity['cred_mes_alq'] . ')',
                    'cred_monto' => '$ ' . $entity['cred_monto'],
                    'cred_fecha' => $entity['cred_fecha'],
                    'print' => $print,
                );
                break;
            case 'services_control':
                $entity = array(
                    'id' => $entity['id'],
                    'transaction_id' => $entity['trans'],
                    'service' => $entity['service'],
                    'month_checked' => $entity['month_checked'],
                    'date' => $entity['date'],
                );
                break;
            case 'debitos':
                $print = false;
                if (strpos($entity['deb_concepto'], 'Rendicion') !== false) {
                    $print = true;
                } else {
                    $print = Report::mustPrintDebit($entity['deb_concepto']);
                }
                $entity = array(
                    'id' => $entity['deb_id'],
                    'transaction_id' => $entity['trans'],
                    'deb_cc' => $entity['deb_cc'],
                    'deb_concepto' => $entity['deb_concepto'] . ' (' . $entity['deb_mes'] . ')',
                    'deb_monto' => '$ ' . $entity['deb_monto'],
                    'deb_domicilio' => $entity['deb_domicilio'],
                    'deb_fecha' => $entity['deb_fecha'],
                    'print' => $print,
                );
                break;
            case 'cuentas_corrientes':
                if (!in_array($entity['cc_prop'], array('INMOBILIARIA', 'CAJA FUERTE'))) {
                    $entity = array(
                        'id' => $entity['cc_id'],
                        'cc_prop' => $entity['cc_prop'],
                        'saldo' => '$ ' . round($entity['cc_saldo'] + $entity['cc_varios'], 2),
                    );
                } else {
                    $entity = array();
                }

                break;
            case 'man_users':
                $entity = array(
                    'id' => $entity['id'],
                    'username' => $entity['username'],
                );
                break;
            case 'transferencias_to_safe':
                $entity = array(
                    'id' => $entity['cred_id'],
                    'transf_fecha' => $entity['cred_fecha'],
                    'transf_monto' => '$ ' . $entity['cred_monto'],
                    'transf_tipo' => $entity['cred_concepto'],
                );
                break;
            case 'transferencias_to_cash':
                $entity = array(
                    'id' => $entity['deb_id'],
                    'transf_fecha' => $entity['deb_fecha'],
                    'transf_monto' => '$ ' . $entity['deb_monto'],
                    'transf_tipo' => $entity['deb_concepto'],
                );
                break;
            case 'conceptos':
                $entity = array(
                    'id' => $entity['conc_id'],
                    'conc_desc' => $entity['conc_desc'],
                    'interes_percibe' => $entity['interes_percibe'] ? 'Si' : 'No',
                    'gestion_percibe' => $entity['gestion_percibe'] ? 'Si' : 'No',
                    'iva_percibe' => $entity['iva_percibe'] ? 'Si' : 'No',
                    'conc_tipo' => $entity['conc_tipo'],
                    'conc_cc' => $entity['conc_cc'] == 'cc_saldo' ? 'Cta. Principal' : 'Cta. Secundaria',
                );
                break;
            case 'proveedores':
                $entity = array(
                    'id' => $entity['prov_id'],
                    'prov_name' => $entity['prov_name'],
                    'prov_tel' => $entity['prov_tel'],
                    'prov_email' => $entity['prov_email'],
                    'prov_domicilio' => $entity['prov_domicilio'],
                    'prov_nota' => $entity['prov_nota'],
                );
                break;
            case 'mantenimientos':
                if ($entity['mant_status'] == 1) {
                    $status = '<a title="Creada" class="glyphicon glyphicon-folder-open maintenance_status"></a>';
                } elseif ($entity['mant_status'] == 2) {
                    $status = '<a title="Asignada y en marcha" class="glyphicon glyphicon-play maintenance_status color_yellow"></a>';
                } else {
                    $status = '<a title="Terminada" class="glyphicon glyphicon-ok maintenance_status color_green"></a>';
                }
                $entity = array(
                    'id' => $entity['mant_id'],
                    'mant_domicilio' => $entity['mant_domicilio'],
                    'mant_prop' => $entity['mant_prop'],
                    'mant_inq' => $entity['mant_inq'],
                    'mant_prov' => $entity['mant_prov_1'] . ($entity['mant_prov_2'] ? ', ' . $entity['mant_prov_2'] : '') . ' ' . ($entity['mant_prov_3'] ? ', ' . $entity['mant_prov_3'] : ''),
                    'mant_date_deadline' => $entity['mant_date_deadline'],
                    'mant_status' => $status,
                    'print' => true,
                );
                break;
            case 'comentarios':
                $entity = array(
                    'id' => $entity['com_id'],
                    'com_prop' => $entity['com_prop'],
                    'com_date' => $entity['com_date'],
                    'com_com' => substr($entity['com_com'], 0, 60) . ' [...]',
                    'com_dom' => $entity['com_dom'],
                );
                break;
            case 'propiedades':
                $entity = array(
                    'id' => $entity['prop_id'],
                    'prop_prop' => $entity['prop_prop'],
                    'prop_dom' => $entity['prop_dom'],
                    'prop_contrato_vigente' => $entity['prop_contrato_vigente'] != '' ? $entity['prop_contrato_vigente'] : 'Libre',
                );
                break;
            case 'contratos':
                $entity = array(
                    'id' => $entity['con_id'],
                    'con_prop' => $entity['con_prop'],
                    'con_inq' => $entity['con_inq'],
                    'con_tipo' => $entity['con_tipo'],
                    'con_iva_alq' => $entity['con_iva_alq'],
                    'con_iva' => $entity['con_iva'],
                    'con_enabled' => $entity['con_enabled'] == 1 ? 'Si' : 'No',
                );
                break;
        }

        return $entity;
    }

    public static function getMonthNumber($month) {
        if (stripos($month, 'Enero') !== false) {
            $month_number = '01';
        }
        if (stripos($month, 'Febrero') !== false) {
            $month_number = '02';
        }
        if (stripos($month, 'Marzo') !== false) {
            $month_number = '03';
        }
        if (stripos($month, 'Abril') !== false) {
            $month_number = '04';
        }
        if (stripos($month, 'Mayo') !== false) {
            $month_number = '05';
        }
        if (stripos($month, 'Junio') !== false) {
            $month_number = '06';
        }
        if (stripos($month, 'Julio') !== false) {
            $month_number = '07';
        }
        if (stripos($month, 'Agosto') !== false) {
            $month_number = '08';
        }
        if (stripos($month, 'Septiembre') !== false) {
            $month_number = '09';
        }
        if (stripos($month, 'Octubre') !== false) {
            $month_number = '10';
        }
        if (stripos($month, 'Noviembre') !== false) {
            $month_number = '11';
        }
        if (stripos($month, 'Diciembre') !== false) {
            $month_number = '12';
        }

        return $month_number;
    }

    public static function getStringMonth($int_month) {

        if ($int_month == '01') {
            $string_month = 'Enero';
        }
        if ($int_month == '02') {
            $string_month = 'Febrero';
        }
        if ($int_month == '03') {
            $string_month = 'Marzo';
        }
        if ($int_month == '04') {
            $string_month = 'Abril';
        }
        if ($int_month == '05') {
            $string_month = 'Mayo';
        }
        if ($int_month == '06') {
            $string_month = 'Junio';
        }
        if ($int_month == '07') {
            $string_month = 'Julio';
        }
        if ($int_month == '08') {
            $string_month = 'Agosto';
        }
        if ($int_month == '09') {
            $string_month = 'Septiembre';
        }
        if ($int_month == '10') {
            $string_month = 'Octubre';
        }
        if ($int_month == '11') {
            $string_month = 'Noviembre';
        }
        if ($int_month == '12') {
            $string_month = 'Diciembre';
        }

        return $string_month;
    }

    /**
     * Algoritmo para comparar entre fechas String, si una de ellas esta entre un rango de fechas
     * @param string $date
     * @param string $from
     * @param string $to
     * @return boolean 
     */
    public static function isBetweenDates($date, $from, $to) {
        if ($date == $from || $date == $to) {
            return true;
        }

        $date = strtotime($date);
        $from = strtotime($from);
        $to = strtotime($to);

        if($from <= $date && $to >= $date){
            return true;
        }else{
            return false;
        }

    }

    public static function msort($array, $key, $sort_flags = SORT_REGULAR) {
        if (is_array($array) && count($array) > 0) {
            if (!empty($key)) {
                $mapping = array();
                foreach ($array as $k => $v) {
                    $sort_key = '';
                    if (!is_array($key)) {
                        $sort_key = $v[$key];
                    } else {
                        foreach ($key as $key_key) {
                            $sort_key .= $v[$key_key];
                        }
                        $sort_flags = SORT_STRING;
                    }
                    $mapping[$k] = $sort_key;
                }
                asort($mapping, $sort_flags);
                $sorted = array();
                foreach ($mapping as $k => $v) {
                    $sorted[] = $array[$k];
                }
                return $sorted;
            }
        }
        return $array;
    }

    public static function isDateSuperior($desde, $fecha) {
        $is_superior = false;
        $desde = explode('-', $desde);
        $f_venc = explode('-', $fecha);
        $resta_ano_des_desde = $f_venc[2] - $desde[2];
        $resta_mes_des_desde = $f_venc[1] - $desde[1];
        $resta_dia_des_desde = $f_venc[0] - $desde[0];
//comprara fecha inferior
        if ($resta_ano_des_desde < 0) {
            $is_superior = true;
        } else {
            if ($resta_ano_des_desde == 0) {
                if ($resta_mes_des_desde < 0) {
                    $is_superior = true;
                } else {
                    if ($resta_mes_des_desde == 0) {
                        if ($resta_dia_des_desde <= 0) {
                            $is_superior = true;
                        }
                    }
                }
            }
        }
        return $is_superior;
    }

    /**
     * Si se cambia un rol, cambiar las areas que lo tienen 
     * @param array $provider_rol
     * @param string $new_provider_rol_name 
     */
    public static function impactEditProviderRol($provider_rol, $new_provider_rol_name) {
        $instance = &get_instance();
        self::loadModels($instance);

        $areas = $instance->basic->get_where('areas_proveedores', array('area_area' => $provider_rol['rol']))->result_array();

        foreach ($areas as $area) {
            $area['area_area'] = $new_provider_rol_name;
            $instance->basic->save('areas_proveedores', 'area_id', $area);
        }
    }

    /**
     * - modificar com_prop en comentarios
      - modificar con_prop en contratos
      - modificar mant_prop en mantenimientos
      - modificar cred_domicilio deb_domicilio en transacciones
     * @param array $property
     * @param string $new_property_name 
     */
    public static function impactEditProperty($property, $new_property_name) {
        $instance = &get_instance();
        self::loadModels($instance);

        // Edit Comentaries   
        /* solo para davinia y rima */
        $comentaries = $instance->basic->get_where('comentarios', array('prop_id' => $property['prop_id']))->result_array();
        $comentaries2 = $instance->basic->get_where('comentarios', array('com_dom' => $property['prop_dom']))->result_array();
        foreach ($comentaries2 as $comentary2) {
            if (!in_array($comentary2, $comentaries))
                array_push($comentaries, $comentary2);
        }
        /* solo para davinia y rima */
        foreach ($comentaries as $comentary) {
            $comentary['com_dom'] = $new_property_name;
            $instance->basic->save('comentarios', 'com_id', $comentary);
        }

        // Edit Maintenances
        $maintenances = $instance->basic->get_where('mantenimientos', array('mant_domicilio' => $property['prop_dom']))->result_array();
        foreach ($maintenances as $maintenance) {
            $maintenance['mant_domicilio'] = $new_property_name;
            $instance->basic->save('mantenimientos', 'mant_id', $maintenance);
        }

        // Edit Contracts
        /* solo para davinia y rima */
        $contracts = $instance->basic->get_where('contratos', array('prop_id' => $property['prop_id']))->result_array();
        $contracts_2 = $instance->basic->get_where('contratos', array('con_domi' => $property['prop_dom']))->result_array();
        foreach ($contracts_2 as $contract_2) {
            if (!in_array($contract_2, $contracts))
                array_push($contracts, $contract_2);
        }
        /* solo para davinia y rima */
        foreach ($contracts as $contract) {
            $contract['con_domi'] = $new_property_name;
            $instance->basic->save('contratos', 'con_id', $contract);
        }

        // Edit credits
        $credits = $instance->basic->get_where('creditos', array('cred_domicilio' => $property['prop_dom'], 'is_transfer' => 0), 'cred_id', 'asc')->result_array();
        foreach ($credits as $credit) {
            $credit['cred_domicilio'] = $new_property_name;
            $instance->basic->save('creditos', 'cred_id', $credit);
        }

        // Edit debits
        $debits = $instance->basic->get_where('debitos', array('deb_domicilio' => $property['prop_dom'], 'is_transfer' => 0), 'deb_id', 'asc')->result_array();
        foreach ($debits as $debit) {
            $debit['deb_domicilio'] = $new_property_name;
            $instance->basic->save('debitos', 'deb_id', $debit);
        }
    }

    /**
     * - modificar el mant_prov donde tenga mantenimientos
     * @param array $provider
     * @param string $new_provider_name 
     */
    public static function impactEditProvider($provider, $new_provider_name) {
        $instance = &get_instance();
        self::loadModels($instance);

        $maintenances1 = $instance->basic->get_where('mantenimientos', array('mant_prov_1' => $provider['prov_name']))->result_array();
        $maintenances2 = $instance->basic->get_where('mantenimientos', array('mant_prov_2' => $provider['prov_name']))->result_array();
        $maintenances3 = $instance->basic->get_where('mantenimientos', array('mant_prov_3' => $provider['prov_name']))->result_array();

        foreach ($maintenances1 as $maintenance) {
            $maintenance['mant_prov_1'] = $new_provider_name;
            $instance->basic->save('mantenimientos', 'mant_id', $maintenance);
        }

        foreach ($maintenances2 as $maintenance) {
            $maintenance['mant_prov_2'] = $new_provider_name;
            $instance->basic->save('mantenimientos', 'mant_id', $maintenance);
        }

        foreach ($maintenances3 as $maintenance) {
            $maintenance['mant_prov_3'] = $new_provider_name;
            $instance->basic->save('mantenimientos', 'mant_id', $maintenance);
        }
    }

    /**
     * - modificar el cred_depositante en sus creditos
      - modificar con_inq en contratos, o con gar1/gar2
      - modificar mant_inq en mantenimientos
      - modificar prop_contrato_vigente en propiedades
      - modificar su cc_prop en su cuenta si tiene, y todo lo que implica esto
     *  @param array $client 
     *  @param string $new_client_name 
     */
    public static function impactEditClient($client, $new_client_name) {
        $instance = &get_instance();
        self::loadModels($instance);

        // Edit credits cred_depositante
        /* solo para davinia y rima */
        $credits = $instance->basic->get_where('creditos', array('client_id' => $client['client_id'], 'is_transfer' => 0), 'cred_id', 'asc')->result_array();
        $credits_2 = $instance->basic->get_where('creditos', array('cred_depositante' => $client['client_name'], 'is_transfer' => 0), 'cred_id', 'asc')->result_array();
        foreach ($credits_2 as $credit_2) {
            if (!in_array($credit_2, $credits))
                array_push($credits, $credit_2);
        }
        /* solo para davinia y rima */
        foreach ($credits as $credit) {
            $credit['cred_depositante'] = $new_client_name;
            $instance->basic->save('creditos', 'cred_id', $credit);
        }

        // Edit contracts con_inq gar1 gar2
        /* solo para davinia y rima */
        $contracts_inq = $instance->basic->get_where('contratos', array('client_id' => $client['client_id']))->result_array();
        $contracts_inq_2 = $instance->basic->get_where('contratos', array('con_inq' => $client['client_name']))->result_array();
        foreach ($contracts_inq_2 as $contract_inq_2) {
            if (!in_array($contract_inq_2, $contracts_inq))
                array_push($contracts_inq, $contract_inq_2);
        }
        /* solo para davinia y rima */
        foreach ($contracts_inq as $contract_inq) {
            $contract_inq['con_inq'] = $new_client_name;
            $instance->basic->save('contratos', 'con_id', $contract_inq);
        }

        /* solo para davinia y rima */
        $contracts_gar1 = $instance->basic->get_where('contratos', array('gar1_id' => $client['client_id']))->result_array();
        $contracts_gar1_2 = $instance->basic->get_where('contratos', array('con_gar1' => $client['client_name']))->result_array();
        foreach ($contracts_gar1_2 as $contract_gar1_2) {
            if (!in_array($contract_gar1_2, $contracts_gar1))
                array_push($contracts_gar1, $contract_gar1_2);
        }
        /* solo para davinia y rima */
        foreach ($contracts_gar1 as $contract_gar1) {
            $contract_gar1['con_gar1'] = $new_client_name;
            $instance->basic->save('contratos', 'con_id', $contract_gar1);
        }

        /* solo para davinia y rima */
        $contracts_gar2 = $instance->basic->get_where('contratos', array('gar2_id' => $client['client_id']))->result_array();
        $contracts_gar2_2 = $instance->basic->get_where('contratos', array('con_gar2' => $client['client_name']))->result_array();
        foreach ($contracts_gar2_2 as $contract_gar2_2) {
            if (!in_array($contract_gar2_2, $contracts_gar2))
                array_push($contracts_gar2, $contract_gar2_2);
        }
        /* solo para davinia y rima */
        foreach ($contracts_gar2 as $contract_gar2) {
            $contract_gar2['con_gar2'] = $new_client_name;
            $instance->basic->save('contratos', 'con_id', $contract_gar2);
        }

        // Edit maintenances mant_inq
        $maintenances = $instance->basic->get_where('mantenimientos', array('mant_inq' => $client['client_name']))->result_array();
        foreach ($maintenances as $maintenance) {
            $maintenance['mant_inq'] = $new_client_name;
            $instance->basic->save('mantenimientos', 'mant_id', $maintenance);
        }

        // Edit properties prop_contrato_vigente
        $properties = $instance->basic->get_where('propiedades', array('prop_contrato_vigente' => $client['client_name']))->result_array();
        foreach ($properties as $property) {
            $property['prop_contrato_vigente'] = $new_client_name;
            $instance->basic->save('propiedades', 'prop_id', $property);
        }

        // Edit account cc_prop and implications
        /* solo para davinia y rima */
        $account = $instance->basic->get_where('cuentas_corrientes', array('client_id' => $client['client_id']))->row_array();
        if (empty($account)) {
            $account = $instance->basic->get_where('cuentas_corrientes', array('cc_prop' => $client['client_name']))->row_array();
        }
        /* solo para davinia y rima */
        if (!empty($account)) {
            self::impactEditAccount($account, $new_client_name);
            $account['cc_prop'] = $new_client_name;
            $instance->basic->save('cuentas_corrientes', 'cc_id', $account);
        }
    }

    /**
     * cuando se modifique una cuenta corriente:
      - modificar el cred_cc y deb_cc en sus transacciones
      - modificar el client_name
      - modificar con_prop en contratos
      - modificar mant_prop en mantenimientos
      - modificar prop_prop en propiedades
      - modificar com_prop en comentarios
     * @param array $account 
     * @param string $new_account_name 
     */
    public static function impactEditAccount(&$account, $new_account_name) {
        $instance = &get_instance();
        self::loadModels($instance);
        $client = array();
        
        if (!empty($account)) {
            // Edit transactions
            /* solo para davinia y rima */
            $credits = $instance->basic->get_where('creditos', array('cc_id' => $account['cc_id'], 'is_transfer' => 0), 'cred_id', 'asc')->result_array();
            $debits = $instance->basic->get_where('debitos', array('cc_id' => $account['cc_id'], 'is_transfer' => 0), 'deb_id', 'asc')->result_array();
            $credits_2 = $instance->basic->get_where('creditos', array('cred_cc' => $account['cc_prop'], 'is_transfer' => 0), 'cred_id', 'asc')->result_array();
            $debits_2 = $instance->basic->get_where('debitos', array('deb_cc' => $account['cc_prop'], 'is_transfer' => 0), 'deb_id', 'asc')->result_array();
            foreach ($debits_2 as $debit_2) {
                if (!in_array($debit_2, $debits))
                    array_push($debits, $debit_2);
            }
            foreach ($credits_2 as $credit_2) {
                if (!in_array($credit_2, $credits))
                    array_push($credits, $credit_2);
            }
            /* solo para davinia y rima */
            foreach ($credits as $credit) {
                $credit['cred_cc'] = $new_account_name;
                $instance->basic->save('creditos', 'cred_id', $credit);
            }
            foreach ($debits as $debit) {
                $debit['deb_cc'] = $new_account_name;
                $instance->basic->save('debitos', 'deb_id', $debit);
            }

            // Edit contracts
            /* solo para davinia y rima */
            $contracts = $instance->basic->get_where('contratos', array('cc_id' => $account['cc_id']))->result_array();
            $contracts2 = $instance->basic->get_where('contratos', array('con_prop' => $account['cc_prop']))->result_array();
            foreach ($contracts2 as $contract2) {
                if (!in_array($contract2, $contracts))
                    array_push($contracts, $contract2);
            }
            foreach ($contracts as $contract) {
                $contract['con_prop'] = $new_account_name;
                $instance->basic->save('contratos', 'con_id', $contract);
            }
            /* solo para davinia y rima */

            // Edit Maintenances
            $maintenances = $instance->basic->get_where('mantenimientos', array('mant_prop' => $account['cc_prop']))->result_array();
            foreach ($maintenances as $maintenance) {
                $maintenance['mant_prop'] = $new_account_name;
                $instance->basic->save('mantenimientos', 'mant_id', $maintenance);
            }

            // Edit Properties
            /* solo para davinia y rima */
            $properties = $instance->basic->get_where('propiedades', array('cc_id' => $account['cc_id']))->result_array();
            $properties2 = $instance->basic->get_where('propiedades', array('prop_prop' => $account['cc_prop']))->result_array();
            foreach ($properties2 as $property2) {
                if (!in_array($property2, $properties))
                    array_push($properties, $property2);
            }
            /* solo para davinia y rima */
            foreach ($properties as $property) {
                $property['prop_prop'] = $new_account_name;
                $instance->basic->save('propiedades', 'prop_id', $property);
            }

            // Edit Comentaries   
            /* solo para davinia y rima */
            $comentaries = $instance->basic->get_where('comentarios', array('cc_id' => $account['cc_id']))->result_array();
            $comentaries2 = $instance->basic->get_where('comentarios', array('com_prop' => $account['cc_prop']))->result_array();
            foreach ($comentaries2 as $comentary2) {
                if (!in_array($comentary2, $comentaries))
                    array_push($comentaries, $comentary2);
            }
            /* solo para davinia y rima */
            foreach ($comentaries as $comentary) {
                $comentary['com_prop'] = $new_account_name;
                $instance->basic->save('comentarios', 'com_id', $comentary);
            }

            // Edit client
            /* solo para davinia y rima */
            if ($account['client_id']) {
                $client = $instance->basic->get_where('clientes', array('client_id' => $account['client_id']))->row_array();
            } else {
                $client = $instance->basic->get_where('clientes', array('client_name' => $account['cc_prop']))->row_array();
            }
            /* solo para davinia y rima */
        }

        if (empty($client)) {
            $client = array(
                'client_name' => $new_account_name,
                'client_categoria' => 'Propietario',
            );
        } else {
            $client['client_name'] = $new_account_name;
            $client['client_categoria'] = 'Propietario';
        }
        $client['client_id'] = $instance->basic->save('clientes', 'client_id', $client);

        return $client['client_id'];
    }

    public static function getPropietaryClientByCredit($credit) {
        $instance = &get_instance();
        self::loadModels($instance);

        /* solo para davinia y rima */
        if ($credit['cc_id']) {
            $account = $instance->basic->get_where('cuentas_corrientes', array('cc_id' => $credit['cc_id']))->row_array();
            if ($account['client_id']) {
                $propietary = $instance->basic->get_where('clientes', array('client_id' => $account['client_id']))->row_array();
            } else {
                $propietary = $instance->basic->get_where('clientes', array('client_name' => $account['cc_prop']))->row_array();
            }
        } else {
            $propietary = $instance->basic->get_where('clientes', array('client_name' => $credit['cred_cc']))->row_array();
        }
        /* solo para davinia y rima */

        return $propietary;
    }

    public static function getRenterClientByCredit($credit) {
        $instance = &get_instance();

        $propietary = $instance->basic->get_where('clientes', array('client_name' => $credit['cred_depositante']))->row_array();

        return $propietary;
    }

}

?>

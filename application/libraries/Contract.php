<?php

/*
 * Project: Cleanbox
 * Document: Contract
 * 
 * @author Manevi Matias Alejandro 
 * Informatic Engineer - Web Developer
 * 
 * Contact: manevimatias@gmail.com
 * 
 * All rights reserved ®
 */

class Contract {

    public static function allowedByPlan() {
        $instance = &get_instance();

        $contracts = count($instance->basic->get_where('contratos', array('con_enabled' => 1))->result_array());

        $quant_allowed = self::quantAllowed();

        // 0 means limitless
        if($quant_allowed == 0){
            return true;
        }

        return $quant_allowed > $contracts;
    }

    public static function quantAllowed() {
        $instance = &get_instance();

        switch ($instance->session->userdata('plan')) {
            case User::FREE_PLAN:
                return 10;
                break;
            case User::BASIC_PLAN:
                return 50;
                break;
            case User::ENTERPRISE_PLAN:
                return 100;
                break;
            case User::FULL_PLAN:
                return 0;
                break;    
        }
    }

    public static function getHonoraryPayments($contract) {
        $instance = &get_instance();

        $honorary_payments = $instance->basic->get_where('creditos', array('cred_concepto' => 'Honorarios', 'con_id' => $contract['con_id']))->result_array();

        $honorary_payments2 = $instance->basic->get_where('creditos', array('cred_concepto' => 'Honorarios', 'cred_depositante' => $contract['con_inq']))->result_array();

        foreach ($honorary_payments2 as $honorary_payment2) {
            if (!in_array($honorary_payment2, $honorary_payments))
                array_push($honorary_payments, $honorary_payment2);
        }

        return $honorary_payments;
    }

    public static function contractMustBeMarkedUsed($credit, $use_contract) {
        if ($use_contract) {
            return $use_contract;
        }

        if (strpos($credit['cred_concepto'], 'Loteo') !== false ||
                strpos($credit['cred_concepto'], 'Alquiler') !== false) {
            return true;
        }

        return false;
    }

    public static function searchContractForPayment($concepto, $con_prop, $con_inq) {
        $instance = &get_instance();
        General::loadModels($instance);

        $contract = $instance->basic->get_where('contratos', array('con_enabled' => 1, 'con_prop' => $con_prop, 'con_inq' => $con_inq))->row_array();

        if ($concepto['conc_desc'] == 'Loteo' || $concepto['conc_desc'] == 'Honorarios' || $concepto['conc_desc'] == 'Alquiler' || $concepto['conc_desc'] == 'Alquiler Comercial') {
            if ($concepto['conc_desc'] != 'Honorarios') {
                $contract = $instance->basic->get_where('contratos', array('con_enabled' => 1, 'con_tipo' => $concepto['conc_desc'], 'con_prop' => $con_prop, 'con_inq' => $con_inq))->row_array();
            } else {
                $contract = $instance->basic->get_where('contratos', array('con_enabled' => 1, 'con_prop' => $con_prop, 'con_inq' => $con_inq))->row_array();
            }
        }

        return $contract;
    }

    public static function useContract(&$contract) {
        $contract['con_usado'] = 1;
    }

    public static function unuseContract($row) {
        if (strpos($row['cred_concepto'], 'Alquiler') !== FALSE || strpos($row['cred_concepto'], 'Loteo') !== FALSE) {

            $instance = &get_instance();
            General::loadModels($instance);

            /* solo para davinia y rima */
            if ($row['con_id']) {
                $contract = $instance->basic->get_where('contratos', array('con_id' => $row['con_id']))->row_array();
            } else {
                $contract = $instance->basic->get_where('contratos', array('con_prop' => $row['cred_cc'], 'con_inq' => $row['cred_depositante']))->row_array();
            }
            /* solo para davinia y rima */

            if (!empty($contract)) {
                $concept = $row['cred_concepto'];
                $last_payment = Transaction::getLastPayment($contract, $concept);

                if (!$last_payment) {
                    $contract['con_usado'] = 0;
                    $instance->basic->save('contratos', 'con_id', $contract);
                }
            }
        }
    }

    public static function getContract($credits) {
        $instance = &get_instance();
        General::loadModels($instance);

        foreach ($credits as $credit) {
            if (Report::mustPrintReport($credit['cred_concepto'])) {
                $contract = self::getContractByCredit($credit);
                if ($contract) {
                    return $contract;
                }
            }
        }

        return false;
    }

    public static function getContractByCredit($credit) {
        $instance = &get_instance();
        General::loadModels($instance);

        if ($credit['con_id']) {
            /* solo para davinia y rima */
            $contract = $instance->basic->get_where('contratos', array('con_id' => $credit['con_id']))->row_array();
        } else {
            if ($credit['cred_concepto'] == 'Honorarios') {
                $contract = $instance->basic->get_where('contratos', array('con_inq' => $credit['cred_depositante']))->row_array();
            } else {
                $contract = $instance->basic->get_where('contratos', array('con_prop' => $credit['cred_cc'], 'con_inq' => $credit['cred_depositante']))->row_array();
            }
        }

        if ($contract) {
            return $contract;
        }

        return false;
    }

    /**
     * Devuelve el Periodo que corresponde a la fecha actual,
     * para pintar su fila en la tabla de periodos
     * @param array $periods
     * @return type 
     */
    public static function getCurrentPeriodDate($periods) {

        foreach ($periods as $period) {
            if (General::isBetweenDates(date('d-m-Y'), $period['per_inicio'], $period['per_fin'])) {
                return $period['per_id'];
            }
        }

        return false;
    }

    public static function getContractDebts($concept, $contract) {
        $instance = &get_instance();
        General::loadModels($instance);
        $total_debt = array();
        $last_payment = Transaction::getLastPayment($contract, $concept);

        if ($last_payment) {
            $periods = $instance->basic->get_where('periodos', array('per_contrato' => $contract['con_id']), 'per_id');

            // Obtener la deuda de pagos hechos a cuenta anteriormente
            $account_debt = Transaction::getAccountPaymentsDebt($last_payment, $periods, $contract);
            // Agregarla a las deudas totales
            if ($account_debt) {
                array_push($total_debt, $account_debt);
            }

            // Obtenemos el ultimo mes y ano de pago mediante el credito $last_payment
            $last_payment_month = trim(preg_replace("/[^^A-Za-z (),.]/", "", $last_payment['cred_mes_alq']));
            $last_payment_month_number = General::getMonthNumber($last_payment_month);
            $last_payment_year = trim(preg_replace("/[^0-9 (),.]/", "", $last_payment['cred_mes_alq']));

            $months_array = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
            while (current($months_array) != $last_payment_month) {
                next($months_array);
            }

            $current_procces_debt_date = strtotime('00-' . $last_payment_month_number . '-' . $last_payment_year);
            $current_date = strtotime('00-' . Date('m-Y'));

            // Me muevo un elemento mas en el array de meses para empezar con el primer mes debido
            // Si ya se llego al tope del array, se resetea para volver a enero y se aumenta el year
            if (key($months_array) == 11) {
                reset($months_array);
                $last_payment_year++;
            } else {
                next($months_array);
            }

            $debt_month_initiate = key($months_array);

            for ($i = $debt_month_initiate; $current_procces_debt_date < $current_date; ($i == 11 ? $i = 0 : $i++)) {
                // crea el arreglo de meses que debe hasta la fecha actual

                $current = General::getMonthNumber($months_array[$i]);

                if ($current < Date('m') || $last_payment_year < Date('Y')) {

                    if (($debt_month_initiate + 1) <= 12) {

                        $debt = array(
                            'month' => $months_array[$i] . ' ' . $last_payment_year,
                            'default_days' => 0,
                            'concept' => $contract['con_tipo'],
                            'interes_percibe' => self::conceptPerceiveInteres($contract['con_tipo']),
                            'iva_percibe' => self::conceptPerceiveIVA($contract['con_tipo']),
                            'sald_account' => 0,
                            'amount' => 0,
                            'intereses' => 0,
                        );

                        $debt_month_initiate++;
                    } else {
                        // new year!
                        $debt_month_initiate = 1;
                        $last_payment_year++;

                        $debt = array(
                            'month' => $months_array[$i] . ' ' . $last_payment_year,
                            'default_days' => 0,
                            'concept' => $contract['con_tipo'],
                            'interes_percibe' => self::conceptPerceiveInteres($contract['con_tipo']),
                            'iva_percibe' => self::conceptPerceiveIVA($contract['con_tipo']),
                            'sald_account' => 0,
                            'amount' => 0,
                            'intereses' => 0,
                        );
                    }

                    $month_debt = '00-' . $current . '-' . $last_payment_year;
                    $debt['amount'] = Transaction::calculateAmount($month_debt, $periods);
                    $debt = Transaction::calculateIntereses($debt, $contract, $month_debt, $periods, Date('d-m-Y'));
                    array_push($total_debt, $debt);
                }

                $current_procces_debt_date = strtotime('00-' . $current . '-' . $last_payment_year);
            }

            // Este bloque fuera del for calcula un mes de deuda mas si es que el dia en el que se ejecuta
            // el informe se esta propasando el limite de tolerancia de mora
            if (!empty($total_debt)) {
                $last_debt = array_pop($total_debt);
                $last_payment_month_number = General::getMonthNumber($last_debt['month']);
                array_push($total_debt, $last_debt);
            }

            if ((empty($total_debt) || Date('d') > $contract['con_tolerancia']) && Date('m') > $last_payment_month_number) {
                $next_month_payment = $last_payment_month_number + 1;
                $debt = array(
                    'month' => General::getStringMonth($next_month_payment) . ' ' . Date('Y'),
                    'default_days' => 0,
                    'concept' => $contract['con_tipo'],
                    'interes_percibe' => self::conceptPerceiveInteres($contract['con_tipo']),
                    'iva_percibe' => self::conceptPerceiveIVA($contract['con_tipo']),
                    'amount' => 0,
                    'sald_account' => 0,
                    'intereses' => 0
                );

                $month_debt = '00-' . $next_month_payment . '-' . Date('Y');
                $debt['amount'] = Transaction::calculateAmount($month_debt, $periods);
                $debt = Transaction::calculateIntereses($debt, $contract, $month_debt, $periods, Date('d-m-Y'));

                if (!in_array($debt, $total_debt)) {
                    array_push($total_debt, $debt);
                }
            }
        }

        return $total_debt;
    }

    public static function conceptPerceiveInteres($concept_name) {
        $instance = &get_instance();
        General::loadModels($instance);

        $concept = $instance->basic->get_where('conceptos', array('conc_desc' => $concept_name, 'conc_tipo' => 'Entrada'))->row_array();

        if ($concept['interes_percibe']) {
            return true;
        } else {
            return false;
        }
    }

    public static function conceptPerceiveGestion($concept_name) {
        $instance = &get_instance();
        General::loadModels($instance);

        $concept = $instance->basic->get_where('conceptos', array('conc_desc' => $concept_name, 'conc_tipo' => 'Entrada'))->row_array();

        if ($concept['gestion_percibe']) {
            return true;
        } else {
            return false;
        }
    }

    public static function conceptPerceiveIVA($concept_name) {
        $instance = &get_instance();
        General::loadModels($instance);

        $concept = $instance->basic->get_where('conceptos', array('conc_desc' => $concept_name, 'conc_tipo' => 'Entrada'))->row_array();

        if ($concept['iva_percibe']) {
            return true;
        } else {
            return false;
        }
    }

    public static function getContractServicesDebts($concept, $contract) {
        $services_debt = array();
        $instance = &get_instance();
        General::loadModels($instance);
        $last_payment = Transaction::getLastPayment($contract, $concept);

        if ($last_payment) {

            $periods = $instance->basic->get_where('periodos', array('per_contrato' => $contract['con_id']), 'per_id');

            // Obtenemos el ultimo mes y ano de pago mediante el credito $last_payment
            $last_payment_month = trim(preg_replace("/[^^A-Za-z (),.]/", "", $last_payment['cred_mes_alq']));
            $last_payment_month_number = General::getMonthNumber($last_payment_month);
            $last_payment_year = trim(preg_replace("/[^0-9 (),.]/", "", $last_payment['cred_mes_alq']));

            $months_array = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
            while (current($months_array) != $last_payment_month) {
                next($months_array);
            }

            $current_procces_debt_date = strtotime('00-' . $last_payment_month_number . '-' . $last_payment_year);
            $current_date = strtotime('00-' . Date('m-Y'));

            // Me muevo un elemento mas en el array de meses para empezar con el primer mes debido
            // Si ya se llego al tope del array, se resetea para volver a enero y se aumenta el year
            if (key($months_array) == 11) {
                reset($months_array);
                $last_payment_year++;
            } else {
                next($months_array);
            }

            $debt_month_initiate = key($months_array);

            for ($i = $debt_month_initiate; $current_procces_debt_date < $current_date; ($i == 11 ? $i = 0 : $i++)) {
                // crea el arreglo de meses que debe hasta la fecha actual

                $current = General::getMonthNumber($months_array[$i]);

                if ($current < Date('m') || $last_payment_year < Date('Y')) {

                    if (($debt_month_initiate + 1) <= 12) {

                        $service_debt = array(
                            'month' => $months_array[$i] . ' ' . $last_payment_year,
                            'default_days' => 0,
                            'concept' => $last_payment['cred_concepto'],
                            'interes_percibe' => self::conceptPerceiveInteres($last_payment['cred_concepto']),
                            'iva_percibe' => self::conceptPerceiveIVA($last_payment['cred_concepto']),
                            'sald_account' => 0,
                            'amount' => 0,
                            'intereses' => 0,
                        );

                        $debt_month_initiate++;
                    } else {
                        // new year!
                        $debt_month_initiate = 1;
                        $last_payment_year++;

                        $service_debt = array(
                            'month' => $months_array[$i] . ' ' . $last_payment_year,
                            'default_days' => 0,
                            'concept' => $last_payment['cred_concepto'],
                            'interes_percibe' => self::conceptPerceiveInteres($last_payment['cred_concepto']),
                            'iva_percibe' => self::conceptPerceiveIVA($last_payment['cred_concepto']),
                            'sald_account' => 0,
                            'amount' => 0,
                            'intereses' => 0,
                        );
                    }

                    if ($service_debt['interes_percibe']) {
                        $month_debt = '00-' . $current . '-' . $last_payment_year;
                        $service_debt = Transaction::calculateExpensInteres($service_debt, $contract, $month_debt, Date('d-m-Y'));
                    }

                    array_push($services_debt, $service_debt);
                }

                $current_procces_debt_date = strtotime('00-' . $current . '-' . $last_payment_year);
            }

            // Este bloque fuera del for calcula un mes de deuda mas si es que el dia en el que se ejecuta
            // el informe se esta propasando el limite de tolerancia de mora
            if (!empty($services_debt)) {
                $last_debt = array_pop($services_debt);
                $last_payment_month_number = General::getMonthNumber($last_debt['month']);
                array_push($services_debt, $last_debt);
            }

            if ((!count($services_debt) || Date('d') > $contract['con_tolerancia']) && Date('m') > $last_payment_month_number) {
                $next_month_payment = $last_payment_month_number + 1;
                $service_debt = array(
                    'month' => General::getStringMonth($next_month_payment) . ' ' . Date('Y'),
                    'default_days' => 0,
                    'concept' => $last_payment['cred_concepto'],
                    'interes_percibe' => self::conceptPerceiveInteres($last_payment['cred_concepto']),
                    'iva_percibe' => self::conceptPerceiveIVA($last_payment['cred_concepto']),
                    'amount' => 0,
                    'sald_account' => 0,
                    'intereses' => 0
                );

                if ($service_debt['interes_percibe']) {
                    $month_debt = '00-' . $next_month_payment . '-' . Date('Y');
                    $service_debt = Transaction::calculateIntereses($service_debt, $contract, $month_debt, $periods, Date('d-m-Y'));
                }

                if (!in_array($service_debt, $services_debt)) {
                    array_push($services_debt, $service_debt);
                }
            }
        }
        return $services_debt;
    }

    public static function getContractServicesControls($last_control_serv) {
        $services_controls = array();
        $instance = &get_instance();
        General::loadModels($instance);

        // Obtenemos el ultimo mes y ano de pago mediante el credito $last_control_serv
        $last_control_serv_month = trim(preg_replace("/[^^A-Za-z (),.]/", "", $last_control_serv['month_checked']));
        $last_control_serv_month_number = General::getMonthNumber($last_control_serv_month);
        $last_control_serv_year = trim(preg_replace("/[^0-9 (),.]/", "", $last_control_serv['month_checked']));

        $months_array = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
        while (current($months_array) != $last_control_serv_month) {
            next($months_array);
        }

        $current_procces_debt_date = strtotime('00-' . $last_control_serv_month_number . '-' . $last_control_serv_year);
        $current_date = strtotime('00-' . Date('m-Y'));

        // Me muevo un elemento mas en el array de meses para empezar con el primer mes debido
        // Si ya se llego al tope del array, se resetea para volver a enero y se aumenta el year
        if (key($months_array) == 11) {
            reset($months_array);
            $last_control_serv_year++;
        } else {
            next($months_array);
        }

        $debt_month_initiate = key($months_array);

        for ($i = $debt_month_initiate; $current_procces_debt_date < $current_date; ($i == 11 ? $i = 0 : $i++)) {
            // crea el arreglo de meses que debe hasta la fecha actual

            $current = General::getMonthNumber($months_array[$i]);

            if ($current < Date('m') || $last_control_serv_year < Date('Y')) {

                if (($debt_month_initiate + 1) <= 12) {

                    $service_control = array(
                        'month' => $months_array[$i] . ' ' . $last_control_serv_year,
                        'concept' => $last_control_serv['service'],
                    );

                    $debt_month_initiate++;
                } else {
                    // new year!
                    $debt_month_initiate = 1;
                    $last_control_serv_year++;

                    $service_control = array(
                        'month' => $months_array[$i] . ' ' . $last_control_serv_year,
                        'concept' => $last_control_serv['service'],
                    );
                }

                array_push($services_controls, $service_control);
            }

            $current_procces_debt_date = strtotime('00-' . $current . '-' . $last_control_serv_year);
        }

        return $services_controls;
    }

    public static function declineContracts() {
        $instance = &get_instance();
        General::loadModels($instance);

        $contracts = $instance->basic->get_where('contratos', array('con_enabled' => 1))->result_array();

        foreach ($contracts as $contract) {

            if (($contract['con_venc'] != '' && $contract['con_prop'] != '' && $contract['con_inq'] != '' && $contract['con_tipo'] != '' && $contract['con_enabled'] != 0)) {

                if (General::isDateSuperior(date('d-m-Y'), $contract['con_venc'])) {

                    if ($contract['prop_id']) {
                        $property = $instance->basic->get_where('propiedades', array('prop_id' => $contract['prop_id']))->row_array();
                    } else {
                        $property = $instance->basic->get_where('propiedades', array('prop_dom' => $contract['con_domi'], 'prop_prop' => $contract['con_prop']))->row_array();
                    }

                    $property['prop_contrato_vigente'] = 'Libre';
                    $contract['con_enabled'] = 0;
                    $contract['con_motivo'] = 'Vencido';

                    $instance->basic->save('contratos', 'con_id', $contract);
                    $instance->basic->save('propiedades', 'prop_id', $property);
                }
            }
        }
    }

    public static function getCountAliveContracts() {
        $instance = &get_instance();
        General::loadModels($instance);

        $contracts = $instance->basic->get_where('contratos', array(), 'con_prop')->result_array();
        $alive_contracts = 0;

        foreach ($contracts as $row) {
            if ($row['con_enabled'] == 1) {
                $alive_contracts++;
            }
        }

        return $alive_contracts;
    }

    public static function isContractConcept($concept) {
        $instance = &get_instance();
        General::loadModels($instance);

        $services_array = $instance->basic->getAllServicesArray();

        if (in_array($concept, $services_array) ||
                $concept == 'Loteo' ||
                $concept == 'Honorarios' ||
                $concept == 'Deposito de garantia' ||
                strpos($concept, 'Alquiler') !== false) {
            $is_contract_concept = true;
        } else {
            $is_contract_concept = false;
        }

        return $is_contract_concept;
    }

    public static function saveServiceControl($service_control, $transaction_id) {
        $instance = &get_instance();
        General::loadModels($instance);

        $service_control['trans'] = $transaction_id;
        unset($service_control['status']);

        $instance->basic->save('services_control', 'id', $service_control);
    }

}

?>

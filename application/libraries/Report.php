<?php

/*
 * Project: Cleanbox
 * Document: Report
 * 
 * @author Manevi Matias Alejandro 
 * Informatic Engineer - Web Developer
 * 
 * Contact: manevimatias@gmail.com
 * 
 * All rights reserved ®
 */

class Report {

    public static function buildAccountsAnualBalanceReport($year) {
        $months = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
        $instance->data['balances'] = array();


        foreach ($months as $value) {
            $month = $value . ' ' . $year;
            $instance->data['balances'][$month] = self::getAccountsBalance($month);
        }

        echo '<pre>';
        print_r($instance->data['balances']);
        die;

    }

    public static function buildOutmonthTransactionsReport($month) {
        $instance = &get_instance();

        $transactions = self::getOutmonthTransactions($month);

        $instance->data['month'] = $month;
        $instance->data['credits'] = $transactions['credits'];
        $instance->data['debits'] = $transactions['debits'];
        $instance->data['months_ins'] = $transactions['months_ins'];
        $instance->data['months_outs'] = $transactions['months_outs'];

        return $instance->load->view('reports/outmonth_transactions_report', $instance->data, TRUE);
    }
    
    public static function getOutmonthTransactions($month) {
        $instance = &get_instance();

        $response = array('credits' => array(), 'debits' => array());

        $year_only = preg_replace('/[^0-9]/', '', $month);
        $month_only = preg_replace('/[0-9]/', '', $month);

        $from = '01-' . General::getMonthNumber($month_only) . '-' . $year_only;
        $to = '31-' . General::getMonthNumber($month_only) . '-' . $year_only;

        $credits = $instance->basic->get_where('creditos', array('is_transfer' => 0))->result_array();
        $debits = $instance->basic->get_where('debitos', array('is_transfer' => 0))->result_array();

        $months_ins = array();
        $months_outs = array();

        foreach ($credits as $credit) {
            if (strpos($credit['cred_concepto'], 'Gestion de Cobro') === FALSE && 
                strpos($credit['cred_concepto'], 'Prestamo') === FALSE) {
                if($credit['cred_mes_alq'] != $month) {
                    if(General::isBetweenDates($credit['cred_fecha'], $from, $to)) {
                        array_push($response['credits'], $credit);
                        if(!isset($months_ins[$credit['cred_mes_alq']])){
                            $months_ins[$credit['cred_mes_alq']] = 0;
                        }
                        $months_ins[$credit['cred_mes_alq']] += $credit['cred_monto'];
                    }
                }
            }
        }

        foreach ($debits as $debit) {
            if (strpos($debit['deb_concepto'], 'Gestion de Cobro') === FALSE &&
                strpos($debit['deb_concepto'], 'Prestamo') === FALSE) {
                if($debit['deb_mes'] != $month) {
                    if(General::isBetweenDates($debit['deb_fecha'], $from, $to)) {
                        array_push($response['debits'], $debit);
                        if(!isset($months_outs[$debit['deb_mes']])){
                            $months_outs[$debit['deb_mes']] = 0;
                        }
                        $months_outs[$debit['deb_mes']] += $debit['deb_monto'];
                    }
                }
            }
        }

        $response['credits'] = General::msort($response['credits'], 'trans');
        $response['debits'] = General::msort($response['debits'], 'trans');
        $response['months_ins'] = $months_ins;
        $response['months_outs'] = $months_outs;

        return $response;
    }

    public static function buildHonoraryPaymentsReport() {
        $instance = &get_instance();

        $contracts = $instance->basic->get_all('contratos', 'con_id')->result_array();
        $honorary_payments = array();
        
        foreach ($contracts as $contract) {
            $honorary_payments[$contract['con_id']]['honoraries'] = Contract::getHonoraryPayments($contract);
            $honorary_payments[$contract['con_id']]['contract'] = $contract;
        }
        
        $instance->data['honorary_payments'] = $honorary_payments;

        return $instance->load->view('reports/honorary_payments_report', $instance->data, TRUE);
    }

    public static function wasDelivered($report_name, $month) {
        $instance = &get_instance();
        
        $report_delivery = $instance->basic->get_where('reports_delivery', array('report_name' => $report_name, 'month' => $month))->row_array();
        
        if (empty($report_delivery)) {
            return false;
        } else {
            return true;
        }
    }

    public static function saveDelivery($report_name, $month) {
        $instance = &get_instance();

        $instance->basic->save('reports_delivery', 'id', array(
            'report_name' => $report_name,
            'month' => $month,
            'date' => date('d-m-Y')
        ));
    }
    
    public static function isPrincipal($concept, $print_report = false){
        if ($print_report) {
            return $print_report;
        }

        if ($concept == 'Loteo' ||
            $concept == 'Honorarios' ||
            strpos($concept, 'Reserva') !== false ||
            strpos($concept, 'Alquiler') !== false) {
            return true;
        }

        return false;
    }

    public static function mustPrintReport($concept, $print_report = false) {
        if ($print_report) {
            return $print_report;
        }

        if (strpos($concept, 'Gestion de Cobro') === false &&
            strpos($concept, 'Prestamo') === false ||
            $concept == 'Loteo' ||
            $concept == 'Honorarios' ||
            strpos($concept, 'Reserva') !== false ||
            strpos($concept, 'Alquiler') !== false) {
            return true;
        }

        return false;
    }

    public static function mustPrintDebit($concept) {
        if (strpos($concept, 'Gestion de Cobro') !== false ||
                strpos($concept, 'Prestamo') !== false) {
            return false;
        }

        return true;
    }

    public static function getAccountsBalance($month) {
        $instance = &get_instance();

        $current_accounts = $instance->basic->get_all('cuentas_corrientes')->result_array();
        $accounts_balance = array();

        $year = preg_replace('/[^0-9]/', '', $month);
        $month = preg_replace('/[0-9]/', '', $month);

        $from = '00-' . General::getMonthNumber($month) . '-' . $year;
        $to = '31-' . General::getMonthNumber($month) . '-' . $year;

        foreach ($current_accounts as $current_account) {
            if (strpos($current_account['cc_prop'], 'INMOBILIARIA') === FALSE && strpos($current_account['cc_prop'], 'CAJA FUERTE') === FALSE) {

                $debits = CurrentAccount::getDebits($current_account);
                $credits = CurrentAccount::getCredits($current_account);

                $ins = CurrentAccount::getCreditsSum($credits, $from, $to, false);
                $outs = CurrentAccount::getDebitsSum($debits, $from, $to, false);
                $balance = round($ins - $outs, 2);

                if($balance != 0) {
                    $propietary_account = array(
                        'name' => $current_account['cc_prop'],
                        'ins' => $ins,
                        'outs' => $outs,
                        'balance' => $balance
                    );

                    array_push($accounts_balance, $propietary_account);
                }
            }
        }

        return $accounts_balance;
    }

    public static function buildAccountsBalanceReport($month) {
        $instance = &get_instance();

        $instance->data['month'] = $month;
        $instance->data['accounts'] = self::getAccountsBalance($month);

        return $instance->load->view('reports/accounts_balance_report', $instance->data, TRUE);
    }

    public static function buildCashReport($date, $cash_type) {
        $instance = &get_instance();
        General::loadModels($instance);

        $date_array = explode('-', $date);
        $instance->data['transfers'] = array();
        $instance->data['movements'] = array();
        $instance->data['date'] = $date;
        $instance->data['ins'] = 0;
        $instance->data['outs'] = 0;

        if ($cash_type == 'cash') {
            $type = 'Caja';
            $type_show = 'Fisica';
      
            $instance->data['begin_cash'] = Cash::getBeginCash($date);

            $transfers_to_cash = $instance->basic->get_where('debitos', array('is_transfer' => 1, 'deb_fecha' => $date), 'deb_id', 'desc')->result_array();
            $transfers_to_safe = $instance->basic->get_where('creditos', array('is_transfer' => 1, 'cred_fecha' => $date), 'cred_id', 'desc')->result_array();

            foreach ($transfers_to_cash as $row) {
                array_push($instance->data['movements'], array(
                    'id' => $row['deb_id'],
                    'cc' => $row['deb_cc'],
                    'concept' => $row['deb_concepto'],
                    'amount' => $row['deb_monto'],
                    'type' => 'debito',
                    'receive_number' => '',
                    'month' => $row['deb_mes'],
                    'trans' => $row['trans'],
                    'is_transfer' => 1
                ));
            }

            foreach ($transfers_to_safe as $row) {
                array_push($instance->data['movements'], array(
                    'id' => $row['cred_id'],
                    'cc' => $row['cred_cc'],
                    'concept' => $row['cred_concepto'],
                    'month' => $row['cred_mes_alq'],
                    'amount' => $row['cred_monto'],
                    'dep' => $row['cred_depositante'],
                    'type' => 'credito',
                    'receive_number' => '',
                    'trans' => $row['trans'],
                    'is_transfer' => 1
                ));
            }
        } else {
            $type = $type_show = 'Bancaria';


            // La caja acumulada se acumula desde el principio
            $from = '00-00-0000';

            if ($date_array[0] - 1 == 0) {
                $to_day = 1;
                // Al primer dia del mes, la caja acumulada se tomara hasta la fecha del mes pasado
                $yesterday = mktime(0, 0, 0, $date_array[1], $date_array[0] - 1, $date_array[2]);
                $yesterday = date('d-m-Y', $yesterday);
                $to = $yesterday;
            } else {
                $to_day = $date_array[0] - 1;
                // Se toma la caja acumulada hasta el dia anterior al que se realiza la caja
                $to = $to_day . '-' . $date_array[1] . '-' . $date_array[2];
            }

            $instance->data['begin_cash'] = Cash::getBankBalanceByDate($from, $to);
        }

        $instance->data['type'] = $type;
        $instance->data['type_show'] = $type_show;

        // fetch movements
        $credits = $instance->basic->get_where('creditos', array('cred_tipo_trans' => $type, 'cred_fecha' => $date, 'is_transfer' => 0))->result_array();
        $debits = $instance->basic->get_where('debitos', array('deb_tipo_trans' => $type, 'deb_fecha' => $date, 'is_transfer' => 0))->result_array();

        foreach ($credits as $row) {
            if (isset($row)) {
                if ($row['cred_concepto'] != 'Gestion de Cobro' && $row['cred_concepto'] != 'Gestion de Cobro Sobre Intereses' &&
                    strpos($row['cred_concepto'], 'Prestamo') === FALSE) {
                    array_push($instance->data['movements'], array(
                        'id' => $row['cred_id'],
                        'cc' => $row['cred_cc'],
                        'receive_number' => $row['receive_number'] ? $row['receive_number'] : '',
                        'concept' => $row['cred_concepto'],
                        'month' => $row['cred_mes_alq'],
                        'amount' => $row['cred_monto'],
                        'debt' => isset($row['cred_tipo_pago']) && $row['cred_tipo_pago'] == 'A Cuenta' ? true : false,
                        'dep' => $row['cred_depositante'],
                        'address' => $row['cred_domicilio'],
                        'type' => 'credito',
                        'trans' => $row['trans'],
                        'is_transfer' => 0
                    ));
                    $instance->data['ins'] += $row['cred_monto'];
                }
            }
        }
        foreach ($debits as $row) {
            if (isset($row)) {
                if ($row['deb_concepto'] != 'Gestion de Cobro' && $row['deb_concepto'] != 'Gestion de Cobro Sobre Intereses' && 
                    strpos($row['deb_concepto'], 'Prestamo') === FALSE) {
                    array_push($instance->data['movements'], array(
                        'id' => $row['deb_id'],
                        'cc' => $row['deb_cc'],
                        'receive_number' => '',
                        'concept' => $row['deb_concepto'],
                        'address' => $row['deb_domicilio'],
                        'amount' => $row['deb_monto'],
                        'type' => 'debito',
                        'month' => $row['deb_mes'],
                        'trans' => $row['trans'],
                        'is_transfer' => 0
                    ));
                    $instance->data['outs'] += $row['deb_monto'];
                }
            }
        }

        $instance->data['movements'] = General::msort($instance->data['movements'], 'trans');

        return $instance->load->view('reports/cash_report', $instance->data, true);
    }

    public static function buildAccountReport($from, $to, $account) {
        $instance = &get_instance();
        General::loadModels($instance);

        $instance->data['account'] = $account;
        $instance->data['principal_ins'] = 0;
        $instance->data['principal_outs'] = 0;
        $instance->data['secondary_outs'] = 0;
        $instance->data['secondary_ins'] = 0;
        $instance->data['from'] = $from;
        $instance->data['to'] = $to;
        $instance->data['bussines_name'] = User::getBussinesName();

        $instance->data['intereses_debt'] = array();
        $instance->data['principal_movements'] = array();
        $instance->data['secondary_movements'] = array();

        $contracts = $instance->basic->get_where('contratos', array('cc_id' => $account['cc_id'], 'con_enabled' => 1))->result_array();
        $credits_prop = $instance->basic->get_where('creditos', array('cc_id' => $account['cc_id'], 'is_transfer' => 0), 'cred_id', 'asc')->result_array();
        $debits_prop = $instance->basic->get_where('debitos', array('cc_id' => $account['cc_id'], 'is_transfer' => 0), 'deb_id', 'asc')->result_array();
        $debts_intereses = $instance->basic->get_where('intereses_mora', array('cc_id' => $account['cc_id']))->result_array();
        $renditions = $instance->basic->get_where('debitos', array('cc_id' => $account['cc_id'], 'deb_concepto' => 'Rendicion'))->result_array();

        /* solo para davinia y rima */
        $contracts2 = $instance->basic->get_where('contratos', array('con_prop' => $account['cc_prop'], 'con_enabled' => 1))->result_array();
        $credits_prop2 = $instance->basic->get_where('creditos', array('cred_cc' => $account['cc_prop'], 'is_transfer' => 0), 'cred_id', 'asc')->result_array();
        $debits_prop2 = $instance->basic->get_where('debitos', array('deb_cc' => $account['cc_prop'], 'is_transfer' => 0), 'deb_id', 'asc')->result_array();
        $debts_intereses2 = $instance->basic->get_where('intereses_mora', array('int_cc' => $account['cc_prop']))->result_array();
        $renditions2 = $instance->basic->get_where('debitos', array('deb_cc' => $account['cc_prop'], 'deb_concepto' => 'Rendicion'))->result_array();

        foreach ($contracts2 as $contract2) {
            if (!in_array($contract2, $contracts))
                array_push($contracts, $contract2);
        }
        foreach ($debits_prop2 as $debit_prop2) {
            if (!in_array($debit_prop2, $debits_prop))
                array_push($debits_prop, $debit_prop2);
        }
        foreach ($credits_prop2 as $credit_prop2) {
            if (!in_array($credit_prop2, $credits_prop))
                array_push($credits_prop, $credit_prop2);
        }
        foreach ($debts_intereses2 as $debt_intereses2) {
            if (!in_array($debt_intereses2, $debts_intereses))
                array_push($debts_intereses, $debt_intereses2);
        }
        foreach ($renditions2 as $rendition2) {
            if (!in_array($rendition2, $renditions))
                array_push($renditions, $rendition2);
        }
        /* solo para davinia y rima */
        $today_rendition_amount = 0;
        $instance->data['address_rendition'] = '';
        $instance->data['month_rendition'] = '';
        $address_rendition = array();
        $month_rendition = array();
        foreach ($renditions as $row) {
            if (General::isBetweenDates($row['deb_fecha'], $from, $to)) {
                $today_rendition_amount += $row['deb_monto'];

                if (!in_array($row['deb_domicilio'], $address_rendition)) {
                    array_push($address_rendition, $row['deb_domicilio']);
                }

                if (!in_array($row['deb_mes'], $month_rendition)) {
                    array_push($month_rendition, $row['deb_mes']);
                }
            }
        }

        $instance->data['address_rendition'] = implode(', ', $address_rendition);
        $instance->data['month_rendition'] = implode(', ', $month_rendition);


        $instance->data['today_rendition_amount'] = round($today_rendition_amount, 2);
        $instance->data['today_rendition_amount_letra'] = Transaction::getTotalInLetters(round($today_rendition_amount, 2));
        $instance->data['contracts'] = $contracts;
        $instance->data['comentaries'] = $instance->basic->get_where('comentarios', array('com_prop' => $account['cc_prop'], 'com_mes' => date('m'), 'com_ano' => date('Y')))->result_array();

        foreach ($debts_intereses as $row) {
            if (General::isBetweenDates($row['int_fecha_pago'], $from, $to)) {
                array_push($instance->data['intereses_debt'], $row);
            }
        }

        /* Agrupo los creditos de alquiler y varios en diferentes arrays, lo mismo ocurre con los debitos */
        foreach ($credits_prop as $row) {
            if (strpos($row['cred_concepto'], "Gestion de Cobro") === false || $account['cc_prop'] == 'INMOBILIARIA') {

                if (General::isBetweenDates($row['cred_fecha'], $from, $to)) {
                    /* Pertenece a un credito del rango de fechas ingresados */
                    $movement = array(
                        'id' => $row['cred_id'],
                        'month' => $row['cred_mes_alq'],
                        'date' => $row['cred_fecha'],
                        'concept' => $row['cred_concepto'],
                        'show_concept' => $row['cred_concepto'] . ' ' . $row['cred_mes_alq'] . ' (con ' . $row['cred_depositante'] . ')',
                        'amount' => $row['cred_monto'],
                        'dep' => $row['cred_depositante'],
                        'type' => 'credito',
                        'address' => $row['cred_domicilio'],
                        'trans' => $row['trans']
                    );

                    $account_type = General::getAccountType($row, 'Entrada', 'cred_concepto');

                    if (isset($account_type)) {
                        if ($account_type == 'cc_saldo') {
                            $instance->data['principal_ins'] += $movement['amount'];
                            array_push($instance->data['principal_movements'], $movement);
                        } else {
                            $instance->data['secondary_ins'] += $movement['amount'];
                            array_push($instance->data['secondary_movements'], $movement);
                        }
                    }
                }
            }
        }

        $management_charge_amount = 0;
        foreach ($debits_prop as $row) {

            if (General::isBetweenDates($row['deb_fecha'], $from, $to)) {
                if (strpos($row['deb_concepto'], "Gestion de Cobro") !== false) {
                    $management_charge_amount += $row['deb_monto'];
                } else {
                    $movement = array(
                        'id' => $row['deb_id'],
                        'date' => $row['deb_fecha'],
                        'concept' => $row['deb_concepto'],
                        'show_concept' => $row['deb_concepto'] . ' ' . $row['deb_mes'] . ' ' . $row['deb_domicilio'],
                        'month' => $row['deb_mes'],
                        'amount' => $row['deb_monto'],
                        'address' => $row['deb_domicilio'],
                        'trans' => $row['trans'],
                        'type' => 'debito'
                    );

                    $account_type = General::getAccountType($row, 'Salida', 'deb_concepto');

                    if (isset($account_type)) {
                        if ($account_type == 'cc_saldo') {
                            $instance->data['principal_outs'] += $movement['amount'];
                            array_push($instance->data['principal_movements'], $movement);
                        } else {
                            $instance->data['secondary_outs'] += $movement['amount'];
                            array_push($instance->data['secondary_movements'], $movement);
                        }
                    }
                }
            }
        }

        // Put services controls in secondary_movements
        foreach ($contracts as $contract) {
            $contract_control_services = $instance->basic->get_where('servicios', array('serv_contrato' => $contract['con_id'], 'serv_accion' => 'Controlar'))->result_array();
            $services_control = $instance->basic->get_where('services_control', array('contract' => $contract['con_id']))->result_array();

            foreach ($contract_control_services as $contract_control_service) {
                $movement = array(
                    'id' => $contract_control_service['serv_id'],
                    'month' => '-',
                    'amount' => 0,
                    'date' => '-',
                    'dep' => $contract['con_inq'],
                    'concept' => $contract_control_service['serv_concepto'],
                    'show_concept' => '-',
                    'type' => 'credito',
                    'controlled' => false,
                    'trans' => 0
                );

                $controled_in_this_period = false;

                $controled = array();
                foreach ($services_control as $service_control) {
                    if ($service_control['service'] == $contract_control_service['serv_concepto']) {
                        if (General::isBetweenDates($service_control['date'], $from, $to)) {
                            $controled_in_this_period = true;
                            $controled = $service_control;
                            break;
                        }
                    }
                }

                if ($controled_in_this_period) {
                    $movement['id'] = $controled['id'];
                    $movement['month'] = $controled['month_checked'];
                    $movement['date'] = $controled['date'];
                    $movement['show_concept'] = $controled['service'] . ' ' . $controled['month_checked'];
                    $movement['controlled'] = true;
                    $movement['trans'] = $controled['trans'];
                }

                array_push($instance->data['secondary_movements'], $movement);
            }
        }

        $instance->data['principal_movements'] = General::msort($instance->data['principal_movements'], 'trans');
        $instance->data['secondary_movements'] = General::msort($instance->data['secondary_movements'], 'trans');

        if ($account['cc_prop'] != 'INMOBILIARIA' && $account['cc_prop'] != 'CAJA FUERTE') {
            $instance->data['contracts_period_status'] = self::getContractsPeriodStatus($from, $to, $contracts, $instance->data['secondary_movements'], $instance->data['principal_movements']);

            $movement = array(
                'id' => '',
                'date' => '',
                'show_concept' => 'Gestion de Cobro acumulada del periodo',
                'month' => '',
                'amount' => $management_charge_amount,
                'address' => '',
                'type' => 'debito'
            );
            $instance->data['principal_outs'] += $movement['amount'];
            array_push($instance->data['principal_movements'], $movement);
        }

        return $instance->load->view('reports/account_report', $instance->data, TRUE);
    }

    public static function getContractsPeriodStatus($from, $to, $contracts, $secondary_movements, $principal_movements) {
        $instance = &get_instance();
        General::loadModels($instance);
        $contracts_status = array();

        foreach ($contracts as $contract) {

            $contracts_status[$contract['con_id']] = array(
                'principals' => array(),
                'secondarys' => array()
            );

            $services = $instance->basic->get_where('servicios', array('serv_contrato' => $contract['con_id']))->result_array();

            foreach ($services as $service) {

                if ($service['serv_accion'] == 'Pagar') {

                    // cargo servicios pagos
                    foreach ($secondary_movements as $secondary_movement) {
                        if ($secondary_movement['type'] == 'credito' &&
                                $secondary_movement['dep'] == $contract['con_inq'] &&
                                $secondary_movement['concept'] == $service['serv_concepto']) {
                            if (General::isBetweenDates($secondary_movement['date'], $from, $to)) {
                                // encontro el pago
                                $contract_movement = array(
                                    'concept' => $service['serv_concepto'],
                                    'action' => 'Pago',
                                    'date' => $secondary_movement['date'],
                                    'month' => $secondary_movement['month']
                                );

                                array_push($contracts_status[$contract['con_id']]['secondarys'], $contract_movement);
                            }
                        }
                    }

                    // cargo dedudas de servicios
                    $contract_service_debts = Contract::getContractServicesDebts($service['serv_concepto'], $contract);
                    foreach ($contract_service_debts as $contract_service_debt) {
                        $contract_movement = array(
                            'concept' => $contract_service_debt['concept'],
                            'action' => 'No Pago',
                            'date' => '-',
                            'month' => $contract_service_debt['month']
                        );
                        array_push($contracts_status[$contract['con_id']]['secondarys'], $contract_movement);
                    }
                } else {

                    // cargo boletas presentadas
                    $services_control = $instance->basic->get_where('services_control', array('contract' => $contract['con_id'], 'service' => $service['serv_concepto']))->result_array();
                    if (!empty($services_control)) {
                        foreach ($services_control as $service_control) {
                            if (General::isBetweenDates($service_control['date'], $from, $to)) {
                                $contract_movement = array(
                                    'concept' => $service_control['service'],
                                    'action' => 'Boleta Presentada',
                                    'date' => $service_control['date'],
                                    'month' => $service_control['month_checked']
                                );
                                array_push($contracts_status[$contract['con_id']]['secondarys'], $contract_movement);
                            }
                        }
                    }

                    // cargo boletas pendientes de presentacion
                    $last_control_serv = Transaction::getLastControl($contract, $service['serv_concepto']);
                    if ($last_control_serv) {
                        $service_control_debt = Contract::getContractServicesControls($last_control_serv);
                        if (!empty($service_control_debt)) {
                            foreach ($service_control_debt as $control_debt) {
                                $contract_movement = array(
                                    'concept' => $control_debt['concept'],
                                    'action' => 'Boleta Pendiente',
                                    'date' => '-',
                                    'month' => $control_debt['month']
                                );
                                array_push($contracts_status[$contract['con_id']]['secondarys'], $contract_movement);
                            }
                        }
                    }
                }
            }

            // cargo pagos de contratos
            foreach ($principal_movements as $principal_movement) {

                if ($principal_movement['type'] == 'credito' &&
                        $principal_movement['dep'] == $contract['con_inq'] &&
                        $principal_movement['concept'] == $contract['con_tipo']) {
                    if (General::isBetweenDates($principal_movement['date'], $from, $to)) {

                        // encontro el pago
                        $contract_movement = array(
                            'concept' => $contract['con_tipo'],
                            'action' => 'Pago',
                            'date' => $principal_movement['date'],
                            'month' => $principal_movement['month']
                        );

                        array_push($contracts_status[$contract['con_id']]['principals'], $contract_movement);
                    }
                }
            }


            // cargo deudas de contratos
            $contract_debts = Contract::getContractDebts($contract['con_tipo'], $contract);
            foreach ($contract_debts as $contract_debt) {

                $contract_movement = array(
                    'concept' => $contract_debt['concept'],
                    'action' => 'No Pago',
                    'date' => '-',
                    'month' => $contract_debt['month']
                );
                array_push($contracts_status[$contract['con_id']]['principals'], $contract_movement);
            }
        }

        return $contracts_status;
    }

    public static function buildPropietaryRenditionsReport($from, $to) {
        $instance = &get_instance();
        General::loadModels($instance);

        $instance->data['accounts'] = array();
        $date_renditions = array();

        $instance->data['from'] = $from;
        $instance->data['to'] = $to;

        $accounts = $instance->basic->get_where('cuentas_corrientes', array(), 'cc_prop')->result_array();

        $renditions = $instance->basic->get_where('debitos', array('deb_concepto' => 'Rendicion'))->result_array();

        foreach ($renditions as $rendition) {
            if (General::isBetweenDates($rendition['deb_fecha'], $from, $to)) {
                array_push($date_renditions, $rendition);
            }
        }

        foreach ($accounts as $account) {
            $rendition_amount = 0;

            if (strpos($account['cc_prop'], 'INMOBILIARIA') === FALSE && strpos($account['cc_prop'], 'CAJA FUERTE') === FALSE) {

                $debits = CurrentAccount::getDebits($account);
                $credits = CurrentAccount::getCredits($account);

                // Suma las rendiciones extraidas por el propietario en la fecha
                foreach ($debits as $row) {
                    if ($row['deb_concepto'] == 'Rendicion') {
                        if (General::isBetweenDates($row['deb_fecha'], $from, $to)) {
                            $rendition_amount += $row['deb_monto'];
                        }
                    }
                }

                $ins = CurrentAccount::getCreditsSum($credits, $from, $to, true);
                $outs = CurrentAccount::getDebitsSum($debits, $from, $to, true);

                $propietary_account = array(
                    'id' => $account['cc_id'],
                    'name' => $account['cc_prop'],
                    'rendition' => $rendition_amount,
                    'ins' => $ins,
                    'outs' => $outs,
                    'account_movements_sald' => round($ins - $outs, 2),
                    'account_sald' => round($ins - $outs, 2),
                    'account_operative_sald' => round($account['cc_saldo'] + $account['cc_varios'], 2),
                    'extract_rendition' => false
                );

                if (count($date_renditions) > 0) {
                    foreach ($date_renditions as $date_rendition) {
                        if ($propietary_account['id'] == $date_rendition['cc_id'] || $propietary_account['name'] == $date_rendition['deb_cc']) {
                            $propietary_account['extract_rendition'] = 1;
                            break;
                        }
                    }
                }

                array_push($instance->data['accounts'], $propietary_account);
            }
        }

        return $instance->load->view('reports/propietary_renditions_report', $instance->data, TRUE);
    }

    public static function buildpropietaryLoansReport($from, $to) {
        $instance = &get_instance();
        General::loadModels($instance);

        $loans = $instance->basic->get_where('creditos', array('cred_depositante' => 'Inmobiliaria'))->result_array();

        $instance->data['from'] = $from;
        $instance->data['to'] = $to;
        $instance->data['total_loans'] = 0;
        $instance->data['total_loans_returned'] = 0;
        $instance->data['default_loans'] = array();
        $instance->data['returned_loans'] = array();

        foreach ($loans as $loan) {
            if (General::isBetweenDates($loan['cred_fecha'], $from, $to)) {

                if ($loan['cred_concepto'] == 'Prestamo') {
                    $instance->data['total_loans'] += $loan['cred_monto'];
                    array_push($instance->data['default_loans'], $loan);
                }

                if ($loan['cred_concepto'] == 'Prestamo Devuelto') {
                    $instance->data['total_loans_returned'] += $loan['cred_monto'];
                    array_push($instance->data['returned_loans'], $loan);
                }
            }
        }

        $instance->data['returned_loans'] = General::msort($instance->data['returned_loans'], 'cred_cc');
        $instance->data['default_loans'] = General::msort($instance->data['default_loans'], 'cred_cc');

        return $instance->load->view('reports/propietary_loans_report', $instance->data, TRUE);
    }

    public static function buildRenterPaymentHistorialReport($year, $client_id) {
        $instance = &get_instance();
        General::loadModels($instance);

        $renter = $instance->basic->get_where('clientes', array('client_id' => $client_id))->row_array();
        $instance->data['year'] = $year;
        $instance->data['renter'] = $renter;

        $instance->data['contract_movements'] = array();
        $instance->data['uncontract_payments'] = array();

        $credits = $instance->basic->get_where('creditos', array('client_id' => $client_id))->result_array();
        $contracts = $instance->basic->get_where('contratos', array('client_id' => $client_id), 'con_id')->result_array();

        /* solo para davinia y rima */
        $credits2 = $instance->basic->get_where('creditos', array('cred_depositante' => $renter['client_name']))->result_array();
        $contracts2 = $instance->basic->get_where('contratos', array('con_inq' => $renter['client_name']), 'con_id')->result_array();
        foreach ($credits2 as $credit2) {
            if (!in_array($credit2, $credits))
                array_push($credits, $credit2);
        }
        foreach ($contracts2 as $contract2) {
            if (!in_array($contract2, $contracts))
                array_push($contracts, $contract2);
        }
        /* solo para davinia y rima */

        foreach ($contracts as $contract) {

            $payments = array();
            foreach ($credits as $key => $credit) {

                if (General::isSameYear($credit['cred_fecha'], $year)) {

                    if ($contract['cc_id'] == $credit['cc_id'] &&
                            $contract['client_id'] == $credit['client_id'] ||
                            $contract['con_prop'] == $credit['cred_cc'] &&
                            $contract['con_inq'] == $credit['cred_depositante']) {
                        // Si el credito es del contrato

                        $payment = array(
                            'concept' => $credit['cred_concepto'],
                            'payment_date' => $credit['cred_fecha'],
                            'month_payed' => $credit['cred_mes_alq'],
                            'type' => $credit['cred_tipo_pago'],
                            'amount' => $credit['cred_monto'],
                            'id' => $credit['cred_id'],
                        );

                        array_push($payments, $payment);

                        unset($credits[$key]);
                    }
                } else {
                    unset($credits[$key]);
                }
            }

            $instance->data['payments'] = General::msort($payments, 'id');

            array_push($instance->data['contract_movements'], array(
                'propietary' => $contract['con_prop'],
                'address' => $contract['con_domi'],
                'decline_date' => $contract['con_venc'],
                'type' => $contract['con_tipo'],
                'enabled' => $contract['con_enabled'],
                'payments' => $payments
            ));
        }

        if (count($credits) > 0) {
            // Si existen creditos que no pertenecen a ningun contracto
            // se mostraran a parte
            foreach ($credits as $credit) {
                if (General::isSameYear($credit['cred_fecha'], $year)) {
                    $payment = array(
                        'concept' => $credit['cred_concepto'],
                        'payment_date' => $credit['cred_fecha'],
                        'month_payed' => $credit['cred_mes_alq'],
                        'type' => $credit['cred_tipo_pago'],
                        'amount' => $credit['cred_monto'],
                        'id' => $credit['cred_id'],
                    );

                    array_push($instance->data['uncontract_payments'], $payment);
                }
            }
            $instance->data['uncontract_payments'] = General::msort($instance->data['uncontract_payments'], 'id');
        }

        return $instance->load->view('reports/renter_payment_historial_report', $instance->data, TRUE);
    }

    /**
     * Tomar el ultimo pago del inquilino
     * Si el pago corresponde al mes anterior al actual de la $fecha y si la tolerancia del contrato
     * no supera la $fecha el inquilino no debe nada
     * Si no corresponde al mes anterior al actual obtener el nro que representa el mes
     * Ej Octubre = 10
     * Si estamos en febrero debe 4 meses, entonces, por cada mes, se creara una entrada
     * al array interno del deudor en cuestion, con el monto correspondiente al periodo del mes q se debe
     * mas los intereses que correspondan, si existen pagos a cuenta de los intereses, seran contemplados
     */
    public static function buildRentersInDefaultReport($date) {
        $instance = &get_instance();

        $instance->data = self::getRentersInDefault($date);

        return $instance->load->view('reports/renters_in_default_report', $instance->data, TRUE);
    }

    public static function getRentersInDefault($date, $renter_id = false) {
        $instance = &get_instance();
        $data = array();

        if($renter_id){
            $data['one_renter'] = true;
            $alive_contracts = $instance->basic->get_where('contratos', array('client_id' => $renter_id, 'con_enabled' => 1), 'con_tipo')->result_array();

            if(empty($alive_contracts)){
                $renter = $instance->basic->get_where('clientes', array('client_id' => $renter_id))->row_array();

                $alive_contracts = $instance->basic->get_where('contratos', array('con_inq' => $renter['client_name'], 'con_enabled' => 1), 'con_tipo')->result_array();
            }

        }else{
            $data['one_renter'] = false;

            $alive_contracts = $instance->basic->get_where('contratos', array('con_enabled' => 1), 'con_tipo')->result_array();
        }

        $contract_renters_debts = array();
        $service_renters_debts = array();
        $control_service_renters_debts = array();
        $data['renters'] = array();

        foreach ($alive_contracts as $alive_contract) {
            /* solo para davinia y rima */
            if ($alive_contract['client_id']) {
                /* solo para davinia y rima */
                $renter = $instance->basic->get_where('clientes', array('client_id' => $alive_contract['client_id']))->row_array();
            } else {
                $renter = $instance->basic->get_where('clientes', array('client_name' => $alive_contract['con_inq']))->row_array();
            }

            if (!empty($renter)) {
                
                $debts_founded = false;

                $service_renters_debts[$renter['client_id']] = array();
                $control_service_renters_debts[$renter['client_id']] = array();
                $contract_renters_debts[$renter['client_id']] = array();

                $renter['type'] = $alive_contract['con_tipo'];
                $renter['propietary'] = $alive_contract['con_prop'];
                $renter['address'] = $alive_contract['con_domi'];

                // Deudas de Alquileres o Loteos
                if ($alive_contract['con_usado']) {
                    $contract_debts = Contract::getContractDebts($alive_contract['con_tipo'], $alive_contract);
                    if (!empty($contract_debts)) {
                        $contract_renters_debts[$renter['client_id']] = $contract_debts;
                        $debts_founded = true;
                    }
                }

                if (empty($contract_renters_debts[$renter['client_id']])) {
                    unset($contract_renters_debts[$renter['client_id']]);
                }

                $services = $instance->basic->get_where('servicios', array('serv_contrato' => $alive_contract['con_id']))->result_array();
                foreach ($services as $service) {
                    if ($alive_contract['con_usado']) {
                        if ($service['serv_accion'] == 'Pagar') {
                            // Deudas de servicios
                            $service_debts = Contract::getContractServicesDebts($service['serv_concepto'], $alive_contract);
                            if (!empty($service_debts)) {
                                array_push($service_renters_debts[$renter['client_id']], $service_debts);
                                $debts_founded = true;
                            }
                        } else {
                            // cargo boletas pendientes de presentacion
                            $last_control_serv = Transaction::getLastControl($alive_contract, $service['serv_concepto']);
                            if ($last_control_serv) {
                                $service_control_debt = Contract::getContractServicesControls($last_control_serv);
                                if (!empty($service_control_debt)) {
                                    array_push($control_service_renters_debts[$renter['client_id']], $service_control_debt);
                                    $debts_founded = true;
                                }
                            }
                        }
                    }
                }

                if (empty($service_renters_debts[$renter['client_id']])) {
                    unset($service_renters_debts[$renter['client_id']]);
                }

                if (empty($control_service_renters_debts[$renter['client_id']])) {
                    unset($control_service_renters_debts[$renter['client_id']]);
                }

                if ($debts_founded) {
                    array_push($data['renters'], $renter);
                }
            }
        }

        $data['contract_renters_debts'] = $contract_renters_debts;
        $data['service_renters_debts'] = $service_renters_debts;
        $data['control_service_renters_debts'] = $control_service_renters_debts;

        $data['date'] = $date;
        $data['renters'] = General::msort($data['renters'], 'client_name');

        return $data;
    }

    public static function buildPendingRenditionsReport($from, $to) {
        $instance = &get_instance();
        General::loadModels($instance);

        $accounts = $instance->basic->get_all('cuentas_corrientes')->result_array();

        $instance->data['from'] = $from;
        $instance->data['to'] = $to;
        $instance->data['heading'] = 'Informe de rendiciones pendientes entre las fechas ' . $from . ' y ' . $to;
        $instance->data['pending_renditions'] = array();

        foreach ($accounts as $account) {
            $is_pending = false;

            if ($account['cc_prop'] != 'INMOBILIARIA' && $account['cc_prop'] != 'CAJA FUERTE') {
                if (($account['cc_saldo'] + $account['cc_varios']) > 0) {

                    $renditions = $instance->basic->get_where('debitos', array('cc_id' => $account['cc_id'], 'deb_concepto' => 'Rendicion'))->result_array();
                    /* solo para davinia y rima */
                    $renditions2 = $instance->basic->get_where('debitos', array('deb_cc' => $account['cc_prop'], 'deb_concepto' => 'Rendicion'))->result_array();
                    foreach ($renditions2 as $rendition2) {
                        if (!in_array($rendition2, $renditions))
                            array_push($renditions, $rendition2);
                    }
                    /* solo para davinia y rima */

                    if (count($renditions) > 0) {

                        foreach ($renditions as $rendition) {

                            if (General::isBetweenDates($rendition['deb_fecha'], $from, $to)) {
                                /* solo para davinia y rima */
                                if ($rendition['cc_id']) {
                                    /* solo para davinia y rima */
                                    $credits = $instance->basic->get_where('creditos', array('cc_id' => $rendition['cc_id']))->result_array();
                                } else {
                                    $credits = $instance->basic->get_where('creditos', array('cred_cc' => $rendition['deb_cc']))->result_array();
                                }

                                // encontro rendicion, no tiene pendientes
                                $is_pending = false;

                                foreach ($credits as $credit) {
                                    if (General::isBetweenDates($credit['cred_fecha'], $rendition['deb_fecha'], $to)) {
                                        // vemos si de ahi en mas no le depositarion nuevamente
                                        if (in_array($credit['cred_concepto'], array('Loteo', 'Alquiler', 'Alquiler Comercial', 'Indemnizacion', 'Intereses'))) {
                                            // si le depositarion nuevamente entonces tiene rendiciones pendientes
                                            $is_pending = true;
                                        }
                                    }
                                }
                            } else {
                                // no tuvo rendiciones en la fecha por lo tanto tiene pendientes
                                if (($account['cc_saldo'] + $account['cc_varios']) > 0) {
                                    $is_pending = true;
                                }
                            }
                        }
                    } else {
                        // Si no hay rendiciones hechas en una cuenta es obviedad que estan pendiente
                        $is_pending = true;
                    }

                    $account['sald'] = round($account['cc_saldo'] + $account['cc_varios'], 2);
//                    $account['sald'] = 0;
//
//                    $credits_prop = $instance->basic->get_where('creditos', array('cred_cc' => $account['cc_prop'], 'is_transfer' => 0), 'cred_id', 'asc')->result_array();
//
//                    $debits_prop = $instance->basic->get_where('debitos', array('deb_cc' => $account['cc_prop'], 'is_transfer' => 0), 'deb_id', 'asc')->result_array();
//
//
//                    $in_concepts = $instance->basic->get_where('conceptos', array('conc_tipo' => 'Entrada'))->result_array();
//                    $in_concepts = self::distinctConcepts($in_concepts);
//                    $out_concepts = $instance->basic->get_where('conceptos', array('conc_tipo' => 'Salida'))->result_array();
//                    $out_concepts = self::distinctConcepts($out_concepts);
//
//                    $account['ins'] = array();
//                    $account['outs'] = array();
//
//                    foreach ($in_concepts as $in_concept) {
//                        $amount = 0;
//                        foreach ($credits_prop as $credit) {
//
//                            if ($in_concept['conc_desc'] == $credit['cred_concepto']) {
//                                $amount += $credit['cred_monto'];
//                                $account['sald'] += $credit['cred_monto'];
//                            }
//                        }
//
//                        if ($amount)
//                            array_push($account['ins'], array($in_concept['conc_desc'] => $amount));
//                    }
//
//                    foreach ($out_concepts as $out_concept) {
//                        $amount = 0;
//                        foreach ($debits_prop as $debit) {
//
//                            if ($out_concept['conc_desc'] == $debit['deb_concepto']) {
//                                $amount += $debit['deb_monto'];
//                                $account['sald'] -= $debit['deb_monto'];
//                            }
//                        }
//                        if ($amount)
//                            array_push($account['outs'], array($out_concept['conc_desc'] => $amount));
//                    }
//
//
//                    print_r($account);
                    if ($is_pending) {
                        array_push($instance->data['pending_renditions'], $account);
                    }
                }
            }
        }

        return $instance->load->view('reports/pending_renditions_report', $instance->data, TRUE);
    }

    public static function buildRenditionsPercentReport($from, $to) {
        $instance = &get_instance();
        General::loadModels($instance);

        $instance->data['from'] = $from;
        $instance->data['to'] = $to;

        $instance->data['heading'] = 'Porcentaje de rendiciones entre las fechas ' . $from . ' y ' . $to;

        $active_accounts = array();
        $accounts = $instance->basic->get_all('cuentas_corrientes')->result_array();
        $contracts = $instance->basic->get_where('contratos', array('con_enabled' => 1))->result_array();

        foreach ($accounts as $account) {

            if ($account['cc_prop'] != 'INMOBILIARIA' && $account['cc_prop'] != 'CAJA FUERTE') {

                foreach ($contracts as $contract) {

                    if ($account['cc_id'] == $contract['cc_id'] || $account['cc_prop'] == $contract['con_prop']) {

                        if (!in_array($account, $active_accounts)) {
                            array_push($active_accounts, $account);
                        }
                    }
                }
            }
        }
        $instance->data['active_accounts'] = count($active_accounts);

        $renditioned_accounts = array();
        foreach ($accounts as $account) {
            if ($account['cc_prop'] != 'INMOBILIARIA' && $account['cc_prop'] != 'CAJA FUERTE') {

                $renditions = $instance->basic->get_where('debitos', array('cc_id' => $account['cc_id'], 'deb_concepto' => 'Rendicion'))->result_array();
                /* solo para davinia y rima */
                $renditions2 = $instance->basic->get_where('debitos', array('deb_cc' => $account['cc_prop'], 'deb_concepto' => 'Rendicion'))->result_array();
                foreach ($renditions2 as $rendition2) {
                    if (!in_array($rendition2, $renditions))
                        array_push($renditions, $rendition2);
                }
                /* solo para davinia y rima */

                foreach ($renditions as $rendition) {
                    if (General::isBetweenDates($rendition['deb_fecha'], $from, $to)) {

                        if (!in_array($account, $renditioned_accounts)) {
                            array_push($renditioned_accounts, $account);
                        }
                    }
                }
            }
        }

        $instance->data['renditioned_accounts'] = count($renditioned_accounts);

        return $instance->load->view('reports/renditions_percent_report', $instance->data, TRUE);
    }

    public static function buildContractsDeclinationReport($from) {
        $instance = &get_instance();
        General::loadModels($instance);

        $to = date('d-m-Y', strtotime($from) + 24 * 60 * 60 * 60); // + 60 dias
        $contracts = $instance->basic->get_where('contratos', array('con_enabled' => 1))->result_array();

        $instance->data['contracts'] = array();
        $instance->data['heading'] = 'Informe de contratos a vencer entre las fechas ' . $from . ' y ' . $to;

        if (count($contracts) > 0) {

            foreach ($contracts as $contract) {

                if (General::isBetweenDates($contract['con_venc'], $from, $to)) {

                    $last_payment = Transaction::getLastPayment($contract, $contract['con_tipo']);
                    $contract['last_payment'] = $last_payment;
                    $contract['con_venc'] = strtotime($contract['con_venc']);
                    array_push($instance->data['contracts'], $contract);
                }
            }

            $instance->data['contracts'] = General::msort($instance->data['contracts'], 'con_venc');
        }

        return $instance->load->view('reports/contracts_declination_report', $instance->data, TRUE);
    }

    public static function distinctConcepts($concepts) {
        $distinct_concepts = array();

        foreach ($concepts as $concept) {
            $concept_shifted = array_shift($concepts);
            $exist = false;

            foreach ($concepts as $concept2) {
                if ($concept_shifted['conc_desc'] == $concept2['conc_desc'] &&
                        $concept_shifted['conc_tipo'] == $concept2['conc_tipo']) {
                    // esta dos veces en la bd el mismo concepto
                    $exist = true;
                }
            }

            if (!$exist) {
                array_push($distinct_concepts, $concept);
            }
        }

        return $distinct_concepts;
    }

    public static function buildAllConceptsMovementsReport($from, $to) {
        $instance = &get_instance();
        General::loadModels($instance);

        $debits = $instance->basic->get_where('debitos', array('is_transfer' => 0))->result_array();
        $credits = $instance->basic->get_where('creditos', array('is_transfer' => 0))->result_array();

        $instance->data['in_concepts'] = $instance->basic->get_where('conceptos', array('conc_tipo' => 'Entrada'))->result_array();
        $instance->data['in_concepts'] = self::distinctConcepts($instance->data['in_concepts']);
        $instance->data['out_concepts'] = $instance->basic->get_where('conceptos', array('conc_tipo' => 'Salida'))->result_array();
        $instance->data['out_concepts'] = self::distinctConcepts($instance->data['out_concepts']);

        $instance->data['credits'] = array();
        $instance->data['debits'] = array();
        $instance->data['from'] = $from;
        $instance->data['to'] = $to;
        $instance->data['total_cred'] = 0;
        $instance->data['total_deb'] = 0;
        $instance->data['gestion'] = 0;

        foreach ($credits as $credit) {
            if (General::isBetweenDates($credit['cred_fecha'], $from, $to)) {
                if (strpos($credit['cred_concepto'], 'Gestion de Cobro') !== FALSE) {
                    $instance->data['gestion'] += $credit['cred_monto'];
                }
                if (strpos($credit['cred_concepto'], 'Prestamo') === FALSE) {
                    array_push($instance->data['credits'], $credit);
                    $instance->data['total_cred'] += $credit['cred_monto'];
                }
            }
        }

        foreach ($debits as $debit) {
            if (General::isBetweenDates($debit['deb_fecha'], $from, $to)) {
                if (strpos($debit['deb_concepto'], 'Gestion de Cobro') === FALSE && strpos($debit['deb_concepto'], 'Prestamo') === FALSE) {
                    array_push($instance->data['debits'], $debit);
                    $instance->data['total_deb'] += $debit['deb_monto'];
                }
            }
        }

        $instance->data['total_cred'] = $instance->data['total_cred'] - $instance->data['gestion'];

        return $instance->load->view('reports/all_concepts_movements_report', $instance->data, TRUE);
    }

    public static function buildBankTransactionsReport($from, $to) {
        $instance = &get_instance();
        General::loadModels($instance);

        $credits = $instance->basic->get_where('creditos', array('cred_tipo_trans' => 'Bancaria'))->result_array();
        $debits = $instance->basic->get_where('debitos', array('deb_tipo_trans' => 'Bancaria'))->result_array();
        $instance->data['credits'] = array();
        $instance->data['debits'] = array();
        $instance->data['from'] = $from;
        $instance->data['to'] = $to;
        $instance->data['total_cred'] = 0;
        $instance->data['total_deb'] = 0;

        foreach ($credits as $credit) {

            if (General::isBetweenDates($credit['cred_fecha'], $from, $to)) {

                if (strpos($credit['cred_concepto'], 'Gestion de Cobro') === FALSE) {
                    array_push($instance->data['credits'], $credit);
                    $instance->data['total_cred'] += $credit['cred_monto'];
                }
            }
        }

        foreach ($debits as $debit) {

            if (General::isBetweenDates($debit['deb_fecha'], $from, $to)) {

                if (strpos($debit['deb_concepto'], 'Gestion de Cobro') === FALSE) {
                    array_push($instance->data['debits'], $debit);
                    $instance->data['total_deb'] += $debit['deb_monto'];
                }
            }
        }

        $instance->data['credits'] = General::msort($instance->data['credits'], 'trans');
        $instance->data['debits'] = General::msort($instance->data['debits'], 'trans');

        return $instance->load->view('reports/bank_transactions_report', $instance->data, TRUE);
    }

    public static function buildGeneralBalanceReport($from, $to) {
        $instance = &get_instance();
        General::loadModels($instance);

        $credits = $instance->basic->get_where('creditos', array('cred_cc' => 'INMOBILIARIA'))->result_array();
        $debits = $instance->basic->get_where('debitos', array('deb_cc' => 'INMOBILIARIA'))->result_array();

        $instance->data['from'] = $from;
        $instance->data['to'] = $to;
        $instance->data['honorary'] = 0;
        $instance->data['gestion'] = 0;
        $instance->data['tasation'] = 0;
        $instance->data['total_facturation'] = 0;
        $instance->data['expenses'] = array();
        $instance->data['total_expenses'] = 0;

        foreach ($credits as $credit) {

            if (General::isBetweenDates($credit['cred_fecha'], $from, $to)) {

                if ($credit['cred_concepto'] == 'Honorarios') {

                    $instance->data['honorary'] += $credit['cred_monto'];
                    $instance->data['total_facturation'] += $credit['cred_monto'];
                }
                if ($credit['cred_concepto'] == 'Tasacion') {

                    $instance->data['tasation'] += $credit['cred_monto'];
                    $instance->data['total_facturation'] += $credit['cred_monto'];
                }
                if (strpos($credit['cred_concepto'], 'Gestion de Cobro') !== false) {

                    $instance->data['gestion'] += $credit['cred_monto'];
                    $instance->data['total_facturation'] += $credit['cred_monto'];
                }
            }
        }

        foreach ($debits as $debit) {

            if (General::isBetweenDates($debit['deb_fecha'], $from, $to)) {

                if (strpos($debit['deb_concepto'], 'Gestion de Cobro') === FALSE &&
                        strpos($debit['deb_concepto'], 'Prestamo') === FALSE) {

                    if (!key_exists($debit['deb_concepto'], $instance->data['expenses'])) {
                        $instance->data['expenses'][$debit['deb_concepto']] = 0;
                    }

                    $instance->data['expenses'][$debit['deb_concepto']] += $debit['deb_monto'];
                    $instance->data['total_expenses'] += $debit['deb_monto'];
                }
            }
        }

        return $instance->load->view('reports/general_balance_report', $instance->data, TRUE);
    }

    public static function buildEndedMaintenancesReport($from, $to) {
        $instance = &get_instance();
        General::loadModels($instance);

        $maintenances = $instance->basic->get_where('mantenimientos', array('mant_status' => 3))->result_array();

        $instance->data['from'] = $from;
        $instance->data['to'] = $to;
        $instance->data['maintenances'] = array();

        foreach ($maintenances as $maintenance) {
            if (strlen($maintenance['mant_date_end'])) {
                if (General::isBetweenDates($maintenance['mant_date_end'], $from, $to)) {
                    array_push($instance->data['maintenances'], $maintenance);
                }
            }
        }

        return $instance->load->view('reports/ended_maintenances_report', $instance->data, TRUE);
    }

    public static function buildRentersPaymentPercentReport($month) {
        $instance = &get_instance();
        General::loadModels($instance);

        $instance->data['month'] = $month;
        $instance->data['heading'] = 'Porcentaje de pago de alquileres para el mes de ' . $month;

        $credits_rents = $instance->basic->get_where('creditos', array('cred_concepto' => 'Alquiler', 'cred_mes_alq' => $month))->result_array();
        $credits_comercial_rents = $instance->basic->get_where('creditos', array('cred_concepto' => 'Alquiler Comercial', 'cred_mes_alq' => $month))->result_array();
        $credits_lot = $instance->basic->get_where('creditos', array('cred_concepto' => 'Loteo', 'cred_mes_alq' => $month))->result_array();

        $contracts = $instance->basic->get_where('contratos', array('con_enabled' => 1))->result_array();

        $instance->data['rent_contracts'] = 0;
        $instance->data['comercial_rents_contracts'] = 0;
        $instance->data['lot_contracts'] = 0;
        $instance->data['total_contracts'] = count($contracts);

        $instance->data['payed_rent_contracts'] = 0;
        $instance->data['payed_comercial_rents_contracts'] = 0;
        $instance->data['payed_lot_contracts'] = 0;
        $instance->data['total_payed'] = 0;

        foreach ($contracts as $contract) {
            switch ($contract['con_tipo']) {
                case 'Alquiler':
                    $instance->data['rent_contracts']++;

                    foreach ($credits_rents as $credit) {
                        if ($credit['con_id'] == $contract['con_id'] ||
                                $credit['cred_depositante'] == $contract['con_inq'] &&
                                $credit['cred_cc'] == $contract['con_prop']) {

                            $instance->data['payed_rent_contracts']++;
                            $instance->data['total_payed']++;
                        }
                    }
                    break;
                case 'Alquiler Comercial':
                    $instance->data['comercial_rents_contracts']++;

                    foreach ($credits_comercial_rents as $credit) {
                        if ($credit['con_id'] == $contract['con_id'] ||
                                $credit['cred_depositante'] == $contract['con_inq'] &&
                                $credit['cred_cc'] == $contract['con_prop']) {

                            $instance->data['payed_comercial_rents_contracts']++;
                            $instance->data['total_payed']++;
                        }
                    }
                    break;
                case 'Loteo':
                    $instance->data['lot_contracts']++;

                    foreach ($credits_lot as $credit) {
                        if ($credit['con_id'] == $contract['con_id'] ||
                                $credit['cred_depositante'] == $contract['con_inq'] &&
                                $credit['cred_cc'] == $contract['con_prop']) {

                            $instance->data['payed_lot_contracts']++;
                            $instance->data['total_payed']++;
                        }
                    }
                    break;
            }
        }

        return $instance->load->view('reports/renters_payment_percent_report', $instance->data, TRUE);
    }

}

?>

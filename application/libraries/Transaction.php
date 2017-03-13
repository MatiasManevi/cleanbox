<?php

/*
 * Project: Cleanbox
 * Document: Transaction
 * 
 * @author Manevi Matias Alejandro 
 * Informatic Engineer - Web Developer
 * 
 * Contact: manevimatias@gmail.com
 * 
 * All rights reserved ®
 */

class Transaction {

    public static function impactCredit($credit, &$contract, $transaction_id) {
        if ($credit['cred_concepto'] == 'Honorarios') {
            // forzamos que honorarios se impactara en cuenta de INMOBILIARIA
            // aca deberia haber una configuracion en los conceptos
            // que permite forzarlos a impactar en X cuenta
            $impacted_credits = self::impactHonorarios($credit, $transaction_id, $contract);
        } else {
            $impacted_credits = self::impactCommonCredit($credit, $transaction_id, $contract);
        }

        // Verificamos si con ese credito se deben devolver montos prestados por la inmo
        self::impactDevolutions($credit, $transaction_id);

        return $impacted_credits;
    }

    /**
     * Cuando se trata de un credito por honorarios, este debe armarse para que impacte en la cuenta de
     * la INMOBILIARIA, y computar, si es que tiene, el IVA
     * @param type $credit
     * @param type $transaction_id
     * @param type $contract
     * @return boolean 
     */
    public static function impactHonorarios($credit, $transaction_id, &$contract) {
        $instance = &get_instance();
        General::loadModels($instance);

        $impacted_credits = array();
        $cc_inmo = $instance->basic->get_where('cuentas_corrientes', array('cc_prop' => 'INMOBILIARIA'))->row_array();

        $monthly_cash = $instance->basic->get_where('mensuales', array('men_mes' => date('m'), 'men_ano' => date('Y')))->row_array();

        if (!empty($contract)) {

            $honorarios_credit = array(
                'cred_depositante' => $credit['cred_depositante'],
                'cred_cc' => 'INMOBILIARIA',
                'cred_forma' => $credit['cred_forma'],
                'con_id' => $credit['con_id'],
                'client_id' => $credit['client_id'],
                'cc_id' => $cc_inmo['cc_id'],
                'cred_banco' => $credit['cred_banco'],
                'cred_nro_cheque' => $credit['cred_nro_cheque'],
                'cred_mes_alq' => $credit['cred_mes_alq'],
                'receive_number' => $credit['receive_number'],
                'cred_concepto' => $credit['cred_concepto'],
                'cred_tipo_trans' => $credit['cred_tipo_trans'],
                'cred_monto' => $credit['cred_monto'],
                'cred_fecha' => $credit['cred_fecha'],
                'cred_interes' => $credit['cred_interes'],
                'cred_domicilio' => $credit['cred_domicilio'],
                'trans' => $transaction_id
            );

            if ($credit['cred_tipo_trans'] == 'Caja') {
                $monthly_cash['men_creditos'] += $honorarios_credit['cred_monto'];
            }

            $cc_inmo['cc_saldo'] += $credit['cred_monto'];

            $honorarios_credit['cred_id'] = $instance->basic->save('creditos', 'cred_id', $honorarios_credit);
            array_push($impacted_credits, $honorarios_credit);

            //Si el contrato contempla IVA/Honorarios
            if ($contract['con_iva'] == 'Si') {

                $iva_honorarios_credit = array(
                    'cred_depositante' => $credit['cred_depositante'],
                    'cred_cc' => 'INMOBILIARIA',
                    'con_id' => $credit['con_id'],
                    'client_id' => $credit['client_id'],
                    'cc_id' => $cc_inmo['cc_id'],
                    'cred_forma' => $credit['cred_forma'],
                    'cred_banco' => $credit['cred_banco'],
                    'cred_nro_cheque' => $credit['cred_nro_cheque'],
                    'receive_number' => $credit['receive_number'],
                    'cred_concepto' => 'IVA',
                    'cred_monto' => $credit['cred_iva_calculado'],
                    'cred_fecha' => $credit['cred_fecha'],
                    'cred_mes_alq' => $credit['cred_mes_alq'],
                    'cred_tipo_trans' => $credit['cred_tipo_trans'],
                    'cred_interes' => $credit['cred_interes'],
                    'cred_domicilio' => $credit['cred_domicilio'],
                    'trans' => $transaction_id
                );

                if ($credit['cred_tipo_trans'] == 'Caja') {
                    $monthly_cash['men_creditos'] += $credit['cred_iva_calculado'];
                }
                $cc_inmo['cc_saldo'] += $credit['cred_iva_calculado'];

                $iva_honorarios_credit['cred_id'] = $instance->basic->save('creditos', 'cred_id', $iva_honorarios_credit);
                array_push($impacted_credits, $iva_honorarios_credit);
            }

            $contract['honorary_cuotes_payed'] = $contract['honorary_cuotes_payed'] + 1;

            $instance->basic->save('mensuales', 'men_id', $monthly_cash);
            $instance->basic->save('cuentas_corrientes', 'cc_id', $cc_inmo);
        }

        return $impacted_credits;
    }

    public function impactCommonCredit($credit, $transaction_id, &$contract) {
        try {
            $instance = &get_instance();
            General::loadModels($instance);

            $impacted_credits = array();

            $cc_inmo = $instance->basic->get_where('cuentas_corrientes', array('cc_prop' => 'INMOBILIARIA'))->row_array();
            $cc_to_impact = $instance->basic->get_where('cuentas_corrientes', array('cc_id' => $credit['cc_id']))->row_array();
            $month_cash = $instance->basic->get_where('mensuales', array('men_mes' => date('m'), 'men_ano' => date('Y')))->row_array();

            $account_type = General::getAccountType($credit, 'Entrada', 'cred_concepto');

            // Impacta credito puro en la cuenta corriente del propietario
            $cc_to_impact[$account_type] += $credit['cred_monto'];
            // Impacta credito puro a la caja mensual si es tipo Fisico
            if ($credit['cred_tipo_trans'] == 'Caja') {
                $month_cash['men_creditos'] += $credit['cred_monto'];
            }

            if (!empty($contract)) {

                if ($credit['cred_concepto'] == 'Intereses') {
                    self::deleteInteresesDebt($credit);
                }

                $gestion_credit = self::createGestionMovements($instance, $credit, $contract, $cc_inmo, $cc_to_impact, $transaction_id, false);
                if (!empty($gestion_credit)) {
                    array_push($impacted_credits, $gestion_credit);
                    // Si se percibe gestion de cobro dicho monto se descuenta de la cuenta corriente del propietario
                    $cc_to_impact['cc_saldo'] -= $gestion_credit['cred_monto'];
                    // Si se percibe gestion de cobro dicho monto se suma a la cuenta corriente de la inmobiliaria
                    $cc_inmo['cc_saldo'] += $gestion_credit['cred_monto'];
                }

                $iva_credit = self::createIVAMovements($instance, $credit, $contract, $transaction_id);
                if (!empty($iva_credit)) {
                    array_push($impacted_credits, $iva_credit);
                    // Si se percibe IVA dicho monto se suma a la cuenta corriente del propietario
                    $cc_to_impact['cc_saldo'] += $iva_credit['cred_monto'];
                    // Si se percibe IVA dicho monto se suma a la caja mensual
                    if ($iva_credit['cred_tipo_trans'] == 'Caja') {
                        $month_cash['men_creditos'] += $iva_credit['cred_monto'];
                    }
                }

                $interes_credit = self::createInteresMovements($instance, $credit, $cc_to_impact, $transaction_id);
                if (!empty($interes_credit)) {
                    array_push($impacted_credits, $interes_credit);

                    // Si se percibe Intereses dicho monto se suma a la cuenta corriente del propietario
                    $cc_to_impact['cc_saldo'] += $interes_credit['cred_monto'];
                    // Si se percibe Intereses dicho monto se suma a la caja mensual
                    if ($interes_credit['cred_tipo_trans'] == 'Caja') {
                        $month_cash['men_creditos'] += $interes_credit['cred_monto'];
                    }

                    if (Contract::conceptPerceiveGestion($credit['cred_concepto'])) {
                        $gestion_interes_credit = self::createGestionMovements($instance, $interes_credit, $contract, $cc_inmo, $cc_to_impact, $transaction_id, true);

                        if (!empty($gestion_interes_credit)) {
                            array_push($impacted_credits, $gestion_interes_credit);
                            // Si se percibe gestion de cobro sobre intereses dicho monto se descuenta de la cuenta corriente del propietario
                            $cc_to_impact['cc_saldo'] -= $gestion_interes_credit['cred_monto'];
                            // Si se percibe gestion de cobro sobre intereses dicho monto se suma a la cuenta corriente de la inmobiliaria
                            $cc_inmo['cc_saldo'] += $gestion_interes_credit['cred_monto'];
                        }
                    }
                }

                if ($credit['cred_concepto'] == 'Deposito de garantia') {
                    $contract['warranty_cuotes_payed'] = $contract['warranty_cuotes_payed'] + 1;
                }
            }

            // Guarda las cuentas corrientes del propietario e inmobiliaria, y la caja mensual
            $instance->basic->save('cuentas_corrientes', 'cc_id', $cc_to_impact);
            $instance->basic->save('cuentas_corrientes', 'cc_id', $cc_inmo);
            $instance->basic->save('mensuales', 'men_id', $month_cash);

            unset($credit['cred_iva_calculado']);
            unset($credit['cred_interes_calculado']);
            unset($credit['paga_intereses']);

            // Guarda el credito puro
            $credit['trans'] = $transaction_id;
            $credit['cred_id'] = $instance->basic->save('creditos', 'cred_id', $credit);

            array_push($impacted_credits, $credit);

            return $impacted_credits;
        } catch (Exception $exc) {
            return false;
        }
    }

    public static function deleteInteresesDebt($credit) {
        $instance = &get_instance();
        General::loadModels($instance);

        $intereses_debt = $instance->basic->get_where('intereses_mora', array('cc_id' => $credit['cc_id'], 'client_id' => $credit['client_id']))->row_array();
        /* solo para davinia y rima */
        $intereses_debt2 = $instance->basic->get_where('intereses_mora', array('int_cc' => $credit['cred_cc'], 'int_depositante' => $credit['cred_depositante']))->row_array();
        foreach ($intereses_debt2 as $interese_debt2) {
            if (!in_array($interese_debt2, $intereses_debt))
                array_push($intereses_debt, $interese_debt2);
        }
        /* solo para davinia y rima */

        if (!empty($intereses_debt)) {
            $instance->basic->del('intereses_mora', 'int_id', $intereses_debt['int_id']);
        }
    }

    /**
     * Si una cuenta tiene prestamos de la Inmobilaria
     * y el credito entrante cubre el monto del prestamo
     * se cre ala devolucion del mismo desde la cuenta del propietario
     * a la cuenta de la inmobiliaria
     * @param array $credit
     * @param int $transaction_id 
     */
    public static function impactDevolutions($credit, $transaction_id) {
        $instance = &get_instance();
        General::loadModels($instance);

        $cc_inmo = $instance->basic->get_where('cuentas_corrientes', array('cc_prop' => 'INMOBILIARIA'))->row_array();

        /* solo para davinia y rima */
        if ($credit['cc_id']) {
            /* solo para davinia y rima */
            $cc_to_impact = $instance->basic->get_where('cuentas_corrientes', array('cc_id' => $credit['cc_id']))->row_array();
        } else {
            $cc_to_impact = $instance->basic->get_where('cuentas_corrientes', array('cc_prop' => $credit['cred_cc']))->row_array();
        }

        // vemos si hay prestamos a esta cuenta
        $loans = $instance->basic->get_where('creditos', array('cc_id' => $cc_to_impact['cc_id'], 'cred_concepto' => 'Prestamo'))->result_array();
        /* solo para davinia y rima */
        $loans2 = $instance->basic->get_where('creditos', array('cred_cc' => $cc_to_impact['cc_prop'], 'cred_concepto' => 'Prestamo'))->result_array();
        foreach ($loans2 as $loan2) {
            if (!in_array($loan2, $loans))
                array_push($loans, $loan2);
        }
        /* solo para davinia y rima */

        $credit_amount = $credit['cred_monto'];

        if (count($loans) > 0) {
            foreach ($loans as $row) {
                // Por cada prestamo percibido por este propietario,
                // veo si el credito ingresado lo puede devolver
                $loan = $row['cred_monto'];
                $difference = $credit_amount - $loan;

                if ($difference >= 0) {
                    $debito_prestamo = $instance->basic->get_where('debitos', array('deb_concepto' => 'Prestamo', 'deb_cc' => 'Inmobiliaria', 'trans' => $row['trans']))->row_array();

                    $row['cred_concepto'] = 'Prestamo Devuelto';
                    $debito_prestamo['deb_concepto'] = 'Prestamo Devuelto';

                    $credit_amount -= $loan;

                    $debito_dev_prestamo = array(
                        'deb_cc' => $cc_to_impact['cc_prop'],
                        'cc_id' => $cc_to_impact['cc_id'],
                        'deb_concepto' => 'Devolucion Prestamo',
                        'deb_monto' => $loan,
                        'deb_tipo_trans' => 'Caja',
                        'deb_mes' => General::getStringMonth(date('m')) . ' ' . Date('Y'),
                        'deb_domicilio' => '',
                        'deb_fecha' => Date('d-m-Y'),
                        'trans' => $transaction_id
                    );

                    $credit_dev_prestamo = array(
                        'cred_cc' => $cc_inmo['cc_prop'], // Inmobiliaria
                        'client_id' => $cc_to_impact['client_id'],
                        'cc_id' => $cc_inmo['cc_id'],
                        'cred_depositante' => $cc_to_impact['cc_prop'],
                        'cred_concepto' => 'Devolucion Prestamo',
                        'cred_monto' => $loan,
                        'cred_tipo_trans' => 'Caja',
                        'cred_domicilio' => '',
                        'cred_mes_alq' => General::getStringMonth(date('m')) . ' ' . Date('Y'),
                        'cred_fecha' => Date('d-m-Y'),
                        'trans' => $transaction_id
                    );

                    $instance->basic->save('debitos', 'deb_id', $debito_prestamo);
                    $instance->basic->save('creditos', 'cred_id', $credit_dev_prestamo);
                    $instance->basic->save('debitos', 'deb_id', $debito_dev_prestamo);
                    $instance->basic->save('creditos', 'cred_id', $row);

                    $cc_inmo['cc_saldo'] += $loan;
                    $cc_to_impact['cc_saldo'] -= $loan;
                }
            }

            $instance->basic->save('cuentas_corrientes', 'cc_id', $cc_inmo);
            $instance->basic->save('cuentas_corrientes', 'cc_id', $cc_to_impact);
        }
    }

    public static function createGestionMovements($instance, $credit, $contract, &$cc_inmo, &$cc_to_impact, $transaction_id, $is_intereses) {
        $credit_gestion = array();
        $debit_gestion = array();

        if (Contract::conceptPerceiveGestion($credit['cred_concepto'])) {

            if ($is_intereses) {
                $concept = 'Gestion de Cobro Sobre Intereses';
            } else {
                $concept = 'Gestion de Cobro';
            }

            $gestion_amount = round($credit['cred_monto'] * $contract['con_porc'], 2);

            $debit_gestion = array(
                'deb_cc' => $cc_to_impact['cc_prop'],
                'cc_id' => $cc_to_impact['cc_id'],
                'deb_concepto' => $concept,
                'deb_monto' => $gestion_amount,
                'deb_tipo_trans' => $credit['cred_tipo_trans'],
                'deb_fecha' => $credit['cred_fecha'],
                'deb_domicilio' => $credit['cred_domicilio'],
                'deb_mes' => $credit['cred_mes_alq'],
                'trans' => $transaction_id
            );

            $credit_gestion = array(
                'cred_depositante' => $credit['cred_cc'],
                'cred_cc' => $cc_inmo['cc_prop'], // Inmobiliaria
                'con_id' => $credit['con_id'],
                'client_id' => $cc_to_impact['client_id'],
                'cc_id' => $cc_inmo['cc_id'],
                'cred_forma' => $credit['cred_forma'],
                'cred_banco' => $credit['cred_banco'],
                'receive_number' => $credit['receive_number'],
                'cred_nro_cheque' => $credit['cred_nro_cheque'],
                'cred_concepto' => $concept,
                'cred_mes_alq' => $credit['cred_mes_alq'],
                'cred_monto' => $gestion_amount,
                'cred_fecha' => $credit['cred_fecha'],
                'cred_tipo_trans' => $credit['cred_tipo_trans'],
                'cred_domicilio' => $credit['cred_domicilio'],
                'trans' => $transaction_id
            );

            $instance->basic->save('debitos', 'deb_id', $debit_gestion);
            $credit_gestion['cred_id'] = $instance->basic->save('creditos', 'cred_id', $credit_gestion);
        }

        return $credit_gestion;
    }

    public static function createIVAMovements($instance, $credit, $contract, $transaction_id) {
        $credit_iva = array();
        if (is_numeric($credit['cred_iva_calculado']) && $credit['cred_iva_calculado'] > 0) {

            if (strpos($credit['cred_concepto'], 'Alquiler') !== false ||
                    strpos($credit['cred_concepto'], 'Loteo') !== false &&
                    $contract['con_iva_alq'] == 'Si' ||
                    strpos($credit['cred_concepto'], 'Alquiler') == false ||
                    strpos($credit['cred_concepto'], 'Loteo') == false &&
                    Contract::conceptPerceiveIVA($credit['cred_concepto'])
            ) {

                $credit_iva = array(
                    'cred_depositante' => $credit['cred_depositante'],
                    'cred_cc' => $credit['cred_cc'],
                    'con_id' => $credit['con_id'],
                    'client_id' => $credit['client_id'],
                    'cc_id' => $credit['cc_id'],
                    'cred_forma' => $credit['cred_forma'],
                    'cred_banco' => $credit['cred_banco'],
                    'cred_mes_alq' => $credit['cred_mes_alq'],
                    'receive_number' => $credit['receive_number'],
                    'cred_nro_cheque' => $credit['cred_nro_cheque'],
                    'cred_concepto' => 'IVA',
                    'cred_monto' => $credit['cred_iva_calculado'],
                    'cred_fecha' => $credit['cred_fecha'],
                    'cred_domicilio' => $credit['cred_domicilio'],
                    'cred_tipo_trans' => $credit['cred_tipo_trans'],
                    'trans' => $transaction_id
                );

                $credit_iva['cred_id'] = $instance->basic->save('creditos', 'cred_id', $credit_iva);
            }
        }

        return $credit_iva;
    }

    public static function createInteresMovements($instance, $credit, &$cc_to_impact, $transaction_id) {
        $credit_intereses = array();

        if (is_numeric($credit['cred_interes']) && json_decode($credit['cred_interes_calculado']) > 0 && Contract::conceptPerceiveInteres($credit['cred_concepto'])) {

            if (json_decode($credit['paga_intereses'])) {
                $credit_intereses = array(
                    'cred_depositante' => $credit['cred_depositante'],
                    'cred_cc' => $cc_to_impact['cc_prop'],
                    'con_id' => $credit['con_id'],
                    'client_id' => $credit['client_id'],
                    'cc_id' => $credit['cc_id'],
                    'cred_concepto' => 'Intereses',
                    'cred_mes_alq' => $credit['cred_mes_alq'],
                    'cred_monto' => $credit['cred_interes_calculado'],
                    'cred_fecha' => $credit['cred_fecha'],
                    'cred_forma' => $credit['cred_forma'],
                    'cred_interes' => '',
                    'cred_banco' => $credit['cred_banco'],
                    'receive_number' => $credit['receive_number'],
                    'cred_nro_cheque' => $credit['cred_nro_cheque'],
                    'cred_tipo_trans' => $credit['cred_tipo_trans'],
                    'cred_domicilio' => $credit['cred_domicilio'],
                    'trans' => $transaction_id
                );
                $credit_intereses['cred_id'] = $instance->basic->save('creditos', 'cred_id', $credit_intereses);
            } else {
                //Si tiene intereses pero no pagara hoy, se le genera un registro de constancia
                $interes_debt = array(
                    'int_depositante' => $credit['cred_depositante'],
                    'int_cc' => $cc_to_impact['cc_prop'],
                    'cc_id' => $credit['cc_id'],
                    'client_id' => $credit['client_id'],
                    'con_id' => $credit['con_id'],
                    'int_fecha_pago' => $credit['cred_fecha'],
                    'int_amount' => $credit['cred_interes_calculado']
                );
                $instance->basic->save('intereses_mora', 'int_id', $interes_debt);
            }
        }

        return $credit_intereses;
    }

    /**
     * Si el debito no es por rendicion entra a crear un prestamo
     * el cual creara una migracion de dinero desde la INMOBILIARIA
     * al Propietario mediante un debito y un credito por la plata
     * que necesita el propietario. Luego esto es devuelto cuando
     * al propietario le ingresa un credito capaz de cubrir ese prestamo
     * @param array $debit
     * @param string $account_type
     * @param array $cc_to_impact 
     */
    public static function createLoan($debit, $account_type, &$cc_to_impact) {
        $instance = &get_instance();
        General::loadModels($instance);

        $cc_inmo = $instance->basic->get_where('cuentas_corrientes', array('cc_prop' => 'INMOBILIARIA'))->row_array();

        $difference = round($cc_to_impact['cc_saldo'] + $cc_to_impact['cc_varios'] - $debit['deb_monto'], 2);

        if ($difference < 0) {

            if ($cc_to_impact['cc_prop'] != 'INMOBILIARIA') {
                $loan = round($difference * (-1), 2);
                $debit_prestamo = array(
                    'deb_cc' => $cc_inmo['cc_prop'], // inmobiliaria
                    'cc_id' => $cc_inmo['cc_id'],
                    'deb_concepto' => 'Prestamo',
                    'deb_monto' => $loan,
                    'deb_domicilio' => $debit['deb_domicilio'],
                    'deb_mes' => $debit['deb_mes'],
                    'deb_tipo_trans' => 'Caja',
                    'deb_fecha' => $debit['deb_fecha'],
                    'trans' => $debit['trans']
                );

                $instance->basic->save('debitos', 'deb_id', $debit_prestamo);

                $credito_prestamo = array(
                    'cred_cc' => $debit['deb_cc'],
                    'cc_id' => $debit['cc_id'],
                    'client_id' => $cc_inmo['cc_id'], // id de la inmobiliaria ya que ella deposita el prestamo
                    'cred_depositante' => 'INMOBILIARIA',
                    'cred_concepto' => 'Prestamo',
                    'cred_monto' => $loan,
                    'cred_domicilio' => $debit['deb_domicilio'],
                    'cred_mes_alq' => $debit['deb_mes'],
                    'cred_fecha' => $debit['deb_fecha'],
                    'trans' => $debit['trans'],
                    'cred_tipo_trans' => 'Caja',
                );

                $instance->basic->save('creditos', 'cred_id', $credito_prestamo);

                $cc_inmo['cc_saldo'] -= $loan;
                $cc_to_impact[$account_type] += $loan;

                $instance->basic->save('cuentas_corrientes', 'cc_id', $cc_inmo);
            }
        }
    }

    public static function generateTaxDebit($bank_transaction_amount, $bank_transaction_month, $transaction_id) {
        $instance = &get_instance();
        General::loadModels($instance);

        $cc_inmo = $instance->basic->get_where('cuentas_corrientes', array('cc_prop' => 'INMOBILIARIA'))->row_array();

        $iibb_tax_percentaje = User::getUserIIBBTAX();

        $debit_tax = array(
            'deb_cc' => $cc_inmo['cc_prop'], // INMOBILIARIA
            'cc_id' => $cc_inmo['cc_id'],
            'deb_concepto' => 'Ingresos Brutos/Credito Bancario',
            'deb_monto' => $bank_transaction_amount * $iibb_tax_percentaje,
            'deb_mes' => $bank_transaction_month,
            'deb_tipo_trans' => 'Bancaria',
            'deb_fecha' => Date('d-m-Y'),
            'trans' => $transaction_id
        );

        self::createDebit($debit_tax, 'cc_saldo', $cc_inmo);

        $instance->basic->save('cuentas_corrientes', 'cc_id', $cc_inmo);
    }

    /**
     * Busca y elimina el debito de IIBB si existe, hace lo siguiente:
     * Busca todos los creditos bancarios de la transaccion de este credito
     * Obtiene la suma de los montos de todos
     * Crea un nuevo debito de IIBB con la nueva sumatoria de creditos bancarios de la transaccion
     *
     * @param array $credit 
     */
    public static function recalculateTaxDebit($credit) {
        $instance = &get_instance();
        General::loadModels($instance);
        $bank_transaction_amount = 0;

        $tax_debit = $instance->basic->get_where('debitos', array('deb_concepto' => 'Ingresos Brutos/Credito Bancario', 'deb_tipo_trans' => 'Bancaria', 'deb_cc' => 'INMOBILIARIA', 'trans' => $credit['trans']))->row_array();
        if (!empty($tax_debit)) {
            self::deleteDebit($tax_debit);

            $transaction_bank_credits = $instance->basic->get_where('creditos', array('cred_tipo_trans' => 'Bancaria', 'trans' => $credit['trans']))->result_array();
            if (!empty($transaction_bank_credits)) {

                foreach ($transaction_bank_credits as $transaction_bank_credit) {
                    if (self::isImpactableCredit($transaction_bank_credit)) {
                        $bank_transaction_amount += $transaction_bank_credit['cred_monto'];
                    }
                }

                if ($bank_transaction_amount > 0) {
                    self::generateTaxDebit($bank_transaction_amount, $credit['cred_mes_alq'], $credit['trans']);
                }
            }
        }
    }

    public static function conceptExistsInAndOut($concept) {
        $instance = &get_instance();
        General::loadModels($instance);

        $concept_in = $instance->basic->get_where('conceptos', array('conc_desc' => $concept, 'conc_tipo' => 'Entrada'))->row_array();
        $concept_out = $instance->basic->get_where('conceptos', array('conc_desc' => $concept, 'conc_tipo' => 'Salida'))->row_array();

        if (!empty($concept_in) && !empty($concept_out)) {
            return true;
        } else {
            return false;
        }
    }

    public static function getLastTransactionId() {
        $instance = &get_instance();
        General::loadModels($instance);

        $transactions = $instance->basic->get_all('trans')->row_array();
        return $transactions['trans'];
    }

    public static function incrementTransactionId() {
        $instance = &get_instance();
        General::loadModels($instance);

        $transactions = $instance->basic->get_all('trans')->row_array();
        $transactions['trans']++;
        $instance->basic->save('trans', 'trans_id', $transactions);
    }

    public static function paymentSamePrincipal($principal_credit, $credit) {
        if ($principal_credit['cred_mes_alq'] == $credit['cred_mes_alq'] &&
                $principal_credit['cred_concepto'] == $credit['cred_concepto']) {
            return true;
        } else {
            return false;
        }
    }

    public static function parseForReceives($receive_elements, $contract) {
        $receive_reports = array();
        $receives = array();
        $principals = array();

        $credits = $receive_elements['credits'];
        $services_control = $receive_elements['services_control'];

        // Identificamos creditos principales unicos
        foreach ($credits as $key => $credit) {
            $is_in_principals = false;

            foreach ($principals as $principal) {
                if ($principal['cred_mes_alq'] == $credit['cred_mes_alq'] &&
                        $principal['cred_concepto'] == $credit['cred_concepto']) {
                    $is_in_principals = true;
                    break;
                }
            }

            if (Report::mustPrintReport($credit['cred_concepto']) && !$is_in_principals) {
                $credit['other_principal'] = array();
                array_push($principals, $credit);
                unset($credits[$key]);
            }
        }

        // Adosamos a creditos principales unicos sus creditos principales consecutivos
        $principals_with_consecutives = array();
        foreach ($principals as $principal) {
            if (Report::mustPrintReport($principal['cred_concepto'])) {
                foreach ($credits as $key => $credit) {
                    if (self::paymentSamePrincipal($principal, $credit)) {
                        array_push($principal['other_principal'], $credit);
                        unset($credits[$key]);
                    }
                }
                array_push($principals_with_consecutives, $principal);
            }
        }

        foreach ($principals_with_consecutives as $credit) {

            $receive = array(
                'principal_credit' => $credit,
                'total_principal' => 0,
                'total_secondarys' => 0,
                'total' => 0,
                'debt' => 0,
                'total_letters' => '',
                'secondary_credits' => array(),
                'services_control' => array(),
                'services_no_control' => array()
            );

            // se adosan creditos secundarios
            foreach ($credits as $key => $secondary_credit) {
                if ($credit['cred_mes_alq'] == $secondary_credit['cred_mes_alq'] && $credit['cred_concepto'] != $secondary_credit['cred_concepto'] && strpos($secondary_credit['cred_concepto'], 'Prestamo') === FALSE) {
                    if (!Report::mustPrintReport($secondary_credit['cred_concepto'])) {
                        if (!self::isAlreadyAdded($receive_reports, $secondary_credit, 'secondary_credits')) {
                            array_push($receive['secondary_credits'], $secondary_credit);
                            unset($credits[$key]);
                        }
                    }
                }
            }

            // se adosan controles de servicios
            foreach ($services_control as $key => $service_control) {
                if (isset($service_control['status'])) {
                    $status = true;
                    if (!$service_control['status']) {
                        $status = false;
                    }
                } else {
                    $status = true;
                }
                if ($status && $credit['cred_mes_alq'] == $service_control['month_checked'] && $credit['cred_concepto']) {
                    array_push($receive['services_control'], $service_control);
                    unset($services_control[$key]);
                }
            }

            // se adosan no-controles de servicios
            foreach ($services_control as $key => $service_control) {
                if (isset($service_control['status'])) {
                    $status = true;
                    if (!$service_control['status']) {
                        $status = false;
                    }
                } else {
                    $status = true;
                }

                if (!$status && $credit['cred_mes_alq'] == $service_control['month_checked'] && $credit['cred_concepto']) {
                    array_push($receive['services_no_control'], $service_control);
                    unset($services_control[$key]);
                }
            }

            array_push($receive_reports, $receive);
        }

        // Cuando un servicio u otro concepto secundario no coincide en el mes de pago o control
        // con algun alquiler se verifica si fue adosado a alguno de ellos y sino
        // se agrega al primero por defecto
        foreach ($credits as $credit) {
            if (!Report::mustPrintReport($credit['cred_concepto']) && strpos($credit['cred_concepto'], 'Prestamo') === FALSE) {
                if (!self::isAlreadyAdded($receive_reports, $credit, 'secondary_credits')) {

                    array_push($receive_reports[0]['secondary_credits'], $credit);
                }
            }
        }
        foreach ($services_control as $service_control) {
            if (isset($service_control['status'])) {
                $status = true;
                if (!$service_control['status']) {
                    $status = false;
                }
            } else {
                $status = true;
            }
            if (!self::isAlreadyAdded($receive_reports, $service_control, 'services_control') && $status) {
                array_push($receive_reports[0]['services_control'], $service_control);
            }
            if (!self::isAlreadyAdded($receive_reports, $service_control, 'services_no_control') && !$status) {
                array_push($receive_reports[0]['services_no_control'], $service_control);
            }
        }

        // Calcula totales y deuda
        foreach ($receive_reports as $receive_report) {
            // neto a cobrar alquileres | honorarios } reserva
            $receive_report['total_principal'] = self::getTotalPrincipal($receive_report['principal_credit']);

            // total servicios y gastos
            $receive_report['total_secondarys'] = self::getTotalSecondarys($receive_report['secondary_credits']);

            $receive_report['total'] = $receive_report['total_principal'] + $receive_report['total_secondarys'];
            $receive_report['total_letters'] = self::getTotalInLetters($receive_report['total']);

            // se debe
            if ($receive_report['principal_credit']['cred_concepto'] != 'Loteo') {
                $receive_report['debt'] = self::getReceiveDebt($receive_report['principal_credit'], $contract);
            }

            array_push($receives, $receive_report);
        }

        return $receives;
    }

    public static function calculateCreditAmount($credit) {
        $credit_amount = 0;
        $credit_amount += $credit['cred_monto'];
        $credit_amount += is_numeric($credit['cred_iva_calculado']) ? $credit['cred_iva_calculado'] : 0;
        if (self::payIntereses($credit)) {
            $credit_amount += is_numeric($credit['cred_interes_calculado']) ? $credit['cred_interes_calculado'] : 0;
        }

        return $credit_amount;
    }

    public static function getTotalPrincipal($receive_report) {
        $total = self::calculateCreditAmount($receive_report);

        if (!empty($receive_report['other_principal'])) {
            foreach ($receive_report['other_principal'] as $other_principal) {
                $total += self::calculateCreditAmount($other_principal);
            }
        }

        return $total;
    }

    public static function payIntereses($credit) {
        $instance = &get_instance();
        General::loadModels($instance);

        $intereses_debt = $instance->basic->get_where('intereses_mora', array('cc_id' => $credit['cc_id'], 'client_id' => $credit['client_id'], 'int_fecha_pago' => $credit['cred_fecha']))->row_array();
        /* solo para davinia y rima */
        $intereses_debt2 = $instance->basic->get_where('intereses_mora', array('int_cc' => $credit['cred_cc'], 'int_depositante' => $credit['cred_depositante'], 'int_fecha_pago' => $credit['cred_fecha']))->row_array();
        foreach ($intereses_debt2 as $interese_debt2) {
            if (!in_array($interese_debt2, $intereses_debt))
                array_push($intereses_debt, $interese_debt2);
        }
        /* solo para davinia y rima */

        if (empty($intereses_debt)) {
            return true;
        } else {
            return false;
        }
    }

    public static function getTotalSecondarys($secondary_credits) {
        $total_secondarys = 0;

        foreach ($secondary_credits as $secondary_credit) {
            $total_secondarys += $secondary_credit['cred_monto'];
            $total_secondarys += $secondary_credit['cred_interes_calculado'];
        }

        return $total_secondarys;
    }

    public static function getTotalInLetters($xcifra) {
        $xarray = array(0 => "Cero",
            1 => "UN", "DOS", "TRES", "CUATRO", "CINCO", "SEIS", "SIETE", "OCHO", "NUEVE",
            "DIEZ", "ONCE", "DOCE", "TRECE", "CATORCE", "QUINCE", "DIECISEIS", "DIECISIETE", "DIECIOCHO", "DIECINUEVE",
            "VEINTI", 30 => "TREINTA", 40 => "CUARENTA", 50 => "CINCUENTA", 60 => "SESENTA", 70 => "SETENTA", 80 => "OCHENTA", 90 => "NOVENTA",
            100 => "CIENTO", 200 => "DOSCIENTOS", 300 => "TRESCIENTOS", 400 => "CUATROCIENTOS", 500 => "QUINIENTOS", 600 => "SEISCIENTOS", 700 => "SETECIENTOS", 800 => "OCHOCIENTOS", 900 => "NOVECIENTOS"
        );

        $xcifra = trim($xcifra);
        $xpos_punto = strpos($xcifra, ".");
        $xaux_int = $xcifra;
        $xdecimales = "00";
        if (!($xpos_punto === false)) {
            if ($xpos_punto == 0) {
                $xcifra = "0" . $xcifra;
                $xpos_punto = strpos($xcifra, ".");
            }
            $xaux_int = substr($xcifra, 0, $xpos_punto); // obtengo el entero de la cifra a covertir
            $xdecimales = substr($xcifra . "00", $xpos_punto + 1, 2); // obtengo los valores decimales
        }

        $XAUX = str_pad($xaux_int, 18, " ", STR_PAD_LEFT); // ajusto la longitud de la cifra, para que sea divisible por centenas de miles (grupos de 6)
        $xcadena = "";
        for ($xz = 0; $xz < 3; $xz++) {
            $xaux = substr($XAUX, $xz * 6, 6);
            $xi = 0;
            $xlimite = 6; // inicializo el contador de centenas xi y establezco el límite a 6 dígitos en la parte entera
            $xexit = true; // bandera para controlar el ciclo del While
            while ($xexit) {
                if ($xi == $xlimite) { // si ya llegó al límite máximo de enteros
                    break; // termina el ciclo
                }

                $x3digitos = ($xlimite - $xi) * -1; // comienzo con los tres primeros digitos de la cifra, comenzando por la izquierda
                $xaux = substr($xaux, $x3digitos, abs($x3digitos)); // obtengo la centena (los tres dígitos)
                for ($xy = 1; $xy < 4; $xy++) { // ciclo para revisar centenas, decenas y unidades, en ese orden
                    switch ($xy) {
                        case 1: // checa las centenas
                            if (substr($xaux, 0, 3) < 100) { // si el grupo de tres dígitos es menor a una centena ( < 99) no hace nada y pasa a revisar las decenas
                            } else {
                                $key = (int) substr($xaux, 0, 3);
                                if (TRUE === array_key_exists($key, $xarray)) {  // busco si la centena es número redondo (100, 200, 300, 400, etc..)
                                    $xseek = $xarray[$key];
                                    $xsub = self::subfijo($xaux); // devuelve el subfijo correspondiente (Millón, Millones, Mil o nada)
                                    if (substr($xaux, 0, 3) == 100)
                                        $xcadena = " " . $xcadena . " CIEN " . $xsub;
                                    else
                                        $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                                    $xy = 3; // la centena fue redonda, entonces termino el ciclo del for y ya no reviso decenas ni unidades
                                }
                                else { // entra aquí si la centena no fue numero redondo (101, 253, 120, 980, etc.)
                                    $key = (int) substr($xaux, 0, 1) * 100;
                                    $xseek = $xarray[$key]; // toma el primer caracter de la centena y lo multiplica por cien y lo busca en el arreglo (para que busque 100,200,300, etc)
                                    $xcadena = " " . $xcadena . " " . $xseek;
                                } // ENDIF ($xseek)
                            } // ENDIF (substr($xaux, 0, 3) < 100)
                            break;
                        case 2: // checa las decenas (con la misma lógica que las centenas)
                            if (substr($xaux, 1, 2) < 10) {
                                
                            } else {
                                $key = (int) substr($xaux, 1, 2);
                                if (TRUE === array_key_exists($key, $xarray)) {
                                    $xseek = $xarray[$key];
                                    $xsub = self::subfijo($xaux);
                                    if (substr($xaux, 1, 2) == 20)
                                        $xcadena = " " . $xcadena . " VEINTE " . $xsub;
                                    else
                                        $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                                    $xy = 3;
                                }
                                else {
                                    $key = (int) substr($xaux, 1, 1) * 10;
                                    $xseek = $xarray[$key];
                                    if (20 == substr($xaux, 1, 1) * 10)
                                        $xcadena = " " . $xcadena . " " . $xseek;
                                    else
                                        $xcadena = " " . $xcadena . " " . $xseek . " Y ";
                                } // ENDIF ($xseek)
                            } // ENDIF (substr($xaux, 1, 2) < 10)
                            break;
                        case 3: // checa las unidades
                            if (substr($xaux, 2, 1) < 1) { // si la unidad es cero, ya no hace nada
                            } else {
                                $key = (int) substr($xaux, 2, 1);
                                $xseek = $xarray[$key]; // obtengo directamente el valor de la unidad (del uno al nueve)
                                $xsub = self::subfijo($xaux);
                                $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                            } // ENDIF (substr($xaux, 2, 1) < 1)
                            break;
                    } // END SWITCH
                } // END FOR
                $xi = $xi + 3;
            } // ENDDO

            if (substr(trim($xcadena), -5, 5) == "ILLON") // si la cadena obtenida termina en MILLON o BILLON, entonces le agrega al final la conjuncion DE
                $xcadena.= " DE";

            if (substr(trim($xcadena), -7, 7) == "ILLONES") // si la cadena obtenida en MILLONES o BILLONES, entoncea le agrega al final la conjuncion DE
                $xcadena.= " DE";

            // ----------- esta línea la puedes cambiar de acuerdo a tus necesidades o a tu país -------
            if (trim($xaux) != "") {
                switch ($xz) {
                    case 0:
                        if (trim(substr($XAUX, $xz * 6, 6)) == "1")
                            $xcadena.= "UN BILLON ";
                        else
                            $xcadena.= " BILLONES ";
                        break;
                    case 1:
                        if (trim(substr($XAUX, $xz * 6, 6)) == "1")
                            $xcadena.= "UN MILLON ";
                        else
                            $xcadena.= " MILLONES ";
                        break;
                    case 2:
                        if ($xcifra < 1) {
                            $xcadena = "CERO PESOS CON $xdecimales/100 CENTAVOS";
                        }
                        if ($xcifra >= 1 && $xcifra < 2) {
                            $xcadena = "UN PESO CON $xdecimales/100 CENTAVOS ";
                        }
                        if ($xcifra >= 2) {
                            $xcadena.= " PESOS CON $xdecimales/100 CENTAVOS "; //
                        }
                        break;
                } // endswitch ($xz)
            } // ENDIF (trim($xaux) != "")
            // ------------------      en este caso, para México se usa esta leyenda     ----------------
            $xcadena = str_replace("VEINTI ", "VEINTI", $xcadena); // quito el espacio para el VEINTI, para que quede: VEINTICUATRO, VEINTIUN, VEINTIDOS, etc
            $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
            $xcadena = str_replace("UN UN", "UN", $xcadena); // quito la duplicidad
            $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
            $xcadena = str_replace("BILLON DE MILLONES", "BILLON DE", $xcadena); // corrigo la leyenda
            $xcadena = str_replace("BILLONES DE MILLONES", "BILLONES DE", $xcadena); // corrigo la leyenda
            $xcadena = str_replace("DE UN", "UN", $xcadena); // corrigo la leyenda
        } // ENDFOR ($xz)
        return trim($xcadena);
    }

    public static function subfijo($xx) { // esta función regresa un subfijo para la cifra
        $xx = trim($xx);
        $xstrlen = strlen($xx);
        if ($xstrlen == 1 || $xstrlen == 2 || $xstrlen == 3)
            $xsub = "";
        if ($xstrlen == 4 || $xstrlen == 5 || $xstrlen == 6)
            $xsub = "MIL";
        return $xsub;
    }

    public static function getReceiveDebt($receive_report, $contract) {
        $debt = 0;
        $min_debt = 0;

        if (Report::mustPrintReport($receive_report['cred_concepto']) && $receive_report['cred_concepto'] != 'Reserva') {

            if ($receive_report['cred_tipo_pago'] == 'A Cuenta') {
                $debt += self::getPrincipalPaymentDebt($receive_report, $contract);

                if (!empty($receive_report['other_principal'])) {
                    foreach ($receive_report['other_principal'] as $other_principal) {
                        $min_debt += $other_principal['cred_monto'];
                    }
                }
            } else {
                $min_debt += self::getPrincipalPaymentDebt($receive_report, $contract);
                if (!empty($receive_report['other_principal'])) {
                    foreach ($receive_report['other_principal'] as $other_principal) {
                        $debt += $other_principal['cred_monto'];
                    }
                }
            }
        }

        $debt -= $min_debt;

        return $debt;
    }

    public static function getPrincipalPaymentDebt($credit, $contract) {
        $debt = 0;

        $instance = &get_instance();
        General::loadModels($instance);

        $period_amount = false;

        $credit_month = trim(preg_replace("/[^^A-Za-z (),.]/", "", $credit['cred_mes_alq']));
        $credit_month_number = General::getMonthNumber($credit_month);
        $credit_year = trim(preg_replace("/[^0-9 (),.]/", "", $credit['cred_mes_alq']));

        $period_search = '05-' . $credit_month_number . '-' . $credit_year;

        $contract_periods = $instance->basic->get_where('periodos', array('per_contrato' => $contract['con_id']))->result_array();

        foreach ($contract_periods as $period) {
            if (General::isBetweenDates($period_search, $period['per_inicio'], $period['per_fin'])) {
                $period_amount = $period['per_monto'];
                break;
            }
        }

        if ($period_amount) {
            $debt = $period_amount - $credit['cred_monto'];
        }

        // pago intereses?
        if (!self::payIntereses($credit)) {
            $debt += $credit['cred_interes_calculado'];
        }

        return $debt;
    }

    /**
     * Si el registro ya esta en el array de manera que no lo agreguemos dos veces
     * @param array $receive_reports
     * @param array $element
     * @param string $searched_element
     * @return boolean 
     */
    public static function isAlreadyAdded($receive_reports, $element, $searched_element) {
        $added = false;

        foreach ($receive_reports as $receive_report) {
            if (in_array($element, $receive_report[$searched_element])) {
                $added = true;
                break;
            }
        }

        return $added;
    }

    /**
     * Limpia los creditos autogenerados de iva, gestion e intereses
     * ya que sus valores seran contenidos dentro de los creditos
     * principal y secundarios correspondientes en el recibo. Los cuales
     * se calculan antes de parsear los creditos para el recibo
     * en calculateIVA y calculateInteres
     * @param type $credits 
     * @return array 
     */
    public static function cleanCalculatedCredits($credits) {
        $cleaned = array();

        foreach ($credits as $credit) {
            if ($credit['cred_concepto'] != 'IVA') {

                if ($credit['cred_concepto'] != 'Gestion de Cobro') {

                    if ($credit['cred_concepto'] != 'Gestion de Cobro Sobre Intereses') {

                        if ($credit['cred_concepto'] != 'Intereses') {

                            array_push($cleaned, $credit);
                        }
                    }
                }
            }
        }

        return $cleaned;
    }

    public static function calculateReceiveIVA($credits, $contract) {
        $iva_calculated = array();

        foreach ($credits as $credit) {
            $calculated_credit = $credit;
            $calculated_credit['cred_iva_calculado'] = 0;
            if (strpos($credit['cred_concepto'], 'Alquiler') !== false) {
                if ($contract['con_iva_alq'] == 'Si') {
                    $calculated_credit['cred_iva_calculado'] = round($credit['cred_monto'] * User::getUserIVATAX(), 2);
                }
            }
            if ($credit['cred_concepto'] == 'Honorarios') {
                if ($contract['con_iva'] == 'Si') {
                    $calculated_credit['cred_iva_calculado'] = round($credit['cred_monto'] * User::getUserIVATAX(), 2);
                }
            }
            array_push($iva_calculated, $calculated_credit);
        }

        return $iva_calculated;
    }

    public static function calculateReceiveInteres($credits, $contract) {
        $interes_calculated = array();

        foreach ($credits as $credit) {
            $calculated_credit = $credit;
            $calculated_credit['cred_interes_calculado'] = '';
            if ($credit['cred_interes'] > 0) {
                $calculated_credit['cred_interes_calculado'] = round($credit['cred_monto'] * $credit['cred_interes'] * $contract['con_punitorio'], 2);
            }
            array_push($interes_calculated, $calculated_credit);
        }

        return $interes_calculated;
    }

    /**
     * Elimina el credito, disminuye en su monto a la cuenta correspondiente 
     * luego impacta su eliminacion en las transferencias y en la caja
     * @param type $credits
     * @param type $monthly_cash
     * @param type $concepts 
     */
    public static function removeCreditAndDecreaseAccount($credits) {
        if (count($credits)) {

            foreach ($credits as $credit) {

                $cash_balance_today = Cash::getBalance('Caja');

                // Eliminando efecto del credito a la cuenta
                self::deleteCredit($credit);

                // Plazmando impacto de la eliminacion
                self::impactCreditDelete($credit, $cash_balance_today);
            }
        }
    }

    public static function deleteCredit($credit) {
        $instance = &get_instance();
        General::loadModels($instance);

        /* solo para davinia y rima */
        if ($credit['cc_id']) {
            /* solo para davinia y rima */
            $account = $instance->basic->get_where('cuentas_corrientes', array('cc_id' => $credit['cc_id']))->row_array();
        } else {
            $account = $instance->basic->get_where('cuentas_corrientes', array('cc_prop' => $credit['cred_cc']))->row_array();
        }

        $type = General::getAccountType($credit, 'Entrada', 'cred_concepto');
        $account[$type] -= $credit['cred_monto'];

        $instance->basic->del('creditos', 'cred_id', $credit['cred_id']);
        $instance->basic->save('cuentas_corrientes', 'cc_id', $account);
    }

    /**
     * Plazma los cambios de la eliminacion del credito, des-usando un contrato,
     * controlando si se deben eliminar transferencias y/o reflejar arrastres en la caja mensual/diaria.
     * @param type $credit 
     * @param type $cash_balance_today 
     */
    public static function impactCreditDelete($credit, $cash_balance_today) {
        $instance = &get_instance();
        General::loadModels($instance);

        Contract::unuseContract($credit);
        $contract = Contract::getContractByCredit($credit);

        if (self::isImpactableCredit($credit) && $credit['cred_tipo_trans'] == 'Caja') {

            if (!empty($contract)) {
                // si es credito de honorario o deposito disminuir cuotas pagadas en el contrato
                if ($credit['cred_concepto'] == 'Honorarios') {
                    if ($contract['honorary_cuotes_payed'] > 0)
                        $contract['honorary_cuotes_payed'] = $contract['honorary_cuotes_payed'] - 1;
                }
                if ($credit['cred_concepto'] == 'Deposito de garantia') {
                    if ($contract['warranty_cuotes_payed'] > 0)
                        $contract['warranty_cuotes_payed'] = $contract['warranty_cuotes_payed'] - 1;
                }
                $instance->basic->save('contratos', 'con_id', $contract);
            }

            $impact_on_cash = $credit['cred_monto'];
            $impact_on_safe_box = 0;

            $transfers = $instance->basic->get_where('creditos', array('cred_concepto' => 'Transferencia a CAJA FUERTE', 'cred_fecha' => $credit['cred_fecha']))->result_array();
            if (count($transfers)) {

                $safe_box = $instance->basic->get_where('cuentas_corrientes', array('cc_prop' => 'CAJA FUERTE'))->row_array();

                // Balance de hoy eliminando este credito
                $balance_if_delete_credit = $cash_balance_today - $credit['cred_monto'];

                if ($balance_if_delete_credit < 0) {
                    // Si eliminando el credito dejamos el balance progresivo actual
                    // menor a 0 quiere decir que este credito u otros han sido
                    // transferidos a caja fuerte.
                    // Por lo que se impactara por esa diferencia a la caja fuerte
                    // y el resto a la caja fisica
                    $impact_on_safe_box = $balance_if_delete_credit * (-1);
                    $impact_on_cash = $credit['cred_monto'] - $impact_on_safe_box;
                }
            }

            if ($impact_on_safe_box > 0) {
                if (($safe_box['cc_saldo'] + $safe_box['cc_varios']) > 0) {
                    $debit = array(
                        'deb_cc' => $safe_box['cc_prop'],
                        'cc_id' => $safe_box['cc_id'],
                        'deb_concepto' => 'Eliminacion de credito',
                        'deb_monto' => $impact_on_safe_box,
                        'deb_mes' => General::getStringMonth(date('m')) . ' ' . Date('Y'),
                        'deb_fecha' => Date('d-m-Y'),
                        'is_transfer' => 1
                    );

                    self::createDebit($debit, 'cc_saldo', $safe_box);
                    $instance->basic->save('cuentas_corrientes', 'cc_id', $safe_box);
                }
            }

            if ($impact_on_cash > 0) {
                // Eliminando arrastre de cajas mensuales si es fisico
                $monthly_cashes = Cash::getMonthlyCashes($credit['cred_fecha']);
                if (count($monthly_cashes)) {
                    foreach ($monthly_cashes as $monthly_cash) {
                        $monthly_cash['men_creditos'] -= $impact_on_cash;
                        $instance->basic->save('mensuales', 'men_id', $monthly_cash);
                    }
                }

                // Eliminando arrastre de cajas diarias si es fisico
                $daily_cashes = Cash::getDialyCashes($credit['cred_fecha']);
                if ($daily_cashes) {
                    foreach ($daily_cashes as $day_cash) {
                        $day_cash['caj_saldo'] -= $impact_on_cash;
                        $instance->basic->save('caja_comienza', 'caj_id', $day_cash);
                    }
                }
            }
        } else if (self::isImpactableCredit($credit) && $credit['cred_tipo_trans'] == 'Bancaria') {
            self::recalculateTaxDebit($credit);
        }
    }

    public static function createDebit($debit, $account_type, &$cc_to_impact) {
        $instance = &get_instance();
        General::loadModels($instance);

        // Impacta debito puro en la cuenta corriente del propietario
        $cc_to_impact[$account_type] -= $debit['deb_monto'];

        // Guarda el debito puro
        return $instance->basic->save('debitos', 'deb_id', $debit);
    }

    public static function deleteDebit($debit) {
        $instance = &get_instance();
        General::loadModels($instance);

        /* solo para davinia y rima */
        if ($debit['cc_id']) {
            /* solo para davinia y rima */
            $account = $instance->basic->get_where('cuentas_corrientes', array('cc_id' => $debit['cc_id']))->row_array();
        } else {
            $account = $instance->basic->get_where('cuentas_corrientes', array('cc_prop' => $debit['deb_cc']))->row_array();
        }

        // Eliminando efecto del debito a la cuenta
        $type = General::getAccountType($debit, 'Salida', 'deb_concepto');
        $account[$type] += $debit['deb_monto'];

        $instance->basic->del('debitos', 'deb_id', $debit['deb_id']);
        $instance->basic->save('cuentas_corrientes', 'cc_id', $account);
    }

    /**
     * Elimina el debito, aumenta en su monto a la cuenta correspondiente y tambien a
     * la caja mensual
     * @param type $debits
     * @param type $monthly_cash
     * @param type $concepts 
     */
    public static function removeDebitAndIncreaseAccount($debits) {
        if (count($debits)) {
            foreach ($debits as $debit) {

                // Eliminando efecto del debito a la cuenta
                self::deleteDebit($debit);

                // Plazmando impacto de la eliminacion
                self::impactDebitDelete($debit);
            }
        }
    }

    public static function impactDebitDelete($debit) {
        $instance = &get_instance();
        General::loadModels($instance);

        if (self::isImpactabledebit($debit) && $debit['deb_tipo_trans'] == 'Caja') {
            // Eliminando arrastre de cajas mensuales si es fisico
            $monthly_cashes = Cash::getMonthlyCashes($debit['deb_fecha']);
            if (count($monthly_cashes)) {
                foreach ($monthly_cashes as $monthly_cash) {
                    $monthly_cash['men_debitos'] -= $debit['deb_monto'];
                    $instance->basic->save('mensuales', 'men_id', $monthly_cash);
                }
            }

            // Eliminando arrastre de cajas diarias si es fisico
            $daily_cashes = Cash::getDialyCashes($debit['deb_fecha']);
            if ($daily_cashes) {
                foreach ($daily_cashes as $day_cash) {
                    $day_cash['caj_saldo'] += $debit['deb_monto'];
                    $instance->basic->save('caja_comienza', 'caj_id', $day_cash);
                }
            }
        }
    }

    public static function getLastControl($contract, $service) {
        $instance = &get_instance();
        General::loadModels($instance);
        $last_control = false;

        if (!empty($contract)) {

            $services_control = $instance->basic->get_where('services_control', array('contract' => $contract['con_id'], 'service' => $service), 'id');

            if ($services_control->num_rows()) {
                $last_control = $services_control->last_row('array');
            }
        }

        return $last_control;
    }

    public static function getLastPayment($contract, $concept) {
        $instance = &get_instance();
        General::loadModels($instance);
        $last_payment = false;

        if (!empty($contract)) {

            /* solo para davinia y rima */
            if ($contract['client_id'] && $contract['cc_id']) {
                $credits = $instance->basic->get_where('creditos', array('client_id' => $contract['client_id'], 'cc_id' => $contract['cc_id'], 'cred_concepto' => $concept), 'cred_id');
            } else {
                /* solo para davinia y rima */
                $credits = $instance->basic->get_where('creditos', array('cred_depositante' => $contract['con_inq'], 'cred_cc' => $contract['con_prop'], 'cred_concepto' => $concept), 'cred_id');
            }

            if ($credits->num_rows()) {
                $last_payment = $credits->last_row('array');
            }
        }

        return $last_payment;
    }

    public static function getAccountPaymentsDebt($last_payment, $periods, $contract) {
        $account_debt = false;
        $instance = &get_instance();
        General::loadModels($instance);

        if ($last_payment['cred_tipo_pago'] == 'A Cuenta') {

            $month_string = trim(preg_replace("/[^^A-Za-z (),.]/", "", $last_payment['cred_mes_alq']));
            $last_payment_year = trim(preg_replace("/[^0-9 (),.]/", "", $last_payment['cred_mes_alq']));
            $last_payment_month = General::getMonthNumber($month_string);
            $payed_account_date = '10' . '-' . $last_payment_month . '-' . $last_payment_year;

            $period_amount = 0;
            foreach ($periods->result_array() as $period) {
                if (General::isBetweenDates($payed_account_date, $period['per_inicio'], $period['per_fin'])) {
                    $period_amount = $period['per_monto'];
                }
            }

            $payed_account_credits = $instance->basic->get_where('creditos', array('cred_mes_alq' => $last_payment['cred_mes_alq'], 'cred_tipo_pago' => 'A Cuenta', 'cc_id' => $contract['cc_id'], 'client_id' => $contract['client_id']))->result_array();
            /* solo para davinia y rima */
            $payed_account_credits2 = $instance->basic->get_where('creditos', array('cred_mes_alq' => $last_payment['cred_mes_alq'], 'cred_tipo_pago' => 'A Cuenta', 'cred_cc' => $contract['con_prop'], 'cred_depositante' => $contract['con_inq']))->result_array();
            foreach ($payed_account_credits2 as $payed_account_credit2) {
                if (!in_array($payed_account_credit2, $payed_account_credits))
                    array_push($payed_account_credits, $payed_account_credit2);
            }
            /* solo para davinia y rima */

            $payed_account = 0;
            if (count($payed_account_credits) > 0) {
                foreach ($payed_account_credits as $row) {
                    if (strpos($row['cred_concepto'], 'Alquiler') !== FALSE || strpos($row['cred_concepto'], 'Loteo') !== FALSE)
                        $payed_account += $row['cred_monto'];
                }
            }

            $difference = $period_amount - $payed_account;
            $account_debt = array(
                'month' => $month_string . ' ' . $last_payment_year,
                'default_days' => 0,
                'concept' => $contract['con_tipo'],
                'sald_account' => 1,
                'amount' => $difference,
                'intereses' => 0
            );

            $month_debt = '00-' . $last_payment_month . '-' . $last_payment_year;
            $account_debt = self::calculateIntereses($account_debt, $contract, $month_debt, $periods, Date('d-m-Y'));
        }

        return $account_debt;
    }

    public static function calculateAmount($month_debt, $periods) {
        $amount = 0;
        $month_debt_explode = explode('-', $month_debt);
        // Problema con el calculo de los montos, a veces no entra en ningun rango
        // Deberia tomar del dia que inician los periodos no del dia 00
        foreach ($periods->result_array() as $row) {
            $per_explode = explode('-', $row['per_inicio']);
            $month_debt = $per_explode[0] . '-' . $month_debt_explode[1] . '-' . $month_debt_explode[2];
            if (General::isBetweenDates($month_debt, $row['per_inicio'], $row['per_fin'])) {
                $amount = $row['per_monto'];
            }
        }

        return $amount;
    }

    public static function getAccountPayedIntereses($contract, $month_debt, $current_date) {
        $instance = &get_instance();
        General::loadModels($instance);
        $month_debt_array = explode('-', $month_debt);
        $account_payed_month = General::getStringMonth($month_debt_array[1]);

        $credits = $instance->basic->get_where('creditos', array('cred_mes_alq' => $account_payed_month . ' ' . $month_debt_array[2], 'client_id' => $contract['client_id'], 'cred_concepto' => 'Intereses'))->result_array();
        /* solo para davinia y rima */
        $credits2 = $instance->basic->get_where('creditos', array('cred_mes_alq' => $account_payed_month . ' ' . $month_debt_array[2], 'cred_depositante' => $contract['con_inq'], 'cred_concepto' => 'Intereses'))->result_array();
        foreach ($credits2 as $credit2) {
            if (!in_array($credit2, $credits))
                array_push($credits, $credit2);
        }
        /* solo para davinia y rima */

        $account_amount_payed = 0;

        // filtro hasta la fecha y por concepto de pago de Alquileres
        foreach ($credits as $cred) {
            if ($cred['con_id']) {
                $controlled = false;
                if ($cred['con_id'] == $contract['con_id'])
                    $controlled = true;
            } else {
                $controlled = true;
            }
            if (General::isBetweenDates($cred['cred_fecha'], $month_debt, $current_date) && $controlled) {
                $account_amount_payed += $cred['cred_monto'];
            }
        }

        return $account_amount_payed;
    }

    public static function calculateIntereses($debt, $contract, $month_debt, $periods, $current_date) {
        $month_debt_array = explode('-', $month_debt);
        $current_date_array = explode('-', $current_date);

        if ($month_debt_array[1] <= $current_date_array[1] && $month_debt_array[2] <= $current_date_array[2] || $month_debt_array[2] < $current_date_array[2]) {
            $intereses = 0;
            $amount = 0;

            // Busco intereses pagados a cuenta para ese mes para ver si los resto
            //  esto anda para el culoo
//            $account_payed_intereses = self::getAccountPayedIntereses($contract, $month_debt, $current_date);
            $account_payed_intereses = 0;

            // Obtengo el monto del alquiler
            $amount = self::calculateAmount($month_debt, $periods);

            // Obtengo la cantidad de dias de mora
            $default_days = self::calculateDaysInDefault($month_debt, $current_date);

            if ($default_days > $contract['con_tolerancia']) {
                if ($debt['amount'] != 0) {
                    $intereses = ($debt['amount'] * $contract['con_punitorio'] * $default_days) - $account_payed_intereses;
                } else {
                    $intereses = ($amount * $contract['con_punitorio'] * $default_days) - $account_payed_intereses;
                }
                $debt['intereses'] = $intereses;
                $debt['default_days'] = $default_days;
            }
        }

        return $debt;
    }

    public static function calculateDaysInDefault($month_debt, $date) {
        $month_debt_explode = explode('-', $month_debt);
        $date_explode = explode('-', $date);
        //defino fecha 1 
        $ano1 = $month_debt_explode[2];
        $mes1 = $month_debt_explode[1];
        $dia1 = $month_debt_explode[0];
        //defino fecha 2 
        $ano2 = $date_explode[2];
        $mes2 = $date_explode[1];
        $dia2 = $date_explode[0];
        //calculo timestam de las dos fechas 
        $timestamp1 = mktime(0, 0, 0, $mes1, $dia1, $ano1);
        $timestamp2 = mktime(0, 0, 0, $mes2, $dia2, $ano2);
        //resto a una fecha la otra 
        $seconds_diff = $timestamp1 - $timestamp2;
        //echo $seconds_diff; 
        //convierto segundos en días 
        $days_diff = $seconds_diff / (60 * 60 * 24);
        //obtengo el valor absoulto de los días (quito el posible signo negativo) 
        $days_diff = abs($days_diff);
        //quito los decimales a los días de diferencia 
        $days_diff = round($days_diff);
        return $days_diff;
    }

    public static function calculateExpensInteres($debt, $contract, $month_debt, $date) {
        // Obtengo la cantidad de dias de mora
        $days_in_default = self::calculateDaysInDefault($month_debt, $date);

        if ($days_in_default > $contract['con_tolerancia']) {
            $debt['default_days'] = $days_in_default;
        }

        return $debt;
    }

    public static function getTotalAlquileres($debts) {
        $total = 0;
        foreach ($debts as $debt) {
            $total += $debt['amount'];
        }
        return $total;
    }

    public static function getTotalIntereses($debts) {
        $total = 0;
        foreach ($debts as $debt) {
            $total += $debt['intereses'];
        }
        return $total;
    }

    public static function impactDebit($debit) {
        $instance = &get_instance();
        General::loadModels($instance);

        $cc_to_impact = $instance->basic->get_where('cuentas_corrientes', array('cc_prop' => $debit['deb_cc']))->row_array();
        $month_cash = $instance->basic->get_where('mensuales', array('men_mes' => date('m'), 'men_ano' => date('Y')))->row_array();

        $account_type = General::getAccountType($debit, 'Salida', 'deb_concepto');

        if ($debit['deb_concepto'] != 'Rendicion' && $cc_to_impact['cc_prop'] != 'INMOBILIARIA') {
            // Crea prestamo si no es por rendicion
            self::createLoan($debit, $account_type, $cc_to_impact);
        }

        $debit['deb_id'] = self::createDebit($debit, $account_type, $cc_to_impact);

        // Impacta debito puro a la caja mensual si es tipo Fisico
        if ($debit['deb_tipo_trans'] == 'Caja') {
            $month_cash['men_debitos'] += $debit['deb_monto'];
        }

        // Guarda las cuentas corrientes del propietario e inmobiliaria, y la caja mensual
        $instance->basic->save('cuentas_corrientes', 'cc_id', $cc_to_impact);
        $instance->basic->save('mensuales', 'men_id', $month_cash);

        return $debit;
    }

    /**
     * Si se elimina una transfrencia se debe impactar sobre el progresivo mensual
     * @param type $transfer 
     */
//    public static function impactRemoveTransfer($transfer) {
//        $instance = &get_instance();
//        General::loadModels($instance);
//
//        if (isset($transfer['cred_concepto']) && $transfer['cred_concepto'] == 'Transferencia a CAJA FUERTE') {
//            $date_explode = explode('-', $transfers['cred_fecha']);
//            $monthly_cash = $instance->basic->get_where('mensuales', array('men_mes' => $date_explode[1], 'men_ano' => $date_explode[2]))->row_array();
//
//            $monthly_cash['men_creditos'] += $transfer['cred_monto'];
//        } else if (isset($transfer['deb_concepto']) && $transfer['deb_concepto'] == 'Transferencia a CAJA FISICA') {
//            $date_explode = explode('-', $transfers['deb_fecha']);
//            $monthly_cash = $instance->basic->get_where('mensuales', array('men_mes' => $date_explode[1], 'men_ano' => $date_explode[2]))->row_array();
//
//            $monthly_cash['men_creditos'] -= $transfer['deb_monto'];
//        }
//
//        $instance->basic->save('mensuales', 'men_id', $monthly_cash);
//    }

    /**
     * Un credito es impactable cuando su valor impacta en la caja fisica o bancaria
     * En tal caso, los creditos de gestion y prestamos autogenerados no impactan
     * porque existen como meras migraciones de dinero y se cancelan
     * @param type $credit
     * @return boolean 
     */
    public static function isImpactableCredit($credit) {
        if (strpos($credit['cred_concepto'], 'Gestion de Cobro') === FALSE &&
                strpos($credit['cred_concepto'], 'Prestamo') === FALSE) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Un debito es impactable cuando su valor impacta en la caja fisica o bancaria
     * En tal caso, los debitos de gestion y prestamos autogenerados no impactan
     * porque existen como meras migraciones de dinero y se cancelan
     * @param type $debit
     * @return boolean 
     */
    public static function isImpactabledebit($debit) {
        if (strpos($debit['deb_concepto'], 'Gestion de Cobro') === FALSE &&
                strpos($debit['deb_concepto'], 'Prestamo') === FALSE) {
            return true;
        } else {
            return false;
        }
    }

    public static function sendNotification($credits) {
        $instance = &get_instance();
        General::loadModels($instance);

        $settings = User::getUserSettings();

        $propietary_client = $instance->basic->get_where('clientes', array('client_name' => $credits[0]['cred_cc']))->row_array();

        $contract = Contract::getContract($credits);

        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset = iso - 8859 - 1' . "\r\n";
        $headers .= 'From: ' . $settings['name'] . ' <' . $settings['email'] . '>' . "\r\n";

        $total_amount = 0;

        $msg = '<h2>Ya puede pasar por ' . $settings['name'] . '  a cobrar su alquiler</h2>';
        $msg .= "<br>";
        $msg .= "<h3>Alquiler y/o servicios pagados por contrato con:</h3> " . $contract['con_inq'];
        $msg .= "<br>";

        foreach ($credits as $credit) {
            if ($credit['cred_concepto'] != 'Honorarios') {
                $msg .= "<br>";
                $msg .= "------------------------------------------------------------------------------------------------";
                $msg .= "<br>";
                $msg .= "<strong>Concepto Abonado: </strong>" . $credit['cred_concepto'] . ' ' . $credit['cred_mes_alq'] . ". <br><strong>Fecha de pago: </strong>" . $credit['cred_fecha'];
                $msg .= "<br>";
                $msg .= "<strong>Monto Abonado: </strong>$ " . $credit['cred_monto'];

                if (Contract::conceptPerceiveGestion($credit['cred_concepto'])) {
                    $total_amount += $credit['cred_monto'] - ($credit['cred_monto'] * $contract['con_porc']);
                } else {
                    $total_amount += $credit['cred_monto'];
                }

                if ($credit['cred_interes'] > 0 && $credit['cred_interes_calculado'] > 0) {
                    if (Contract::conceptPerceiveGestion($credit['cred_concepto'])) {
                        $total_amount += $credit['cred_interes_calculado'] - ($credit['cred_interes_calculado'] * $contract['con_porc']);
                    } else {
                        $total_amount += $credit['cred_interes_calculado'];
                    }
                    $msg .= "<br>";
                    $msg .= "<strong>Intereses abonados por " . $credit['cred_interes'] . " dia/s de mora: </strong>$ " . $credit['cred_interes_calculado'];
                }
                if ($credit['cred_iva_calculado'] > 0) {
                    $total_amount += $credit['cred_iva_calculado'];
                    $msg .= "<br>";
                    $msg .= "<strong>Monto Abonados IVA: </strong>$ " . $credit['cred_iva_calculado'];
                }
            }
        }

        $msg .= "<br>";
        $msg .= '<h2>Monto total: $ ' . round($total_amount, 2) . '</h2>';

        mail($propietary_client['client_email'], 'Han Pagado su Alquiler!', $msg, $headers);
    }

}

?>
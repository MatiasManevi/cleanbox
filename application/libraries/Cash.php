<?php

/*
 * Project: Cleanbox
 * Document: Cash
 * 
 * @author Manevi Matias Alejandro 
 * Informatic Engineer - Web Developer
 * 
 * Contact: manevimatias@gmail.com
 * 
 * All rights reserved ®
 */

class Cash {

    public static function isFirstCashOfMonth() {
        $instance = &get_instance();
        General::loadModels($instance);

        $daily_starting_cashes = $instance->basic->get_where('caja_comienza', array('caj_mes' => date('m'), 'caj_ano' => date('Y')))->result_array();

        if (empty($daily_starting_cashes)) {
            return true;
        } else {
            return false;
        }
    }

    public static function getMonthlyCashes($date) {
        $date = explode('-', $date);
        $instance = &get_instance();
        General::loadModels($instance);

        if ($date[1] == Date('m') && $date[2] == Date('Y')) {
            return $instance->basic->get_where('mensuales', array('men_mes' => $date[1], 'men_ano' => $date[2]))->result_array();
        } else {
            $monthly_cashes = array();
            $today_m = Date('m');
            $today_y = Date('Y');

            $current_m = $date[1];
            $current_y = $date[2];

            while ($current_m <= $today_m && $current_y <= $today_y) {
                $cash = $instance->basic->get_where('mensuales', array('men_mes' => $current_m, 'men_ano' => $current_y))->row_array();
                array_push($monthly_cashes, $cash);

                if (($current_m + 1) > 12) {
                    $current_m = 1;
                    $current_y++;
                } else {
                    $current_m++;
                }
            }

            return $monthly_cashes;
        }
    }

    public static function getDialyCashes($date) {
        $date = explode('-', $date);
        $instance = &get_instance();
        General::loadModels($instance);

        if ($date[0] == Date('d') && $date[1] == Date('m') && $date[2] == Date('Y')) {
            // Si esta eliminando un movimiento del dia, no modifica a la caja_comienza del dia
            return false;
        } else {
            // Si esta pasando la fecha de un dia pasado
            // No se debe modificar la caja de ese dia, sino de todas las
            // siguientes a ese dia
            // Traemos la caja del dia del movimiento, unicamente para luego traer todas
            // las cajas que tengan un id superior a ella

            $movement_day_cash = $instance->basic->get_where('caja_comienza', array('caj_dia' => $date[0], 'caj_mes' => $date[1], 'caj_ano' => $date[2]))->row_array();

            $instance->db->select('*');
            $instance->db->where('caj_id >', $movement_day_cash['caj_id']);
            $monthly_cashes = $instance->db->get('caja_comienza')->result_array();

            return $monthly_cashes;
        }
    }

    public static function loadMonthlyCash() {
        self::loadDailyStartingCash();

        $instance = &get_instance();
        General::loadModels($instance);

        $month_cash = $instance->basic->get_where('mensuales', array('men_mes' => date('m'), 'men_ano' => date('Y')))->row_array();

        if (empty($month_cash)) {

            if (User::beginCashZero()) {
                $begin_amount = 0;
            } else {
                $begin_amount = self::getBalance('Caja');
            }

            $month_cash = array(
                'men_mes' => date('m'),
                'men_ano' => date('Y'),
                'men_creditos' => $begin_amount,
                'men_debitos' => 0
            );

            $instance->basic->save('mensuales', 'men_id', $month_cash);
        }
    }

    public static function loadDailyStartingCash() {
        $instance = &get_instance();
        General::loadModels($instance);
        // creditos - debitos, desde comienzo de mes hasta el dia anterior al actual, ese es el monto
        // con el cual comienza este dia
        $begin_amount = 0;
        $daily_starting_cash = $instance->basic->get_where('caja_comienza', array('caj_dia' => date('d'), 'caj_mes' => date('m'), 'caj_ano' => date('Y')))->row_array();

        if (empty($daily_starting_cash)) {

            // Para calcular el monto con el que comienza la caja fisica,
            // obtiene la caja comienza del dia anterior + los creditos - debitos de ese dia
            //  */- el saldo de transferencias, depende tambien de la configuracion establecida
            // en beginCashZero()

            if (User::beginCashZero() && self::isFirstCashOfMonth()) {
                $begin_amount = 0;
            } else {
                $begin_amount = self::getBalance('Caja');
            }

            $daily_starting_cash = array(
                'caj_dia' => date('d'),
                'caj_mes' => date('m'),
                'caj_ano' => date('Y'),
                'caj_saldo' => $begin_amount
            );
            $instance->basic->save('caja_comienza', 'caj_id', $daily_starting_cash);
        }
    }

    public static function getBalance($type, $fecha = null) {
        $balance = 0;
        $instance = &get_instance();
        General::loadModels($instance);

        if ($type == 'Caja') {
            if (!$fecha) {
                // actual balance
                $last_begin = $instance->basic->get_where('caja_comienza', array(), 'caj_id')->last_row('array');
                if (empty($last_begin)) {
                    // first day using balance = 0
                    $balance = 0;
                } else {
                    // actual balance
                    $fecha = $last_begin['caj_dia'] . '-' . $last_begin['caj_mes'] . '-' . $last_begin['caj_ano'];

                    $balance = self::getBalanceByDate($fecha, $last_begin['caj_saldo']);
                }
            } else {
                // balance of specific date
                $balance = self::getBalanceByDate($fecha, 0);
            }
        } else if ($type == 'Bancaria') {
            $arr_date = explode('-', $fecha);

            if ($arr_date[0] - 1 == 0) {
                $to_day = 1;
            } else {
                $to_day = $arr_date[0] - 1;
            }

            $from = '01-' . $arr_date[1] . '-' . $arr_date[2];
            $to = $to_day . '-' . $arr_date[1] . '-' . $arr_date[2];

            $balance = self::getBankBalanceByDate($from, $to);
        }

        return $balance;
    }

    /**
     * Devuelve el balance hasta la fecha anterior a la que se paso
     * osea si el 17-08-2016 quiero saber el comienza caja bancaria
     * esto recibe 17-08-2016 y calcula el balance de entradas y salidas
     * desde el 01-08-2016 al 16-08-2016
     * 
     * @param type $date 
     */
    public static function getBankBalanceByDate($from, $to) {
        $balance = 0;
        $instance = &get_instance();
        General::loadModels($instance);

        $credits = $instance->basic->get_where('creditos', array('cred_tipo_trans' => 'Bancaria'))->result_array();
        $debits = $instance->basic->get_where('debitos', array('deb_tipo_trans' => 'Bancaria'))->result_array();

        $ins = 0;
        foreach ($credits as $row) {
            if (strpos($row['cred_concepto'], 'Gestion de Cobro') === false && strpos($row['cred_concepto'], 'Prestamo') === false && strpos($row['cred_concepto'], 'devolucion') === false) {
                if (General::isBetweenDates($row['cred_fecha'], $from, $to)) {
                    $ins += $row['cred_monto'];
                }
            }
        }

        $outs = 0;
        foreach ($debits as $row) {
            if (strpos($row['deb_concepto'], 'Gestion de Cobro') === false && strpos($row['deb_concepto'], 'Prestamo') === false && strpos($row['deb_concepto'], 'devolucion') === false) {
                if (General::isBetweenDates($row['deb_fecha'], $from, $to)) {
                    $outs += $row['deb_monto'];
                }
            }
        }

        $balance = $ins - $outs;

        return $balance;
    }

    public static function getBalanceByDate($date, $begin_saldo) {
        $balance = 0;
        $instance = &get_instance();
        General::loadModels($instance);

        $credits = $instance->basic->get_where('creditos', array('cred_fecha' => $date, 'cred_tipo_trans' => 'Caja'))->result_array();
        $debits = $instance->basic->get_where('debitos', array('deb_fecha' => $date, 'deb_tipo_trans' => 'Caja'))->result_array();
        $transfers_to_safe = $instance->basic->get_where('creditos', array('cred_concepto' => 'Transferencia a CAJA FUERTE'))->result_array();
        $transfers_to_cash = $instance->basic->get_where('debitos', array('deb_concepto' => 'Transferencia a CAJA FISICA'))->result_array();
        $transfers_to_safe_elimination = $instance->basic->get_where('debitos', array('deb_concepto' => 'Eliminacion de credito'))->result_array();

        $ins = 0;
        foreach ($credits as $credit) {
            if (strpos($credit['cred_concepto'], 'Gestion de Cobro') === false && strpos($credit['cred_concepto'], 'Prestamo') === false && strpos($credit['cred_concepto'], 'devolucion') === false) {
                $ins += $credit['cred_monto'];
            }
        }

        $outs = 0;
        foreach ($debits as $debit) {
            if (strpos($debit['deb_concepto'], 'Gestion de Cobro') === false && strpos($debit['deb_concepto'], 'Prestamo') === false && strpos($debit['deb_concepto'], 'devolucion') === false) {
                $outs += $debit['deb_monto'];
            }
        }

        $transfers_to_safe_sum = 0;
        foreach ($transfers_to_safe as $row) {
            if (General::isBetweenDates($row['cred_fecha'], $date, $date)) {
                $transfers_to_safe_sum += $row['cred_monto'];
            }
        }

        $transfers_to_cash_sum = 0;
        foreach ($transfers_to_cash as $row) {
            if (General::isBetweenDates($row['deb_fecha'], $date, $date)) {
                $transfers_to_cash_sum += $row['deb_monto'];
            }
        }

        $transfers_to_safe_elimination_sum = 0;
        foreach ($transfers_to_safe_elimination as $row) {
            if (General::isBetweenDates($row['deb_fecha'], $date, $date)) {
                $transfers_to_safe_elimination_sum += $row['deb_monto'];
            }
        }

        $entrys_minus_outs = $ins - $outs; // movimiento del dia

        $balance = $begin_saldo + $entrys_minus_outs - $transfers_to_safe_sum + $transfers_to_cash_sum;

        if ($balance < 0) {
            // La unica situacion en la que el balance es negativo es cuando se elimina un credito
            // que desconto toda la caja fisica y una parte de la caja fuerte
            // entonces se balancea con la suma de esa diferencia desconada en la caja fuerte a la caja
            // fisica para que no quede en negativa sino en cero
            $balance += $transfers_to_safe_elimination_sum;
        }

        return $balance;
    }

    /**
     * Verifica si existen fondos suficientes en caja fisica para estos debitos
     * Solo implican los debitos cuyo tipo es 'Caja' no los de 'Bancaria'
     * @param type $debits
     * @return boolean 
     */
    public static function sufficientFounds($debits) {
        $cash_founds = self::getBalance('Caja');
        $debits_sum = 0;

        foreach ($debits as $debit) {
            if ($debit['deb_tipo_trans'] == 'Caja') {
                $debits_sum += $debit['deb_monto'];
            }
        }

        if ($debits_sum > $cash_founds) {
            return false;
        } else {
            return true;
        }
    }

}

?>

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
require 'vendor/autoload.php';
use Carbon\Carbon;

class Cash {

    public static function getBalance($type, $date = null) {
        $balance = 0;
        $instance = &get_instance();
        General::loadModels($instance);

        if ($type == 'Caja') {
            if (!$date) {
                // actual balance
                $date = date('d-m-Y');

                $balance = self::getBalanceByDate($date, self::getBeginCash($date));
            } else {
                // balance of specific date
                $balance = self::getBalanceByDate($date, 0);
            }
        } else if ($type == 'Bancaria') {
            $arr_date = explode('-', $date);

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

    public static function getBeginCash($date) {
        $balance = 0;
        $instance = &get_instance();
        General::loadModels($instance);

        $date_array = explode('-', $date);
        $dt = Carbon::create($date_array[2], $date_array[1], $date_array[0], 0);
        $dt->toDateTimeString();    
        
        /*
        si empiezo en 0 todos los meses
            es el primer dia del mes
                empiezo en 0
            es otro dia del mes
                empiezo con el arrastrado hasta ayer

        sino empiezo en 0 todos los meses
            es el primer dia del mes
                hago el balance desde el principio de los tiempos hasta ayer
            es otro dia del mes
                hago el balace desde el principio de los tiempos hasta ayer
         */
        if(User::beginCashZero()){
            if($dt->day == 1){
                return $balance;
            }else{
                $dt->subDay();
                $from = '01-'.$dt->month.'-'.$dt->year;
                $to = $dt->day.'-'.$dt->month.'-'.$dt->year;
            }
        }else{
            $dt->subDay();
            $from = '01-01-2000'; // from beginin
            $to = $dt->day.'-'.$dt->month.'-'.$dt->year;
        }

        $credits = $instance->basic->get_where('creditos', array('cred_tipo_trans' => 'Caja'))->result_array();
        $debits = $instance->basic->get_where('debitos', array('deb_tipo_trans' => 'Caja'))->result_array();
        
        $transfers_to_safe = $instance->basic->get_where('creditos', array('cred_concepto' => 'Transferencia a CAJA FUERTE'))->result_array();
        $transfers_to_cash = $instance->basic->get_where('debitos', array('deb_concepto' => 'Transferencia a CAJA FISICA'))->result_array();
        $transfers_to_safe_elimination = $instance->basic->get_where('debitos', array('deb_concepto' => 'Eliminacion de credito'))->result_array();

        $ins = 0;
        foreach ($credits as $credit) {
            if (strpos($credit['cred_concepto'], 'Gestion de Cobro') === false){
                if( strpos($credit['cred_concepto'], 'Prestamo') === false){
                    if(strpos($credit['cred_concepto'], 'devolucion') === false) {
                        if (General::isBetweenDates($credit['cred_fecha'], $from, $to)) {
                            $ins += $credit['cred_monto'];
                        }
                    }
                }
            }
        }

        $outs = 0;
        foreach ($debits as $debit) {
            if (strpos($debit['deb_concepto'], 'Gestion de Cobro') === false){
                if( strpos($debit['deb_concepto'], 'Prestamo') === false){
                    if(strpos($debit['deb_concepto'], 'devolucion') === false) {
                        if (General::isBetweenDates($debit['deb_fecha'], $from, $to)) {
                            $outs += $debit['deb_monto'];
                        }
                    }
                }
            }
        }

        $transfers_to_safe_sum = 0;
        foreach ($transfers_to_safe as $row) {
            if (General::isBetweenDates($row['cred_fecha'], $from, $to)) {
                $transfers_to_safe_sum += $row['cred_monto'];
            }
        }

        $transfers_to_cash_sum = 0;
        foreach ($transfers_to_cash as $row) {
            if (General::isBetweenDates($row['deb_fecha'], $from, $to)) {
                $transfers_to_cash_sum += $row['deb_monto'];
            }
        }

        $transfers_to_safe_elimination_sum = 0;
        foreach ($transfers_to_safe_elimination as $row) {
            if (General::isBetweenDates($row['deb_fecha'], $from, $to)) {
                $transfers_to_safe_elimination_sum += $row['deb_monto'];
            }
        }

        $entrys_minus_outs = $ins - $outs; // movimiento del dia

        $balance = $entrys_minus_outs - $transfers_to_safe_sum + $transfers_to_cash_sum;
   
        if ($balance < 0) {
            // La unica situacion en la que el balance es negativo es cuando se elimina un credito
            // que desconto toda la caja fisica y una parte de la caja fuerte
            // entonces se balancea con la suma de esa diferencia desconada en la caja fuerte a la caja
            // fisica para que no quede en negativa sino en cero
            $balance += $transfers_to_safe_elimination_sum;
        }

        return round($balance, 2);
    }

    public static function getBalanceByDate($date, $begin_saldo) {
        $balance = 0;
        $instance = &get_instance();
        General::loadModels($instance);

        $credits = $instance->basic->get_where('creditos', array('cred_fecha' => $date, 'cred_tipo_trans' => 'Caja'))->result_array();
        $debits = $instance->basic->get_where('debitos', array('deb_fecha' => $date, 'deb_tipo_trans' => 'Caja'))->result_array();
        $transfers_to_safe = $instance->basic->get_where('creditos', array('is_transfer' => 1))->result_array();
        $transfers_to_cash = $instance->basic->get_where('debitos', array('is_transfer' => 1))->result_array();
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
     * @param array $debits
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
    
    /**
     * Verifica si existen fondos suficientes en caja fisica para eliminar estos creditos
     * Solo implican los creditos cuyo tipo es 'Caja' no los de 'Bancaria'
     * @param integer $amount
     * @return boolean 
     */
    public static function canDeleteCredits($amount) {
        $cash_founds = self::getBalance('Caja');

        if ($amount > $cash_founds) {
            return false;
        } else {
            return true;
        }
    }

}

?>

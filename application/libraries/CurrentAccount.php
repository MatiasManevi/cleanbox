<?php

/*
 * Project: Cleanbox
 * Document: CurrentAccount
 * 
 * @author Manevi Matias Alejandro 
 * Informatic Engineer - Web Developer
 * 
 * Contact: manevimatias@gmail.com
 * 
 * All rights reserved ®
 */

class CurrentAccount {

    public static function getDebits($account) {
        $instance = &get_instance();

        $debits = $instance->basic->get_where('debitos', array('cc_id' => $account['cc_id']))->result_array();
        /* solo para davinia y rima */
        $debits2 = $instance->basic->get_where('debitos', array('deb_cc' => $account['cc_prop']))->result_array();
        foreach ($debits2 as $debit2) {
            if (!in_array($debit2, $debits))
                array_push($debits, $debit2);
        }
        /* solo para davinia y rima */

        return $debits;
    }

    public static function getCredits($account) {
        $instance = &get_instance();

        $credits = $instance->basic->get_where('creditos', array('cc_id' => $account['cc_id']))->result_array();                
        /* solo para davinia y rima */
        $credits2 = $instance->basic->get_where('creditos', array('cred_cc' => $account['cc_prop']))->result_array();
        foreach ($credits2 as $credit2) {
            if (!in_array($credit2, $credits))
                array_push($credits, $credit2);
        }
        /* solo para davinia y rima */

        return $credits;
    }

    public static function getCreditsSum($credits, $from = false, $to = false, $sum_loans = false) {
        $sum = 0;

        foreach ($credits as $row) {
            if($sum_loans || strpos($row['cred_concepto'], 'Prestamo') === false) {
                if($from && $to){
                    if (General::isBetweenDates($row['cred_fecha'], $from, $to)) {       
                        $sum += $row['cred_monto'];
                    }
                }else{
                    $sum += $row['cred_monto'];
                }
            }
        }

        return $sum;
    }

    public static function getDebitsSum($debits, $from = false, $to = false, $sum_loans = false) {
        $sum = 0;

        foreach ($debits as $row) {
            if($sum_loans || strpos($row['deb_concepto'], 'Prestamo') === false) {
                if($from && $to){
                    if (General::isBetweenDates($row['deb_fecha'], $from, $to)) {
                        $sum += $row['deb_monto'];
                    }
                }else{
                    $sum += $row['deb_monto'];
                }
            }
        }

        return $sum;
    }
}

?>

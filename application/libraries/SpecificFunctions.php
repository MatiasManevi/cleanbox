<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SpecificFunctions
 *
 * @author Matias
 * revisar cochera
 * revisar impresion de recibo de wolozyn alejandra y que incluye prestamos en el recibo
 */
class SpecificFunctions {

    public function nueva() {
        $cc_inmo = $this->basic->get_where('cuentas_corrientes', array('cc_prop' => 'INMOBILIARIA'))->row_array();

        $prop_wrongs = $this->db->query("SELECT DISTINCT `cred_cc` FROM `creditos` WHERE `cc_id` = 1 AND `cred_cc` NOT LIKE 'INMOBILIARIA'")->result_array();

        echo '<pre>';
        foreach ($prop_wrongs as $prop_wrong) {

            if ($prop_wrong['cred_cc'] == 'CONDOMINIO SANTIAGO ANGEL RAMOS MONTES Y OTROS') {
                $cc_to_impact = $this->basic->get_where('cuentas_corrientes', array('cc_prop' => $prop_wrong['cred_cc']))->row_array();

                print_r($cc_to_impact);
                echo '<br>';

                $credits = $this->basic->get_where('creditos', array('cc_id' => 1, 'cred_cc' => $prop_wrong['cred_cc']))->result_array();

                $trans = array();
                foreach ($credits as $credit) {
                    if (!in_array($credit['trans'], $trans)) {
                        array_push($trans, $credit['trans']);
                        $credits_t = $this->basic->get_where('creditos', array('trans' => $credit['trans']))->result_array();
                        $debits_t = $this->basic->get_where('debitos', array('trans' => $credit['trans']))->result_array();

                        echo '<strong>' . $credit['trans'] . '</strong><br>';
                        echo 'credits';
                        echo '<br>';
                        print_r($credits_t);
                        echo 'debits';
                        echo '<br>';
                        print_r($debits_t);

//                        foreach ($credits_t as $credit_t) {
//                            if ($credit_t['cred_concepto'] != 'Honorarios' && strpos($credit_t['cred_concepto'], 'Gestion de Cobro') === false) {
//                                $credit_t['cc_id'] = $cc_to_impact['cc_id'];
//
//                                $cc_to_impact['cc_saldo'] += $credit_t['cred_monto'];
//                                $cc_inmo['cc_saldo'] -= $credit_t['cred_monto'];
//
//                                $this->basic->save('creditos', 'cred_id', $credit_t);
//                            }
//                        }
//                        foreach ($debits_t as $debit_t) {
//                            if (strpos($debit_t['deb_concepto'], 'Gestion de Cobro') !== false) {
//                                if ($debit_t['cc_id'] == 1) {
//
//                                    $debit_t['cc_id'] = $cc_to_impact['cc_id'];
//                                    $debit_t['deb_cc'] = $cc_to_impact['cc_prop'];
//
//                                    $cc_to_impact['cc_saldo'] -= $debit_t['deb_monto'];
//                                    $cc_inmo['cc_saldo'] += $debit_t['deb_monto'];
//
//                                    $this->basic->save('debitos', 'deb_id', $debit_t);
//                                }
//                            }
//                        }
                    }
                    $this->basic->save('cuentas_corrientes', 'cc_id', $cc_to_impact);
                }
            }
        }

        $this->basic->save('cuentas_corrientes', 'cc_id', $cc_inmo);

        die;
    }

    /**
     * Actualiza todos los contratos con el correcto cc_id de la cuenta
     * del propietario correspondiente 
     */
    public function updateCCID() {
        $accounts = $this->basic->get_all('cuentas_corrientes')->result_array();
        $contracts = $this->basic->get_all('contratos')->result_array();

        foreach ($contracts as $contract) {
            if ($contract['cc_id'] == 1 || $contract['cc_id'] == 0) {
                foreach ($accounts as $account) {
                    if ($account['cc_prop'] == $contract['con_prop']) {

                        $contract['cc_id'] = $account['cc_id'];

                        $this->basic->save('contratos', 'con_id', $contract);
                    }
                }
            }
        }
    }

    /**
     * Sirvio para arreglar los campos de cred_mes_alq de RIMA, ya que no todos cuplian
     * el patron (Mes Ano)
     */
    public function arrangeMonths() {
        $credits = $this->basic->get_all('creditos')->result_array();
        $debits = $this->basic->get_all('debitos')->result_array();

//        $debs = $this->basic->query("SELECT * FROM `debitos` WHERE `deb_fecha` LIKE '%-2014'")->result_array();
        echo '<pre>';
        // script para controlar si hay meses fuera de formato
//        foreach ($credits as $credit) {
//
//            if (!$this->monthContainYear($credit['cred_mes_alq']) && strlen($credit['cred_mes_alq'])) {
//                $months_array = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
//
//                if (!in_array($credit['cred_mes_alq'], $months_array)) {
//                    print_r($credit);
//                }
//            }
//        }
//        foreach ($debits as $debit) {
//
//            if (!$this->monthContainYear($debit['deb_mes']) && strlen($debit['deb_mes'])) {
//                $months_array = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
//
//                if (!in_array($debit['deb_mes'], $months_array)) {
//                    print_r($debit);
//                }
//            }
//        }
        // script para controlar si hay meses vacios
//        foreach ($credits as $credit) {
//
//            if (!strlen($credit['cred_mes_alq'])) {
//
//                print_r($credit);
//            }
//        }
//        foreach ($debits as $debit) {
//
//            if (!strlen($debit['deb_mes'])) {
//
//                print_r($debit);
//            }
//        }
//        script que comprueba que todos los meses tengan ano
//        foreach ($credits as $credit) {
//
//            if (!$this->monthContainYear($credit['cred_mes_alq'])) {
//
//                print_r($credit);
//            }
//        }
//        
        // script para controlar si hay errpres garrafales
        $months_array = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
        $year_array = array(2014, 2015, 2016);
        foreach ($credits as $credit) {
            $ok = false;
            foreach ($months_array as $m) {
                foreach ($year_array as $y) {
                    $my = $m . ' ' . $y;
                    if ($credit['cred_mes_alq'] == $my) {
                        $ok = true;
                    }
                }
            }
            if (!$ok) {
                print_r($credit);
            }
        }

        foreach ($debits as $debit) {

            $ok = false;
            foreach ($months_array as $m) {
                foreach ($year_array as $y) {
                    $my = $m . ' ' . $y;
                    if ($debit['deb_mes'] == $my) {
                        $ok = true;
                    }
                }
            }
            if (!$ok) {
                print_r($debit);
            }
        }


        die;
        // script para corregir $credits todos los meses y ponerle ano
        foreach ($credits as $credit) {

            if (!$this->monthContainYear($credit['cred_mes_alq'])) {
                $date = explode('-', $credit['cred_fecha']);
                $year_ = $date[2];
                $month_ = $date[1];

                switch ($year_) {
                    case 2014:
                        if (!strlen($credit['cred_mes_alq'])) {
                            // esta VACIO
                            $month = General::getStringMonth($month_);
                            $credit['cred_mes_alq'] = $month . ' ' . $year_;
                        } else {
                            // el resto todos tienen ano
                        }
                        break;
                    case 2015:
                        $int_month_regis = intval($date[1]);
                        $int_month_payed = intval(General::getMonthNumber($credit['cred_mes_alq']));
                        $month = $credit['cred_mes_alq'] . ' ' . $year_;

                        if ($int_month_regis != $int_month_payed) {
                            if ($int_month_payed >= 4) {
                                if ($int_month_regis < 7) {
                                    $year_--;
                                    $month = $credit['cred_mes_alq'] . ' ' . $year_;
                                }
                            } else {
                                if ($int_month_regis > 6) {
                                    $year_++;
                                    $month = $credit['cred_mes_alq'] . ' ' . $year_;
                                }
                            }
                        }

                        $credit['cred_mes_alq'] = $month;
                        break;
                    case 2016:
                        $int_month_regis = intval($date[1]);
                        $int_month_payed = intval(General::getMonthNumber($credit['cred_mes_alq']));
                        $month = $credit['cred_mes_alq'] . ' ' . $year_;
                        if ($int_month_regis != $int_month_payed) {
                            if ($int_month_payed > 6) {
                                if ($int_month_regis <= 4) {
                                    $year_--;
                                    $month = $credit['cred_mes_alq'] . ' ' . $year_;
                                }
                            } else {
                                if ($int_month_regis > 10) {
                                    $year_++;
                                    $month = $credit['cred_mes_alq'] . ' ' . $year_;
                                }
                            }
                        }

                        $credit['cred_mes_alq'] = $month;
                        break;
                }

                $this->basic->save('creditos', 'cred_id', $credit);
            }
        }

        // script para corregir $debits todos los meses y ponerle ano
        foreach ($debits as $debit) {

            if (!$this->monthContainYear($debit['deb_mes'])) {
                $date = explode('-', $debit['deb_fecha']);
                $year_ = $date[2];
                $month_ = $date[1];


                switch ($year_) {
                    case 2014:
                        if (!strlen($debit['deb_mes'])) {
                            // esta VACIO
                            $month = General::getStringMonth($month_) . ' ' . $year_;
                        } else {

                            $month = $debit['deb_mes'] . ' ' . $year_;
                            if ($int_month_regis != $int_month_payed) {
                                // no hay debitos con meses distintos en 2014
                            }
                        }

                        $debit['deb_mes'] = $month;
                        break;
                    case 2015:

                        if (!strlen($debit['deb_mes'])) {
                            // esta VACIO
                            $month = General::getStringMonth($month_) . ' ' . $year_;
                        } else {
                            $int_month_regis = intval($date[1]);
                            $int_month_payed = intval(General::getMonthNumber($debit['deb_mes']));

                            $month = $debit['deb_mes'] . ' ' . $year_;
                            if ($int_month_regis != $int_month_payed) {
                                if ($int_month_payed >= 4) {
                                    if ($int_month_regis < 7) {
                                        $year_--;
                                        $month = $debit['deb_mes'] . ' ' . $year_;
                                    }
                                } else {
                                    if ($int_month_regis > 6) {
                                        $year_++;
                                        $month = $debit['deb_mes'] . ' ' . $year_;
                                    }
                                }
                            }
                        }

                        $debit['deb_mes'] = $month;
                        break;
                    case 2016:

                        if (!strlen($debit['deb_mes'])) {
                            // esta VACIO
                            $month = General::getStringMonth($month_) . ' ' . $year_;
                        } else {
                            $int_month_regis = intval($date[1]);
                            $int_month_payed = intval(General::getMonthNumber($debit['deb_mes']));

                            $month = $debit['deb_mes'] . ' ' . $year_;
                            if ($int_month_regis != $int_month_payed) {
                                if ($int_month_payed > 6) {
                                    if ($int_month_regis <= 4) {
                                        $year_--;
                                        $month = $debit['deb_mes'] . ' ' . $year_;
                                    }
                                } else {
                                    if ($int_month_regis > 10) {
                                        $year_++;
                                        $month = $debit['deb_mes'] . ' ' . $year_;
                                    }
                                }
                            }
                        }

                        $debit['deb_mes'] = $month;
                        break;
                }

                $this->basic->save('debitos', 'deb_id', $debit);
            }
        }
    }

    public function monthContainYear($string) {
        return preg_match('/\\d/', $string) > 0;
    }

}

?>

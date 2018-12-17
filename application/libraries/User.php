<?php

/*
 * Project: Cleanbox
 * Document: User
 * 
 * @author Manevi Matias Alejandro 
 * Informatic Engineer - Web Developer
 * 
 * Contact: manevimatias@gmail.com
 * 
 * All rights reserved ®
 */

class User {

    const FREE_PLAN = 1;
    const BASIC_PLAN = 2;
    const ENTERPRISE_PLAN = 3;
    const FULL_PLAN = 4;

    public static function checkLogin() {
        $instance = &get_instance();

        if (!$instance->session->userdata('logged_in') && strpos(current_url(), 'login') === FALSE) {
            redirect(site_url('login'));
        }
    }

    public static function getUserId() {
        $instance = &get_instance();

        return $instance->session->userdata('id');
    }

    public static function getUserAdminId() {
        $instance = &get_instance();

        return $instance->session->userdata('admin_id');
    }

    public static function getUserSettings() {
        $instance = &get_instance();
        General::loadModels($instance);

        $user_id = self::getUserAdminId();

        return $instance->basic->get_where('settings', array('user_id' => $user_id))->row_array();
    }

    /**
     * Especifica si la caja fisica comenzara el nuevo mes con monto en $0.00 o arrastrara el
     * monto con el que termino el mes anterior
     * @return int 
     */
    public static function beginCashZero() {
        $settings = self::getUserSettings();

        if (!empty($settings)) {
            return $settings['begin_cash_zero'];
        }
    }

    /**
     * Especifica si estara activo el sistema de generacion de codigos
     * Estos codigos sirven para:
     * Solicitar habilitar campo 'dias de mora' para modificarlo
     * @return int 
     */
    public static function codeControl() {
        $settings = self::getUserSettings();

        if (!empty($settings)) {
            return $settings['code_control'];
        }
    }

    /**
     * Por cada transaccion con creditos bancarios el sistema debe debitar a la caja
     * bancaria un % de IIBB por el total de creditos de la transac. Bancaria. 
     * Ese porcentaje es obtenido de las configuraciones con esta funcion
     * @return string 
     */
    public static function getUserIIBBTAX() {
        $settings = self::getUserSettings();

        if (!empty($settings)) {
            return $settings['iibb_bank_tax'];
        } else {
            return false;
        }
    }

    /**
     * Obtiene el porcentaje de IVA para calcular automaticamente lo que se encesite
     * @return string 
     */
    public static function getUserIVATAX() {
        $settings = self::getUserSettings();

        if (!empty($settings)) {
            return $settings['iva_tax'];
        } else {
            return false;
        }
    }

    /**
     * Devuelve el nombre de la inmobiliaria
     * @return string 
     */
    public static function getBussinesName() {
        $settings = self::getUserSettings();

        if (!empty($settings)) {
            return $settings['name'];
        } else {
            return false;
        }
    }

    /**
     * Devuelve el email de la inmobiliaria
     * @return string 
     */
    public static function getBussinesEmail() {
        $settings = self::getUserSettings();

        if (!empty($settings)) {
            return $settings['email'];
        } else {
            return false;
        }
    }

    /**
     * Devuelve el email de la inmobiliaria
     * @return string 
     */
    public static function getReportsEmail() {
        $settings = self::getUserSettings();

        if (!empty($settings)) {
            return $settings['reports_email'];
        } else {
            return false;
        }
    }

    /**
     * Especifica si se genera un recibo para imprimir luego de cobrar un alquiler
     * @return int 
     */
    public static function printReceive(){
        $settings = self::getUserSettings();

        if (!empty($settings)) {
            return $settings['print_receive'];
        } else {
            return false;
        }
    }

    /**
     * Especifica si se permite que Rendiciones dejen en negativo la cuenta, por lo cual
     * se generara un prestamo de la Inmobiliaria para solventar la Rendicion en la cuenta sin fondos * en la cuenta del propietario
     * @return int 
     */
    public static function loanRendition(){
        $settings = self::getUserSettings();

        if (!empty($settings)) {
            return $settings['loan_rendition'];
        } else {
            return false;
        }
    }

    /**
     * Especifica si al generar un recibo por alquiler sera con su copia al lado en la misma hoja
     * @return int 
     */
    public static function printCopy(){
        $settings = self::getUserSettings();

        if (!empty($settings)) {
            return $settings['print_copy'];
        } else {
            return false;
        }
    }

    /**
     * Esoecifica si al crear un debito se generara un recibo para imprimir
     * @return int 
     */
    public static function printDebit(){
        $settings = self::getUserSettings();

        if (!empty($settings)) {
            return $settings['print_debit'];
        } else {
            return false;
        }
    }

    /**
     * Esoecifica si para devolver prestamos se utilizara una devolucion flexible o estricta.
     * La devolucion flexible permite devolver en cuotas, es decir no es necesario que el monto
     * del credito cubra completamente el saldo prestado para realizar la devolucion.
     * La devolucion estricta implica que el credito si o si debe cubrir el monto del prestamo
     * para realizarse la devolucion
     * @return int 
     */
    public static function returnLoanInDues(){
        $settings = self::getUserSettings();

        if (!empty($settings)) {
            return $settings['return_loan_in_dues'];
        } else {
            return false;
        }
    }

    public static function emailReceiveRenter(){
        $settings = self::getUserSettings();
        
        if (!empty($settings)) {
            return $settings['email_receive_renter'];
        } else {
            return false;
        }
    } 

}

?>

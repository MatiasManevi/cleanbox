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

}

?>

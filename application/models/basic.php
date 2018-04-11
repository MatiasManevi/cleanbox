<?php

/*
  Document   : basic_model
  Author     : manevi matias
 */

Class Basic extends CI_Model {

    function repairTables() {
        $tables = $this->db->query('SHOW TABLES IN cleanbox')->result_array();
        $database = $this->db->query('select database();')->row_array();
        $database = $database['database()'];

        foreach ($tables as $table) {
            $this->db->query('REPAIR TABLE `' . $table['Tables_in_' . $database] . '`');
        }
    }

    function save($table, $id_field, $data = array()) {
        if (!isset($data[$id_field]) || empty($data[$id_field])) {
            $this->db->insert($table, $data);
            return $this->db->insert_id();
        } else {
            $this->db->where($id_field, $data[$id_field]);
            $this->db->update($table, $data);
            return $data[$id_field];
        }
    }

    function get_auto($nombre = false, $tabla = false, $type = false) {
        $this->db->select("*");
        if ($tabla == 'proveedores_prop') {
            $tabla = 'proveedores';
        }
        $this->db->from($tabla);
        if ($tabla == 'cuentas_corrientes') {
            $this->db->like('cc_prop', $nombre, 'match');
        }
        if ($tabla == 'clientes') {
            $this->db->like('client_name', $nombre, 'match');
        }
        if ($tabla == 'providers_rols') {
            $this->db->like('rol', $nombre, 'match');
        }
        if ($tabla == 'conceptos') {
            $this->db->like('conc_desc', $nombre, 'match');

            if ($type == 'cc_varios') {
                // conceptos para servicios de contrato
                $this->db->like('conc_cc', $type, 'match');
                $this->db->like('conc_tipo', 'Entrada', 'match');
            } else if ($type != 'both') {
                // busca conceptos para seccion debitos o creditos
                $this->db->like('conc_tipo', $type, 'match');
            }
        }
        if ($tabla == 'propiedades') {
            $this->db->like('prop_dom', $nombre, 'match');
        }
        if ($tabla == 'proveedores') {
            $this->db->like('prov_name', $nombre, 'match');
        }
        if ($tabla == 'contratos') {
            $this->db->like('con_prop', $nombre, 'match');
        }
        $this->db->limit(10);
        return $this->db->get();
    }

    function get_all($table, $order = false, $ord = 'asc', $limit = false, $l = false) {
        if ($order)
            $this->db->order_by($order, $ord);
        if ($l)
            $this->db->limit($limit, $l);
        elseif ($limit)
            $this->db->limit($limit);
        return $this->db->get($table);
    }

    function get_where($table, $where_array, $order = false, $or = 'asc', $limit = false, $l = false) {
        if ($order)
            $this->db->order_by($order, $or);
        if ($l)
            $this->db->limit($limit, $l);
        elseif ($limit)
            $this->db->limit($limit);
        return $this->db->get_where($table, $where_array);
    }

    function like_and_where($table, $where_like = false, $where = false) {
        if($where_like){
            $this->db->like($where_like);
        }

        if($where){
            $this->db->where($where);
        }
        
        return $this->db->get($table);
    }

    function del($table, $id_field, $id) {
        return $this->db->delete($table, array($id_field => $id));
    }

    function query($sql) {
        return $this->db->query($sql);
    }

    function getAllServicesArray() {
        $servs = $this->db->query('SELECT DISTINCT serv_concepto FROM servicios')->result_array();
        $arr_servs = array();

        foreach ($servs as $serv) {
            array_push($arr_servs, $serv['serv_concepto']);
        }

        return $arr_servs;
    }

}
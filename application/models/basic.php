<?php

/*
  Document   : basic_model
  Created on : 04-may-2011, 10:46:14
  Author     : Rodrigo E. Torres
  Sobrelaweb Web Developer
  rtorres@sobrelaweb.com | torresrodrigoe@gmail.com
 */

Class Basic extends CI_Model {

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

    function get_auto($nombre = false, $tabla = false, $tipo = false) {
        $this->db->select("*");
        $this->db->from($tabla);
        if ($tabla == 'cuentas_corrientes') {
            $this->db->like('cc_prop', $nombre, 'match');
            // WHERE $nombre LIKE '%cc_prop% anda mejor que el AFTER, EL COMODIN VA A AMBOS LADOS
        }
        if ($tabla == 'clientes') {
            $this->db->like('client_name', $nombre, 'match');
            //el parametro after produce where client_name like $nombre%
        }
        if ($tabla == 'conceptos') {
            $this->db->like('conc_desc', $nombre, 'match');
            if ($tipo != false && $tipo != 'cc_varios') {
                $this->db->like('conc_tipo', $tipo, 'match');
            } else if ($tipo == 'cc_varios') {
                $this->db->like('conc_cc', $tipo, 'match');
                $this->db->like('conc_tipo', 'Salida', 'match');
            }
            //el parametro after produce where client_name like $nombre%
        }
        if ($tabla == 'propiedades') {
            $this->db->like('prop_dom', $nombre, 'match');
            //el parametro after produce where client_name like $nombre%
        }
        if ($tabla == 'contratos') {
            $this->db->like('con_prop', $nombre, 'match');
            //el parametro after produce where client_name like $nombre%
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

    function del($table, $id_field, $id) {
        $this->db->delete($table, array($id_field => $id));
    }

    function query($sql) {
        return $this->db->query($sql);
    }

}
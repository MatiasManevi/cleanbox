<?php

/*
  Document   : front
  Author     : Manevi A. Matias
  Web Developer
  manevimatias@gmail.com
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Front extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->CI = & get_instance();
        $this->load->model(array('basic'));
        $this->load->library(array('session'));
        $this->load->language('common');
        $response = array('error' => 0, 'success' => 0, 'js' => '', 'html' => '');
        $this->data['lang'] = $this->CI->config->item('language_abbr');
    }

    public function _remap($method, $params = array()) {
        if (method_exists($this, $method)) {
            return call_user_func_array(array($this, $method), $params);
        } elseif ($method == 'loadredir') {
            echo '<script>
          cad = location.toString();
          first = cad.split(\'#state=\')[1];
          state = first.split(\'&\')[0];
          second = first.split(\'&\')[1];
          token = second.split(\'access_token=\')[1];
          token = token.split(\'&\')[0];
          //alert(\'fbenter/\'+token+\'/\'+state);
          window.top.location.href = \'fbenter/\'+token+\'/\'+state;
          </script>';
            exit(0);
        } elseif ($method == 'home') {
            return $this->index();
        } elseif ($this->basic->get_where('sections', array('sect_uri' => $method))->num_rows > 0) {
            return $this->section($method);
        } else {
            show_404();
        }
    }

    public function index() {
        $this->data['uri'] = 'login';
        $this->load_similar_content('login');
        $this->load->view('default', $this->data);
    }

    function load_similar_content($section) {
        $this->data['uri'] = $section;
        $this->data['section'] = $this->basic->get_where('sections', array('sect_uri' => $section))->row_array();
        $this->data['head'] = $this->load->view('partials/head', $this->data, TRUE);
        $this->data['header'] = $this->load->view('partials/header', $this->data, TRUE);
        if ($section == 'login') {
            $this->data['header'] = null;
            $this->data['content'] = $this->load->view('partials/login', $this->data, TRUE);
        }
        $this->data['footer'] = $this->load->view('partials/footer', $this->data, TRUE);
    }

    public function section($uri) {
        $this->data['uri'] = $uri;
        $this->load_similar_content($uri);
        $this->load->view('default', $this->data);
    }

   
    function login($msg = '') {
        $this->data['head'] = $this->load->view('manager/partials/head', '', TRUE);
        $this->data['msg'] = $msg;
        $this->load->view('manager/login', $this->data);
    }

    function logout() {
        $this->session->sess_destroy();
        $this->login();
    }

    function make_login() {
        $this->CI = & get_instance();

        if (trim($_POST['user']) == '' OR trim($_POST['password']) == '') {
            $err = 'Escriba su usuario y contraseña';
            $this->login($err);
            return false;
        }
        if ($this->session->userdata('username') == $_POST['user']) {
            redirect('manager/index', 'location', 301);
            return true;
        }
        $this->CI->db->where('username', $_POST['user']);
        $query = $this->CI->db->get_where('man_users');

        if ($query->num_rows() > 0) {
            $row = $query->row_array();

            if ($_POST['password'] != $row['password']) {
                $err = 'Error de contraseña';
                $this->login($err);
                return false;
            }
            $this->session->sess_destroy();
            $this->session->sess_create();
            unset($row['password']);
            $this->session->set_userdata($row);
            $this->session->set_userdata(array('logged_in' => true));

            redirect('manager/index', 'location', 301);
        } else {
            $err = 'El usuario no existe';
            $this->login($err);
            return false;
        }
    }
    function mail_contact($direcciones) {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<p>', '</p>');
        $this->form_validation->set_rules('email', t('email'), "required|trim|valid_email");
        $this->form_validation->set_rules('nombre', t('name'), "required|trim");
        $this->form_validation->set_rules('consulta', t('message'), "required|trim");

        if ($this->form_validation->run()) {

//            $msg = "<h1>Nuevo Mensaje de contacto</h1><br/> Sent : " . date('m-d-Y') . "<br/>";
//            $msg .= "Remitente: " . $this->input->post('nombre') . "<br/> Telefono: " . $this->input->post('telefono') . "<br/>  e-mail: (" . $this->input->post('email') . ")  <br/><br/><br/>";
//            $msg .= "<i>Mensaje</i> <br/>" . $this->input->post('consulta') . "<br/><br/><a href=\"mailto:" . $this->input->post('email') . " \"><b>Responder</b> </a>";
            $msg = $this->load->view('manager/mail_marketing/template', '', TRUE);

//            $email = $this->basic->get_where('configs', array('conf_type' => 'datos_email'))->row();
            $contact_send = array('to' => $direcciones, //'manevimatias@gmail.com' $email->conf_content
                'subject' => 'Nuevo Mensaje de contacto',
                'message' => $msg);

            if ($this->send_email($contact_send)) {
                $response['html'] = 'Mensaje enviado exitosamente';
                echo json_encode($response);
            } else {
                $response['html'] = 'Ocurrió un error al enviar el mensaje, intente nuevamente';
                $response['error'] = 1;
                echo json_encode($response);
            }
        } else {
            $response['html'] = validation_errors();
            $response['error'] = 1;
            echo json_encode($response);
        }
    }

}


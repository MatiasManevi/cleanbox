<?php

/*
  Document   : manager
  Author     : Manevi A. Matias
  Web Developer
  manevimatias@gmail.com
 */

class Manager extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->form_validation->set_error_delimiters('', '\\n');
        if (!$this->session->userdata('logged_in') && strpos(current_url(), 'login') === FALSE) {
            echo "<script>window.top.location.href = '" . site_url('login') . "';</script>";
        }
    }

    /* Inicio */

    function index() {
        $this->crear_caja();
        $this->data['sections_list'] = $this->basic->get_all('sections', 'sect_name_es');
        $this->data['section'] = $this->basic->get_where('sections', array('sect_uri' => 'home'))->row();
        $this->data['head'] = $this->load->view('partials/head', '', TRUE);
        $this->data['menu'] = $this->load->view('partials/header', $this->data, TRUE);
        $this->cargar_vista_caja();
        $this->vencer_contratos();
        $this->load->view('manager/man_view', $this->data);
    }

    function eliminar_vacios() {
        $contratos = $this->basic->get_where('contratos', array());
        foreach ($contratos->result_array() as $row) {
            if ($row['con_prop'] == '' && $row['con_inq'] == '' && $row['con_tipo'] == '') {
                $this->basic->del('contratos', 'con_id', $row['con_id']);
            }
        }
        $clientes = $this->basic->get_where('clientes', array());
        foreach ($clientes->result_array() as $row) {
            if ($row['client_name'] == '' && $row['client_email'] == '' && $row['client_tel'] == '') {
                $this->basic->del('clientes', 'client_id', $row['client_id']);
            }
        }
        $cuentas = $this->basic->get_where('cuentas_corrientes', array());
        foreach ($cuentas->result_array() as $row) {
            if ($row['cc_prop'] == '' && $row['cc_saldo'] == 0 && $row['cc_varios'] == 0) {
                $this->basic->del('cuentas_corrientes', 'cc_id', $row['cc_id']);
            }
        }
        $props = $this->basic->get_where('propiedades', array());
        foreach ($props->result_array() as $row) {
            if ($row['prop_dom'] == '' && $row['prop_prop'] == '' && $row['prop_enabled'] == 0 && $row['prop_contrato_vigente'] == 'Libre') {
                $this->basic->del('propiedades', 'prop_id', $row['prop_id']);
            }
        }
    }

    function vencer_contratos() {
        $this->eliminar_vacios();
        $contratos = $this->basic->get_where('contratos', array('con_enabled' => 1));
        foreach ($contratos->result_array() as $con) {
            if (($con['con_venc'] != '' && $con['con_prop'] != '' && $con['con_inq'] != '' && $con['con_tipo'] != '' && $con['con_enabled'] != 0)) {
                $agregar = $this->comp_fecha_sup(date('d-m-Y'), $con['con_venc']);
                if ($agregar == '1') {
                    $domicilio = $this->basic->get_where('propiedades', array('prop_dom' => $con['con_domi'], 'prop_prop' => $con['con_prop']))->row_array();
                    $domicilio['prop_contrato_vigente'] = 'Libre';
                    $con['con_enabled'] = 0;
                    $con['con_motivo'] = 'Vencido';
                    $this->basic->save('contratos', 'con_id', $con);
                    $this->basic->save('propiedades', 'prop_id', $domicilio);
                }
            }
        }
    }

    function comp_fecha_sup($desde, $fecha) {
        $dagre = 0;
        $desde = explode('-', $desde);
        $f_venc = explode('-', $fecha);
        $resta_ano_des_desde = $f_venc[2] - $desde[2];
        $resta_mes_des_desde = $f_venc[1] - $desde[1];
        $resta_dia_des_desde = $f_venc[0] - $desde[0];
        //comprara fecha inferior
        if ($resta_ano_des_desde < 0) {
            $dagre = 1;
        } else {
            if ($resta_ano_des_desde == 0) {
                if ($resta_mes_des_desde < 0) {
                    $dagre = 1;
                } else {
                    if ($resta_mes_des_desde == 0) {
                        if ($resta_dia_des_desde <= 0) {
                            $dagre = 1;
                        }
                    }
                }
            }
        }
        return $dagre;
    }

    function hacercc() {
        $contratos = $this->basic->get_where('contratos', array('con_enabled' => 1));
        $existe_cc1 = false;
        foreach ($contratos->result_array() as $con) {
            $cuentas = $this->basic->get_all('cuentas_corrientes');
            foreach ($cuentas->result_array() as $cc) {
                if ($cc['cc_prop'] == $con['con_prop']) {
                    $existe_cc1 = true;
                }
            }
            if (!$existe_cc1) {
                //si no existe se crea la cc
                $cc = array(
                    'cc_prop' => strtoupper($con['con_prop']),
                    'cc_saldo' => 0,
                    'cc_varios' => 0
                );
                $this->basic->save('cuentas_corrientes', 'cc_id', $cc);
            }
            $existe_cc = false;
            $existe_inq = false;
            $clientes = $this->basic->get_all('clientes');
            foreach ($clientes->result_array() as $cli) {
                if ($cli['client_name'] == $con['con_prop']) {
                    $existe_cc = true;
                }
                if ($cli['client_name'] == $con['con_inq']) {
                    $existe_inq = true;
                }
            }
            if (!$existe_inq) {
                //si no existe se crea el inq
                $inq = array(
                    'client_name' => strtoupper($con['con_inq']),
                    'client_email' => '',
                    'client_tel' => ''
                );
                $this->basic->save('clientes', 'client_id', $inq);
            }
            if (!$existe_cc) {
                //si no existe se crea la cc
                $inq = array(
                    'client_name' => strtoupper($con['con_prop']),
                    'client_email' => '',
                    'client_tel' => ''
                );
                $this->basic->save('clientes', 'client_id', $inq);
            }
        }
    }

    function cargar_vista_caja() {
        /* Vista de contenido gral de caja y con que comenzo el dia */
        $caja_fuerte = $this->basic->get_where('cuentas_corrientes', array('cc_prop' => 'CAJA FUERTE'))->row_array();
        $this->data['caja_fuerte'] = $caja_fuerte['cc_saldo'];
        $this->data['dia'] = $this->basic->get_where('caja_comienza', array('caj_dia' => date('d'), 'caj_mes' => date('m'), 'caj_ano' => date('Y')))->row_array();
        $this->data['mes'] = $this->basic->get_where('mensuales', array('men_mes' => date('m'), 'men_ano' => date('Y')))->row_array();
        $this->data['mensual_progresivo'] = $this->data['mes']['men_creditos'] - $this->data['mes']['men_debitos'];
        $this->data['comments'] = $this->load->view('manager/comments', $this->data, TRUE);
        $this->data['caja'] = $this->load->view('manager/caja_comienza', $this->data, TRUE);
        $this->data['codes'] = $this->load->view('manager/codes', $this->data, TRUE);
    }

    function crear_caja() {
        $caja = false;
        $this->caja_comienza();
        $caja = $this->basic->get_where('mensuales', array('men_mes' => date('m'), 'men_ano' => date('Y')))->row_array();
        if ($caja == false) {
            $caja = $this->basic->get_where('caja_comienza', array('caj_dia' => date('d'), 'caj_mes' => date('m'), 'caj_ano' => date('Y')))->row_array();
            $caja = array(
                'men_mes' => date('m'),
                'men_ano' => date('Y'),
                'men_creditos' => $caja['caj_saldo'],
                'men_debitos' => 0
            );
            $caja['men_date'] = Date('d-m-Y  h:i:s A');
            $caja['men_info'] = '';
            $this->basic->save('mensuales', 'men_id', $caja);
        }
    }

    function caja_comienza() {
        //creditos - debitos, desde comienzo de mes hasta el dia anterior al actual
        $caja = false;
        $creditos_bancarios = $this->basic->get_where('creditos', array('cred_tipo_trans' => 'Bancaria'));
        $debitos_bancarios = $this->basic->get_where('debitos', array('deb_tipo_trans' => 'Bancaria'));
        $monto_comienza = 0;
        $caja = $this->basic->get_where('caja_comienza', array('caj_dia' => date('d'), 'caj_mes' => date('m'), 'caj_ano' => date('Y')))->row_array();
        if ($caja == false) {
            $caja_del_mes = $this->basic->get_where('caja_comienza', array('caj_mes' => date('m')));
            if ($caja_del_mes->num_rows() > 0) {
                //ya hay cajas del mes    
                $caja_mensual = $this->basic->get_where('mensuales', array('men_mes' => Date('m'), 'men_ano' => Date('Y')))->row_array();
                $monto_comienza = $caja_mensual['men_creditos'] - $caja_mensual['men_debitos'];
            } else {
                //no hay cajas del mes
                $mes_anterior = date('m') - 1;
                $ano = Date('Y');
                if ($mes_anterior == 0) {
                    $mes_anterior = 12;
                    $ano = $ano - 1;
                }
                $COM_BANC_MES = 0; //movim bancarios del mes pasado
                foreach ($creditos_bancarios->result_array() as $row) {
                    $agregar = $this->comp_fecha($row['cred_fecha'], '01-' . $mes_anterior . '-' . $ano, '31' . '-' . $mes_anterior . '-' . $ano);
                    if ($agregar == '11') {
                        if ($row['cred_concepto'] != 'Gestion de Cobro' && $row['cred_concepto'] != 'Gestion de Cobro Sobre Intereses') {
                            $COM_BANC_MES += $row['cred_monto'];
                        }
                    }
                }
                foreach ($debitos_bancarios->result_array() as $row) {
                    $agregar = $this->comp_fecha($row['deb_fecha'], '01-' . $mes_anterior . '-' . $ano, '31' . '-' . $mes_anterior . '-' . $ano);
                    if ($agregar == '11') {
                        if ($row['deb_concepto'] != 'Gestion de Cobro' && $row['deb_concepto'] != 'Gestion de Cobro Sobre Intereses') {
                            $COM_BANC_MES -= $row['deb_monto'];
                        }
                    }
                }

                $caja_mensual = $this->basic->get_where('mensuales', array('men_mes' => $mes_anterior, 'men_ano' => $ano))->row_array();
                $monto_comienza = $caja_mensual['men_creditos'] - $caja_mensual['men_debitos'] - $COM_BANC_MES;
            }
            $caja = array(
                'caj_dia' => date('d'),
                'caj_mes' => date('m'),
                'caj_ano' => date('Y'),
                'caj_saldo' => $monto_comienza
            );
            $this->basic->save('caja_comienza', 'caj_id', $caja);
        } else {
            
        }
    }

    function liquidar() {
        $liquidar = $this->basic->get_all('liquidado')->row_array();
        if (date('d') == 8 || date('d') == 1 || date('d') == 10 || date('d') == 11) {
            $propietarios = $this->basic->get_all('cuentas_corrientes');
            foreach ($propietarios->result_array() as $prop) {
                if ($prop['cc_prop'] != 'CAJA FUERTE' && $prop['cc_prop'] != 'INMOBILIARIA' && strpos($prop['cc_prop'], 'LOTEO') === FALSE) {
                    if ($prop['cc_varios'] < 0) {
                        $devuelve = $prop['cc_varios'] * (-1);
                        $prop['cc_saldo'] -= $devuelve;

// Acredito la suma en la cuenta de varios para cancelarla
                        $prop['cc_varios'] += $devuelve;

//Devolviendo la cantidad que se este recuperando a la cuenta de Rima                                            

                        $this->basic->save('cuentas_corrientes', 'cc_id', $prop);
                    }
                }
            }

            $liquidar['liq_date'] = date('d-m-Y');
            $this->basic->save('liquidado', 'liq_id', $liquidar);
        }
    }

    function pasar_caja_diaria($saldo) {
        $cc_fuerte = $this->basic->get_where('cuentas_corrientes', array('cc_prop' => 'CAJA FUERTE'))->row_array();
        $caja = $this->basic->get_where('mensuales', array('men_mes' => date('m'), 'men_ano' => date('Y')))->row_array();
        $transfer = array(
            'transf_fecha' => Date('d-m-Y'),
            'transf_monto' => $saldo,
            'transf_tipo' => 'Debitado Caja Fuerte - Acreditado Caja Diaria'
        );
        $caja['men_debitos'] -= $saldo;
        $cc_fuerte['cc_saldo'] -= $saldo;
        $calc = $caja['men_creditos'] - $caja['men_debitos'];
        $response['texto'] = '$ ' . $calc;
        $response['textof'] = '$ ' . $cc_fuerte['cc_saldo'];
        $response['js'] = "$('#mensual_progresivo').html(R.texto);$('#cf').html(R.textof);";
        $this->basic->save('cuentas_corrientes', 'cc_id', $cc_fuerte);
        $caja['men_date'] = Date('d-m-Y  h:i:s A');
        $caja['men_info'] = 'Transf a diaria $ ' . $saldo;
        $this->basic->save('mensuales', 'men_id', $caja);
        $this->basic->save('transferencias', 'transf_id', $transfer);
        echo json_encode($response);
    }

    function pasar_caja_fuerte($saldo) {
        $transfer = array(
            'transf_fecha' => Date('d-m-Y'),
            'transf_monto' => $saldo,
            'transf_tipo' => 'Debitado Caja Daria - Acreditado Caja Fuerte'
        );
        $cc_fuerte = $this->basic->get_where('cuentas_corrientes', array('cc_prop' => 'CAJA FUERTE'))->row_array();
        $caja = $this->basic->get_where('mensuales', array('men_mes' => date('m'), 'men_ano' => date('Y')))->row_array();
        $caja['men_debitos'] += $saldo;
        $cc_fuerte['cc_saldo'] += $saldo;
        $calc = $caja['men_creditos'] - $caja['men_debitos'];
        $response['texto'] = '$ ' . $calc;
        $response['textof'] = '$ ' . $cc_fuerte['cc_saldo'];
        $response['js'] = "$('#mensual_progresivo').html(R.texto);$('#cf').html(R.textof);";
        $this->basic->save('cuentas_corrientes', 'cc_id', $cc_fuerte);
        $caja['men_date'] = Date('d-m-Y  h:i:s A');
        $caja['men_info'] = 'Transf a fuerte $ ' . $saldo;
        $this->basic->save('mensuales', 'men_id', $caja);
        $this->basic->save('transferencias', 'transf_id', $transfer);
        echo json_encode($response);
    }

// Codigos de autorizacion

    function generarCodigo($longitud) {
        $key = '';
        $pattern = '1234567890abcdefghijklmnopqrstuvwxyz';
        $max = strlen($pattern) - 1;
        for ($i = 0; $i < $longitud; $i++)
            $key .= $pattern{mt_rand(0, $max)};
        $codigo = array(
            'code_code' => $key
        );
        $this->basic->save('codes', 'code_id', $codigo);
        $response['html'] = $codigo['code_code'];
        $response['js'] = "$('#codigo').val(R.html)";
        echo json_encode($response);
    }

    function vaciar_codes() {
        $codigos = $this->basic->get_all('codes');
        foreach ($codigos->result_array() as $row) {
            $this->basic->del('codes', 'code_id', $row['code_id']);
        }
    }

// Codigos de autorizacion
//
    //MAILING

    function mail_aviso_prop($credito, $interes = false, $dias = false, $gestion = false, $iva_alq = false) {
        $prop = $this->basic->get_where('clientes', array('client_name' => $credito['cred_cc']))->row_array();
        if (filter_var($prop['client_email'], FILTER_VALIDATE_EMAIL) && strpos($credito['cred_concepto'], 'Loteo') !== false || strpos($credito['cred_concepto'], 'Alquiler') !== false) {
            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset = iso - 8859 - 1' . "\r\n";
            $headers .= 'From: andresdavina@gmail.com' . "\r\n";
            $monto = $credito['cred_monto'] - ($credito['cred_monto'] * $gestion);
            $msg = '<h1>Ya puede pasar por Andes Daviña Inmobiliaria a cobrar su alquiler</h1>';
            $msg .= "<br>";
            $msg .= "<h2>Alquiler pagado por contrato con:</h2> " . $credito['cred_depositante'];
            $msg .= "<br>";
            $msg .= "<h2>Mes Abonado: </h2>" . $credito['cred_mes_alq'] . ". <h2>Fecha de pago: </h2>" . $credito['cred_fecha'];
            $msg .= "<br>";
            $msg .= "<h2>Monto Abonado: </h2>$ " . $monto;
            if ($interes != false) {
                $msg .= "<br>";
                $msg .= "<h2>Monto de Intereses Abonados por " . $dias . " dia/s de mora: </h2>$ " . $interes;
            }
            if ($iva_alq != false) {
                $msg .= "<br>";
                $msg .= "<h2>Monto Abonados IVA/Alquiler: </h2>$ " . $iva_alq;
            }
            mail($prop['client_email'], 'Han Pagado su Alquiler!', $msg, $headers);
        }
    }

//MAILING

    function load_similar_content($section) {
        $this->data['uri'] = $section;
        $this->data['section'] = $this->basic->get_where('sections', array('sect_uri' => $section))->row_array();
        $this->data['head'] = $this->load->view('partials/head', $this->data, TRUE);
        $this->data['header'] = $this->load->view('partials/header', $this->data, TRUE);
        if ($section == 'login') {
            $this->data['header'] = null;
            $this->data['content'] = $this->load->view('manager/login', $this->data, TRUE);
        }
        $this->data['footer'] = $this->load->view('partials/footer', $this->data, TRUE);
    }

    public

    function section($uri) {
        $this->data['uri'] = $uri;
        $this->load_similar_content($uri);
        $this->load->view('default', $this->data);
    }

    /* Usuarios */

    function admin() {
        $this->data['admin'] = $this->basic->get_where('man_users', array('username' => 'admin'))->row();
        $this->data['users'] = $this->basic->get_all('man_users');
        $this->load_similar_content('admin');
        $this->data['content'] = $this->load->view('manager/users/users', $this->data, TRUE);
        $this->load->view('default', $this->data);
    }

    function del_user($id) {
        $user = $this->basic->get_where('man_users', array('id' => $id));
        if ($user->num_rows > 0) {
            $this->basic->del('man_users', 'id', $id);
            $response['html'] = t('Usuario eliminado');
            echo json_encode($response);
        }
    }

    function redirect_debitos($prop) {
        $this->data['prop'] = urldecode($prop);
        $propietario = $this->basic->get_where('cuentas_corrientes', array('cc_prop' => $this->data['prop']))->row();
        $this->data['prop_id'] = $propietario->cc_id;
        $concepto = $this->basic->get_where('conceptos', array('conc_desc' => 'Rendicion'))->row();
        $this->data['concepto'] = $concepto->conc_desc;
        $this->data['concepto_id'] = $concepto->conc_id;
        $this->data['concepto_control'] = $concepto->conc_control;
        $response['html'] = $this->load->view('manager/transacciones/debitos', $this->data, TRUE);
        echo json_encode($response);
    }

    function save_user() {
        $this->form_validation->set_rules('username', 'Nombre Usuario', "required|trim");
        $this->form_validation->set_rules('password', 'Clave', "required|trim");
        if ($this->form_validation->run() == TRUE) {
            if (!$this->input->post('id'))
                $this->input->post();
            $this->basic->save('man_users', 'id', $this->input->post());
            $response['js'] = 'window.top.location.href="' . site_url('admin') . '"';
            echo json_encode($response);
        } else {
            $response['js'] = "$('#com_display').css('display','block');$('#com_display').addClass('alert-error');$('#com_display').css('width','229px')";
            $response['html'] = validation_errors();
            $response['error'] = '1';
            echo json_encode($response);
        }
    }

    function login($msg = '') {
        $this->data['head'] = $this->load->view('partials/head', '', TRUE);
        $this->data['msg'] = $msg;
        $this->load->view('manager/users/login', $this->data);
    }

    function logout() {
        $this->session->sess_destroy();
        $this->vaciar_codes();
        $this->login();
    }

    function make_login() {
        $this->CI = & get_instance();

//        if (date('d-m-Y') == '10-02-2015') {
//            $err = 'El periodo de prueba del sistema ha caducado!';
//            $this->login($err);
//            return false;
//        }
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
            $this->vaciar_codes();
            redirect('manager/index', 'location', 301);
        } else {
            $err = 'El usuario no existe';
            $this->login($err);
            return false;
        }
    }

    function load_edit_users($id = false) {
        $this->data['row'] = null;
        if ($id) {
            $this->data['row'] = $this->basic->get_where('man_users', array('id' => $id))->row();
            $this->data['id'] = $id;
        }
        $response['html'] = $this->load->view('manager/users/form_user', $this->data, TRUE);
        $response['js'] = "$('.contenedor_centro').css('width','95%');";
        echo json_encode($response);
    }

    function save_password() {
        $user = $this->basic->get_where('man_users', array('username' => 'admin'))->row();
        if ($user->password == $this->input->post('actual')) {
            if ($this->input->post('nueva') == $this->input->post('nueva_c')) {
                $data = array(
                    'id' => $user->id,
                    'username' => $user->username,
                    'password' => $this->input->post('nueva'),
                );
                if ($this->basic->save('man_users', 'id', $data)) {
                    $response['html'] = 'La Contraseña ha sido modificada';
                    $response['js'] = "$('.msg_display').css('margin',0);$('.msg_display').css('display','table');$('.msg_display').addClass('alert-success').removeClass('alert-error')";
                } else {
                    $response['html'] = 'Ocurrió un error al intentar modificar la contraseña, intente nuevamente';
                    $response['error'] = 1;
                }
            } else {
                $response['js'] = "$('.msg_display').css('margin',0);$('.msg_display').css('display','table');$('.msg_display').addClass('alert-error').removeClass('alert-success')";
                $response['html'] = 'Las contraseñas nuevas no coinciden';
            }
        } else {
            $response['js'] = "$('.msg_display').css('margin',0);$('.msg_display').css('display','table');$('.msg_display').addClass('alert-error').removeClass('alert-success')";
            $response['html'] = 'Contraseña actual incorrecta';
        }
        echo json_encode($response);
    }

    /* Fin Users */

    function to_del($id, $table, $table_pk, $table_file = false, $thumbs = false) {
        if ($table_file != false) {
            $file_path = './' . urldecode($this->input->post('path')) . '/';
            $file = $this->basic->get_where($table, array($table_pk => $id))->row_array();
            if (is_file($file_path . $file[$table_file])) {
                unlink($file_path . $file[$table_file]);
                if ($thumbs)
                    unlink($file_path . 'thumbs/' . $file[$table_file]);
            }
        }
        $this->basic->del($table, $table_pk, $id);
        echo 'Eliminado';
    }

    function to_save($table, $table_pk) {
        $this->basic->save($table, $table_pk, $this->input->post());
    }

    function to_edit($id, $table, $table_pk, $partial) {
        $data['edit'] = $this->basic->get_where($table, array($table_pk => $id))->row();
        $this->load->view('manager/partials/' . $partial, $data);
    }

    /* Transferencias */

    function transferencias() {
        $this->load_similar_content('transferencias');
        $this->data['transferencias'] = $this->basic->get_where('transferencias', array(), 'transf_id', 'desc', '50');
        $this->data['lista'] = $this->load->view('manager/transferencias/lista', $this->data, TRUE);
        $this->data['content'] = $this->load->view('manager/transferencias/transferencias', $this->data, TRUE);
        $this->load->view('default', $this->data);
    }

    function filtrar_transf($desde, $hasta) {
        $comentarios = $this->basic->get_all('transferencias');
        $agregar = 0;
        $array = array();
        foreach ($comentarios->result_array() as $row) {
            $agregar = $this->comp_fecha($row['transf_fecha'], $desde, $hasta);
            if ($agregar == '11') {
                array_push($array, $row);
                $agregar = 0;
            }
        }
        $this->data['transferencias'] = $array;
        $response['html'] = $this->load->view('manager/transferencias/buscar_fila_transferencias_arr', $this->data, TRUE);
        echo json_encode($response);
    }

    /* Transferencias */

    /* Contratos */

    function contratos() {
        $this->data['segunda_solapa'] = 0;
        $this->load_similar_content('contratos');
        $this->eliminar_vacios();
        $this->data['contratos'] = $this->basic->get_where('contratos', array(), 'con_prop', '');
        $this->data['contratos_vigentes'] = 0;
        foreach ($this->data['contratos']->result_array() as $row) {
            if ($row['con_enabled'] == 1) {
                $this->data['contratos_vigentes']++;
            }
        }
        $this->data['lista'] = $this->load->view('manager/contratos/lista', $this->data, TRUE);
        $this->data['content'] = $this->load->view('manager/contratos/contratos', $this->data, TRUE);
        $this->load->view('default', $this->data);
    }

    function load_edit_contratos($id = false) {
        $this->data['id'] = $id;
        $this->data['contratos'] = $this->basic->get_where('contratos', array(), 'con_prop');
        $this->data['contratos_vigentes'] = 0;
        foreach ($this->data['contratos']->result_array() as $row) {
            if ($row['con_enabled'] == 1) {
                $this->data['contratos_vigentes']++;
            }
        }
        $this->data['lista'] = $this->load->view('manager/contratos/lista', $this->data, TRUE);
        if ($id) {
            $contrato = $this->basic->get_where('contratos', array('con_id' => $id))->row_array();
            $this->data['periodos'] = $this->basic->get_where('periodos', array('per_contrato' => $id), 'per_id');
            if (strpos($contrato['con_tipo'], 'Alquiler') !== FALSE) {
                $this->data['servicios'] = $this->basic->get_where('servicios', array('serv_contrato' => $id), 'serv_id');
                $i = 0;
                foreach ($this->data['servicios']->result_array() as $row) {
                    $i++;
                    $this->data['concepto'] = false;
                    $this->data['concepto'] = $this->basic->get_where('conceptos', array('conc_desc' => $row['serv_concepto']))->row_array();
                    $this->data['num'] = $i;
                    $this->data['servicio'] = $row;
                    $this->data['block_servicio' . $i] = $this->load->view('manager/contratos/textloaders/block_servicio', $this->data, TRUE);
                }
                $this->data['num_servicios'] = $i;
                $this->data['servicios_loader'] = $this->load->view('manager/contratos/servicios_loader', $this->data, TRUE);
            }
            $i = 0;
            foreach ($this->data['periodos']->result_array() as $row) {
                $i++;
                $this->data['contrato'] = $contrato;
                $this->data['num'] = $i;
                $this->data['periodo'] = $row;
                $this->data['block_periodo' . $i] = $this->load->view('manager/contratos/textloaders/block_periodo', $this->data, TRUE);
            }
            $this->data['num_periodos'] = $i;
            $this->data['periodos_loader'] = $this->load->view('manager/contratos/periodos_loader', $this->data, TRUE);
            $this->data['row'] = $this->basic->get_where('contratos', array('con_id' => $id))->row();
            $response['js'] = "$('add_op').css('display','none');$('.contenedor_centro').css('width','95%');";
            $response['html'] = $this->load->view('manager/contratos/contratos', $this->data, TRUE);
        }
        $response['js'] = "$('.contenedor_centro').css('width','95%');";
        echo json_encode($response);
    }

    function del_contratos($id) {
        $con = $this->basic->get_where('contratos', array('con_id' => $id));
        if ($con->num_rows > 0) {
            $this->basic->del('contratos', 'con_id', $id);
            $this->basic->del('servicios', 'serv_contrato', $id);
            $this->basic->del('periodos', 'per_contrato', $id);
            $response['html'] = t('Contrato eliminado');
            echo json_encode($response);
        }
    }

    function save_contratos() {
        $cons = $this->basic->get_where('contratos', array(), 'con_prop', '');
        $this->data['contratos'] = $cons;
        $this->data['contratos_vigentes'] = 0;
        foreach ($cons->result_array() as $row) {
            if ($row['con_enabled'] == 1) {
                $this->data['contratos_vigentes']++;
            }
        }
        $servicios = $this->input->post('cant_bloques');
        /* Controlo que esten bien cargados los conceptos */
        $todo_bien = true;
        $noload = array();
        for ($x = 1; $x <= $servicios; $x++) {
            if ($this->input->post('conc_id_' . $x) == 0) {
                $todo_bien = false;
                $noload[] = $this->input->post('servicio' . $x);
            }
        }
        $this->form_validation->set_rules('con_prop', 'Propietario', "required|trim");
        $this->form_validation->set_rules('con_inq', 'Inquilino', "required|trim");
        $this->form_validation->set_rules('con_venc', 'Fecha Vencimiento', "required|trim");
        $this->form_validation->set_rules('con_tipo', 'Tipo de Contrato', "required|trim");
        $this->form_validation->set_rules('con_tolerancia', 'Tolerancia', "required|trim");
        $this->form_validation->set_rules('con_porc', 'Porcentaje Gestion de Cobro', "required|trim");
        $this->form_validation->set_rules('con_punitorio', 'Porcentaje Interes Punitorio', "required|trim");
        $this->form_validation->set_rules('con_domi', 'Domicilio Inmueble', "required|trim");
        $this->form_validation->set_rules('con_gar1', 'Garante (al menos uno)', "required|trim");
        if ($todo_bien) {
            if ($this->form_validation->run() == TRUE) {
                if (!$this->input->post('con_id'))
                    $this->input->post();
                $contrato = array(
                    'con_prop' => strtoupper($this->input->post('con_prop')),
                    'con_inq' => strtoupper($this->input->post('con_inq')),
                    'con_id' => $this->input->post('con_id'),
                    'con_venc' => $this->input->post('con_venc'),
                    'con_tipo' => $this->input->post('con_tipo'),
                    'con_iva' => $this->input->post('con_iva'),
                    'con_domi' => strtoupper($this->input->post('con_domi')),
                    'con_iva_alq' => $this->input->post('con_iva_alq'),
                    'con_porc' => $this->input->post('con_porc'),
                    'con_gar1' => strtoupper($this->input->post('con_gar1')),
                    'con_gar2' => strtoupper($this->input->post('con_gar2')),
                    'con_motivo' => $this->input->post('con_motivo'),
                    'con_punitorio' => $this->input->post('con_punitorio'),
                    'con_tolerancia' => $this->input->post('con_tolerancia'),
                    'con_enabled' => $this->input->post('con_enabled')
                );
                //Si no existen las partes y el inmueble, se crean, y al propietario ya se le asigna una cta cte
                $gar1 = false;
                $gar2 = false;
                $inq = false;
                $domicilio = false;
                $prop_ca = false;
                $prop_cl = false;
                $inq = $this->basic->get_where('clientes', array('client_name' => $this->input->post('con_inq')))->row_array();
                $gar1 = $this->basic->get_where('clientes', array('client_name' => $this->input->post('con_gar1')))->row_array();
                $gar2 = $this->basic->get_where('clientes', array('client_name' => $this->input->post('con_gar2')))->row_array();
                $prop_cl = $this->basic->get_where('clientes', array('client_name' => $this->input->post('con_prop')))->row_array();
                $prop_ca = $this->basic->get_where('cuentas_corrientes', array('cc_prop' => $this->input->post('con_prop')))->row_array();
                if ($gar1 == false) {
                    $gar1 = array(
                        'client_name' => strtoupper($this->input->post('con_gar1')),
                        'client_categoria' => 'Garante',
                    );
                    $this->basic->save('clientes', 'client_id', $gar1);
                }
                if ($gar2 == false) {
                    $gar2 = array(
                        'client_name' => strtoupper($this->input->post('con_gar2')),
                        'client_categoria' => 'Garante',
                    );
                    $this->basic->save('clientes', 'client_id', $gar2);
                }
                if ($inq == false) {
                    $inq = array(
                        'client_name' => strtoupper($this->input->post('con_inq')),
                        'client_categoria' => 'Inquilino',
                    );
                    $this->basic->save('clientes', 'client_id', $inq);
                }
                if ($prop_cl == false) {
                    $prop_cl = array(
                        'client_name' => strtoupper($this->input->post('con_prop')),
                        'client_categoria' => 'Propietario',
                    );
                    $this->basic->save('clientes', 'client_id', $prop_cl);
                }
                if ($prop_ca == false) {
                    $prop_ca = array(
                        'cc_prop' => strtoupper($this->input->post('con_prop')),
                        'cc_saldo' => 0,
                        'cc_varios' => 0
                    );
                    $this->basic->save('cuentas_corrientes', 'cc_id', $prop_ca);
                }
                $domicilio = $this->basic->get_where('propiedades', array('prop_dom' => $this->input->post('con_domi'), 'prop_prop' => $this->input->post('con_prop')))->row_array();
                if ($domicilio == false) {
                    $domicilio = array(
                        'prop_dom' => strtoupper($this->input->post('con_domi')),
                        'prop_prop' => strtoupper($this->input->post('con_prop')),
                        'prop_contrato_vigente' => strtoupper($this->input->post('con_inq')),
                        'prop_enabled' => 1
                    );
                    $this->basic->save('propiedades', 'prop_id', $domicilio);
                }
                $this->transferir_sena($contrato['con_prop'], $contrato['con_inq']);
                $periodos = $this->input->post('cant_bloques_periodo');

                $con_id = $this->basic->save('contratos', 'con_id', $contrato);
                /* Creo los Periodos del contrato */
                for ($x = 1; $x <= $periodos; $x++) {
                    if ($this->input->post('per_id_' . $x) != '0') {
                        $periodo = array(
                            'per_id' => $this->input->post('per_id_' . $x),
                            'per_contrato' => $con_id,
                            'per_inicio' => $this->input->post('periodo_i' . $x),
                            'per_fin' => $this->input->post('periodo_f' . $x),
                            'per_monto' => $this->input->post('monto' . $x),
                            'per_iva' => $this->input->post('iva' . $x)
                        );
                    } else {
                        $periodo = array(
                            'per_contrato' => $con_id,
                            'per_inicio' => $this->input->post('periodo_i' . $x),
                            'per_fin' => $this->input->post('periodo_f' . $x),
                            'per_monto' => $this->input->post('monto' . $x),
                            'per_iva' => $this->input->post('iva' . $x)
                        );
                    }
                    $this->basic->save('periodos', 'per_id', $periodo);
                }

                /* Creo los Servicios del contrato */

                for ($x = 1; $x <= $servicios; $x++) {
                    if ($this->input->post('serv_id_' . $x) != '0') {
                        $servicio = array(
                            'serv_id' => $this->input->post('serv_id_' . $x),
                            'serv_contrato' => $con_id,
                            'serv_concepto' => $this->input->post('servicio' . $x),
                            'serv_accion' => $this->input->post('accion' . $x)
                        );
                    } else {
                        $servicio = array(
                            'serv_contrato' => $con_id,
                            'serv_concepto' => $this->input->post('servicio' . $x),
                            'serv_accion' => $this->input->post('accion' . $x)
                        );
                    }
                    $this->basic->save('servicios', 'serv_id', $servicio);
                }
                $this->data['contratos'] = $this->basic->get_all('contratos');
                $this->data['lista'] = $this->load->view('manager/contratos/lista', $this->data, TRUE);
                $response['html'] = $this->load->view('manager/contratos/contratos', $this->data, TRUE);
            } else {
                $response['html'] = validation_errors();
                $response['js'] = '$("#com_display").css("display","block")';
                $response['error'] = '1';
            }
        } else {
            $response['html'] = 'Los conceptos: ';
            for ($x = 0; $x < count($noload); $x++) {
                $response['html'] .= $noload[$x] . ', ';
            }
            $response['html'] .= ' no existen aun en el Sistema, creelos.';
            $response['js'] = '$("#com_display").css("display","block")';
            $response['error'] = '1';
        }
        echo json_encode($response);
    }

    function transferir_sena($prop, $inq) {
        $credito = null;
        $credito = $this->basic->get_where('creditos', array('cred_concepto' => 'Seña', 'cred_depositante' => $inq, 'cred_cc' => $prop))->row_array();
        if ($credito != null) {
            $id = $credito['cred_id'];
            $cc_prop = $this->basic->get_where('cuentas_corrientes', array('cc_prop' => $prop))->row_array();
            $transf_sena_alquiler = array(
                'deb_cc' => $prop,
                'deb_concepto' => 'Transferencia Seña-Alquiler',
                'deb_monto' => $credito['cred_monto'],
                'deb_fecha' => Date('d-m-Y'),
                'trans' => $id
            );
            $credito = array(
                'cred_depositante' => $inq,
                'cred_cc' => $prop,
                'cred_forma' => $credito['cred_forma'],
                'cred_banco' => $credito['cred_banco'],
                'cred_nro_cheque' => $credito['cred_nro_cheque'],
                'cred_concepto' => 'Alquiler',
                'cred_monto' => $credito['cred_monto'],
                'cred_fecha' => Date('d-m-Y'),
                'cred_interes' => $credito['cred_interes'],
                'trans' => $id
            );
            $cc_prop['cc_varios'] -= $credito['cred_monto'];
            $cc_prop['cc_saldo'] += $credito['cred_monto'];
            $this->basic->save('debitos', 'deb_id', $transf_sena_alquiler);
            $this->basic->save('creditos', 'cred_id', $credito);
            $this->basic->save('cuentas_corrientes', 'cc_id', $cc_prop);
        }
    }

    function deleteservper($tabla, $id, $idr) {
        $response['js'] = '';
        $this->basic->del($tabla, $id, $idr);
        echo json_encode($response);
    }

    /* Fin Contratos */

    /* Comentarios */

    function comentarios() {
        $this->load_similar_content('comentarios');
        $this->data['comentarios'] = $this->basic->get_all('comentarios');
        $this->eliminar_vacios();
        $this->data['lista'] = $this->load->view('manager/comentarios/lista', $this->data, TRUE);
        $this->data['content'] = $this->load->view('manager/comentarios/comentarios', $this->data, TRUE);
        $this->load->view('default', $this->data);
    }

    function filtrar_comentario3($cc = FALSE, $desde = FALSE, $hasta = FALSE) {
        $cc = str_replace('.', ',', $cc);
        $cc = urldecode($cc);
        $comentarios = $this->basic->get_where('comentarios', array('com_prop' => $cc));
        $agregar = 0;
        $array = array();
        foreach ($comentarios->result_array() as $row) {
            $agregar = $this->comp_fecha($row['com_date'], $desde, $hasta);
            if ($agregar == '11') {
                array_push($array, $row);
                $agregar = 0;
            }
        }
        $this->data['comentarios'] = $array;
        $response['html'] = $this->load->view('manager/comentarios/buscar_fila_comentarios_arr', $this->data, TRUE);
        echo json_encode($response);
    }

    function filtrar_comentario_2($prop) {
        $prop = str_replace('.', ',', $prop);
        $prop = urldecode($prop);
        $this->data['comentarios'] = $this->basic->get_where('comentarios', array('com_prop' => $prop));
        $response['html'] = $this->load->view('manager/comentarios/buscar_fila_comentarios', $this->data, TRUE);
        echo json_encode($response);
    }

    function filtrar_comentario($desde, $hasta) {
        $comentarios = $this->basic->get_all('comentarios');
        $agregar = 0;
        $array = array();
        foreach ($comentarios->result_array() as $row) {
            $agregar = $this->comp_fecha($row['com_date'], $desde, $hasta);
            if ($agregar == '11') {
                array_push($array, $row);
                $agregar = 0;
            }
        }
        $this->data['comentarios'] = $array;
        $response['html'] = $this->load->view('manager/comentarios/buscar_fila_comentarios_arr', $this->data, TRUE);
        echo json_encode($response);
    }

    function validate_double($id_tabla, $val_tabla, $tabla, $auto_tab_id, $input, $val) {
        $row_out = false;
        $row_in = false;
        $val = urldecode($val);
        $row_in = $this->basic->get_where($tabla, array($val_tabla => $val, 'conc_tipo' => 'Entrada'))->row_array();
        $row_out = $this->basic->get_where($tabla, array($val_tabla => $val, 'conc_tipo' => 'Salida'))->row_array();
        if ($row_out != false && $row_in != false) {
            $response['id'] = $row_out[$id_tabla];
            $response['js'] = "$('#tooltipInt').remove();$('#" . $auto_tab_id . "').val(R.id);$('#" . $input . "').css('box-shadow','1px 0px 0px #00FF00 inset, 0 0 4px #00FF00');";
        } else {
            $response['id'] = null;
            $response['js'] = "$('#" . $auto_tab_id . "').val(R.id);$('#" . $input . "').css('box-shadow','1px 0px 0px #FF0000  inset, 0 0 4px #FF0000 ');";
        }
        echo json_encode($response);
    }

    function validate($id_tabla, $val_tabla, $tabla, $auto_tab_id, $input, $val) {
        $row = false;
        $val = urldecode($val);
        $id = preg_replace("/[^0-9]/", "", $auto_tab_id);
        $row = $this->basic->get_where($tabla, array($val_tabla => $val))->row_array();
        if ($row != false) {
            $response['id'] = $row[$id_tabla];
            $response['control'] = '';
            if ($tabla == 'conceptos') {
                $response['control'] = $row['conc_control'];
            }
            $response['js'] = "$('#" . $auto_tab_id . "').val(R.id);$('#" . $input . "').css('box-shadow','1px 0px 0px #00FF00 inset, 0 0 4px #00FF00');";
            $response['js'] .= "$('#conc_control" . $id . "').val(R.control);";
        } else {
            $response['id'] = null;
            $response['js'] = "$('#" . $auto_tab_id . "').val(R.id);$('#" . $input . "').css('box-shadow','1px 0px 0px #FF0000  inset, 0 0 4px #FF0000 ');";
        }
        echo json_encode($response);
    }

    function save_coment($id = false, $id_inm = false, $prop = false, $comment = false, $domi = false) {
        if ($id != false && $id_inm != false && $prop != false && $comment != false && $domi != false) {
            $comentario = array(
                'com_prop' => urldecode($prop),
                'com_com' => urldecode($comment),
                'com_dom' => urldecode($domi),
                'com_ano' => date('Y'),
                'com_mes' => date('m'),
                'com_date' => date('d-m-Y')
            );
            $this->basic->save('comentarios', 'com_id', $comentario);
            $response['texto'] = 'Comentario Guardado';
            $response['js'] = "$('#com_display').html(R.texto);$('#com_display').removeClass('alert alert-danger');$('#com_display').addClass('alert alert-success');$('#com_display').css('display','block');$('#com_display').fadeOut(3500, 'linear');";
            $response['js'] .= "$('#coment').val('');
                $('#con_prop').val('');
                $('#prop_domi').val('');
                $('#auto_cc_id').val('');
                $('#auto_inm_id').val('');
                $('#con_prop').css('box-shadow','none');
                $('#prop_domi').css('box-shadow','none');";
            echo json_encode($response);
        } else {
            $response['texto'] = 'Datos Faltantes';
            $response['js'] = "$('#com_display').html(R.texto);$('#com_display').removeClass('alert alert-success');$('#com_display').addClass('alert alert-danger');$('#com_display').css('display','block');$('#com_display').fadeOut(3500, 'linear');";
            echo json_encode($response);
        }
    }

    function save_comentarios() {
        $this->form_validation->set_rules('com_prop', 'Propietario', "required|trim");
        $this->form_validation->set_rules('auto_cc_id', 'Propietario: ' . $this->input->post('com_prop') . ' no existe. Creelo, ', "required|numeric");
        $this->form_validation->set_rules('com_dom', 'Domicilio de Propiedad', "required|trim");
        $this->form_validation->set_rules('auto_inm_id', 'Domicilio de Propiedad: ' . $this->input->post('com_dom') . ' no existe. Creelo, ', "required|numeric");
        $this->form_validation->set_rules('com_com', 'Comentarios', "required|trim");
        if ($this->form_validation->run() == TRUE) {
            if (!$this->input->post('com_id'))
                $this->input->post();
            $comentario = array(
                'com_prop' => $this->input->post('com_prop'),
                'com_com' => $this->input->post('com_com'),
                'com_dom' => $this->input->post('com_dom'),
                'com_ano' => $this->input->post('com_ano'),
                'com_mes' => $this->input->post('com_mes'),
                'com_date' => $this->input->post('com_date'),
                'com_id' => $this->input->post('com_id')
            );
            $this->basic->save('comentarios', 'com_id', $comentario);
            $this->data['comentarios'] = $this->basic->get_all('comentarios');
            $this->data['lista'] = $this->load->view('manager/comentarios/lista', $this->data, TRUE);
            $response['html'] = $this->load->view('manager/comentarios/comentarios', $this->data, TRUE);
        } else {
            $response['html'] = validation_errors();
            $response['js'] = '$("#com_display").css("display","block")';
            $response['error'] = '1';
        }

        echo json_encode($response);
    }

    function load_edit_comentarios($id = false) {
        $this->data['comentarios'] = $this->basic->get_all('comentarios');
        $this->data['lista'] = $this->load->view('manager/comentarios/lista', $this->data, TRUE);
        if ($id) {
            $this->data['row'] = $this->basic->get_where('comentarios', array('com_id' => $id))->row();
            $this->data['id'] = $id;
            $response['blur'] = "$('#con_prop').blur();$('#prop_domi').blur();";
            $response['html'] = $this->load->view('manager/comentarios/comentarios', $this->data, TRUE);
        }
        echo json_encode($response);
    }

    function del_comentarios($id) {
        $com = $this->basic->get_where('comentarios', array('com_id' => $id));
        if ($com->num_rows > 0) {
            $this->basic->del('comentarios', 'com_id', $id);
            $response['html'] = t('Comentario eliminado');
            echo json_encode($response);
        }
    }

    /* Fin Comentarios */


    /* Conceptos */

    function conceptos() {
        $this->load_similar_content('conceptos');
        $this->eliminar_vacios();
        $this->data['conceptos'] = $this->basic->get_where('conceptos', array(), 'conc_desc', '');
        $this->data['lista'] = $this->load->view('manager/conceptos/lista', $this->data, TRUE);
        $this->data['content'] = $this->load->view('manager/conceptos/conceptos', $this->data, TRUE);
        $this->load->view('default', $this->data);
    }

    function load_edit_conceptos($id = false) {
        $this->data['conceptos'] = $this->basic->get_where('conceptos', array(), 'conc_desc', '');
        $this->data['lista'] = $this->load->view('manager/conceptos/lista', $this->data, TRUE);
        if ($id) {
            $this->data['row'] = $this->basic->get_where('conceptos', array('conc_id' => $id))->row();
            $this->data['id'] = $id;
            $response['js'] = "$('add_op').css('display','none');$('.contenedor_centro').css('width','95%');";
            $response['html'] = $this->load->view('manager/conceptos/conceptos', $this->data, TRUE);
        }
        $response['js'] = "$('.contenedor_centro').css('width','95%');";
        echo json_encode($response);
    }

    function del_conceptos($id) {
        $user = $this->basic->get_where('conceptos', array('conc_id' => $id));
        if ($user->num_rows > 0) {
            $this->basic->del('conceptos', 'conc_id', $id);
            $response['html'] = t('Concepto eliminado');
            echo json_encode($response);
        }
    }

    function save_conceptos() {
        $this->form_validation->set_rules('conc_desc', 'Concepto', "required|trim");
        $this->form_validation->set_rules('conc_tipo', 'Tipo de Concepto', "required|trim");
        if ($this->form_validation->run() == TRUE) {
            if (!$this->input->post('conc_id'))
                $this->input->post();
            $this->basic->save('conceptos', 'conc_id', $this->input->post());
            $this->data['conceptos'] = $this->basic->get_all('conceptos');
            $this->data['lista'] = $this->load->view('manager/conceptos/lista', $this->data, TRUE);
            $response['html'] = $this->load->view('manager/conceptos/conceptos', $this->data, TRUE);
        } else {
            $response['html'] = validation_errors();
            $response['js'] = '$("#com_display").css("display","block")';
            $response['error'] = '1';
        }

        echo json_encode($response);
    }

    function save_conceptos_pop() {
        $this->form_validation->set_rules('conc_desc', 'Concepto', "required|trim");
        $this->form_validation->set_rules('conc_tipo', 'Tipo de Concepto', "required|trim");
        if ($this->form_validation->run() == TRUE) {
            if (!$this->input->post('conc_id'))
                $this->input->post();
            $this->basic->save('conceptos', 'conc_id', $this->input->post());
            $response['js'] = "$('#back_fader').hide();$('#popup').hide();";
            $response['js'] .= "$('#back_fader2').hide();$('#popup2').hide();";
        } else {
            $response['html'] = validation_errors();
            $response['js'] = '$("#msg_display").css("display","block")';
            $response['error'] = '1';
        }

        echo json_encode($response);
    }

    /* Fin Conceptos */

    /* Clientes */

    function clientes() {
        $this->load_similar_content('clientes');
        $this->data['clientes'] = $this->basic->get_where('clientes', array(), 'client_name', '');
        $this->data['lista'] = $this->load->view('manager/clientes/lista', $this->data, TRUE);
        $this->eliminar_vacios();
        $this->data['content'] = $this->load->view('manager/clientes/clientes', $this->data, TRUE);
        $this->load->view('default', $this->data);
    }

    function load_edit_clientes($id = false) {
        $this->data['clientes'] = $this->basic->get_where('clientes', array(), 'client_name');
        $this->data['lista'] = $this->load->view('manager/clientes/lista', $this->data, TRUE);
        if ($id) {
            $this->data['row'] = $this->basic->get_where('clientes', array('client_id' => $id))->row();
            $this->data['id'] = $id;
            $response['js'] = "$('add_op').css('display','none');$('.contenedor_centro').css('width','95%');";
            $response['html'] = $this->load->view('manager/clientes/clientes', $this->data, TRUE);
        }
        $response['js'] = "$('.contenedor_centro').css('width','95%');";
        echo json_encode($response);
    }

    function del_clientes($id) {
        $user = $this->basic->get_where('clientes', array('client_id' => $id));
        if ($user->num_rows > 0) {
            $this->basic->del('clientes', 'client_id', $id);
            $response['html'] = t('Cliente eliminado');
            echo json_encode($response);
        }
    }

    function save_clientes() {
        $this->form_validation->set_rules('client_name', 'Nombre', "required|trim");
        $this->form_validation->set_rules('client_nro_calle', 'Nro. Calle', "numeric");
        $this->form_validation->set_rules('client_postal', 'Código Postal', "numeric");
        $this->form_validation->set_rules('client_tel', 'Teléfono Fijo', "numeric");
        $this->form_validation->set_rules('client_celular', 'Celular', "numeric");
        if ($this->form_validation->run() == TRUE) {
            if (!$this->input->post('client_id'))
                $this->input->post();
            $cliente = array(
                'client_name' => strtoupper($this->input->post('client_name')),
                'client_email' => $this->input->post('client_email'),
                'client_tel' => $this->input->post('client_tel'),
                'client_celular' => $this->input->post('client_celular'),
                'client_cuit' => $this->input->post('client_cuit'),
                'client_postal' => $this->input->post('client_postal'),
                'client_nro_calle' => $this->input->post('client_nro_calle'),
                'client_calle' => strtoupper($this->input->post('client_calle')),
                'client_dto' => $this->input->post('client_dto'),
                'client_localidad' => strtoupper($this->input->post('client_localidad')),
                'client_provincia' => strtoupper($this->input->post('client_provincia')),
                'client_categoria' => strtoupper($this->input->post('client_categoria')),
                'client_razon_vinculo' => strtoupper($this->input->post('client_razon_vinculo')),
                'client_comentario' => $this->input->post('client_comentario'),
                'client_id' => $this->input->post('client_id')
            );
            $this->basic->save('clientes', 'client_id', $cliente);
            $this->data['clientes'] = $this->basic->get_where('clientes', array(), 'client_name');
            $this->data['lista'] = $this->load->view('manager/clientes/lista', $this->data, TRUE);
            $response['html'] = $this->load->view('manager/clientes/clientes', $this->data, TRUE);
        } else {
            $response['html'] = validation_errors();
            $response['js'] = '$("#com_display").css("display","block")';
            $response['error'] = '1';
        }
        echo json_encode($response);
    }

    /* fin clientes */

    /* Propiedades */

    function propiedades() {
        $this->data['id_inq'] = null;
        $this->load_similar_content('propiedades');
        $this->eliminar_vacios();
        $this->data['propiedades'] = $this->basic->get_where('propiedades', array(), 'prop_prop', '', '');
        $this->data['lista'] = $this->load->view('manager/propiedades/lista', $this->data, TRUE);
        $this->data['content'] = $this->load->view('manager/propiedades/propiedades', $this->data, TRUE);
        $this->load->view('default', $this->data);
    }

    function load_edit_propiedad($id = false) {
        $this->data['propiedades'] = $this->basic->get_where('propiedades', array(), 'prop_prop');
        $this->data['lista'] = $this->load->view('manager/propiedades/lista', $this->data, TRUE);
        $this->data['id_inq'] = null;
        if ($id) {
            $this->data['row'] = $this->basic->get_where('propiedades', array('prop_id' => $id))->row();
            $inq = false;
            $this->data['id'] = $id;
            $inq = $this->basic->get_where('clientes', array('client_name' => $this->data['row']->prop_contrato_vigente))->row();
            $prop = $this->basic->get_where('cuentas_corrientes', array('cc_prop' => $this->data['row']->prop_prop))->row();
//            $response['blur'] = "$('#propietario').blur()";
            if ($inq != false) {
                $this->data['id_inq'] = $inq->client_id;
            }
            $response['blur'] = "$('#propietario').blur();$('#inquilino').blur();";

            $this->data['id_prop'] = $prop->cc_id;
            $response['js'] = "$('add_op').css('display','none');$('.contenedor_centro').css('width','95%');";
            $response['html'] = $this->load->view('manager/propiedades/propiedades', $this->data, TRUE);
        }
        $response['js'] = "$('.contenedor_centro').css('width','95%');";
        echo json_encode($response);
    }

    function save_propiedad() {
        $this->form_validation->set_rules('prop_dom', 'Domicilio de propiedad', "required|trim");
        $this->form_validation->set_rules('prop_prop', 'Propietario', "required|trim");
        $this->form_validation->set_rules('auto_cc_id', 'Propietario: ' . $this->input->post('prop_prop') . ' no existe. Creelo, ', "required|numeric");
        $this->form_validation->set_rules('auto_depo_id', 'Inquilino: ' . $this->input->post('prop_contrato_vigente') . ' no existe. Creelo, ', "required|numeric");
        if ($this->form_validation->run() == TRUE) {
            if (!$this->input->post('prop_id'))
                $this->input->post();
            $propiedad = array(
                'prop_prop' => strtoupper($this->input->post('prop_prop')),
                'prop_dom' => strtoupper($this->input->post('prop_dom')),
                'prop_contrato_vigente' => strtoupper($this->input->post('prop_contrato_vigente')),
                'prop_id' => $this->input->post('prop_id')
            );
            $this->basic->save('propiedades', 'prop_id', $propiedad);
            $this->data['propiedades'] = $this->basic->get_where('propiedades', array(), 'prop_prop');
            $this->data['lista'] = $this->load->view('manager/propiedades/lista', $this->data, TRUE);
            $response['html'] = $this->load->view('manager/propiedades/propiedades', $this->data, TRUE);
            echo json_encode($response);
        } else {
            $response['js'] = "$('#com_display').css('display','block');$('#com_display').css('margin-top','135px');$('#com_display').css('width','344px')";
            $response['html'] = validation_errors();
            $response['error'] = '1';
            echo json_encode($response);
        }
    }

    function del_propiedad($id) {
        $cuenta = $this->basic->get_where('propiedades', array('prop_id' => $id));
        if ($cuenta->num_rows > 0) {
            $this->basic->del('propiedades', 'prop_id', $id);
            $response['html'] = t('Propiedad eliminada');
            echo json_encode($response);
        }
    }

    /* Fin Propiedades */

    /* Cuentas */

    function cuentas() {
        $this->load_similar_content('cuentas_corrientes');
        $this->data['cuentas_corrientes'] = $this->basic->get_where('cuentas_corrientes', array(), 'cc_prop', '', '');
        $this->data['lista'] = $this->load->view('manager/cuentas_corrientes/lista', $this->data, TRUE);
        $this->eliminar_vacios();
        $this->data['content'] = $this->load->view('manager/cuentas_corrientes/cuentas', $this->data, TRUE);
        $this->load->view('default', $this->data);
    }

    function load_edit_cuenta($id = false) {
        $this->data['cuentas_corrientes'] = $this->basic->get_where('cuentas_corrientes', array(), 'cc_prop');
        $this->data['lista'] = $this->load->view('manager/cuentas_corrientes/lista', $this->data, TRUE);
        if ($id) {
            $this->data['row'] = $this->basic->get_where('cuentas_corrientes', array('cc_id' => $id))->row();
            $this->data['id'] = $id;
            $response['js'] = "$('add_op').css('display','none');$('.contenedor_centro').css('width','95%');";
            $response['html'] = $this->load->view('manager/cuentas_corrientes/cuentas', $this->data, TRUE);
        }
        $response['js'] = "$('.contenedor_centro').css('width','95%');";
        echo json_encode($response);
    }

    function save_cuenta() {
        $this->form_validation->set_rules('cc_prop', 'Propietario', "required|trim");
        $esta = false;
        $esta_cliente = false;
        if ($this->form_validation->run() == TRUE) {
            if (!$this->input->post('cc_id'))
                $this->input->post();
            $cuentas = $this->basic->get_all('cuentas_corrientes');
            foreach ($cuentas->result() as $row) {
                if ($row->cc_prop == $this->input->post('cc_prop')) {
                    $esta = true;
                }
            }
            $clientes = $this->basic->get_all('clientes');
            foreach ($clientes->result() as $row) {
                if ($row->client_name == $this->input->post('cc_prop')) {
                    $esta_cliente = true;
                }
            }
            if (!$esta_cliente) {
                $cliente = array(
                    'client_name' => strtoupper($this->input->post('cc_prop')),
                    'client_email' => '',
                    'client_tel' => ''
                );
                $this->basic->save('clientes', 'client_id', $cliente);
            }
            if (!$esta || $this->input->post('cc_id') != 0) {
                $cuenta = array(
                    'cc_prop' => strtoupper($this->input->post('cc_prop')),
                    'cc_saldo' => $this->input->post('cc_saldo'),
                    'cc_varios' => $this->input->post('cc_varios'),
                    'cc_id' => $this->input->post('cc_id')
                );
                $this->basic->save('cuentas_corrientes', 'cc_id', $cuenta);
                $this->data['cuentas_corrientes'] = $this->basic->get_where('cuentas_corrientes', array(), 'cc_prop');
                $this->data['lista'] = $this->load->view('manager/cuentas_corrientes/lista', $this->data, TRUE);
                $response['html'] = $this->load->view('manager/cuentas_corrientes/cuentas', $this->data, TRUE);
                echo json_encode($response);
            } else {
                $response['js'] = "$('#com_display').css('display','block');$('#com_display').css('margin-top','137px');$('#com_display').css('width','344px')";
                $response['html'] = 'La cuenta corriente de ' . $this->input->post('cc_prop') . ' ya existe';
                $response['error'] = '2';
                echo json_encode($response);
            }
        } else {
            $response['js'] = "$('#com_display').css('display','block');$('#com_display').css('margin-top','135px');$('#com_display').css('width','344px')";
            $response['html'] = validation_errors();
            $response['error'] = '1';
            echo json_encode($response);
        }
    }

    function del_cuenta($id) {
        $cuenta = $this->basic->get_where('cuentas_corrientes', array('cc_id' => $id));
        if ($cuenta->num_rows > 0) {
            $this->basic->del('cuentas_corrientes', 'cc_id', $id);
            $response['html'] = t('Cuenta eliminada');
            echo json_encode($response);
        }
    }

    /* fin cuentas */

    /* Transacciones */

    function debitos() {
        $this->load_similar_content('debitos');
        $this->data['debitos'] = $this->basic->get_where('debitos', array(), 'deb_id', 'desc', '50');
        $this->data['lista'] = $this->load->view('manager/transacciones/lista_debitos', $this->data, TRUE);
        $this->data['content'] = $this->load->view('manager/transacciones/debitos', $this->data, TRUE);
        $this->load->view('default', $this->data);
    }

    function del_transact($tipo_transaccion, $id_t) {
        $this->data['creditos'] = $this->basic->get_where('creditos', array('trans' => $id_t));
        $this->data['tipo_transaccion'] = $tipo_transaccion;
        $this->data['debitos'] = $this->basic->get_where('debitos', array('trans' => $id_t));
        $this->data['trans'] = $id_t;
        $response['html'] = $this->load->view('manager/transacciones/transacciones', $this->data, TRUE);
        echo json_encode($response);
    }

    function del_creditos($id) {
        $cred = $this->basic->get_where('creditos', array('cred_id' => $id))->row_array();
        $contrato = false;
        $concepto = false;
        $fecha = explode('-', $cred['cred_fecha']);
        $caja = $this->basic->get_where('mensuales', array('men_mes' => $fecha[1], 'men_ano' => $fecha[2]))->row_array();
        if (strpos($cred['cred_concepto'], 'Alquiler Comercial') !== FALSE || strpos($cred['cred_concepto'], 'Alquiler') !== FALSE || strpos($cred['cred_concepto'], 'Loteo') !== FALSE) {
            $contrato = $this->basic->get_where('contratos', array('con_prop' => $cred['cred_cc'], 'con_inq' => $cred['cred_depositante']))->row_array();
            $concepto = $cred['cred_concepto'];
            $ultimo_pago = false;
        }
        $cc = $this->basic->get_where('cuentas_corrientes', array('cc_prop' => $cred['cred_cc']))->row_array();
        $conceptos = $this->basic->get_all('conceptos');
        foreach ($conceptos->result() as $con) {
            if (strpos($cred['cred_concepto'], $con->conc_desc) !== FALSE) {
                $cuenta = $con->conc_cc;
            }
        }
        $cc[$cuenta] -= $cred['cred_monto'];
        if (strpos($cred['cred_concepto'], 'Gestion de Cobro') === FALSE) {
            $caja['men_creditos'] -= $cred['cred_monto'];
        }
        $this->basic->del('creditos', 'cred_id', $id);
        $this->basic->save('cuentas_corrientes', 'cc_id', $cc);
        $caja['men_date'] = Date('d-m-Y  h:i:s A');
        $caja['men_info'] = 'Elim cred, dep ' . $cred['cred_depositante'] . ', cc ' . $cred['cred_cc'] . ' $ ' . $cred['cred_monto'];
        $this->basic->save('mensuales', 'men_id', $caja);
        $ultimo_pago = $this->get_last_payment($contrato, $concepto);
        if ($ultimo_pago == false) {
            $contrato['con_usado'] = 0;
            $this->basic->save('contratos', 'con_id', $contrato);
        }
        $response['html'] = t('Credito eliminado');
        echo json_encode($response);
    }

    function del_debitos($id) {
        $deb = $this->basic->get_where('debitos', array('deb_id' => $id))->row_array();
        $fecha = explode('-', $deb['deb_fecha']);
        $caja = $this->basic->get_where('mensuales', array('men_mes' => $fecha[1], 'men_ano' => $fecha[2]))->row_array();
        $cc = $this->basic->get_where('cuentas_corrientes', array('cc_prop' => $deb['deb_cc']))->row_array();
        $conceptos = $this->basic->get_all('conceptos');
        foreach ($conceptos->result() as $con) {
            if (strpos($deb['deb_concepto'], $con->conc_desc) !== FALSE) {
                $cuenta = $con->conc_cc;
            }
        }
        $cc[$cuenta] += $deb['deb_monto'];
        if (strpos($deb['deb_concepto'], 'Gestion de Cobro') === FALSE) {
            $caja['men_debitos'] -= $deb['deb_monto'];
        }
        $this->basic->del('debitos', 'deb_id', $id);
        $caja['men_date'] = Date('d-m-Y  h:i:s A');
        $caja['men_info'] = 'Elim deb, cc ' . $deb['deb_cc'] . ' $ ' . $deb['deb_monto'];
        $this->basic->save('mensuales', 'men_id', $caja);
        $this->basic->save('cuentas_corrientes', 'cc_id', $cc);
        $response['html'] = t('Debito eliminado');
        echo json_encode($response);
    }

    function delete_transact($id_t, $tipo_transaccion) {
        $fecha = false;
        $cc_prop = false;
        $depo = 'depo? nadie, es dbito';
        $debitos = $this->basic->get_where('debitos', array('trans' => $id_t));
        $creditos = $this->basic->get_where('creditos', array('trans' => $id_t));
        foreach ($creditos->result_array() as $row) {
            $fecha = $row['cred_fecha'];
            $depo = $row['cred_depositante'];
            $cc_prop = $row['cred_cc'];
            break;
        }
        $monto = 0;
        if ($fecha == false || $cc_prop == false) {
            foreach ($debitos->result_array() as $row) {
                $fecha = $row['deb_fecha'];
                $cc_prop = $row['deb_cc'];
                break;
            }
        }
        $fecha = explode('-', $fecha);
        $caja = $this->basic->get_where('mensuales', array('men_mes' => $fecha[1], 'men_ano' => $fecha[2]))->row_array();
        $conceptos = $this->basic->get_all('conceptos');
        foreach ($creditos->result_array() as $row) {
            $cc = $this->basic->get_where('cuentas_corrientes', array('cc_prop' => $row['cred_cc']))->row_array();
            foreach ($conceptos->result() as $con) {
                if (strpos($row['cred_concepto'], $con->conc_desc) !== FALSE && $con->conc_tipo == 'Entrada') {
                    $cuenta = $con->conc_cc;
                }
            }
            if (strpos($row['cred_concepto'], 'Gestion de Cobro') === FALSE) {
                $caja['men_creditos'] -= $row['cred_monto'];
                $monto += $row['cred_monto'];
            }
            $cc[$cuenta] -= $row['cred_monto'];
            $contrato = false;
            $concepto = false;
            $ultimo_pago = false;
            if (strpos($row['cred_concepto'], 'Alquiler Comercial') !== FALSE || strpos($row['cred_concepto'], 'Alquiler') !== FALSE || strpos($row['cred_concepto'], 'Loteo') !== FALSE) {
                $contrato = $this->basic->get_where('contratos', array('con_prop' => $row['cred_cc'], 'con_inq' => $row['cred_depositante']))->row_array();
//                echo '<pre>';print_r($row);print_r($contrato);echo'</pre>';
                $concepto = $row['cred_concepto'];
                $ultimo_pago = $this->get_last_payment($contrato, $concepto);
                if ($ultimo_pago == false) {
                    $contrato['con_usado'] = 0;
                    $this->basic->save('contratos', 'con_id', $contrato);
                }
            }
            $this->basic->del('creditos', 'trans', $id_t);
            $this->basic->save('cuentas_corrientes', 'cc_id', $cc);
        }
        foreach ($debitos->result_array() as $row) {
            $cc = $this->basic->get_where('cuentas_corrientes', array('cc_prop' => $row['deb_cc']))->row_array();
            foreach ($conceptos->result() as $con) {
                if (strpos($row['deb_concepto'], $con->conc_desc) !== FALSE && $con->conc_tipo == 'Salida') {
                    $cuenta = $con->conc_cc;
                }
            }
            $cc[$cuenta] += $row['deb_monto'];
            if (strpos($row['deb_concepto'], 'Gestion de Cobro') === FALSE) {
                $caja['men_debitos'] -= $row['deb_monto'];
                $monto -= $row['deb_monto'];
            }
            $this->basic->del('debitos', 'trans', $id_t);
            $this->basic->save('cuentas_corrientes', 'cc_id', $cc);
        }
        $caja['men_date'] = Date('d-m-Y  h:i:s A');
        $caja['men_info'] = 'Elim transac, dep ' . $depo . ', cc ' . $cc_prop . ' $ ' . $monto;
        $this->basic->save('mensuales', 'men_id', $caja);
        $response['js'] = 'window.top.location.href="' . site_url($tipo_transaccion) . '"';
        echo json_encode($response);
    }

    function transaccion_debito($debito, $trans) {
        $r = 0;
        $cc_prop = $this->basic->get_where('cuentas_corrientes', array('cc_prop' => $debito['deb_cc']))->row_array();
        if ($debito['deb_cc'] != 'INMOBILIARIA') {
            $cc_rima = $this->basic->get_where('cuentas_corrientes', array('cc_prop' => 'INMOBILIARIA'))->row_array();
        }
        $concepto = false;
        $concepto = $this->basic->get_where('conceptos', array('conc_desc' => $debito['deb_concepto']))->row_array();
        $a = false;
        if ($concepto != false) {
            $cuenta = $concepto['conc_cc'];
            $saldo_total_prop = $cc_prop['cc_saldo'] + $cc_prop['cc_varios'];
            $diff_con_total = round($saldo_total_prop - $debito['deb_monto'], 2);
            $a = true;
            if ($diff_con_total < 0) {
                if ($debito['deb_concepto'] != 'Rendicion') {
                    if ($cc_prop['cc_prop'] != 'INMOBILIARIA') {
                        $prestamo = round($diff_con_total * (-1), 2);
                        $debito_prestamo = array(
                            'deb_cc' => 'Inmobiliaria',
                            'deb_concepto' => 'Prestamo',
                            'deb_monto' => $prestamo,
                            'deb_domicilio' => $debito['deb_domicilio'],
                            'deb_mes' => $debito['deb_mes'],
                            'deb_tipo_trans' => 'Caja',
                            'deb_fecha' => Date('d-m-Y'),
                            'trans' => $trans
                        );
                        $this->basic->save('debitos', 'deb_id', $debito_prestamo);
                        $credito_prestamo = array(
                            'cred_cc' => $debito['deb_cc'],
                            'cred_depositante' => 'Inmobiliaria',
                            'cred_concepto' => 'Prestamo',
                            'cred_monto' => $prestamo,
                            'cred_domicilio' => $debito['deb_domicilio'],
                            'cred_mes_alq' => $debito['deb_mes'],
                            'cred_fecha' => Date('d-m-Y'),
                            'trans' => $trans,
                            'cred_tipo_trans' => 'Caja',
                        );
                        $this->basic->save('creditos', 'cred_id', $credito_prestamo);
                        $cc_rima['cc_saldo'] -= $prestamo;
                        $cc_prop[$cuenta] += $prestamo;
                    }
                }
            } else {
                // La cc_prop tiene el saldo suficiente para el debito 
            }
            $debito = array(
                'deb_cc' => $debito['deb_cc'],
                'deb_concepto' => $debito['deb_concepto'],
                'deb_monto' => round($debito['deb_monto'], 2),
                'deb_domicilio' => $debito['deb_domicilio'],
                'deb_forma' => $debito['deb_forma'],
                'deb_tipo_trans' => $debito['deb_tipo_trans'],
                'deb_mes' => $debito['deb_mes'],
                'deb_fecha' => Date('d-m-Y'),
                'trans' => $trans
            );
            $this->basic->save('debitos', 'deb_id', $debito);
            $r = 1;
            $cc_prop[$cuenta] = $cc_prop[$cuenta] - $debito['deb_monto'];
        }
        $this->basic->save('cuentas_corrientes', 'cc_id', $cc_prop);
        if ($debito['deb_cc'] != 'INMOBILIARIA') {
            $this->basic->save('cuentas_corrientes', 'cc_id', $cc_rima);
        }
        return $r;
    }

    function creditos() {
        $this->load_similar_content('creditos');
        $this->data['creditos'] = $this->basic->get_where('creditos', array(), 'cred_id', 'desc', '50');
        $this->data['lista'] = $this->load->view('manager/transacciones/lista_creditos', $this->data, TRUE);
        $this->data['content'] = $this->load->view('manager/transacciones/creditos', $this->data, TRUE);
        $this->eliminar_vacios();
        $this->load->view('default', $this->data);
    }

    function imprimir_recibo($trans) {
        $response['js'] = 'window.location.href="' . site_url('manager/show_recibo') . '/' . $trans . '"';
        echo json_encode($response);
    }

    function imprimir_rendicion($trans) {
        $response['js'] = 'window.location.href="' . site_url('manager/show_rendicion') . '/' . $trans . '"';
        echo json_encode($response);
    }

    function show_recibo($trans) {
        $contrato = null;
        $do_recibo = false;
        $reserva = false;
        $this->load_similar_content('creditos');
        $this->data['creditos_recibo_alquiler'] = array();
        $this->data['creditos_recibo_servicios'] = array();
        $transaccion = $this->basic->get_where('creditos', array('trans' => $trans));
        $this->data['total_transaccion'] = 0;
        foreach ($transaccion->result_array() as $credito) {
            if ($credito['cred_concepto'] == 'Reserva') {
                $reserva = true;
            }
            $prop = $credito['cred_cc'];
            if (strpos($credito['cred_concepto'], 'Devolucion Prestamo') === FALSE && strpos($credito['cred_concepto'], 'Gestion de Cobro') === FALSE && strpos($credito['cred_concepto'], 'IVA') === FALSE) {
                if ($credito['cred_concepto'] == 'Comision') {
                    $contrato = $this->basic->get_where('contratos', array('con_inq' => $credito['cred_depositante']))->row_array();
                } else {
                    $contrato = $this->basic->get_where('contratos', array('con_prop' => $credito['cred_cc'], 'con_inq' => $credito['cred_depositante']))->row_array();
                }
                $this->data['contrato'] = $contrato;
                $this->data['total_transaccion'] += $credito['cred_monto'];
                if (strpos($credito['cred_concepto'], 'Reserva') !== FALSE || strpos($credito['cred_concepto'], 'Alquiler') !== FALSE || strpos($credito['cred_concepto'], 'Loteo') !== FALSE || strpos($credito['cred_concepto'], 'Comision') !== FALSE) {
                    $credito['cred_debe_pagar'] = 0;
                    $credito['cred_iva_comi'] = 0;
                    $credito['cred_iva_alq'] = 0;
                    if (strpos($credito['cred_concepto'], 'Reserva') === FALSE) {
                        $this->data['periodos'] = $this->basic->get_where('periodos', array('per_contrato' => $contrato['con_id']));
                        $mes_last = preg_replace("/[^^A-Za-z (),.]/", "", $credito['cred_mes_alq']);
                        $mes_last = trim($mes_last);
                        $ano_last = preg_replace("/[^0-9 (),.]/", "", $credito['cred_mes_alq']);
                        $ano_last = trim($ano_last);
                        $mes_last = $this->get_nro_mes($mes_last);
                        $agregar = 0;
                        $debe_pagar = 0;
                        if ($credito['cred_concepto'] != 'Comision') {
                            foreach ($this->data['periodos']->result_array() as $row) {
                                $inicio = explode('-', $row['per_inicio']);
                                $fecha = $inicio[0] . '-' . $mes_last . '-' . $ano_last;
                                $agregar = $this->comp_fecha($fecha, $row['per_inicio'], $row['per_fin']);
                                if ($agregar == '11') {
                                    $debe_pagar = $row['per_monto'];
                                }
                            }
                        } else {
                            $debe_pagar = $credito['cred_monto'];
                        }
                        $credito['cred_debe_pagar'] = $debe_pagar;
                        if ($contrato['con_iva'] == 'Si' && $credito['cred_concepto'] == 'Comision') {
                            $credito['cred_iva_comi'] = round($credito['cred_monto'] * 0.21, 2);
                            $this->data['total_transaccion'] += $credito['cred_iva_comi'];
                        }
                        if ($contrato['con_iva_alq'] == 'Si' && $credito['cred_concepto'] != 'Comision') {
                            $credito['cred_iva_alq'] = round($credito['cred_monto'] * 0.21, 2);
                            $this->data['total_transaccion'] += $credito['cred_iva_alq'];
                        }
                        // Si hay intereses en el alquiler o loteo lo adhiero al credito
                        $credito['cred_interes_calculados'] = 0;
                        if ($credito['cred_interes'] != 0) {
                            $interes = round($credito['cred_monto'] * $credito['cred_interes'] * $contrato['con_punitorio'], 2);
                            $credito['cred_interes_calculados'] = $interes;
                        }
                        array_push($this->data['creditos_recibo_alquiler'], $credito);
                    } else {
                        array_push($this->data['creditos_recibo_alquiler'], $credito);
                    }
                } else {
                    if ($credito['cred_concepto'] != 'IVA' && strpos($credito['cred_concepto'], 'Intereses') === false) {
                        $credito['usado'] = 0;
                        $credito['cred_interes_calculados'] = 0;
                        $credito['usado_cont'] = 0;
                        if ($credito['cred_concepto'] == 'Expensas' && $credito['cred_interes'] != 0) {
                            // De ser expensa se revisara si tiene algun interes
                            $interes = $credito['cred_monto'] * $credito['cred_interes'] * $contrato['con_punitorio'];
                            $credito['cred_interes_calculados'] = $interes;
                        }
                        array_push($this->data['creditos_recibo_servicios'], $credito);
                    }
                }
                if ($contrato != false) {
                    $this->data['propietario'] = $this->basic->get_where('clientes', array('client_name' => $contrato['con_prop']))->row_array();
                } else {
                    $this->data['propietario'] = $this->basic->get_where('clientes', array('client_name' => $prop))->row_array();
                }
                $do_recibo = true;
            }
        }
        if ($do_recibo) {
            for ($x = 0; $x < count($this->data['creditos_recibo_alquiler']); $x++) {
                $creditos_pagados_a_cuenta = $this->basic->get_where('creditos', array('cred_cc' => $this->data['creditos_recibo_alquiler'][$x]['cred_cc'], 'cred_depositante' => $this->data['creditos_recibo_alquiler'][$x]['cred_depositante'], 'cred_mes_alq' => $this->data['creditos_recibo_alquiler'][$x]['cred_mes_alq'], 'cred_tipo_pago' => 'A Cuenta'));
                $pagado_a_cuenta = 0;
                if ($creditos_pagados_a_cuenta->num_rows() > 0) {
                    foreach ($creditos_pagados_a_cuenta->result_array() as $row) {
                        if ($row['cred_id'] < $this->data['creditos_recibo_alquiler'][$x]['cred_id']) {
                            if (strpos($row['cred_concepto'], 'Alquiler') !== FALSE || strpos($row['cred_concepto'], 'Loteo') !== FALSE) {
                                if ($row['cred_monto'] > 0) {
                                    $pagado_a_cuenta += $row['cred_monto'];
                                }
                            }
                        }
                    }
                }
                $this->data['creditos_recibo_alquiler'][$x]['cred_debe_pagar'] -= $pagado_a_cuenta;
                if ($this->data['creditos_recibo_alquiler'][$x]['cred_concepto'] != 'Reserva') {
                    $this->data['creditos_recibo_alquiler'][$x]['adeuda'] = $this->data['creditos_recibo_alquiler'][$x]['cred_debe_pagar'];
                } else {
                    $this->data['creditos_recibo_alquiler'][$x]['adeuda'] = 0;
                }
                $this->data['total_transaccion_letra'] = $this->numtoletras($this->data['total_transaccion']);
            }
            $this->data['fecha'] = Date('d-m-Y');
        }
        if ($reserva) {
            $this->data['content'] = $this->load->view('manager/transacciones/recibo_reserva', $this->data, TRUE);
        } else {
            $this->data['content'] = $this->load->view('manager/transacciones/recibo_post', $this->data, TRUE);
        }
        $this->load->view('default', $this->data);
    }

    function show_rendicion($trans) {
        $this->load_similar_content('debitos');
        $rendicion = $this->basic->get_where('debitos', array('trans' => $trans, 'deb_concepto' => 'Rendicion'))->row_array();
        $cuenta = $rendicion['deb_cc'];
        $fecha_v = explode('-', $rendicion['deb_fecha']);
        $agregar = 0;
        $agregar1 = 0;
        $this->data['prop'] = $this->basic->get_where('cuentas_corrientes', array('cc_prop' => $cuenta))->row_array();
        $this->data['comentarios'] = $this->basic->get_where('comentarios', array('com_prop' => $cuenta, 'com_mes' => $fecha_v[1], 'com_ano' => $fecha_v[2]));
        $this->data['entrada_prin'] = 0;
        $this->data['salida_prin'] = 0;
        $this->data['salida_sec'] = 0;
        $this->data['entrada_sec'] = 0;
        $this->data['desde'] = '01-' . $fecha_v[1] . '-' . $fecha_v[2];
        $this->data['hasta'] = $fecha_v[0] . '-' . $fecha_v[1] . '-' . $fecha_v[2];
        $desde = $this->data['desde'];
        $hasta = $this->data['hasta'];
        $this->data['cuenta'] = $cuenta;
        $this->data['intereses_mora'] = array();
        $this->data['alquileres'] = array();
        $rendiciones = $this->basic->get_where('debitos', array('deb_cc' => $cuenta, 'deb_concepto' => 'Rendicion'));
        $monto_rendicion_hoy = 0;
        $this->data['monto_rendicion_domis'] = '';
        $this->data['monto_rendicion_meses'] = '';
        foreach ($rendiciones->result_array() as $row) {
            $agregar1 = $this->comp_fecha($row['deb_fecha'], $desde, $hasta);
            if ($agregar1 == '11') {
                $monto_rendicion_hoy += $row['deb_monto'];
                $this->data['monto_rendicion_domis'] .= $row['deb_domicilio'] . ', ';
                $this->data['monto_rendicion_meses'] .= $row['deb_mes'] . ', ';
            }
        }
        $this->data['monto_rendicion_hoy'] = round($monto_rendicion_hoy, 2);
        $this->data['monto_rendicion_hoy_letra'] = $this->numtoletras(round($monto_rendicion_hoy, 2));
        $this->data['servicios'] = $this->basic->get_all('servicios');
        $this->data['contratos'] = $this->basic->get_where('contratos', array('con_prop' => $cuenta, 'con_enabled' => 1));
        $this->data['conceptos'] = $this->basic->get_all('conceptos');
        $creditos_prop = $this->basic->get_where('creditos', array('cred_cc' => $cuenta), 'cred_id', 'asc');
        $debitos_prop = $this->basic->get_where('debitos', array('deb_cc' => $cuenta), 'deb_id', 'asc');
        $conceptos = $this->basic->get_all('conceptos');
        $ints = $this->basic->get_where('intereses_mora', array('int_cc' => $cuenta));
        foreach ($ints->result_array() as $row) {
            $agregar1 = $this->comp_fecha($row['int_fecha_pago'], $desde, $hasta);
            if ($agregar1 == '11') {
                array_push($this->data['intereses_mora'], $row);
                $agregar1 = 0;
            }
        }
        $this->data['varios'] = array();
        /* Agrupo los creditos de alquiler y varios en diferentes arrays, lo mismo ocurre con los debitos */
        foreach ($creditos_prop->result_array() as $row) {
            foreach ($conceptos->result_array() as $con) {
                if (strpos($row['cred_concepto'], $con['conc_desc']) !== FALSE && $con['conc_tipo'] == 'Entrada') {
                    $cuenta = $con['conc_cc'];
                    break;
                }
            }
            $agregar = $this->comp_fecha($row['cred_fecha'], $desde, $hasta);
            if ($agregar == '11') {
                /* Pertenece a un credito del rango de fechas ingresados */
                $arreglo = array(
                    'id' => $row['cred_id'],
                    'mes' => $row['cred_mes_alq'],
                    'fecha' => $row['cred_fecha'],
                    'concepto' => $row['cred_concepto'],
                    'monto' => $row['cred_monto'],
                    'depositante' => $row['cred_depositante'],
                    'operacion' => 'credito',
                    'domicilio' => $row['cred_domicilio'],
                    'mes' => $row['cred_mes_alq'],
                    'trans' => $row['trans'],
                    'mostrar' => 1
                );
                if (isset($cuenta)) {
                    if ($cuenta == 'cc_saldo') {
                        $this->data['entrada_prin'] += $arreglo['monto'];
                        array_push($this->data['alquileres'], $arreglo);
                    } else {
                        $this->data['entrada_sec'] += $arreglo['monto'];
                        array_push($this->data['varios'], $arreglo);
                    }
                }
                $agregar = 0;
            }
        }
        $gestion_cobro = 0;
        foreach ($debitos_prop->result_array() as $row) {
            $agregar = $this->comp_fecha($row['deb_fecha'], $desde, $hasta);
            foreach ($conceptos->result_array() as $con) {
                if (strpos($row['deb_concepto'], $con['conc_desc']) !== FALSE && $con['conc_tipo'] == 'Salida') {
                    $cuenta = $con['conc_cc'];
                    break;
                }
            }
            if ($agregar == '11') {
                if (strpos($row['deb_concepto'], "Gestion de Cobro") !== false) {
                    $gestion_cobro += $row['deb_monto'];
                } else {
                    $arreglo = array(
                        'id' => $row['deb_id'],
                        'fecha' => $row['deb_fecha'],
                        'concepto' => $row['deb_concepto'],
                        'mes' => $row['deb_mes'],
                        'monto' => $row['deb_monto'],
                        'domicilio' => $row['deb_domicilio'],
                        'trans' => $row['trans'],
                        'operacion' => 'debito',
                        'mostrar' => 1
                    );
                    if (isset($cuenta)) {
                        if ($cuenta == 'cc_saldo') {
                            $this->data['salida_prin'] += $arreglo['monto'];
                            array_push($this->data['alquileres'], $arreglo);
                        } else {
                            $this->data['salida_sec'] += $arreglo['monto'];
                            array_push($this->data['varios'], $arreglo);
                        }
                    }
                }
                $agregar = 0;
            }
        }
        $arreglo = array(
            'id' => '',
            'fecha' => '',
            'concepto' => 'Gestion de Cobro',
            'mes' => '',
            'monto' => $gestion_cobro,
            'domicilio' => '',
            'trans' => '999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999',
            'operacion' => 'debito',
            'mostrar' => 1
        );
        $this->data['salida_prin'] += $arreglo['monto'];
        array_push($this->data['alquileres'], $arreglo);
        $this->data['alquileres'] = $this->msort($this->data['alquileres'], 'trans');
        $this->data['varios'] = $this->msort($this->data['varios'], 'trans');
        $this->data['content'] = $this->load->view('manager/reportes/informe_prop', $this->data, TRUE);
        $this->load->view('default', $this->data);
    }

    function migrar() {
        $this->load_similar_content('migrar');
        $this->eliminar_vacios();
        $this->data['content'] = $this->load->view('manager/transacciones/migrar', '', TRUE);
        $this->load->view('default', $this->data);
    }

    function save_migracion_inside() {
        $this->form_validation->set_rules('origen', 'Cuenta Cte.', "required|trim");
        $this->form_validation->set_rules('concepto1', 'Concepto', "required|trim");
        $this->form_validation->set_rules('monto1', 'Monto', "required|trim|numeric");
        $this->form_validation->set_rules('auto_id_interno', 'Cta. Cte.: ' . $this->input->post('origen') . ' no existe. Creelo, ', "required|numeric");
        $this->form_validation->set_rules('auto_conc_id_int', ' Concepto: ' . $this->input->post('concepto') . ' debe existir tanto de Entrada como de Salida. Creelo, ', "required|numeric");

        if ($this->form_validation->run() == TRUE) {
            $transac = $this->basic->get_all('trans')->row_array();
            $trans = $transac['trans'];
            $credito = array(
                'cred_depositante' => $this->input->post('origen'),
                'cred_cc' => $this->input->post('origen'),
                'cred_forma' => $this->input->post('cred_forma'),
                'cred_banco' => $this->input->post('banco'),
                'cred_nro_cheque' => $this->input->post('nro_cheque'),
                'cred_mes_alq' => $this->input->post('mes1'),
                'cred_concepto' => $this->input->post('concepto1'),
                'cred_monto' => $this->input->post('monto1'),
                'cred_fecha' => Date('d-m-Y'),
                'cred_interes' => '',
                'trans' => $trans
            );
            $debito = array(
                'deb_cc' => $this->input->post('origen'),
                'deb_concepto' => $this->input->post('concepto1'),
                'deb_monto' => $this->input->post('monto1'),
                'deb_fecha' => Date('d-m-Y'),
                'trans' => $trans
            );
            $conceptos = $this->basic->get_all('conceptos');
            $cuenta = false;
            foreach ($conceptos->result() as $con) {
                if (strpos($credito['cred_concepto'], $con->conc_desc) !== FALSE) {
                    $cuenta = $con->conc_cc;
                }
            }
            if ($cuenta != false) {
                $cc = $this->basic->get_where('cuentas_corrientes', array('cc_prop' => $this->input->post('origen')))->row_array();
                if ($this->input->post('direccion') == 'cc_saldo') {
                    $cc['cc_varios'] -= $credito['cred_monto'];
                    $cc['cc_saldo'] += $credito['cred_monto'];
                } else {
                    $cc['cc_varios'] += $credito['cred_monto'];
                    $cc['cc_saldo'] -= $credito['cred_monto'];
                }
                $this->basic->save('creditos', 'cred_id', $credito);
                $this->basic->save('debitos', 'deb_id', $debito);
                $this->basic->save('cuentas_corrientes', 'cc_id', $cc);
                $transac['trans'] = $transac['trans'] + 1;
                $this->basic->save('trans', 'trans_id', $transac);
                $response['html'] = $this->load->view('manager/transacciones/migrar', '', TRUE);
            } else {
                $response['js'] = "$('#com_display').css('display','block');$('#com_display').addClass('alert-error');$('#com_display').css('margi-left','27px');";
                $response['html'] = 'El concepto ' . $debito['deb_concepto'] . ' no Existe, creelo por favor.';
                $response['error'] = '1';
            }
        } else {
            $response['js'] = "$('#com_display').css('display','block');$('#com_display').addClass('alert-error');$('#com_display').css('margi-left','27px')";
            $response['html'] = validation_errors();
            $response['error'] = '1';
        }
        echo json_encode($response);
    }

    function save_migracion() {
        $this->form_validation->set_rules('origen', 'Cuenta Origen', "required|trim");
        $this->form_validation->set_rules('destino', 'Cuenta Destino', "required|trim");
        $this->form_validation->set_rules('concepto', 'Concepto', "required|trim");
        $this->form_validation->set_rules('monto', 'Monto', "required|trim|numeric");
        $this->form_validation->set_rules('auto_dest_id', 'Cta. Cte.: ' . $this->input->post('destino') . ' no existe. Creelo, ', "required|numeric");
        $this->form_validation->set_rules('auto_orig_id', 'Cta. Cte.: ' . $this->input->post('origen') . ' no existe. Creelo, ', "required|numeric");
        $this->form_validation->set_rules('auto_conc_id', ' Concepto: ' . $this->input->post('concepto') . ' debe existir tanto de Entrada como de Salida. Creelo, ', "required|numeric");

        if ($this->form_validation->run() == TRUE) {
            $transac = $this->basic->get_all('trans')->row_array();
            $trans = $transac['trans'];
            $credito = array(
                'cred_depositante' => $this->input->post('origen'),
                'cred_cc' => $this->input->post('destino'),
                'cred_forma' => $this->input->post('cred_forma'),
                'cred_banco' => $this->input->post('banco'),
                'cred_nro_cheque' => $this->input->post('nro_cheque'),
                'cred_mes_alq' => $this->input->post('mes'),
                'cred_concepto' => $this->input->post('concepto'),
                'cred_monto' => $this->input->post('monto'),
                'cred_tipo_trans' => $this->input->post('cred_tipo_trans'),
                'cred_fecha' => Date('d-m-Y'),
                'cred_interes' => '',
                'trans' => $trans
            );
            $debito = array(
                'deb_tipo_trans' => 'Caja',
                'deb_cc' => $this->input->post('origen'),
                'deb_concepto' => $this->input->post('concepto'),
                'deb_monto' => $this->input->post('monto'),
                'deb_fecha' => Date('d-m-Y'),
                'trans' => $trans
            );
            $conceptos = $this->basic->get_all('conceptos');
            $cuenta = false;
            foreach ($conceptos->result() as $con) {
                if (strpos($credito['cred_concepto'], $con->conc_desc) !== FALSE) {
                    $cuenta = $con->conc_cc;
                }
            }
            if ($cuenta != false) {
                $cc_rima = $this->basic->get_where('cuentas_corrientes', array('cc_prop' => 'Inmobiliaria'))->row_array();
                $cc_orig = $this->basic->get_where('cuentas_corrientes', array('cc_prop' => $this->input->post('origen')))->row_array();
                $cc_dest = $this->basic->get_where('cuentas_corrientes', array('cc_prop' => $this->input->post('destino')))->row_array();
                if ($cc_dest['cc_prop'] != $cc_orig['cc_prop']) {
                    $cc_dest[$cuenta] += $credito['cred_monto'];
                    $diff = $cc_orig['cc_saldo'] + $cc_orig['cc_varios'] - $debito['deb_monto'];
                    $cc_orig[$cuenta] -= $credito['cred_monto'];
                    // Prestamo                
                    if ($diff < 0) {
                        // La cc_prop no tiene el saldo suficiente para el debito
                        // Se migrara desde la cta cte de la Inmobilaria un prestamo para
                        // suplir la falta de dinero a la cuenta Principal 
                        $prestamo = $diff * (-1);
                        $debito_prestamo = array(
                            'deb_cc' => 'INMOBILIARIA',
                            'deb_concepto' => 'Prestamo',
                            'deb_monto' => $prestamo,
                            'deb_tipo_trans' => 'Caja',
                            'deb_fecha' => Date('d-m-Y'),
                            'trans' => $trans
                        );
                        $this->basic->save('debitos', 'deb_id', $debito_prestamo);
                        $credito_prestamo = array(
                            'cred_cc' => $debito['deb_cc'],
                            'cred_depositante' => 'INMOBILIARIA',
                            'cred_concepto' => 'Prestamo',
                            'cred_monto' => $prestamo,
                            'cred_tipo_trans' => 'Caja',
                            'cred_mes_alq' => $this->get_mes(date('m')),
                            'cred_fecha' => Date('d-m-Y'),
                            'trans' => $trans
                        );
                        $this->basic->save('creditos', 'cred_id', $credito_prestamo);
                        $cc_rima['cc_saldo'] -= $prestamo;
                        $cc_orig[$cuenta] += $prestamo;
                    }
                } else {
                    // La cc_prop tiene el saldo suficiente para el debito 
                }
                // Prestamo
                $this->basic->save('creditos', 'cred_id', $credito);
                $this->basic->save('debitos', 'deb_id', $debito);
                $this->basic->save('cuentas_corrientes', 'cc_id', $cc_orig);
                $this->basic->save('cuentas_corrientes', 'cc_id', $cc_dest);
                $this->basic->save('cuentas_corrientes', 'cc_id', $cc_rima);
                $transac['trans'] = $trans + 1;
                $this->basic->save('trans', 'trans_id', $transac);
                $response['html'] = $this->load->view('manager/transacciones/migrar', '', TRUE);
            }
        } else {
            $response['js'] = "$('#com_display').removeClass('alert alert-success');$('#com_display').addClass('alert alert-danger');$('#com_display').fadeIn(1300,'linear');";
            $response['html'] = validation_errors();
            $response['error'] = '1';
        }
        echo json_encode($response);
    }

    function autorizar($id, $codigo_ingresado = false) {
        $code = false;
        $code = $this->basic->get_where('codes', array('code_code' => $codigo_ingresado))->row_array();
        if ($codigo_ingresado != false) {
            if ($code) {
                $response['js'] = "$('#com_display1').css('display','none');";
                $response['js'] .= "$('#interes" . $id . "').removeAttr('readonly');";
                $response['js'] .= "$('#interes" . $id . "').removeAttr('onclick');";
                $response['js'] .= "$('#interes" . $id . "').css('cursor','auto');";
                $response['js'] .= "$('#popup').hide();$('#back_fader').hide();";
                $this->basic->del('codes', 'code_id', $code['code_id']);
            } else {
                $response['html'] = 'El Código de autorización ingresado no es válido';
                $response['js'] = "$('#com_display1').removeClass('alert alert-success');$('#com_display1').addClass('alert alert-danger');$('#com_display1').fadeIn(1300,'linear');";
                $response['error'] = '1';
            }
        } else {
            $response['html'] = 'Ingrese un código';
            $response['js'] = "$('#com_display1').removeClass('alert alert-success');$('#com_display1').addClass('alert alert-danger');$('#com_display1').fadeIn(1300,'linear');";
            $response['error'] = '1';
        }
        echo json_encode($response);
    }

    function save_debitos() {
        $entro = false;
        if ($this->input->post('need_auth') == 1) {
            $entro = true;
            $code = false;
            $code = $this->basic->get_where('codes', array('code_code' => $this->input->post('codigo')))->row_array();
            if ($code) {
                $this->saving_debs();
                $this->basic->del('codes', 'code_id', $code['code_id']);
            } else {
                $response['html'] = 'El Código de autorización ingresado no es válido';
                $response['js'] = "$('#com_display').removeClass('alert alert-success');$('#com_display').addClass('alert alert-danger');$('#com_display').fadeIn(1300,'linear');";
                $response['error'] = '1';
                echo json_encode($response);
            }
        }
        if ($this->input->post('supera_saldo') == 1) {
            $entro = true;
            $response['html'] = 'Las rendiciones no pueden superar el saldo disponible en la Cta. Cte. del propietario';
            $response['js'] = "$('#com_display').removeClass('alert alert-success');$('#com_display').addClass('alert alert-danger');$('#com_display').fadeIn(1300,'linear');";
            $response['error'] = '1';
            echo json_encode($response);
        }
        if (!$entro) {
            $this->saving_debs();
        }
    }

    function saving_debs() {
        $cant_bloques = $this->input->post('cant_bloques');
        $this->form_validation->set_rules('cc', 'Cta. Cte. de Propietario', "required|trim");
        $this->form_validation->set_rules('auto_cc_id', 'Cta. Cte.: ' . $this->input->post('cc') . ' no existe. Creelo, ', "required|numeric");
        for ($x = 1; $x <= $cant_bloques; $x++) {
            $this->form_validation->set_rules('auto_conc_id' . $x, ' Concepto: ' . $this->input->post('concepto' . $x) . ' no existe. Creelo, ', "required|numeric");
            $this->form_validation->set_rules('concepto' . $x, 'Concepto', "required|trim");
            $this->form_validation->set_rules('monto' . $x, 'Monto', "required|numeric");
            $this->form_validation->set_rules('mes' . $x, 'Mes', "callback_meses_check");
        }
        if ($this->form_validation->run() == TRUE) {
            $caja = $this->basic->get_where('mensuales', array('men_mes' => date('m'), 'men_ano' => date('Y')))->row_array();
            if (!$this->input->post('debito_id'))
                $this->input->post();
            $conc_succes = true;
            $transac = $this->basic->get_all('trans')->row_array();
            $trans = $transac['trans'];
            $suma_debitos = 0;
            for ($x = 1; $x <= $cant_bloques; $x++) {
                $r = 0;
                $debito = array(
                    'deb_cc' => $this->input->post('cc'),
                    'deb_forma' => $this->input->post('deb_forma'),
                    'deb_tipo_trans' => $this->input->post('deb_tipo_trans'),
                    'deb_concepto' => $this->input->post('concepto' . $x),
                    'deb_monto' => $this->input->post('monto' . $x),
                    'deb_domicilio' => $this->input->post('domicilio' . $x),
                    'deb_mes' => $this->input->post('mes' . $x),
                    'deb_fecha' => Date('d-m-Y')
                );
                $r = $this->transaccion_debito($debito, $trans);
                if ($r) {
                    $suma_debitos += $debito['deb_monto'];
                } else {
                    $conc_succes = false;
                }
            }
            if ($conc_succes) {
                $caja['men_info'] = 'Crea deb, cc ' . $debito['deb_cc'] . ' $ ' . $suma_debitos;
                // Si no hubo NINGUN error en la transaccion suma los nros de transaccion y los montos de los debitos
                // al mensual de la caja
                $transac['trans'] = $trans + 1;
                $this->basic->save('trans', 'trans_id', $transac);
                $caja['men_debitos'] += $suma_debitos;
                $caja['men_date'] = Date('d-m-Y  h:i:s A');
                $this->basic->save('mensuales', 'men_id', $caja);
                $this->data['debitos'] = $this->basic->get_where('debitos', array(), 'deb_id', 'desc', '50');
                $this->data['lista'] = $this->load->view('manager/transacciones/lista_debitos', $this->data, TRUE);
                $response['html'] = $this->load->view('manager/transacciones/debitos', $this->data, TRUE);
            }
        } else {
//            $response['js'] = "$('.msg_display').css('display','block');$('.msg_display').addClass('alert-error');$('.msg_display').css('margi-left','27px');$('.msg_display').css('float','left');$('.msg_display').css('width','229px')";
            $response['html'] = validation_errors();
            $response['js'] = "$('#com_display').removeClass('alert alert-success');$('#com_display').addClass('alert alert-danger');$('#com_display').fadeIn(1300,'linear');";
            $response['error'] = '1';
        }
        echo json_encode($response);
    }

    function load_authorization() {
        $this->data['post'] = $_POST;
        $response['html'] = $this->load->view('manager/transacciones/form_authorization', $this->data, TRUE);
        echo json_encode($response);
    }

    function devoluciones($credito, $cc_rima, $cc_prop, $trans) {
        $prestamos = $this->basic->get_where('creditos', array('cred_cc' => $cc_prop['cc_prop'], 'cred_concepto' => 'Prestamo'));
        $monto_credito = $credito['cred_monto'];
        if (count($prestamos) > 0) {
            foreach ($prestamos->result_array() as $row) {
                // Por cada prestamo percibido por este propietario, veo si el credito ingresado lo puede devolver
                $prestamo = $row['cred_monto'];
                $diff = $monto_credito - $prestamo;
                if ($diff >= 0) {
                    $debito_prestamo = $this->basic->get_where('debitos', array('deb_cc' => 'Inmobiliaria', 'trans' => $row['trans']))->row_array();
                    $row['cred_concepto'] = 'Prestamo Devuelto';
                    $debito_prestamo['deb_concepto'] = 'Prestamo Devuelto';
                    $monto_credito -= $prestamo;
                    $debito_dev_prestamo = array(
                        'deb_cc' => $cc_prop['cc_prop'],
                        'deb_concepto' => 'Devolucion Prestamo',
                        'deb_monto' => $prestamo,
                        'deb_tipo_trans' => 'Caja',
                        'deb_domicilio' => '',
                        'deb_fecha' => Date('d-m-Y'),
                        'trans' => $trans
                    );
                    $this->basic->save('debitos', 'deb_id', $debito_dev_prestamo);
                    $this->basic->save('debitos', 'deb_id', $debito_prestamo);
                    $credito_dev_prestamo = array(
                        'cred_cc' => 'Inmobiliaria',
                        'cred_depositante' => $cc_prop['cc_prop'],
                        'cred_concepto' => 'Devolucion Prestamo',
                        'cred_monto' => $prestamo,
                        'cred_tipo_trans' => 'Caja',
                        'cred_domicilio' => '',
                        'cred_mes_alq' => $this->get_mes(date('m')),
                        'cred_fecha' => Date('d-m-Y'),
                        'trans' => $trans
                    );
                    $this->basic->save('creditos', 'cred_id', $credito_dev_prestamo);
                    $this->basic->save('creditos', 'cred_id', $row);
                    $cc_rima['cc_saldo'] += $prestamo;
                    $cc_prop['cc_saldo'] -= $prestamo;
                }
            }
            $this->basic->save('cuentas_corrientes', 'cc_id', $cc_rima);
            $this->basic->save('cuentas_corrientes', 'cc_id', $cc_prop);
        }
    }

    function numtoletras($xcifra) {
        $xarray = array(0 => "Cero",
            1 => "UN", "DOS", "TRES", "CUATRO", "CINCO", "SEIS", "SIETE", "OCHO", "NUEVE",
            "DIEZ", "ONCE", "DOCE", "TRECE", "CATORCE", "QUINCE", "DIECISEIS", "DIECISIETE", "DIECIOCHO", "DIECINUEVE",
            "VEINTI", 30 => "TREINTA", 40 => "CUARENTA", 50 => "CINCUENTA", 60 => "SESENTA", 70 => "SETENTA", 80 => "OCHENTA", 90 => "NOVENTA",
            100 => "CIENTO", 200 => "DOSCIENTOS", 300 => "TRESCIENTOS", 400 => "CUATROCIENTOS", 500 => "QUINIENTOS", 600 => "SEISCIENTOS", 700 => "SETECIENTOS", 800 => "OCHOCIENTOS", 900 => "NOVECIENTOS"
        );
//
        $xcifra = trim($xcifra);
        $xlength = strlen($xcifra);
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
                                    $xsub = $this->subfijo($xaux); // devuelve el subfijo correspondiente (Millón, Millones, Mil o nada)
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
                                    $xsub = $this->subfijo($xaux);
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
                                $xsub = $this->subfijo($xaux);
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

// END FUNCTION

    function subfijo($xx) { // esta función regresa un subfijo para la cifra
        $xx = trim($xx);
        $xstrlen = strlen($xx);
        if ($xstrlen == 1 || $xstrlen == 2 || $xstrlen == 3)
            $xsub = "";
        //
        if ($xstrlen == 4 || $xstrlen == 5 || $xstrlen == 6)
            $xsub = "MIL";
        //
        return $xsub;
    }

// END FUNCTION
    public function meses_check($mes) {
        $mes_form = preg_replace("/[^^A-Za-z (),.]/", "", $mes);
        $mes_form = trim($mes_form);
        $ano_form = preg_replace("/[^0-9 (),.]/", "", $mes);
        $ano_form = trim($ano_form);
        $meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
        $es_mes = false;
        for ($x = 0; $x < count($meses); $x++) {
            if ($meses[$x] == $mes_form) {
                $es_mes = true;
                $x = count($meses);
            }
        }
        if ($ano_form >= (Date('Y') - 10) && $ano_form <= (Date('Y') + 10)) {
            if ($es_mes) {
                return TRUE;
            } else {
                $this->form_validation->set_message('meses_check', 'El campo %s debe estar conformado por el Mes y el Año, Ej: Marzo 2015');
                return FALSE;
            }
        } else {
            $this->form_validation->set_message('meses_check', 'El campo %s debe estar conformado por el Mes y el Año, Ej: Marzo 2015');
            return FALSE;
        }
    }

    function save_creditos() {
        $this->data['total_transaccion'] = 0;
        $cant_bloques = $this->input->post('cant_bloques');
        $this->form_validation->set_rules('depositante', 'Depositante', "required|trim");
        $this->form_validation->set_rules('cc', 'Cta. Cte de Propietario', "required|trim");
        $this->form_validation->set_rules('auto_cc_id', 'Cta. Cte: ' . $this->input->post('cc') . ' no existe. Creelo, ', "required|numeric");
        $this->form_validation->set_rules('auto_depo_id', 'Depositante: ' . $this->input->post('depositante') . ' no existe. Creelo, ', "required|numeric");
        for ($x = 1; $x <= $cant_bloques; $x++) {
            $this->form_validation->set_rules('auto_conc_id' . $x, ' Concepto: ' . $this->input->post('concepto' . $x) . ' no existe. Creelo, ', "required|trim");
            $this->form_validation->set_rules('concepto' . $x, 'Concepto', "required|trim");
            $this->form_validation->set_rules('monto' . $x, 'Monto', "required|trim|numeric");
            $this->form_validation->set_rules('mes' . $x, 'Mes', "callback_meses_check");
        }
        if ($this->form_validation->run() == TRUE) {
            if (!$this->input->post('credito_id'))
                $this->input->post();
            $this->data['prop'] = $this->input->post('cc');
            $transac = $this->basic->get_all('trans')->row_array();
            $trans = $transac['trans'];
            $success_trans = true;
            $do_recibo = false;
            $this->data['creditos_recibo_alquiler'] = array();
            $this->data['creditos_recibo_servicios'] = array();
            $creditos_acumulados = 0;
            $iva_alq = 0;
            $iva_comi = 0;
            $intereses_gastos_acumulados = 0;
            for ($x = 1; $x <= $cant_bloques; $x++) {
                $agregar_a_recibo = false;
                $r = 0;
                $credito = array(
                    'cred_depositante' => $this->input->post('depositante'),
                    'cred_cc' => $this->input->post('cc'),
                    'cred_forma' => $this->input->post('cred_forma'),
                    'cred_tipo_trans' => $this->input->post('cred_tipo_trans'),
                    'cred_banco' => $this->input->post('banco'),
                    'cred_nro_cheque' => $this->input->post('nro_cheque'),
                    'cred_mes_alq' => $this->input->post('mes' . $x),
                    'cred_concepto' => $this->input->post('concepto' . $x),
                    'cred_monto' => $this->input->post('monto' . $x),
                    'cred_fecha' => Date('d-m-Y'),
                    'cred_interes' => preg_replace("/[^0-9]/", "", $this->input->post('interes' . $x)),
                    'cred_domicilio' => $this->input->post('domicilio' . $x),
                    'trans' => $trans,
                    'cred_tipo_pago' => $this->input->post('cred_tipo_pago'),
                    'cred_interes_calculados' => 0
                );
                $contrato = false;
                $usar = false;
                $reserva = false;
                if ($credito['cred_concepto'] == 'Reserva') {
                    $reserva = true;
                    $prop = $credito['cred_cc'];
                    $do_recibo = true;
                    $agregar_a_recibo = true;
                }
                if (strpos($credito['cred_concepto'], 'Alquiler Comercial') !== FALSE || strpos($credito['cred_concepto'], 'Loteo') !== FALSE || strpos($credito['cred_concepto'], 'Alquiler') !== FALSE) {
                    $usar = true;
                }
                if ($credito['cred_concepto'] == 'Comision') {
                    $cc_rima = $this->basic->get_where('cuentas_corrientes', array('cc_prop' => 'INMOBILIARIA'))->row_array();
                    $contrato = $this->basic->get_where('contratos', array('con_enabled' => 1, 'con_prop' => $credito['cred_cc'], 'con_inq' => $credito['cred_depositante']))->row_array();
                    $r = $this->impacto_credito_comision($cc_rima, $credito, $trans, $contrato);
                } else if (strpos($credito['cred_concepto'], 'Alquiler Comercial') !== FALSE) {
                    $contrato = $this->basic->get_where('contratos', array('con_enabled' => 1, 'con_prop' => $credito['cred_cc'], 'con_inq' => $credito['cred_depositante']))->row_array();
                    $r = $this->impacto_credito_comercial($trans, $credito, $contrato, $this->input->post('con_porc'), $this->input->post('con_punitorio'));
                } else {
                    if ((strpos($credito['cred_concepto'], 'Loteo') !== FALSE || strpos($credito['cred_concepto'], 'indemnizacion') !== FALSE || $credito['cred_concepto'] == 'Alquiler' || strpos($credito['cred_concepto'], 'Comision') !== FALSE || strpos($credito['cred_concepto'], 'Intereses') !== FALSE || strpos($credito['cred_concepto'], 'Gestion de Cobro') !== FALSE)) {
                        $contrato = $this->basic->get_where('contratos', array('con_enabled' => 1, 'con_prop' => $credito['cred_cc'], 'con_inq' => $credito['cred_depositante']))->row_array();
                        if ($contrato != false) {
                            $this->basic->save('creditos', 'cred_id', $credito);
                            $r = $this->transaccion_credito($trans, $credito, $contrato, $this->input->post('con_porc'), $this->input->post('con_punitorio'));
                        } else {
                            $r = 0;
                        }
                    } else {
                        $this->basic->save('creditos', 'cred_id', $credito);
                        $r = $this->transaccion_credito($trans, $credito, $contrato, $this->input->post('con_porc'), $this->input->post('con_punitorio'));
                    }
                }
                if ($r) {
                    // $r declara el exito de la creacion del credito.
                    $creditos_acumulados += $credito['cred_monto'];
                    if ((strpos($this->input->post('concepto' . $x), 'Comision') !== FALSE) || (strpos($this->input->post('concepto' . $x), 'Loteo') !== FALSE) || (strpos($this->input->post('concepto' . $x), 'Alquiler') !== FALSE)) {
                        if ($this->input->post('notifica') == 1) {
                            // Declara si se mandara un mail al prop por pago de sus alquileres
                            $interes = 0;
                            if ($this->input->post('interes' . $x) != 0 && $this->input->post('paga_intereses') == 'Si') {
                                $interes = round($credito['cred_monto'] * $this->input->post('interes' . $x) * $this->input->post('con_punitorio'), 2);
                                $gestion_intereses = round(($interes * $this->input->post('con_porc')), 2);
                                $interes = $interes - $gestion_intereses;
                            }
                            if ($this->input->post('con_iva_alq') == 'Si') {
                                $iva_alq = round($credito['cred_monto'] * 0.21, 2);
                            }
                            $this->mail_aviso_prop($credito, $interes, $this->input->post('interes' . $x), $this->input->post('con_porc'), $iva_alq);
                        }
                        $do_recibo = true;
                        $agregar_a_recibo = true;
                        // Si hay intereses en el alquiler o loteo lo adhiero al credito
                        $credito['cred_interes_calculados'] = 0;
                        if ($this->input->post('interes' . $x) != 0 && $this->input->post('paga_intereses') == 'Si') {
                            $interes = round($credito['cred_monto'] * $this->input->post('interes' . $x) * $this->input->post('con_punitorio'), 2);
                            $credito['cred_interes_calculados'] = $interes;
                            $intereses_gastos_acumulados += $interes;
                        }
                        if ($this->input->post('paga_intereses') == 'No') {
                            //Si tiene intereses pero no pagara hoy, se le genera un registro de constancia
                            $interes_mora = array(
                                'int_depositante' => $this->input->post('depositante'),
                                'int_cc' => $this->input->post('cc'),
                                'int_fecha_pago' => Date('d-m-Y')
                            );
                            $this->basic->save('intereses_mora', 'int_id', $interes_mora);
                        }
                    } else {
                        $contrato = $this->basic->get_where('contratos', array('con_prop' => $credito['cred_cc'], 'con_inq' => $credito['cred_depositante']))->row_array();
                        if ($contrato != null) {
                            // Si es un servicio del alquiler tambien se registrara
                            // la entrada del dinero en el recibo
                            $do_recibo = true;
                            $agregar_a_recibo = true;
                            $credito['cred_interes_calculados'] = 0;
                            if ($credito['cred_concepto'] == 'Expensas' && $this->input->post('interes' . $x) != 0 && $this->input->post('paga_intereses') == 'Si') {
                                // De ser expensa se revisara si tiene algun interes
                                $interes = round($credito['cred_monto'] * $this->input->post('interes' . $x) * $this->input->post('con_punitorio'), 2);
                                $credito['cred_interes_calculados'] = $interes;
                                $intereses_gastos_acumulados += $interes;
                            }
                        }
                    }
                } else {
                    $success_trans = false;
                }
                // Guardo el credito para mostrarlo en el recibo
                if ($agregar_a_recibo) {
                    if (strpos($credito['cred_concepto'], 'Reserva') !== FALSE || strpos($credito['cred_concepto'], 'Alquiler') !== FALSE || strpos($credito['cred_concepto'], 'Loteo') !== FALSE || strpos($credito['cred_concepto'], 'Comision') !== FALSE) {
                        if (strpos($credito['cred_concepto'], 'Reserva') === FALSE) {
                            $credito['cred_debe_pagar'] = 0;
                            $this->data['periodos'] = $this->basic->get_where('periodos', array('per_contrato' => $contrato['con_id']));
                            $mes_last = preg_replace("/[^^A-Za-z (),.]/", "", $credito['cred_mes_alq']);
                            $mes_last = trim($mes_last);
                            $ano_last = preg_replace("/[^0-9 (),.]/", "", $credito['cred_mes_alq']);
                            $ano_last = trim($ano_last);
                            $mes_last = $this->get_nro_mes($mes_last);
                            $agregar = 0;
                            $debe_pagar = 0;
                            if ($credito['cred_concepto'] != 'Comision') {
                                foreach ($this->data['periodos']->result_array() as $row) {
                                    $inicio = explode('-', $row['per_inicio']);
                                    $fecha = $inicio[0] . '-' . $mes_last . '-' . $ano_last;
                                    $agregar = $this->comp_fecha($fecha, $row['per_inicio'], $row['per_fin']);
                                    if ($agregar == '11') {
                                        $debe_pagar = $row['per_monto'];
                                    }
                                }
                            } else {
                                $debe_pagar = $credito['cred_monto'];
                            }
                            $credito['cred_iva_comi'] = 0;
                            $credito['cred_iva_alq'] = 0;
                            if ($contrato['con_iva'] == 'Si' && $credito['cred_concepto'] == 'Comision') {
                                $credito['cred_iva_comi'] = round($credito['cred_monto'] * 0.21, 2);
                                $iva_comi += $credito['cred_iva_comi'];
                            }
                            if ($contrato['con_iva_alq'] == 'Si' && $credito['cred_concepto'] != 'Comision') {
                                $credito['cred_iva_alq'] = round($credito['cred_monto'] * 0.21, 2);
                                $iva_alq += $credito['cred_iva_alq'];
                            }
                            $credito['cred_debe_pagar'] = $debe_pagar;
                            $credito['cred_neto_a_cobrar'] = $debe_pagar;
                            array_push($this->data['creditos_recibo_alquiler'], $credito);
                        } else {
                            $credito['cred_iva_comi'] = 0;
                            $credito['cred_iva_alq'] = 0;
                            $credito['cred_debe_pagar'] = 0;
                            array_push($this->data['creditos_recibo_alquiler'], $credito);
                        }
                    } else {
                        $credito['usado'] = 0;
                        $credito['usado_cont'] = 0;
                        if ($credito['cred_concepto'] != 'IVA') {
                            array_push($this->data['creditos_recibo_servicios'], $credito);
                            if ($contrato != false) {
                                $this->data['propietario'] = $this->basic->get_where('clientes', array('client_name' => $contrato['con_prop']))->row_array();
                            } else {
                                $this->data['propietario'] = $this->basic->get_where('clientes', array('client_name' => $prop))->row_array();
                            }
                        }
                    }
                }
            }
            if ($success_trans) {
                if ($contrato != null && $usar) {
                    $contrato['con_usado'] = 1;
                    $this->basic->save('contratos', 'con_id', $contrato); // Marco como usado al contrato
                }
                // Si todos los creditos se guardaron exitosamente
                $caja = $this->basic->get_where('mensuales', array('men_mes' => date('m'), 'men_ano' => date('Y')))->row_array();
                $caja['men_creditos'] += $creditos_acumulados;
                $this->data['total_transaccion'] = $creditos_acumulados + $intereses_gastos_acumulados + $iva_comi + $iva_alq;
                $caja['men_date'] = Date('d-m-Y  h:i:s A');
                $caja['men_info'] = 'Crea cred, cc ' . $credito['cred_cc'] . ' depo ' . $credito['cred_depositante'];
                $this->basic->save('mensuales', 'men_id', $caja);
                $transac['trans'] = $trans + 1;
                $this->basic->save('trans', 'trans_id', $transac);
                $this->data['creditos'] = $this->basic->get_where('creditos', array(), 'cred_id', 'desc', '50');
                $this->data['lista'] = $this->load->view('manager/transacciones/lista_creditos', $this->data, TRUE);
                if ($do_recibo) {
                    for ($x = 0; $x < count($this->data['creditos_recibo_alquiler']); $x++) {
                        $creditos_pagados_a_cuenta = $this->basic->get_where('creditos', array('cred_cc' => $this->data['creditos_recibo_alquiler'][$x]['cred_cc'], 'cred_depositante' => $this->data['creditos_recibo_alquiler'][$x]['cred_depositante'], 'cred_mes_alq' => $this->data['creditos_recibo_alquiler'][$x]['cred_mes_alq'], 'cred_tipo_pago' => 'A Cuenta'));
                        $pagado_a_cuenta = 0;
                        if ($creditos_pagados_a_cuenta->num_rows() > 0) {
                            foreach ($creditos_pagados_a_cuenta->result_array() as $row) {
                                if (strpos($row['cred_concepto'], 'Alquiler') !== FALSE || strpos($row['cred_concepto'], 'Loteo') !== FALSE) {
                                    if ($row['cred_monto'] > 0) {
                                        if ($row['trans'] != $this->data['creditos_recibo_alquiler'][$x]['trans']) {
                                            $pagado_a_cuenta += $row['cred_monto'];
                                        }
                                    }
                                }
                            }
                        }
                        if ($this->data['creditos_recibo_alquiler'][$x]['cred_debe_pagar'] == $this->data['creditos_recibo_alquiler'][$x]['cred_monto'] || $this->data['creditos_recibo_alquiler'][$x]['cred_tipo_pago'] == 'Saldo') {
                            $this->data['creditos_recibo_alquiler'][$x]['adeuda'] = 0;
                        } else {
                            $this->data['creditos_recibo_alquiler'][$x]['adeuda'] = $this->data['creditos_recibo_alquiler'][$x]['cred_debe_pagar'] - $pagado_a_cuenta;
                        }

//                        print_r($pagado_a_cuenta . ' + ');
//                        print_r($this->data['creditos_recibo_alquiler'][$x]['cred_interes_calculados']);
                        if (isset($debe_pagar)) {
                            $this->data['creditos_recibo_alquiler'][$x]['cred_neto_a_cobrar'] = $debe_pagar - $pagado_a_cuenta + $this->data['creditos_recibo_alquiler'][$x]['cred_interes_calculados'];
                        } else {
                            $this->data['creditos_recibo_alquiler'][$x]['cred_neto_a_cobrar'] = $this->data['creditos_recibo_alquiler'][$x]['cred_interes_calculados'] - $pagado_a_cuenta;
                        }
                        $this->data['total_transaccion_letra'] = $this->numtoletras($this->data['total_transaccion']);
                    }
                    $this->data['fecha'] = Date('d-m-Y');
                    if ($contrato != false) {
                        $this->data['propietario'] = $this->basic->get_where('clientes', array('client_name' => $contrato['con_prop']))->row_array();
                        $this->data['contrato'] = $contrato;
                    } else {
                        $this->data['propietario'] = $this->basic->get_where('clientes', array('client_name' => $prop))->row_array();
                    }
                    $response['html'] = $this->load->view('manager/transacciones/recibo', $this->data, TRUE);
                } else {
                    $response['html'] = $this->load->view('manager/transacciones/creditos', $this->data, TRUE);
                }
            } else {
                $response['js'] = "$('#com_display').removeClass('alert alert-success');$('#com_display').addClass('alert alert-danger');$('#com_display').fadeIn(1300,'linear');";
                $response['html'] = 'Ha ocurrido un error en la creacion, verifique los datos ingresados';
                $response['error'] = '1';
            }
        } else {
            $response['js'] = "$('#com_display').removeClass('alert alert-success');$('#com_display').addClass('alert alert-danger');$('#com_display').fadeIn(1300,'linear');";
            $response['html'] = validation_errors();
            $response['error'] = '1';
        }
        if ($reserva) {
            $response['html'] = $this->load->view('manager/transacciones/recibo_reserva', $this->data, TRUE);
        }
        echo json_encode($response);
    }

    function transaccion_credito($trans, $credito, $contrato = false, $gestion = false, $punitorio = false) {
        /*
         * -Realiza las transacciones a las Ctas Ctes correspondientes
         * -Crea Pagos correspondientes por computo de gestion de cobro, iva e intereses
         */
        $r = 0;
        $conceptos = $this->basic->get_all('conceptos');
        $cc_rima = $this->basic->get_where('cuentas_corrientes', array('cc_prop' => 'INMOBILIARIA'))->row_array();
        $cc_prop = $this->basic->get_where('cuentas_corrientes', array('cc_prop' => $credito['cred_cc']))->row_array();
        //Funcion de devolucion
        $this->devoluciones($credito, $cc_rima, $cc_prop, $trans);
        $cc_rima = $this->basic->get_where('cuentas_corrientes', array('cc_prop' => 'INMOBILIARIA'))->row_array();
        $cc_prop = $this->basic->get_where('cuentas_corrientes', array('cc_prop' => $credito['cred_cc']))->row_array();
        foreach ($conceptos->result() as $con) {
            if (strpos($credito['cred_concepto'], $con->conc_desc) !== FALSE) {
                $cuenta = $con->conc_cc;
            }
        }
        if ($cuenta == 'cc_saldo') {
            $r = $this->impacto_credito_alquiler($trans, $cc_prop, $cc_rima, $credito, $contrato, $gestion, $punitorio);
        } else {
            $r = $this->impacto_credito_servicios($trans, $cc_prop, $cc_rima, $credito, $punitorio);
        }
        return $r;
    }

    function impacto_credito_servicios($trans, $cc_prop, $cc_rima, $credito, $punitorio) {
        //Los intereses automaticos solo cuando es en blanco lo hace automatico
        if ($credito['cred_interes'] != 0 && $credito['cred_interes'] != '' && isset($punitorio)) {
            //Deposito los intereses cobrados del alquiler en la cuenta del prop
            $monto_castigo = round(($credito['cred_monto'] * $credito['cred_interes'] * $punitorio), 2);
            $credito_intereses = array(
                'cred_depositante' => $credito['cred_depositante'],
                'cred_cc' => $cc_prop['cc_prop'],
                'cred_concepto' => 'Intereses',
                'cred_tipo_trans' => $credito['cred_tipo_trans'],
                'cred_mes_alq' => $credito['cred_mes_alq'],
                'cred_monto' => $monto_castigo,
                'cred_mes_alq' => $credito['cred_mes_alq'],
                'cred_fecha' => $credito['cred_fecha'],
                'cred_forma' => $credito['cred_forma'],
                'cred_interes' => '',
                'cred_tipo_trans' => $credito['cred_tipo_trans'],
                'cred_domicilio' => $credito['cred_domicilio'],
                'trans' => $trans
            );
            $this->basic->save('creditos', 'cred_id', $credito_intereses);
            $cc_prop['cc_saldo'] += $monto_castigo;
            $caja = $this->basic->get_where('mensuales', array('men_mes' => date('m'), 'men_ano' => date('Y')))->row_array();
            $caja['men_creditos'] += $monto_castigo;
            $caja['men_date'] = Date('d-m-Y  h:i:s A');
            $caja['men_info'] = 'Crea cred, cc ' . $credito['cred_cc'] . ' depo ' . $credito['cred_depositante'] . ' monto ' . $credito['cred_monto'];
            $this->basic->save('mensuales', 'men_id', $caja);
        }
        $cc_prop['cc_varios'] += $credito['cred_monto'];
        $r = 1;
        $this->basic->save('cuentas_corrientes', 'cc_id', $cc_prop);
        $this->basic->save('cuentas_corrientes', 'cc_id', $cc_rima);
        return $r;
    }

    function impacto_credito_alquiler($trans, $cc_prop, $cc_rima, $credito, $contrato = false, $gestion = false, $punitorio = false) {
        $r = 0;
        //Realizo los movimientos de gestion de cobro
        $cc_prop['cc_saldo'] += $credito['cred_monto'];
        if ($credito['cred_concepto'] == 'Intereses') {
            /* Pago de Intereses atrasados */
            $ints = false;
            $ints = $this->basic->get_where('intereses_mora', array('int_cc' => $cc_prop['cc_prop'], 'int_depositante' => $credito['cred_depositante']))->row_array();
            if ($ints != false) {
                $id1 = $ints['int_id'];
                $this->basic->del('intereses_mora', 'int_id', $id1);
            }
        }
        if ($contrato != false) {
            $gestion_m = round($credito['cred_monto'] * $gestion, 2);
            $debito_gestion = array(
                'deb_cc' => $cc_prop['cc_prop'],
                'deb_concepto' => 'Gestion de Cobro',
                'deb_monto' => $gestion_m,
                'deb_domicilio' => $credito['cred_domicilio'],
                'deb_tipo_trans' => $credito['cred_tipo_trans'],
                'deb_fecha' => $credito['cred_fecha'],
                'trans' => $trans
            );
            $this->basic->save('debitos', 'deb_id', $debito_gestion);
            $credito_gestion = array(
                'cred_depositante' => $cc_prop['cc_prop'],
                'cred_cc' => $cc_rima['cc_prop'],
                'cred_mes_alq' => $credito['cred_mes_alq'],
                'cred_concepto' => 'Gestion de Cobro',
                'cred_monto' => $gestion_m,
                'cred_tipo_trans' => $credito['cred_tipo_trans'],
                'cred_fecha' => $credito['cred_fecha'],
                'cred_mes_alq' => $credito['cred_mes_alq'],
                'cred_forma' => $credito['cred_forma'],
                'cred_interes' => '',
                'cred_tipo_trans' => $credito['cred_tipo_trans'],
                'cred_domicilio' => $credito['cred_domicilio'],
                'trans' => $trans
            );
            $this->basic->save('creditos', 'cred_id', $credito_gestion);
            //Actualizo cuentas           
            $cc_prop['cc_saldo'] -= round($credito['cred_monto'] * $gestion, 2);
            $cc_rima['cc_saldo'] += round($credito['cred_monto'] * $gestion, 2);
//            }
            //Los intereses automaticos solo cuando es en blanco lo hace automatico
            if ($credito['cred_interes'] != 0 && $credito['cred_interes'] != '' && isset($punitorio)) {
                //Deposito los intereses cobrados del alquiler en la cuenta del prop
                $monto_castigo = round(($credito['cred_monto'] * $credito['cred_interes'] * $punitorio), 2);
                $credito_intereses = array(
                    'cred_depositante' => $credito['cred_depositante'],
                    'cred_cc' => $cc_prop['cc_prop'],
                    'cred_concepto' => 'Intereses',
                    'cred_tipo_trans' => $credito['cred_tipo_trans'],
                    'cred_mes_alq' => $credito['cred_mes_alq'],
                    'cred_monto' => $monto_castigo,
                    'cred_mes_alq' => $credito['cred_mes_alq'],
                    'cred_fecha' => $credito['cred_fecha'],
                    'cred_forma' => $credito['cred_forma'],
                    'cred_interes' => '',
                    'cred_tipo_trans' => $credito['cred_tipo_trans'],
                    'cred_domicilio' => $credito['cred_domicilio'],
                    'trans' => $trans
                );
                $this->basic->save('creditos', 'cred_id', $credito_intereses);
                $cc_prop['cc_saldo'] += $monto_castigo;
                //Quito de la cuenta del propietario la gestion de cobro sobre esos intereses
                $gestion_intereses = round(($monto_castigo * $gestion), 2);
                $debito_gestion_intereses = array(
                    'deb_cc' => $cc_prop['cc_prop'],
                    'deb_concepto' => 'Gestion de Cobro Sobre Intereses',
                    'deb_monto' => $gestion_intereses,
                    'deb_domicilio' => $credito['cred_domicilio'],
                    'deb_fecha' => $credito['cred_fecha'],
                    'trans' => $trans,
                    'deb_tipo_trans' => $credito['cred_tipo_trans'],
                );
                $this->basic->save('debitos', 'deb_id', $debito_gestion_intereses);
                $cc_prop['cc_saldo'] -= $gestion_intereses;
                //Deposito dicha GC a la cta cte de Rima               
                $credito_gestion_intereses = array(
                    'cred_depositante' => $cc_prop['cc_prop'],
                    'cred_cc' => $cc_rima['cc_prop'],
                    'cred_mes_alq' => $credito['cred_mes_alq'],
                    'cred_concepto' => 'Gestion de Cobro Sobre Intereses',
                    'cred_monto' => $gestion_intereses,
                    'cred_forma' => $credito['cred_forma'],
                    'cred_tipo_trans' => $credito['cred_tipo_trans'],
                    'cred_mes_alq' => $credito['cred_mes_alq'],
                    'cred_fecha' => $credito['cred_fecha'],
                    'cred_interes' => '',
                    'cred_tipo_trans' => $credito['cred_tipo_trans'],
                    'cred_domicilio' => $credito['cred_domicilio'],
                    'trans' => $trans
                );
                $this->basic->save('creditos', 'cred_id', $credito_gestion_intereses);
                $cc_rima['cc_saldo'] += $gestion_intereses;
                $caja = $this->basic->get_where('mensuales', array('men_mes' => date('m'), 'men_ano' => date('Y')))->row_array();
                $caja['men_creditos'] += $monto_castigo;
                $caja['men_date'] = Date('d-m-Y  h:i:s A');
                $caja['men_info'] = 'Crea cred, cc ' . $credito['cred_cc'] . ' depo ' . $credito['cred_depositante'];
                $this->basic->save('mensuales', 'men_id', $caja);
            }
        }
        $this->basic->save('cuentas_corrientes', 'cc_id', $cc_rima);
        $this->basic->save('cuentas_corrientes', 'cc_id', $cc_prop);
        $r = 1;
        return $r;
    }

    function impacto_credito_comercial($trans, $credito, $contrato = false, $gestion = false, $punitorio = false) {
        $r = 0;
        if ($contrato != false) {
            // Los intereses se cobran en credito aparte
            $this->basic->save('creditos', 'cred_id', $credito);
            $cc_rima = $this->basic->get_where('cuentas_corrientes', array('cc_prop' => 'INMOBILIARIA'))->row_array();
            $cc_prop = $this->basic->get_where('cuentas_corrientes', array('cc_prop' => $credito['cred_cc']))->row_array();
            $agr = 0;
            $this->devoluciones($credito, $cc_rima, $cc_prop, $trans);
            $cc_rima = $this->basic->get_where('cuentas_corrientes', array('cc_prop' => 'INMOBILIARIA'))->row_array();
            $cc_prop = $this->basic->get_where('cuentas_corrientes', array('cc_prop' => $credito['cred_cc']))->row_array();
            $caja = $this->basic->get_where('mensuales', array('men_mes' => date('m'), 'men_ano' => date('Y')))->row_array();
            $periodos = $this->basic->get_where('periodos', array('per_contrato' => $contrato['con_id']));
            foreach ($periodos->result_array()as $row) {
                //Queda programar si desdea pagar alquiler comercial de periodo pasado como calcular bien el IVA?
                $agr = $this->comp_fecha($credito['cred_fecha'], $row['per_inicio'], $row['per_fin']);
                if ($agr == '11') {
                    $periodo = $row;
                }
            }
            //La Gestion de cobro se calcula de misma forma para ambos comercial blanco o en negro
            $ges = round($credito['cred_monto'] * $contrato['con_porc'], 2);
            $debito_gestion = array(
                'deb_cc' => $cc_prop['cc_prop'],
                'deb_concepto' => 'Gestion de Cobro',
                'deb_monto' => $ges,
                'deb_tipo_trans' => $credito['cred_tipo_trans'],
                'deb_fecha' => $credito['cred_fecha'],
                'deb_domicilio' => $credito['cred_domicilio'],
                'trans' => $trans
            );
            $credito_gestion = array(
                'cred_depositante' => $credito['cred_cc'],
                'cred_cc' => $cc_rima['cc_prop'],
                'cred_forma' => $credito['cred_forma'],
                'cred_banco' => $credito['cred_banco'],
                'cred_nro_cheque' => $credito['cred_nro_cheque'],
                'cred_concepto' => 'Gestion de Cobro',
                'cred_mes_alq' => $credito['cred_mes_alq'],
                'cred_monto' => $ges,
                'cred_fecha' => $credito['cred_fecha'],
                'cred_interes' => $credito['cred_interes'],
                'cred_tipo_trans' => $credito['cred_tipo_trans'],
                'cred_domicilio' => $credito['cred_domicilio'],
                'trans' => $trans
            );
            $cc_prop['cc_saldo'] += round($credito['cred_monto'] - $ges, 2);
            $cc_rima['cc_saldo'] += $ges;
            if ($credito['cred_concepto'] == 'Alquiler Comercial N') {
                //Si el contrato contempla IVA/alquiler lo cobra
                if ($contrato['con_iva_alq'] == 'Si') {
                    $iva = round($periodo['per_iva'], 2);
                    $credito_iva = array(
                        'cred_depositante' => $credito['cred_depositante'],
                        'cred_cc' => $credito['cred_cc'],
                        'cred_forma' => $credito['cred_forma'],
                        'cred_banco' => $credito['cred_banco'],
                        'cred_mes_alq' => $credito['cred_mes_alq'],
                        'cred_nro_cheque' => $credito['cred_nro_cheque'],
                        'cred_concepto' => 'IVA',
                        'cred_monto' => $iva,
                        'cred_fecha' => $credito['cred_fecha'],
                        'cred_interes' => $credito['cred_interes'],
                        'cred_tipo_trans' => $credito['cred_tipo_trans'],
                        'cred_domicilio' => $credito['cred_domicilio'],
                        'trans' => $trans
                    );
                    $cc_prop['cc_saldo'] += $iva;
                    $caja['men_creditos'] += $iva;
                    $this->basic->save('creditos', 'cred_id', $credito_iva);
                }
            } else if ($credito['cred_concepto'] == 'Alquiler Comercial') {
                //Si el contrato contempla IVA/alquiler lo cobra
                if ($contrato['con_iva_alq'] == 'Si') {
                    $iva = round($credito['cred_monto'] * 0.21, 2);
                    $credito_iva = array(
                        'cred_depositante' => $credito['cred_depositante'],
                        'cred_cc' => $credito['cred_cc'],
                        'cred_forma' => $credito['cred_forma'],
                        'cred_banco' => $credito['cred_banco'],
                        'cred_mes_alq' => $credito['cred_mes_alq'],
                        'cred_nro_cheque' => $credito['cred_nro_cheque'],
                        'cred_concepto' => 'IVA',
                        'cred_monto' => $iva,
                        'cred_fecha' => $credito['cred_fecha'],
                        'cred_interes' => $credito['cred_interes'],
                        'cred_domicilio' => $credito['cred_domicilio'],
                        'cred_tipo_trans' => $credito['cred_tipo_trans'],
                        'trans' => $trans
                    );
                    $cc_prop['cc_saldo'] += $iva;
                    $caja['men_creditos'] += $iva;
                    $this->basic->save('creditos', 'cred_id', $credito_iva);
                }
                //Los intereses automaticos solo cuando es en blanco lo hace automatico
                if ($credito['cred_interes'] != '' && isset($punitorio)) {
                    //Deposito los intereses cobrados del alquiler en la cuenta del prop
                    $monto_castigo = round(($credito['cred_monto'] * $credito['cred_interes'] * $punitorio), 2);
                    $credito_intereses = array(
                        'cred_depositante' => $credito['cred_depositante'],
                        'cred_cc' => $cc_prop['cc_prop'],
                        'cred_concepto' => 'Intereses',
                        'cred_mes_alq' => $credito['cred_mes_alq'],
                        'cred_monto' => $monto_castigo,
                        'cred_mes_alq' => $credito['cred_mes_alq'],
                        'cred_fecha' => $credito['cred_fecha'],
                        'cred_forma' => $credito['cred_forma'],
                        'cred_interes' => '',
                        'cred_tipo_trans' => $credito['cred_tipo_trans'],
                        'cred_domicilio' => $credito['cred_domicilio'],
                        'trans' => $trans
                    );
                    $this->basic->save('creditos', 'cred_id', $credito_intereses);
                    $cc_prop['cc_saldo'] += $monto_castigo;
                    //Quito de la cuenta del propietario la gestion de cobro sobre esos intereses
                    $gestion_intereses = round(($monto_castigo * $gestion), 2);
                    $debito_gestion_intereses = array(
                        'deb_cc' => $cc_prop['cc_prop'],
                        'deb_concepto' => 'Gestion de Cobro Sobre Intereses',
                        'deb_monto' => $gestion_intereses,
                        'deb_tipo_trans' => $credito['cred_tipo_trans'],
                        'deb_domicilio' => $credito['cred_domicilio'],
                        'deb_fecha' => $credito['cred_fecha'],
                        'trans' => $trans
                    );
                    $this->basic->save('debitos', 'deb_id', $debito_gestion_intereses);
                    $cc_prop['cc_saldo'] -= $gestion_intereses;
                    //Deposito dicha GC a la cta cte de Rima               
                    $credito_gestion_intereses = array(
                        'cred_depositante' => $cc_prop['cc_prop'],
                        'cred_cc' => $cc_rima['cc_prop'],
                        'cred_mes_alq' => $credito['cred_mes_alq'],
                        'cred_concepto' => 'Gestion de Cobro Sobre Intereses',
                        'cred_monto' => $gestion_intereses,
                        'cred_forma' => $credito['cred_forma'],
                        'cred_mes_alq' => $credito['cred_mes_alq'],
                        'cred_fecha' => $credito['cred_fecha'],
                        'cred_interes' => '',
                        'cred_tipo_trans' => $credito['cred_tipo_trans'],
                        'cred_domicilio' => $credito['cred_domicilio'],
                        'trans' => $trans
                    );
                    $this->basic->save('creditos', 'cred_id', $credito_gestion_intereses);
                    $cc_rima['cc_saldo'] += $gestion_intereses;
                    $caja['men_creditos'] += $monto_castigo;
                }
            }
            $this->basic->save('debitos', 'deb_id', $debito_gestion);
            $this->basic->save('creditos', 'cred_id', $credito_gestion);
            $this->basic->save('cuentas_corrientes', 'cc_id', $cc_prop);
            $this->basic->save('cuentas_corrientes', 'cc_id', $cc_rima);
            $caja['men_date'] = Date('d-m-Y  h:i:s A');
            $caja['men_info'] = 'Crea cred comercial, cc ' . $credito['cred_cc'] . ' depo ' . $credito['cred_depositante'];
            $this->basic->save('mensuales', 'men_id', $caja);
            $r = 1;
        }
        return $r;
    }

    function impacto_credito_comision($cc_rima, $credito, $trans, $contrato = false) {
        $r = 0;
        $caja = $this->basic->get_where('mensuales', array('men_mes' => date('m'), 'men_ano' => date('Y')))->row_array();
        if ($contrato != false) {

            $credito = array(
                'cred_depositante' => $credito['cred_depositante'],
                'cred_cc' => 'INMOBILIARIA',
                'cred_forma' => $credito['cred_forma'],
                'cred_banco' => $credito['cred_banco'],
                'cred_nro_cheque' => $credito['cred_nro_cheque'],
                'cred_mes_alq' => $credito['cred_mes_alq'],
                'cred_concepto' => $credito['cred_concepto'],
                'cred_tipo_trans' => $credito['cred_tipo_trans'],
                'cred_monto' => $credito['cred_monto'],
                'cred_fecha' => Date('d-m-Y'),
                'cred_interes' => $credito['cred_interes'],
                'cred_domicilio' => $credito['cred_domicilio'],
                'trans' => $credito['trans']
            );
            $this->basic->save('creditos', 'cred_id', $credito);
            //Si el contrato contempla IVA/Comision
            if ($contrato['con_iva'] == 'Si') {
                $iva = round($credito['cred_monto'] * 0.21, 2);
                $iva_comision = array(
                    'cred_depositante' => $credito['cred_depositante'],
                    'cred_cc' => 'INMOBILIARIA',
                    'cred_forma' => $credito['cred_forma'],
                    'cred_banco' => $credito['cred_banco'],
                    'cred_nro_cheque' => $credito['cred_nro_cheque'],
                    'cred_concepto' => 'IVA',
                    'cred_monto' => $iva,
                    'cred_fecha' => Date('d-m-Y'),
                    'cred_mes_alq' => $credito['cred_mes_alq'],
                    'cred_tipo_trans' => $credito['cred_tipo_trans'],
                    'cred_interes' => $credito['cred_interes'],
                    'cred_domicilio' => $credito['cred_domicilio'],
                    'trans' => $trans
                );
                $caja['men_creditos'] += $iva;
                $cc_rima['cc_saldo'] += $iva;
                $this->basic->save('creditos', 'cred_id', $iva_comision);
            }
            $caja['men_date'] = Date('d-m-Y  h:i:s A');
            $caja['men_info'] = 'Crea cred comision, cc ' . $credito['cred_cc'] . ' depo ' . $credito['cred_depositante'] . ' $ ' . $credito['cred_monto'];
            $this->basic->save('mensuales', 'men_id', $caja);
            $cc_rima['cc_saldo'] += $credito['cred_monto'];
            $this->basic->save('cuentas_corrientes', 'cc_id', $cc_rima);
            $r = 1;
        }
        return $r;
    }

    function load_locker($id) {
        $this->data['id'] = $id;
        $response['html'] = $this->load->view('manager/transacciones/form_locker', $this->data, TRUE);
        echo json_encode($response);
    }

    function load_concept() {
        $response['html'] = $this->load->view('manager/transacciones/form_conceptos', '', TRUE);
        echo json_encode($response);
    }

    /* fin transacciones */

    /*
     * Funciones que inician la creacion de los reportes
     */

    function rendiciones_pendientes() {
        $this->eliminar_vacios();
        $this->data['head'] = $this->load->view('partials/head', '', TRUE);
        $this->data['header'] = $this->load->view('partials/header', $this->data, TRUE);
        $this->data['footer'] = $this->load->view('partials/footer', $this->data, TRUE);
        $this->data['content'] = $this->load->view('manager/reportes/pendientes', $this->data, TRUE);
        $this->load->view('default', $this->data);
    }

    function porcentaje_rendiciones() {
        $this->eliminar_vacios();
        $this->data['head'] = $this->load->view('partials/head', '', TRUE);
        $this->data['header'] = $this->load->view('partials/header', $this->data, TRUE);
        $this->data['footer'] = $this->load->view('partials/footer', $this->data, TRUE);
        $this->data['content'] = $this->load->view('manager/reportes/porcentaje', $this->data, TRUE);
        $this->load->view('default', $this->data);
    }

    function por_cobrar() {
        $this->eliminar_vacios();
        $this->data['head'] = $this->load->view('partials/head', '', TRUE);
        $this->data['header'] = $this->load->view('partials/header', $this->data, TRUE);
        $this->data['footer'] = $this->load->view('partials/footer', $this->data, TRUE);
        $this->data['content'] = $this->load->view('manager/reportes/propietarios_cobro', $this->data, TRUE);
        $this->load->view('default', $this->data);
    }

    function prestamos() {
        $this->eliminar_vacios();
        $this->data['head'] = $this->load->view('partials/head', '', TRUE);
        $this->data['header'] = $this->load->view('partials/header', $this->data, TRUE);
        $this->data['footer'] = $this->load->view('partials/footer', $this->data, TRUE);
        $this->data['content'] = $this->load->view('manager/reportes/propietarios_prestamos', $this->data, TRUE);
        $this->load->view('default', $this->data);
    }

    function reporte_morosos() {
        $this->eliminar_vacios();
        $this->data['head'] = $this->load->view('partials/head', '', TRUE);
        $this->data['header'] = $this->load->view('partials/header', $this->data, TRUE);
        $this->data['footer'] = $this->load->view('partials/footer', $this->data, TRUE);
        $this->data['content'] = $this->load->view('manager/reportes/inquilinos_morosos', $this->data, TRUE);
        $this->load->view('default', $this->data);
    }

    function reporte_historial() {
        $this->eliminar_vacios();
        $this->data['head'] = $this->load->view('partials/head', '', TRUE);
        $this->data['header'] = $this->load->view('partials/header', $this->data, TRUE);
        $this->data['footer'] = $this->load->view('partials/footer', $this->data, TRUE);
        $this->data['content'] = $this->load->view('manager/reportes/historial_inquilinos', $this->data, TRUE);
        $this->load->view('default', $this->data);
    }

    function vencimientos() {
        $this->eliminar_vacios();
        $this->data['head'] = $this->load->view('partials/head', '', TRUE);
        $this->data['header'] = $this->load->view('partials/header', $this->data, TRUE);
        $this->data['footer'] = $this->load->view('partials/footer', $this->data, TRUE);
        $this->data['content'] = $this->load->view('manager/reportes/vencimientos', $this->data, TRUE);
        $this->load->view('default', $this->data);
    }

    function bancarias() {
        $this->eliminar_vacios();
        $this->data['head'] = $this->load->view('partials/head', '', TRUE);
        $this->data['header'] = $this->load->view('partials/header', $this->data, TRUE);
        $this->data['footer'] = $this->load->view('partials/footer', $this->data, TRUE);
        $this->data['content'] = $this->load->view('manager/reportes/bancarias', $this->data, TRUE);
        $this->load->view('default', $this->data);
    }

    function mensual() {
        $this->eliminar_vacios();
        $this->data['head'] = $this->load->view('partials/head', '', TRUE);
        $this->data['header'] = $this->load->view('partials/header', $this->data, TRUE);
        $this->data['footer'] = $this->load->view('partials/footer', $this->data, TRUE);
        $this->data['content'] = $this->load->view('manager/reportes/mensual', $this->data, TRUE);
        $this->load->view('default', $this->data);
    }

    function reporte_prop() {
        $this->eliminar_vacios();
        $this->data['head'] = $this->load->view('partials/head', '', TRUE);
        $this->data['header'] = $this->load->view('partials/header', $this->data, TRUE);
        $this->data['footer'] = $this->load->view('partials/footer', $this->data, TRUE);
        $this->data['content'] = $this->load->view('manager/reportes/propietario', $this->data, TRUE);
        $this->load->view('default', $this->data);
    }

    function informar_mensual($desde = false, $hasta = false) {
        if ($desde != false && $hasta != false) {
            $agregar = 0;
            $this->data['conceptos_entrada'] = $this->basic->get_where('conceptos', array('conc_tipo' => 'Entrada'));
            $this->data['conceptos_salida'] = $this->basic->get_where('conceptos', array('conc_tipo' => 'Salida'));
            $debitos = $this->basic->get_all('debitos');
            $creditos = $this->basic->get_all('creditos');
            $this->data['creditos'] = array();
            $this->data['debitos'] = array();
            $this->data['desde'] = $desde;
            $this->data['hasta'] = $hasta;
            $this->data['total_cred'] = 0;
            $this->data['total_deb'] = 0;
            $this->data['gestion'] = 0;
            foreach ($creditos->result_array() as $row) {
                $agregar = $this->comp_fecha($row['cred_fecha'], $desde, $hasta);
                if ($agregar == '11') {
                    array_push($this->data['creditos'], $row);
                    if (strpos($row['cred_concepto'], 'Gestion de Cobro') !== FALSE) {
                        $this->data['gestion'] += $row['cred_monto'];
                    }
                    $this->data['total_cred'] += $row['cred_monto'];
                }
            }
            foreach ($debitos->result_array() as $row) {
                $agregar = $this->comp_fecha($row['deb_fecha'], $desde, $hasta);
                if ($agregar == '11') {
                    if (strpos($row['deb_concepto'], 'Gestion de Cobro') === FALSE) {
                        array_push($this->data['debitos'], $row);
                        $this->data['total_deb'] += $row['deb_monto'];
                    }
                }
            }
            $this->data['total_cred'] = $this->data['total_cred'] - $this->data['gestion'];
            $response['html'] = $this->load->view('manager/reportes/informe_mensual', $this->data, TRUE);
            echo json_encode($response);
        } else {
            $response['texto'] = 'Datos Faltantes';
            $response['js'] = "$('#com_display').html(R.texto);$('#com_display').removeClass('alert alert-success');$('#com_display').addClass('alert alert-danger');$('#com_display').css('display','block');$('#com_display').fadeOut(3500, 'linear');";
            echo json_encode($response);
        }
    }

    function informar_bancarias($desde = false, $hasta = false) {
        if ($desde != false && $hasta != false) {
            $agregar = 0;
            $entradas = $this->basic->get_where('creditos', array('cred_tipo_trans' => 'Bancaria'));
            $salidas = $this->basic->get_where('debitos', array('deb_tipo_trans' => 'Bancaria'));
            $this->data['creditos'] = array();
            $this->data['debitos'] = array();
            $this->data['desde'] = $desde;
            $this->data['hasta'] = $hasta;
            $this->data['total_cred'] = 0;
            $this->data['total_deb'] = 0;
            foreach ($entradas->result_array() as $row) {
                $agregar = $this->comp_fecha($row['cred_fecha'], $desde, $hasta);
                if ($agregar == '11') {
                    if (strpos($row['cred_concepto'], 'Gestion de Cobro') === FALSE) {
                        array_push($this->data['creditos'], $row);
                        $this->data['total_cred'] += $row['cred_monto'];
                    }
                }
            }
            foreach ($salidas->result_array() as $row) {
                $agregar = $this->comp_fecha($row['deb_fecha'], $desde, $hasta);
                if ($agregar == '11') {
                    if (strpos($row['deb_concepto'], 'Gestion de Cobro') === FALSE) {
                        array_push($this->data['debitos'], $row);
                        $this->data['total_deb'] += $row['deb_monto'];
                    }
                }
            }

            $this->data['creditos'] = $this->msort($this->data['creditos'], 'trans');
            $this->data['debitos'] = $this->msort($this->data['debitos'], 'trans');
            $response['html'] = $this->load->view('manager/reportes/informe_bancarias', $this->data, TRUE);
            echo json_encode($response);
        } else {
            $response['texto'] = 'Datos Faltantes';
            $response['js'] = "$('#com_display').html(R.texto);$('#com_display').removeClass('alert alert-success');$('#com_display').addClass('alert alert-danger');$('#com_display').css('display','block');$('#com_display').fadeOut(3500, 'linear');";
            echo json_encode($response);
        }
    }

    function informar_prestamos($desde = false, $hasta = false) {
        if ($desde != false && $hasta != false) {
            $prestamos_creds = $this->basic->get_where('creditos', array('cred_depositante' => 'Inmobiliaria'));
            $this->data['desde'] = $desde;
            $this->data['hasta'] = $hasta;
            $this->data['total_prestado'] = 0;
            $this->data['total_devuelto'] = 0;
            $this->data['prestamos_mora'] = array();
            $this->data['prestamos_devueltos'] = array();
            foreach ($prestamos_creds->result_array() as $row) {
                $agregar = $this->comp_fecha($row['cred_fecha'], $desde, $hasta);
                if ($agregar == '11') {
                    if ($row['cred_concepto'] == 'Prestamo') {
                        $this->data['total_prestado'] += $row['cred_monto'];
                        array_push($this->data['prestamos_mora'], $row);
                    }
                    if ($row['cred_concepto'] == 'Prestamo Devuelto') {
                        $this->data['total_devuelto'] += $row['cred_monto'];
                        array_push($this->data['prestamos_devueltos'], $row);
                    }
                }
            }
            $this->data['prestamos_devueltos'] = $this->msort($this->data['prestamos_devueltos'], 'cred_cc');
            $this->data['prestamos_mora'] = $this->msort($this->data['prestamos_mora'], 'cred_cc');
            $response['html'] = $this->load->view('manager/reportes/informe_prestamos_prop', $this->data, TRUE);
            echo json_encode($response);
        } else {
            $response['texto'] = 'Datos Faltantes';
            $response['js'] = "$('#com_display').html(R.texto);$('#com_display').removeClass('alert alert-success');$('#com_display').addClass('alert alert-danger');$('#com_display').css('display','block');$('#com_display').fadeOut(3500, 'linear');";
            echo json_encode($response);
        }
    }

    function informar_cobros($desde = false, $hasta = false) {
        if ($desde != false && $hasta != false) {
            $agregar = 0;
            $monto = 0;
            $cred_m = 0;
            $deb_m = 0;
            $this->data['props'] = array();
            $this->data['no_cobro'] = array();
            $this->data['positivos'] = array();
            $this->data['negativos'] = array();
            $cobros_prop = array();
            $this->data['desde'] = $desde;
            $this->data['monto_total'] = 0;
            $debitos = $this->basic->get_all('debitos');
            $creditos = $this->basic->get_all('creditos');
            foreach ($debitos->result_array() as $row) {
                $deb_m += $row['deb_monto'];
            }
            foreach ($creditos->result_array() as $row) {
                $cred_m += $row['cred_monto'];
            }
            $this->data['caja'] = $cred_m - $deb_m;
            $this->data['pri_total'] = 0;
            $this->data['sec_total'] = 0;
            $this->data['total_ccs'] = 0;
            $this->data['hasta'] = $hasta;
            $props = $this->basic->get_where('cuentas_corrientes', array(), 'cc_prop');
            $cobros = $this->basic->get_where('debitos', array('deb_concepto' => 'Rendicion'));
            foreach ($cobros->result_array() as $row) {
                $agregar = $this->comp_fecha($row['deb_fecha'], $desde, $hasta);
                if ($agregar == '11') {
                    array_push($cobros_prop, $row);
                    $agregar = 0;
                }
            }
            foreach ($props->result_array() as $rowp) {
                if (strpos($rowp['cc_prop'], 'CAJA FUERTE') === FALSE) {
                    $debitos = $this->basic->get_where('debitos', array('deb_cc' => $rowp['cc_prop']));
                    $creditos = $this->basic->get_where('creditos', array('cred_cc' => $rowp['cc_prop']));
                    foreach ($debitos->result_array() as $row) {
                        // BUSCA LOS RETIROS POR CONCEPTO DE ALQUILER
                        if ($row['deb_concepto'] == 'Rendicion') {
                            $agregar = $this->comp_fecha($row['deb_fecha'], $desde, $hasta);
                            if ($agregar == '11') {
                                $monto += $row['deb_monto'];
                            }
                        }
                    }
                    $entradas = 0;
                    $salidas = 0;
                    foreach ($creditos->result_array() as $row) {
                        $agregar = $this->comp_fecha($row['cred_fecha'], $desde, $hasta);
                        if ($agregar == '11') {
                            $entradas += $row['cred_monto'];
                        }
                    }
                    foreach ($debitos->result_array() as $row) {
                        $agregar = $this->comp_fecha($row['deb_fecha'], $desde, $hasta);
                        if ($agregar == '11') {
                            $salidas += $row['deb_monto'];
                        }
                    }
                    $prop = array(
                        'id' => $rowp['cc_id'],
                        'prop' => $rowp['cc_prop'],
                        'retiro' => $monto,
                        'entro' => $entradas,
                        'salio' => $salidas,
                        'total_cc' => round($entradas - $salidas, 2),
                        'cobro' => 0
                    );
                    // Marcar los propietarios segun, 
                    // -los que no cobraron Alquileres

                    $this->data['monto_total'] += $monto;
                    $this->data['total_ccs'] += $entradas - $salidas;
                    if (count($cobros_prop) > 0) {
                        for ($x = 0; $x < count($cobros_prop); $x++) {
                            if ($prop['prop'] == $cobros_prop[$x]['deb_cc']) {
                                $prop['cobro'] = 1;
                                break;
                            }
                        }
                    }
                    if ($prop['total_cc'] < 0) {
                        array_push($this->data['negativos'], $prop);
                    } else {
                        array_push($this->data['positivos'], $prop);
                    }
                    $agregar = 0;
                    $monto = 0;
                    // -los que tienen saldo negativo
                    // -los que tienen saldo positivo
                }
            }
            $response['html'] = $this->load->view('manager/reportes/informe_lista_prop', $this->data, TRUE);
            echo json_encode($response);
        } else {
            $response['texto'] = 'Datos Faltantes';
            $response['js'] = "$('#com_display').html(R.texto);$('#com_display').removeClass('alert alert-success');$('#com_display').addClass('alert alert-danger');$('#com_display').css('display','block');$('#com_display').fadeOut(3500, 'linear');";
            echo json_encode($response);
        }
    }

    function informar_historial_inq($ano = false, $inquilino = false, $domicilio = false) {
        if ($ano != false && $inquilino != false && $domicilio != false) {
            $inquilino = urldecode($inquilino);
            $domicilio = urldecode($domicilio);
            $contrato = false;
            $this->data['contrato'] = $this->basic->get_where('contratos', array('con_inq' => $inquilino, 'con_domi' => $domicilio))->row_array();
            $this->data['ano'] = $ano;
            $contrato = $this->data['contrato'];
            $this->data['inquilino'] = $inquilino;
            $this->data['domicilio'] = $domicilio;
            if ($contrato != false) {
                $creditos = $this->basic->get_where('creditos', array('cred_depositante' => $inquilino, 'cred_concepto' => $contrato['con_tipo']));
                $agregar = 0;
                $mes = 1;
                $this->data['pagos'] = array();
                foreach ($creditos->result_array() as $row) {
                    $agregar = $this->comp_ano($row['cred_fecha'], $ano);
                    if ($agregar == '11') {
                        $fecha_pago = explode('-', $row['cred_fecha']);
                        $dias_mora = $fecha_pago[0] - $contrato['con_tolerancia'];
                        if ($dias_mora <= 0) {
                            $dias_mora = 0;
                        } else {
                            $dias_mora = $fecha_pago[0];
                        }
                        $mes_string = $this->get_mes($mes);
                        $arreglo = array(
                            'mes' => $mes_string,
                            'fecha_pago' => $row['cred_fecha'],
                            'mes_pagado' => $row['cred_mes_alq'],
                            'tipo' => $row['cred_tipo_pago'],
                            'dias_mora' => $dias_mora,
                            'monto' => $row['cred_monto'],
                            'id' => $row['cred_id'],
                        );
                        array_push($this->data['pagos'], $arreglo);
                        $mes++;
                    }
                }
                $this->data['pagos'] = $this->msort($this->data['pagos'], 'id');
                $response['html'] = $this->load->view('manager/reportes/informe_historial_inquilino', $this->data, TRUE);
                echo json_encode($response);
            } else {
                $response['texto'] = 'No existe vinculacion por contrato entre el Inquilino y el Inmueble';
                $response['js'] = "$('#com_display').html(R.texto);$('#com_display').removeClass('alert alert-success');$('#com_display').addClass('alert alert-danger');$('#com_display').css('display','block');$('#com_display').fadeOut(3500, 'linear');";
                echo json_encode($response);
            }
        } else {
            $response['texto'] = 'Datos Faltantes';
            $response['js'] = "$('#com_display').html(R.texto);$('#com_display').removeClass('alert alert-success');$('#com_display').addClass('alert alert-danger');$('#com_display').css('display','block');$('#com_display').fadeOut(3500, 'linear');";
            echo json_encode($response);
        }
    }

    function informar_prop($desde = false, $hasta = false, $cuenta = false) {
        if ($desde != false && $hasta != false && $cuenta != false) {
            $cuenta = urldecode($cuenta);
            $agregar = 0;
            $agregar1 = 0;
            $this->data['prop'] = $this->basic->get_where('cuentas_corrientes', array('cc_prop' => $cuenta))->row_array();
            $propie = $this->data['prop'];
            $this->data['comentarios'] = $this->basic->get_where('comentarios', array('com_prop' => $cuenta, 'com_mes' => date('m'), 'com_ano' => date('Y')));
            $this->data['entrada_prin'] = 0;
            $this->data['salida_prin'] = 0;
            $this->data['salida_sec'] = 0;
            $this->data['entrada_sec'] = 0;
            $this->data['desde'] = $desde;
            $this->data['hasta'] = $hasta;
            $this->data['cuenta'] = $cuenta;
            $this->data['intereses_mora'] = array();
            $this->data['alquileres'] = array();
            $rendiciones = $this->basic->get_where('debitos', array('deb_cc' => $cuenta, 'deb_concepto' => 'Rendicion'));
            $monto_rendicion_hoy = 0;
            $this->data['monto_rendicion_domis'] = '';
            $this->data['monto_rendicion_meses'] = '';
            foreach ($rendiciones->result_array() as $row) {
                $agregar1 = $this->comp_fecha($row['deb_fecha'], Date('d-m-Y'), Date('d-m-Y'));
                if ($agregar1 == '11') {
                    $monto_rendicion_hoy += $row['deb_monto'];
                    $this->data['monto_rendicion_domis'] .= $row['deb_domicilio'] . ', ';
                    $this->data['monto_rendicion_meses'] .= $row['deb_mes'] . ', ';
                }
            }
            $this->data['monto_rendicion_hoy'] = round($monto_rendicion_hoy, 2);
            $this->data['monto_rendicion_hoy_letra'] = $this->numtoletras(round($monto_rendicion_hoy, 2));
            $this->data['servicios'] = $this->basic->get_all('servicios');
            $this->data['contratos'] = $this->basic->get_where('contratos', array('con_prop' => $cuenta, 'con_enabled' => 1));
            $this->data['conceptos'] = $this->basic->get_all('conceptos');
            $creditos_prop = $this->basic->get_where('creditos', array('cred_cc' => $cuenta), 'cred_id', 'asc');
            $debitos_prop = $this->basic->get_where('debitos', array('deb_cc' => $cuenta), 'deb_id', 'asc');
            $conceptos = $this->basic->get_all('conceptos');
            $ints = $this->basic->get_where('intereses_mora', array('int_cc' => $cuenta));
            foreach ($ints->result_array() as $row) {
                $agregar1 = $this->comp_fecha($row['int_fecha_pago'], $desde, $hasta);
                if ($agregar1 == '11') {
                    array_push($this->data['intereses_mora'], $row);
                    $agregar1 = 0;
                }
            }
            $this->data['varios'] = array();
            /* Agrupo los creditos de alquiler y varios en diferentes arrays, lo mismo ocurre con los debitos */
            foreach ($creditos_prop->result_array() as $row) {
                foreach ($conceptos->result_array() as $con) {
                    if (strpos($row['cred_concepto'], $con['conc_desc']) !== FALSE && $con['conc_tipo'] == 'Entrada') {
                        $cuenta = $con['conc_cc'];
                        break;
                    }
                }
                $agregar = $this->comp_fecha($row['cred_fecha'], $desde, $hasta);
                $por_mes = $this->comp_alq($row, $this->get_mes(date('m')));
                if ($agregar == '11') {
//                    if ($agregar != '11') {
//                        /* No es de la fecha de los rangos el pago pero es del mes presente, x ej Mes Pagado Adelantado 
//                         * Entonces este credito no se va mostrar en el resumen solo va ser para dejar en claro q se pago el
//                         * alquiler
//                         */
//                        $arreglo = array(
//                            'id' => $row['cred_id'],
//                            'fecha' => $row['cred_fecha'],
//                            'concepto' => $row['cred_concepto'],
//                            'monto' => $row['cred_monto'],
//                            'mes' => $row['cred_mes_alq'],
//                            'depositante' => $row['cred_depositante'],
//                            'domicilio' => $row['cred_domicilio'],
//                            'operacion' => 'credito',
//                            'trans' => $row['trans'],
//                            'mostrar' => 0
//                        );
//                    } else {
                    /* Pertenece a un credito del rango de fechas ingresados */
                    $arreglo = array(
                        'id' => $row['cred_id'],
                        'mes' => $row['cred_mes_alq'],
                        'fecha' => $row['cred_fecha'],
                        'concepto' => $row['cred_concepto'],
                        'monto' => $row['cred_monto'],
                        'depositante' => $row['cred_depositante'],
                        'operacion' => 'credito',
                        'domicilio' => $row['cred_domicilio'],
                        'mes' => $row['cred_mes_alq'],
                        'trans' => $row['trans'],
                        'mostrar' => 1
                    );
//                    }
                    if (isset($cuenta)) {
                        if ($cuenta == 'cc_saldo') {
                            $this->data['entrada_prin'] += $arreglo['monto'];
                            array_push($this->data['alquileres'], $arreglo);
                        } else {
                            $this->data['entrada_sec'] += $arreglo['monto'];
                            array_push($this->data['varios'], $arreglo);
                        }
                    }

                    $agregar = 0;
                }
            }
            $gestion_cobro = 0;
            foreach ($debitos_prop->result_array() as $row) {
                $agregar = $this->comp_fecha($row['deb_fecha'], $desde, $hasta);
                foreach ($conceptos->result_array() as $con) {
                    if (strpos($row['deb_concepto'], $con['conc_desc']) !== FALSE && $con['conc_tipo'] == 'Salida') {
                        $cuenta = $con['conc_cc'];
                        break;
                    }
                }
                if ($agregar == '11') {
                    if (strpos($row['deb_concepto'], "Gestion de Cobro") !== false) {
                        $gestion_cobro += $row['deb_monto'];
                    } else {
                        $arreglo = array(
                            'id' => $row['deb_id'],
                            'fecha' => $row['deb_fecha'],
                            'concepto' => $row['deb_concepto'],
                            'mes' => $row['deb_mes'],
                            'monto' => $row['deb_monto'],
                            'domicilio' => $row['deb_domicilio'],
                            'trans' => $row['trans'],
                            'operacion' => 'debito',
                            'mostrar' => 1
                        );
                        if (isset($cuenta)) {
                            if ($cuenta == 'cc_saldo') {
                                $this->data['salida_prin'] += $arreglo['monto'];
                                array_push($this->data['alquileres'], $arreglo);
                            } else {
                                $this->data['salida_sec'] += $arreglo['monto'];
                                array_push($this->data['varios'], $arreglo);
                            }
                        }
                    }
                    $agregar = 0;
                }
            }
            $arreglo = array(
                'id' => '',
                'fecha' => '',
                'concepto' => 'Gestion de Cobro',
                'mes' => '',
                'monto' => $gestion_cobro,
                'domicilio' => '',
                'trans' => '999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999',
                'operacion' => 'debito',
                'mostrar' => 1
            );
            $this->data['salida_prin'] += $arreglo['monto'];
            array_push($this->data['alquileres'], $arreglo);
            $this->data['alquileres'] = $this->msort($this->data['alquileres'], 'trans');
            $this->data['varios'] = $this->msort($this->data['varios'], 'trans');
            $response['html'] = $this->load->view('manager/reportes/informe_prop', $this->data, TRUE);
            echo json_encode($response);
        } else {
            $response['texto'] = 'Datos Faltantes';
            $response['js'] = "$('#com_display').html(R.texto);$('#com_display').removeClass('alert alert-success');$('#com_display').addClass('alert alert-danger');$('#com_display').css('display','block');$('#com_display').fadeOut(3500, 'linear');";
            echo json_encode($response);
        }
    }

    function informar_porcentaje($desde = false, $hasta = false) {
        if ($desde != false && $hasta != false) {
            $this->data['desde'] = $desde;
            $this->data['hasta'] = $hasta;
            $activos = array();
            $cuentas = $this->basic->get_all('cuentas_corrientes');
            $contratos = $this->basic->get_where('contratos', array('con_enabled' => 1));
            foreach ($cuentas->result_array() as $row) {
                foreach ($contratos->result_array() as $con) {
                    if ($row['cc_prop'] == $con['con_prop']) {
                        if (!in_array($row, $activos)) {
                            array_push($activos, $row);
                        }
                    }
                }
            }
            $this->data['props_activos'] = count($activos);
            $this->data['encabezado'] = 'Porcentaje de rendiciones entre las fechas ' . $desde . ' y ' . $hasta;
            $rendidos = array();
            foreach ($cuentas->result_array() as $row) {
                if ($row['cc_prop'] != 'INMOBILIARIA' && $row['cc_prop'] != 'CAJA FUERTE') {
                    $rendiciones = $this->basic->get_where('debitos', array('deb_cc' => $row['cc_prop'], 'deb_concepto' => 'Rendicion'));
                    foreach ($rendiciones->result_array() as $rend) {
                        if ($this->comp_fecha($rend['deb_fecha'], $desde, $hasta) == '11') {
                            if (!in_array($row, $rendidos)) {
                                array_push($rendidos, $row);
                            }
                        }
                    }
                }
            }
            $this->data['props_rendidos'] = count($rendidos);
            $response['html'] = $this->load->view('manager/reportes/informe_porcentaje', $this->data, TRUE);
            echo json_encode($response);
        } else {
            $response['texto'] = 'Datos Faltantes';
            $response['js'] = "$('#com_display').html(R.texto);$('#com_display').removeClass('alert alert-success');$('#com_display').addClass('alert alert-danger');$('#com_display').css('display','block');$('#com_display').fadeOut(3500, 'linear');";
            echo json_encode($response);
        }
    }

    function informar_pendientes($desde = false, $hasta = false) {
        if ($desde != false && $hasta != false) {
            $this->data['desde'] = $desde;
            $this->data['hasta'] = $hasta;
            $cuentas = $this->basic->get_all('cuentas_corrientes');
            $this->data['encabezado'] = 'Informe de rendiciones pendientes entre las fechas ' . $desde . ' y ' . $hasta;
            $this->data['pendientes'] = array();
            foreach ($cuentas->result_array() as $row) {
                $retiro = false;
                if ($row['cc_prop'] != 'INMOBILIARIA' && $row['cc_prop'] != 'CAJA FUERTE') {
                    $rendiciones = $this->basic->get_where('debitos', array('deb_cc' => $row['cc_prop'], 'deb_concepto' => 'Rendicion'));
                    foreach ($rendiciones->result_array() as $rend) {
                        if ($this->comp_fecha($rend['deb_fecha'], $desde, $hasta) == '11') {
                            $retiro = true;
                        }
                    }
                }
                if (!$retiro) {
                    if ($row['cc_prop'] != 'INMOBILIARIA' && $row['cc_prop'] != 'CAJA FUERTE') {
                        if (($row['cc_saldo'] + $row['cc_varios']) > 0) {
                            array_push($this->data['pendientes'], $row);
                        }
                    }
                }
            }
            $response['html'] = $this->load->view('manager/reportes/informe_pendientes', $this->data, TRUE);
            echo json_encode($response);
        } else {
            $response['texto'] = 'Datos Faltantes';
            $response['js'] = "$('#com_display').html(R.texto);$('#com_display').removeClass('alert alert-success');$('#com_display').addClass('alert alert-danger');$('#com_display').css('display','block');$('#com_display').fadeOut(3500, 'linear');";
            echo json_encode($response);
        }
    }

    function comp_alq($credito, $mes) {
        $ret = '0';
        if (strpos($credito['cred_concepto'], "Alquiler") !== false) {
            if ($credito['cred_mes_alq'] == $mes) {
                $ret = '11';
            }
        }
        return $ret;
    }

    function msort($array, $key, $sort_flags = SORT_REGULAR) {
        if (is_array($array) && count($array) > 0) {
            if (!empty($key)) {
                $mapping = array();
                foreach ($array as $k => $v) {
                    $sort_key = '';
                    if (!is_array($key)) {
                        $sort_key = $v[$key];
                    } else {
// @TODO This should be fixed, now it will be sorted as string
                        foreach ($key as $key_key) {
                            $sort_key .= $v[$key_key];
                        }
                        $sort_flags = SORT_STRING;
                    }
                    $mapping[$k] = $sort_key;
                }
                asort($mapping, $sort_flags);
                $sorted = array();
                foreach ($mapping as $k => $v) {
                    $sorted[] = $array[$k];
                }
                return $sorted;
            }
        }
        return $array;
    }

    function aasort(&$array, $key) {
        $sorter = array();
        $ret = array();
        reset($array);
        foreach ($array as $ii => $va) {
            $sorter[$ii] = $va[$key];
        }
        asort($sorter);
        foreach ($sorter as $ii => $va) {
            $ret[$ii] = $array[$ii];
        }
        $array = $ret;
    }

    function caja_general() {
        $this->eliminar_vacios();
        $this->data['head'] = $this->load->view('partials/head', '', TRUE);
        $this->data['header'] = $this->load->view('partials/header', $this->data, TRUE);
        $this->data['footer'] = $this->load->view('partials/footer', $this->data, TRUE);
        $this->data['content'] = $this->load->view('manager/reportes/caja_g', $this->data, TRUE);
        $this->load->view('default', $this->data);
    }

    function caja_detallada() {
        $this->eliminar_vacios();
        $this->data['head'] = $this->load->view('partials/head', '', TRUE);
        $this->data['header'] = $this->load->view('partials/header', $this->data, TRUE);
        $this->data['footer'] = $this->load->view('partials/footer', $this->data, TRUE);
        $this->data['content'] = $this->load->view('manager/reportes/caja', $this->data, TRUE);
        $this->load->view('default', $this->data);
    }

    function informar_caja_detallada($fecha = false) {
        if ($fecha != false) {
            $agregar = 0;
            $fecha_v = explode('-', $fecha);
            $desde = explode('-', $fecha);
            $primero = false;
            $dia_anterior = $fecha_v[0] - 1;
            if ($dia_anterior == 0) {
                $primero = true;
                $dia_anterior = 1;
            } else {
                $dia_anterior = $fecha_v[0] - 1;
            }
            $dia_caja_com = $this->basic->get_where('caja_comienza', array('caj_dia' => $desde[0], 'caj_mes' => $desde[1], 'caj_ano' => $desde[2]))->row_array();
            $this->data['transferencias'] = array();
            $transfers = $this->basic->get_where('transferencias', array(), 'transf_id', 'desc');
            foreach ($transfers->result_array() as $row) {
                $agregar = $this->comp_fecha($row['transf_fecha'], date('1-' . $desde[1] . '-' . $desde[2]), $fecha);
                if ($agregar == '11') {
                    array_push($this->data['transferencias'], $row);
                }
            }
//            $this->data['transferencias'] = $this->msort($this->data['transferencias'], 'transf_id');
            $this->data['comienza_banco'] = 0;
            $dia_hasta = $fecha_v[0] - 1;
            $mes_hasta = $fecha_v[1];
            $ano_hasta = $fecha_v[2];
            if ($dia_hasta == 0) {
                $primero = true;
                $dia_hasta = 31;
                $mes_hasta = $mes_hasta - 1;
                if ($mes_hasta == 0) {
                    $mes_hasta = 12;
                    $ano_hasta = $ano_hasta - 1;
                }
            }
            $creditos_bancarios = $this->basic->get_where('creditos', array('cred_tipo_trans' => 'Bancaria'));
            $debitos_bancarios = $this->basic->get_where('debitos', array('deb_tipo_trans' => 'Bancaria'));
            $creditos_todos = $this->basic->get_where('creditos', array());
            foreach ($creditos_bancarios->result_array() as $row) {
                $agregar = $this->comp_fecha($row['cred_fecha'], '00-00-0000', $dia_hasta . '-' . $mes_hasta . '-' . $ano_hasta);
                if ($agregar == '11') {
                    if ($row['cred_concepto'] != 'Gestion de Cobro' && $row['cred_concepto'] != 'Gestion de Cobro Sobre Intereses') {
                        $this->data['comienza_banco'] += $row['cred_monto'];
                    }
                }
            }
            $debitos_todos = $this->basic->get_where('debitos', array());
            foreach ($debitos_bancarios->result_array() as $row) {
                $agregar = $this->comp_fecha($row['deb_fecha'], '00-00-0000', $dia_hasta . '-' . $mes_hasta . '-' . $ano_hasta);
                if ($agregar == '11') {
                    if ($row['deb_concepto'] != 'Gestion de Cobro' && $row['deb_concepto'] != 'Gestion de Cobro Sobre Intereses') {
                        $this->data['comienza_banco'] -= $row['deb_monto'];
                    }
                }
            }
            $COM_BANC_MES = 0;
            foreach ($creditos_bancarios->result_array() as $row) {
                $agregar = $this->comp_fecha($row['cred_fecha'], '01-' . $fecha_v[1] . '-' . $fecha_v[2], $fecha_v[0] . '-' . $fecha_v[1] . '-' . $fecha_v[2]);
                if ($agregar == '11') {
                    if ($row['cred_concepto'] != 'Gestion de Cobro' && $row['cred_concepto'] != 'Gestion de Cobro Sobre Intereses') {
                        $COM_BANC_MES += $row['cred_monto'];
                    }
                }
            }
            foreach ($debitos_bancarios->result_array() as $row) {
                $agregar = $this->comp_fecha($row['deb_fecha'], '01-' . $fecha_v[1] . '-' . $fecha_v[2], $fecha_v[0] . '-' . $fecha_v[1] . '-' . $fecha_v[2]);
                if ($agregar == '11') {
                    if ($row['deb_concepto'] != 'Gestion de Cobro' && $row['deb_concepto'] != 'Gestion de Cobro Sobre Intereses') {
                        $COM_BANC_MES -= $row['deb_monto'];
                    }
                }
            }

            $bancario_hasta_ayer = 0;
            $bancario_hoy = 0;
            if ($fecha_v[0] != 1) {
                foreach ($creditos_bancarios->result_array() as $row) {
                    $agregar = $this->comp_fecha($row['cred_fecha'], '01-' . $fecha_v[1] . '-' . $fecha_v[2], $fecha_v[0] - 1 . '-' . $fecha_v[1] . '-' . $fecha_v[2]);
                    if ($agregar == '11') {
                        if ($row['cred_concepto'] != 'Gestion de Cobro' && $row['cred_concepto'] != 'Gestion de Cobro Sobre Intereses') {
                            $bancario_hasta_ayer += $row['cred_monto'];
                        }
                    }
                    $agregar = $this->comp_fecha($row['cred_fecha'], $fecha_v[0] . '-' . $fecha_v[1] . '-' . $fecha_v[2], $fecha_v[0] . '-' . $fecha_v[1] . '-' . $fecha_v[2]);
                    if ($agregar == '11') {
                        if ($row['cred_concepto'] != 'Gestion de Cobro' && $row['cred_concepto'] != 'Gestion de Cobro Sobre Intereses') {
                            $bancario_hoy += $row['cred_monto'];
                        }
                    }
                }
                foreach ($debitos_bancarios->result_array() as $row) {
                    $agregar = $this->comp_fecha($row['deb_fecha'], '01-' . $fecha_v[1] . '-' . $fecha_v[2], $fecha_v[0] - 1 . '-' . $fecha_v[1] . '-' . $fecha_v[2]);
                    if ($agregar == '11') {
                        if ($row['deb_concepto'] != 'Gestion de Cobro' && $row['deb_concepto'] != 'Gestion de Cobro Sobre Intereses') {
                            $bancario_hasta_ayer -= $row['deb_monto'];
                        }
                    }
                    $agregar = $this->comp_fecha($row['deb_fecha'], $fecha_v[0] . '-' . $fecha_v[1] . '-' . $fecha_v[2], $fecha_v[0] . '-' . $fecha_v[1] . '-' . $fecha_v[2]);
                    if ($agregar == '11') {
                        if ($row['deb_concepto'] != 'Gestion de Cobro' && $row['deb_concepto'] != 'Gestion de Cobro Sobre Intereses') {
                            $bancario_hoy -= $row['deb_monto'];
                        }
                    }
                }
            }
            $com_todo_MES = 0;
            foreach ($creditos_todos->result_array() as $row) {
                $agregar = $this->comp_fecha($row['cred_fecha'], '01-' . Date('m') . '-' . Date('Y'), $fecha_v[0] . '-' . $mes_hasta . '-' . $ano_hasta);
                if ($agregar == '11') {
                    if ($row['cred_concepto'] != 'Gestion de Cobro' && $row['cred_concepto'] != 'Gestion de Cobro Sobre Intereses') {
                        $com_todo_MES += $row['cred_monto'];
                    }
                }
            }
            foreach ($debitos_todos->result_array() as $row) {
                $agregar = $this->comp_fecha($row['deb_fecha'], '01-' . Date('m') . '-' . Date('Y'), $fecha_v[0] . '-' . $mes_hasta . '-' . $ano_hasta);
                if ($agregar == '11') {
                    if ($row['deb_concepto'] != 'Gestion de Cobro' && $row['deb_concepto'] != 'Gestion de Cobro Sobre Intereses') {
                        $com_todo_MES -= $row['deb_monto'];
                    }
                }
            }
            $this->data['caja'] = $dia_caja_com['caj_saldo'] - $bancario_hasta_ayer;
            $this->data['intervalo_fecha_banco'] = '01/' . $fecha_v[1] . '/' . $fecha_v[2] . ' - ' . $fecha_v[0] . '/' . $fecha_v[1] . '/' . $fecha_v[2];
            $this->data['saldo_banco_periodo'] = $COM_BANC_MES;
            $this->data['saldo_banco_hoy'] = $bancario_hoy;
            $creditos = $this->basic->get_where('creditos', array('cred_fecha' => $fecha), 'cred_id', 'asc');
            $debitos = $this->basic->get_where('debitos', array('deb_fecha' => $fecha), 'deb_id', 'asc');
            $this->data['movimientos'] = array();
            $this->data['fecha'] = $fecha;
            $this->data['entradas'] = 0;
            $this->data['salidas'] = 0;
            $a = 0;
            foreach ($creditos->result_array() as $row) {
                if (isset($row)) {
                    if ($row['cred_tipo_trans'] == 'Caja') {
                        $a += $row['cred_monto'];
                    }
                    if ($row['cred_concepto'] != 'Gestion de Cobro' && $row['cred_concepto'] != 'Gestion de Cobro Sobre Intereses') {
                        $arreglo = array(
                            'id' => $row['cred_id'],
                            'fecha' => $row['cred_fecha'],
                            'cc' => $row['cred_cc'],
                            'concepto' => $row['cred_concepto'],
                            'mes' => $row['cred_mes_alq'],
                            'monto' => $row['cred_monto'],
                            'depositante' => $row['cred_depositante'],
                            'domicilio' => $row['cred_domicilio'],
                            'tipo_trans' => $row['cred_tipo_trans'],
                            'operacion' => 'credito',
                            'trans' => $row['trans']
                        );
                        array_push($this->data['movimientos'], $arreglo);
                        $this->data['entradas'] += $row['cred_monto'];
                    }
                }
            }
            foreach ($debitos->result_array() as $row) {
                if (isset($row)) {
                    if ($row['deb_tipo_trans'] == 'Caja') {
                        $a -= $row['deb_monto'];
                    }
                    if ($row['deb_concepto'] != 'Gestion de Cobro' && $row['deb_concepto'] != 'Gestion de Cobro Sobre Intereses') {
                        $arreglo = array(
                            'id' => $row['deb_id'],
                            'fecha' => $row['deb_fecha'],
                            'cc' => $row['deb_cc'],
                            'concepto' => $row['deb_concepto'],
                            'domicilio' => $row['deb_domicilio'],
                            'monto' => $row['deb_monto'],
                            'operacion' => 'debito',
                            'mes' => $row['deb_mes'],
                            'tipo_trans' => $row['deb_tipo_trans'],
                            'trans' => $row['trans']
                        );
                        array_push($this->data['movimientos'], $arreglo);
                        $this->data['salidas'] += $row['deb_monto'];
                    }
                }
            }

            $this->data['mes'] = $this->basic->get_where('mensuales', array('men_mes' => date('m'), 'men_ano' => date('Y')))->row_array();
//            print_r('banco $ ' . $COM_BANC_MES);            
//            print_r(' caja total al dia $ ' . $com_todo_MES);
//            print_r(' mensual cred $ ' . $this->data['mes']['men_creditos']);
//            print_r(' mensual deb $ ' . $this->data['mes']['men_debitos']);
            $this->data['sal_nue'] = $a;
            $this->data['movimientos'] = $this->msort($this->data['movimientos'], 'trans');
            $response['html'] = $this->load->view('manager/reportes/informe_caja_detallada', $this->data, TRUE);
            echo json_encode($response);
        } else {
            $response['texto'] = 'Datos Faltantes';
            $response['js'] = "$('#com_display').html(R.texto);$('#com_display').removeClass('alert alert-success');$('#com_display').addClass('alert alert-danger');$('#com_display').css('display','block');$('#com_display').fadeOut(3500, 'linear');";
            echo json_encode($response);
        }
    }

    function get_ultimo_pago($contrato) {
        $creditos = $this->basic->get_where('creditos', array('cred_cc' => $contrato['con_prop'], 'cred_concepto' => $contrato['con_tipo']), 'cred_id', 'asc');
        $ultimo_pago = $creditos->last_row('array');
        return $ultimo_pago;
    }

    function informar_vencimientos($desde = false) {
        if ($desde != false) {
            $ultimo_pago = '';
            $hasta = date('d-m-Y', strtotime($desde) + 24 * 60 * 60 * 60); // + 60 dias
            $contratos = $this->basic->get_where('contratos', array('con_enabled' => 1));
            $agregar = 0;
            $this->data['contratos'] = array();
            $this->data['encabezado'] = 'Informe de contratos a vencer entre las fechas ' . $desde . ' y ' . $hasta;
            if (count($contratos) > 0) {
                foreach ($contratos->result_array() as $row) {
                    $agregar = $this->comp_fecha($row['con_venc'], $desde, $hasta);
                    if ($agregar == '11') {
                        $ultimo_pago = $this->get_ultimo_pago($row);
                        $row['ultimo_pago'] = $ultimo_pago;
                        $this->data['contratos'][] = $row;
                    }
                }
                $this->data['contratos'] = $this->msort($this->data['contratos'], 'con_prop');
            }
            $response['html'] = $this->load->view('manager/reportes/informe_vencimientos', $this->data, TRUE);
            echo json_encode($response);
        } else {
            $response['texto'] = 'Datos Faltantes';
            $response['js'] = "$('#com_display').html(R.texto);$('#com_display').removeClass('alert alert-success');$('#com_display').addClass('alert alert-danger');$('#com_display').css('display','block');$('#com_display').fadeOut(3500, 'linear');";
            echo json_encode($response);
        }
    }

    function get_last_payment($contrato, $concepto) {
        $creditos = $this->basic->get_where('creditos', array('cred_depositante' => $contrato['con_inq']));
        $agr_cred = 0;
        $cred_filt = array();
        $tiene_creds = false;
        $last_payment = false;
        foreach ($creditos->result_array() as $cred) {
            if ($cred['cred_concepto'] === $concepto) {
                $agr_cred = $this->comp_fecha($cred['cred_fecha'], '00-00-0000', Date('d-m-Y'));
//                if ($agr_cred == '11') {
                array_push($cred_filt, $cred);
                $tiene_creds = true;
//                }
            }
        }
        if ($tiene_creds) {
            //obtengo el ultimo pago
//            $cred_filt = $this->msort($cred_filt, 'cred_id');
            $ult_mes = 0;
            $ult_ano = 0;
            $ult_id = 0;

            for ($i = 0; $i < count($cred_filt); $i++) {
                $mes_last = preg_replace("/[^A-Za-z (),.]/", "", $cred_filt[$i]['cred_mes_alq']);
                $mes_last = trim($mes_last);
                $ano_last = preg_replace("/[^0-9 (),.]/", "", $cred_filt[$i]['cred_mes_alq']);
                $ano_last = trim($ano_last);
                $mes_last_nro = $this->get_nro_mes($mes_last);

                if ($contrato['con_id'] == 167) {
//                    print_r(' '.$mes_last_nro.' > ' . $ult_mes);
//                    print_r(' '.$ano_last.' > ' . $ult_ano);
//                    print_r(' ' . $ult_id);
                }

                if ($ano_last >= $ult_ano && $mes_last_nro >= $ult_mes) {
                    $ult_mes = $mes_last_nro;
                    $ult_ano = $ano_last;
                    $ult_id = $cred_filt[$i]['cred_id'];
                }
            }
//            $last_payment = end($cred_filt);
            $last_payment = $this->basic->get_where('creditos', array('cred_id' => $ult_id))->row_array();
        }
        return $last_payment;
    }

    function informar_morosos($fecha = false) {
        // Tomar el ultimo pago del inquilino
        // Si el pago corresponde al mes anterior al actual de la $fecha y si la tolerancia del contrato
        // no supera la $fecha el inquilino no debe nada
        // Si no corresponde al mes anterior al actual obtener el nro que representa el mes
        // Ej Octubre = 10
        // Si estamos en febrero debe 4 meses, entonces, por cada mes, se creara una entrada
        // al array interno del deudor en cuestion, con el monto correspondiente al periodo del mes q se debe
        // mas los intereses que correspondan, si existen pagos a cuenta de los intereses, seran contemplados
        if ($fecha != false) {
            $contratos_vigentes = $this->basic->get_where('contratos', array('con_enabled' => 1), 'con_tipo');
            //Deudores de alquileres
            $deudas_inquilinos = array();

            $deudas_inquilinos_serv = array();
            $this->data['inquilinos'] = array();
            foreach ($contratos_vigentes->result_array() as $con) {
                $ultimo_pago = false;
                $inquilino = $this->basic->get_where('clientes', array('client_name' => $con['con_inq']))->row_array();
                $inquilino['tipo'] = $con['con_tipo'];
                $inquilino['prop'] = $con['con_prop'];
                $inquilino['domi'] = $con['con_domi'];
                array_push($this->data['inquilinos'], $inquilino);
                // Deudas de Servicios
                $deudas_inquilinos_serv[$inquilino['client_name']] = array();
                $deudas_inquilinos[$inquilino['client_name']] = array();
                $servicios = $this->basic->get_where('servicios', array('serv_contrato' => $con['con_id']));
                foreach ($servicios->result_array() as $serv) {
                    if ($serv['serv_accion'] == 'Pagar') {
                        if ($con['con_usado'] == 1) {
                            $ultimo_pago = $this->get_last_payment($con, $serv['serv_concepto']);
                            $deuda = $this->get_deudas_serv($ultimo_pago, $con);
//                            echo '<pre>';
//                            print_r($deuda);
//                            echo '</pre>';
                            if (count($deuda) > 0) {
                                if ($ultimo_pago != false) {
                                    for ($i = 0; $i < count($deuda); $i++) {
                                        $deuda_fecha = '01-' . $this->get_nro_mes($deuda[$i]['mes']) . '-' . $deuda[$i]['ano'];
                                        $mes_last = preg_replace("/[^^A-Za-z (),.]/", "", $ultimo_pago['cred_mes_alq']);
                                        $mes_last = trim($mes_last);
                                        $ano_last = preg_replace("/[^0-9 (),.]/", "", $ultimo_pago['cred_mes_alq']);
                                        $ano_last = trim($ano_last);
                                        $mes_last = $this->get_nro_mes($mes_last);
                                        if ($ano_last != $ultimo_pago['cred_mes_alq']) {
                                            $fecha_last_pay = '01-' . $mes_last . '-' . $ano_last;
                                        } else {
                                            $ano_last = explode('-', $ultimo_pago['cred_fecha']);
                                            $ano_last = $ano_last[2];
                                            $fecha_last_pay = '01-' . $mes_last . '-' . $ano_last;
                                        }
                                        $fecha_last_pay = strtotime($fecha_last_pay);
                                        $deuda_fecha = strtotime($deuda_fecha);
                                        if ($fecha_last_pay < $deuda_fecha) {
                                            array_push($deudas_inquilinos_serv[$inquilino['client_name']], $deuda[$i]);
                                        } else {
                                            $deuda[$i] = array();
                                        }
                                    }
                                } else {
                                    for ($i = 0; $i < count($deuda); $i++) {
                                        array_push($deudas_inquilinos_serv[$inquilino['client_name']], $deuda[$i]);
                                    }
                                }
                            }
                        }
                    }
                }
                // Deudas de Alquileres o Loteos
                if ($con['con_usado'] == 1) {
                    $ultimo_pago = $this->get_last_payment($con, $con['con_tipo']);
                    $deuda = $this->get_deudas($ultimo_pago, $con);
//                    if ($con['con_id'] == '217') {
//                        echo '<pre>';
//                        print_r($ultimo_pago);
//                        echo '</pre>';
//
//                        echo '<pre>';
//                        print_r($deuda);
//                        echo '</pre>';
//                    }
                    if (count($deuda) > 0) {
                        if ($ultimo_pago != false) {
                            for ($i = 0; $i < count($deuda); $i++) {
//                               if ($con['con_id'] == '217') {
//                                            echo '<pre>';
//                                            print_r($deuda[$i]);
//                                            echo '</pre>';
//                                        }
                                $deuda_fecha = '01-' . $this->get_nro_mes($deuda[$i]['mes']) . '-' . $deuda[$i]['ano'];
                                $mes_last = preg_replace("/[^A-Za-z (),.]/", "", $ultimo_pago['cred_mes_alq']);
                                $mes_last = trim($mes_last);
                                $ano_last = preg_replace("/[^0-9 (),.]/", "", $ultimo_pago['cred_mes_alq']);
                                $ano_last = trim($ano_last);
                                $mes_last_nro = $this->get_nro_mes($mes_last);
                                if ($ano_last != $ultimo_pago['cred_mes_alq']) {
                                    $fecha_last_pay = '01-' . $mes_last_nro . '-' . $ano_last;
                                } else {
                                    $ano_last = explode('-', $ultimo_pago['cred_fecha']);
                                    $ano_last = $ano_last[2];
                                    $fecha_last_pay = '01-' . $mes_last_nro . '-' . $ano_last;
                                }
//                                if ($con['con_id'] == '217') {
//                                    print_r('last ' . $fecha_last_pay . ' ');
//                                    print_r('deuda ' . $deuda_fecha . ' ');
//                                }
                                $fecha_last_pay = strtotime($fecha_last_pay);
                                $deuda_fecha = strtotime($deuda_fecha);
                                if ($fecha_last_pay <= $deuda_fecha) {

//                                    if ($con['con_id'] == '217')
//                                        print_r('aefd');
                                    if ($fecha_last_pay == $deuda_fecha && $deuda[$i]['saldo_cuenta'] == 0) {
                                        $deuda[$i] = array();
                                    }
                                    if (!empty($deuda[$i])) {
//                                        if ($con['con_id'] == '217') {
//                                            echo '<pre>';
//                                            print_r($deuda[$i]);
//                                            echo '</pre>';
//                                        }
                                        array_push($deudas_inquilinos[$inquilino['client_name']], $deuda[$i]);
                                    }
                                } else {
                                    $deuda[$i] = array();
                                }
                            }
                        } else {
//                            print_r('jeje');
//                        echo '<pre>';
//                        print_r($ultimo_pago);
//                        echo '</pre>';
//                        echo '<pre>';
//                        print_r($con);
//                        echo '</pre>';
//                        
//                            
//                            echo '<pre>';
//                        print_r($deuda);
//                        echo '</pre>';
//                            print_r('jeje');
                            for ($i = 0; $i < count($deuda); $i++) {
                                array_push($deudas_inquilinos[$inquilino['client_name']], $deuda[$i]);
                            }
                        }
                    }
                }
            }
//            echo'<pre>';print_r($deudas_inquilinos['MONICA VIVIANA SANTA CRUZ']);echo'</pre>';
//            echo'<pre>';print_r($deudas_inquilinos['BETTINA ELIZABETH ACOSTA']);echo'</pre>';
//            echo'<pre>';print_r($deudas_inquilinos['JORGE ALBERTO CABRERA']);echo'</pre>';
//            echo'<pre>';print_r($deudas_inquilinos['DENIS EZEQUIEL DOS SANTOS']);echo'</pre>';
//            echo'<pre>';print_r($deudas_inquilinos['LETICIA BELEN IBARRA']);echo'</pre>';
//            echo'<pre>';print_r($deudas_inquilinos['ANA ABIGAIL MARIN']);echo'</pre>';
            $this->data['deudas_inquilinos'] = $deudas_inquilinos;
            $this->data['deudas_inquilinos_serv'] = $deudas_inquilinos_serv;
            $this->data['fecha'] = $fecha;
            $this->data['inquilinos'] = $this->msort($this->data['inquilinos'], 'client_name');
            $response['html'] = $this->load->view('manager/reportes/informe_morosos', $this->data, TRUE);
            echo json_encode($response);
        } else {
            $response['texto'] = 'Datos Faltantes';
            $response['js'] = "$('#com_display').html(R.texto);$('#com_display').removeClass('alert alert-success');$('#com_display').addClass('alert alert-danger');$('#com_display').css('display','block');$('#com_display').fadeOut(3500, 'linear');";
            echo json_encode($response);
        }
    }

    function calcular_monto($mes_debido, $periodos) {
        $monto = 0;
        $mes_debido_explode = explode('-', $mes_debido);
        $mes_debido_explode = explode('-', $mes_debido);
        $ano1 = $mes_debido_explode[2];
        $mes1 = $mes_debido_explode[1];
        // Problema con el calculo de los montos, a veces no entra en ningun rango
        // Deberia tomar del dia que inician los periodos no del dia 00
        foreach ($periodos->result_array() as $row) {
            $per_explode = explode('-', $row['per_inicio']);
            $mes_debido = $per_explode[0] . '-' . $mes1 . '-' . $ano1;
            $agregar = $this->comp_fecha($mes_debido, $row['per_inicio'], $row['per_fin']);
            if ($agregar == '11') {
                $monto = $row['per_monto'];
            }
        }
        return $monto;
    }

    function calcular_intereses($deuda, $contrato, $mes_debido, $periodos, $fecha_informe) {
        $mes_debido_array = explode('-', $mes_debido);
        $fecha_informe_array = explode('-', $fecha_informe);
        if ($mes_debido_array[1] <= $fecha_informe_array[1] && $mes_debido_array[2] <= $fecha_informe_array[2]) {
            $intereses = 0;
            $monto = 0;
            // Busco intereses pagados a cuenta para ese mes para ver si los resto
            $intereses_pagados_acuenta = 0;
            $intereses_pagados_acuenta = $this->pagos_acuenta($contrato, $mes_debido, $fecha_informe);
            //
            $fecha_informe_exp = explode('-', $fecha_informe);
            $dia_informe = $fecha_informe_exp[0];
            // Obtengo el monto del alquiler
            $monto = $this->calcular_monto($mes_debido, $periodos);
            // Obtengo la cantidad de dias de mora
            $dias_de_mora = $this->calcular_dias_mora($mes_debido, $fecha_informe);
//            print_r($dias_de_mora);
            if ($dias_de_mora > $contrato['con_tolerancia']) {
//                if ($dia_informe <= $contrato['con_tolerancia']) {
//                    // Unicamente si la fecha en la que se solicita el contrato sobrepasa la tolerancia 
//                    // tambien se calculan los intereses para tal mes
//                    if ($deuda['saldo_cuenta'] == 0) {
//                        $dias_de_mora = $dias_de_mora - $dia_informe;
//                    } else {
//                        $dias_de_mora = $dias_de_mora;
//                    }
//                }
                if ($deuda['monto'] != 0) {
                    $intereses = ($deuda['monto'] * $contrato['con_punitorio'] * $dias_de_mora) - $intereses_pagados_acuenta;
                } else {
                    $intereses = ($monto * $contrato['con_punitorio'] * $dias_de_mora) - $intereses_pagados_acuenta;
                }
                $deuda['intereses'] = $intereses;
                $deuda['dias_mora'] = $dias_de_mora;
            }
        }
        return $deuda;
    }

    function pagos_acuenta($contrato, $mes_debido, $fecha_informe) {
        $mes_debido_explode = explode('-', $mes_debido);
        $mes_pagado_acuenta = $this->get_mes($mes_debido_explode[1]);
        $creditos = $this->basic->get_where('creditos', array('cred_mes_alq' => $mes_pagado_acuenta, 'cred_depositante' => $contrato['con_inq'], 'cred_concepto' => 'Intereses'));
        $agr_cred = 0;
        $tiene_creds = false;
        $acuenta_acumulado = 0;
        //filtro hasta la $fecha y por concepto de pago de Alquileres, y ordemar
        foreach ($creditos->result_array() as $cred) {
            $tiene_creds = true;
            $agr_cred = $this->comp_fecha($cred['cred_fecha'], $mes_debido, $fecha_informe);
            if ($agr_cred == '11') {
                $acuenta_acumulado = $acuenta_acumulado + $cred['cred_monto'];
            }
        }

        return $acuenta_acumulado;
    }

    function calcular_dias_mora($mes_debido, $fecha) {
        $mes_debido_explode = explode('-', $mes_debido);
        $fecha_explode = explode('-', $fecha);
        //defino fecha 1 
        $ano1 = $mes_debido_explode[2];
        $mes1 = $mes_debido_explode[1];
        $dia1 = $mes_debido_explode[0];
        //defino fecha 2 
        $ano2 = $fecha_explode[2];
        $mes2 = $fecha_explode[1];
        $dia2 = $fecha_explode[0];
        //calculo timestam de las dos fechas 
        $timestamp1 = mktime(0, 0, 0, $mes1, $dia1, $ano1);
        $timestamp2 = mktime(0, 0, 0, $mes2, $dia2, $ano2);
        //resto a una fecha la otra 
        $segundos_diferencia = $timestamp1 - $timestamp2;
        //echo $segundos_diferencia; 
        //convierto segundos en días 
        $dias_diferencia = $segundos_diferencia / (60 * 60 * 24);
        //obtengo el valor absoulto de los días (quito el posible signo negativo) 
        $dias_diferencia = abs($dias_diferencia);
        //quito los decimales a los días de diferencia 
        $dias_diferencia = round($dias_diferencia);
        return $dias_diferencia;
    }

    function informar_caja_general($fecha) {
        $this->data['caja'] = $this->basic->get_where('caja_comienza', array('caj_dia' => date('d'), 'caj_mes' => date('m'), 'caj_ano' => date('Y')))->row_array();
        $creditos = $this->basic->get_where('creditos', array('cred_fecha' => $fecha), 'cred_id', 'asc');
        $debitos = $this->basic->get_where('debitos', array('deb_fecha' => $fecha), 'deb_id', 'asc');
        $this->data['fecha'] = $fecha;
        $this->data['entrada_gestion'] = 0;
        $this->data['entrada_alquileres'] = 0;
        $this->data['entrada_comision'] = 0;
        $this->data['iva'] = 0;
        $this->data['iva_mensual'] = 0;
        $credits = $this->basic->get_where('creditos', array('cred_concepto' => 'IVA', 'cred_cc' => 'INMOBILIARIA'));
        foreach ($credits->result_array() as $row) {
            $arr = $this->comp_fecha($row['cred_fecha'], date('01-m-Y'), date('31-m-Y'));
            if ($arr == '11') {
                $this->data['iva_mensual'] += $row['cred_monto'];
            }
        }
        $this->data['caja_fuerte'] = 0;
        $this->data['salida_arreglos'] = 0;
        $this->data['entrada_varios'] = 0;
        $this->data['salida_varios'] = 0;
        $this->data['salida_alquileres'] = 0;
        foreach ($creditos->result_array() as $row) {
            if (isset($row)) {
                $concepto = $this->basic->get_where('conceptos', array('conc_desc' => $row['cred_concepto']))->row_array();
                if ($concepto['conc_cc'] == 'cc_saldo') {
                    if (strpos($row['cred_concepto'], 'Alquiler') !== FALSE) {
                        $this->data['entrada_alquileres'] += $row['cred_monto'];
                    }
                    if (strpos($row['cred_concepto'], 'Gestion de Cobro') !== FALSE) {
                        $this->data['entrada_gestion'] += $row['cred_monto'];
                    }
                    if ($row['cred_concepto'] == 'Comision') {
                        $this->data['entrada_comision'] += $row['cred_monto'];
                    }
                    if ($row['cred_concepto'] == 'IVA' && $row['cred_cc'] == 'INMOBILIARIA') {
                        $this->data['iva'] += $row['cred_monto'];
                    }
                } else {
                    $this->data['entrada_varios']+= $row['cred_monto'];
                }
            }
        }
        foreach ($debitos->result_array() as $row) {
            if (isset($row)) {
                $concepto = $this->basic->get_where('conceptos', array('conc_desc' => $row['deb_concepto']))->row_array();
                if ($concepto['conc_cc'] == 'cc_saldo') {
                    if ($row['deb_concepto'] == 'Rendicion') {
                        $this->data['salida_alquileres'] += $row['deb_monto'];
                    }
                    if ($row['deb_concepto'] == 'Arreglos') {
                        $this->data['salida_arreglos'] += $row['deb_monto'];
                    }
                } else {
                    if ($row['deb_concepto'] == 'Arreglos') {
                        $this->data['salida_arreglos'] += $row['deb_monto'];
                    } else {
                        $this->data['salida_varios']+= $row['deb_monto'];
                    }
                }
            }
        }
        $this->data['alquileres_monto'] = 0;
        $this->data['varios'] = 0;
        $this->data['loteos'] = 0;
        $this->data['rima'] = 0;
        $cuentas = $this->basic->get_all('cuentas_corrientes');
        foreach ($cuentas->result_array() as $row) {
            if (stripos($row['cc_prop'], 'INMOBILIARIA') !== FALSE) {
                $this->data['rima'] += $row['cc_saldo'] + $row['cc_varios'];
            }
            if (stripos($row['cc_prop'], 'LOTEO') !== FALSE) {
                $this->data['loteos'] += $row['cc_saldo'];
            }
            if (stripos($row['cc_prop'], 'LOTEO') === FALSE && stripos($row['cc_prop'], 'INMOBILIARIA') === FALSE && stripos($row['cc_prop'], 'CAJA FUERTE') === FALSE) {
                $this->data['alquileres_monto'] += $row['cc_saldo'];
                $this->data['varios'] += $row['cc_varios'];
            }
        }
        $this->data['subtotal'] = $this->data['rima'] + $this->data['alquileres_monto'] + $this->data['varios'];
        $this->data['total'] = $this->data['subtotal'] + $this->data['loteos'];
        $response['html'] = $this->load->view('manager/reportes/informe_caja_general', $this->data, TRUE);
        echo json_encode($response);
    }

    // fin informes



    function comp_ano($fecha, $ano) {
        $dagre = 0;
        $hagre = 0;
        $fecha = explode('-', $fecha);
        $fecha_ano = $fecha[2];
        if ($fecha_ano == $ano) {
            $dagre = 1;
            $hagre = 1;
        }
        $agregar = $dagre . $hagre;
        return $agregar;
    }

    function comp_mes($fecha, $mes, $ano) {
        $dagre = 0;
        $hagre = 0;
        $fecha = explode('-', $fecha);
        $fecha_mes = $fecha[1];
        $fecha_ano = $fecha[2];
        if ($fecha_mes == $mes && $fecha_ano == $ano) {
            $dagre = 1;
            $hagre = 1;
        }
        $agregar = $dagre . $hagre;
        return $agregar;
    }

    function get_nro_mes($mes) {
        $messtring = 'NADA';
        if (strpos($mes, 'Enero') !== false) {
            $messtring = '01';
        }
        if (strpos($mes, 'Febrero') !== false) {
            $messtring = '02';
        }
        if (strpos($mes, 'Marzo') !== false) {
            $messtring = '03';
        }
        if (strpos($mes, 'Abril') !== false) {
            $messtring = '04';
        }
        if (strpos($mes, 'Mayo') !== false) {
            $messtring = '05';
        }
        if (strpos($mes, 'Junio') !== false) {
            $messtring = '06';
        }
        if (strpos($mes, 'Julio') !== false) {
            $messtring = '07';
        }
        if (strpos($mes, 'Agosto') !== false) {
            $messtring = '08';
        }
        if (strpos($mes, 'Septiembre') !== false) {
            $messtring = '09';
        }
        if (strpos($mes, 'Octubre') !== false) {
            $messtring = '10';
        }
        if (strpos($mes, 'Noviembre') !== false) {
            $messtring = '11';
        }
        if (strpos($mes, 'Diciembre') !== false) {
            $messtring = '12';
        }
        return $messtring;
    }

    function get_mes($mes) {
        $messtring = '';
        if ($mes == '01') {
            $messtring = 'Enero';
        }
        if ($mes == '02') {
            $messtring = 'Febrero';
        }
        if ($mes == '03') {
            $messtring = 'Marzo';
        }
        if ($mes == '04') {
            $messtring = 'Abril';
        }
        if ($mes == '05') {
            $messtring = 'Mayo';
        }
        if ($mes == '06') {
            $messtring = 'Junio';
        }
        if ($mes == '07') {
            $messtring = 'Julio';
        }
        if ($mes == '08') {
            $messtring = 'Agosto';
        }
        if ($mes == '09') {
            $messtring = 'Septiembre';
        }
        if ($mes == '10') {
            $messtring = 'Octubre';
        }
        if ($mes == '11') {
            $messtring = 'Noviembre';
        }
        if ($mes == '12') {
            $messtring = 'Diciembre';
        }
        return $messtring;
    }

    //autocompletar
    function autocomplete($tabla, $tipo = false) {
        $buscar = $this->input->get('term');
        if ($buscar) {
            $r = array();
            $dats = array();
            $busqueda = $this->basic->get_auto($buscar, $tabla, $tipo);
            if ($busqueda->num_rows() > 0) {
                foreach ($busqueda->result_array() as $item) {
                    if ($tabla == 'clientes') {
                        $dats['id'] = $item['client_id'];
                        $dats['label'] = $item['client_name'];
                        $dats['value'] = $item['client_name'];
                        $r[] = $dats;
                    }
                    if ($tabla == 'contratos') {
                        $dats['id'] = $item['con_id'];
                        $dats['label'] = $item['con_prop'];
                        $dats['value'] = $item['con_prop'];
                        $r[] = $dats;
                    }
                    if ($tabla == 'propiedades') {
                        $dats['id'] = $item['prop_id'];
                        $dats['label'] = $item['prop_dom'];
                        $dats['value'] = $item['prop_dom'];
                        $r[] = $dats;
                    }
                    if ($tabla == 'conceptos') {
                        $dats['id'] = $item['conc_id'];
                        $dats['label'] = $item['conc_desc'];
                        $dats['value'] = $item['conc_desc'];
                        $r[] = $dats;
                    }
                    if ($tabla == 'cuentas_corrientes') {
                        $dats['id'] = $item['cc_id'];
                        $dats['label'] = $item['cc_prop'];
                        $dats['value'] = $item['cc_prop'];
                        $r[] = $dats;
                    }
                }
                echo json_encode($r);
            }
        }
    }

    function get_mes_meses($mes_alq = false) {
        $mes = '';
        $meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
        $flag = true;
        reset($meses);
        while ($flag) {
//            print_r(' ' . $mes_alq);
//            print_r(' strpos ' . current($meses) . '  ');
            if (strpos($mes_alq, current($meses)) !== FALSE) {
                $mes = current($meses);
//                print_r('compara entra');
                $flag = false;
            } else {
                next($meses);
            }
        }
        return $mes;
    }

    function buscar_fila($table, $needle = false, $inq = false) {
        if ($needle != false) {
            $inq = urldecode($inq);
            if ($table == 'cuentas_corrientes') {
                /* Obtiene una lista de los pagos, si es que $table es pagos */
                $this->data['cuentas_corrientes'] = $this->basic->get_where($table, array('cc_id' => $needle));
                $cc = $this->basic->get_where($table, array('cc_id' => $needle))->row();
                $response['id'] = $cc->cc_id;
            }
            if ($table == 'conceptos') {
                /* Obtiene una lista de los pagos, si es que $table es pagos */
                $this->data[$table] = $this->basic->get_where($table, array('conc_id' => $needle));
            }
            if ($table == 'contratos') {
                /* Obtiene una lista de los clientes, si es que $table es clientes */
                $this->data[$table] = $this->basic->get_where($table, array('con_id' => $needle));
            }
            if ($table == 'clientes') {
                /* Obtiene una lista de los clientes, si es que $table es clientes */
                $this->data[$table] = $this->basic->get_where($table, array('client_id' => $needle));
                $cli = $this->basic->get_where($table, array('client_id' => $needle))->row();
                $response['id'] = $cli->client_id;
            }
        } else {
            $this->data[$table] = $this->basic->get_all($table);
        }
        $response['html'] = $this->load->view('manager/' . $table . '/buscar_fila_' . $table, $this->data, TRUE);
        echo json_encode($response);
    }

    function refresh($tabla, $tabla_pk = FALSE, $order = FALSE) {
        $cons = $this->basic->get_where('contratos', array(), 'con_prop', '');
        $this->data['contratos_vigentes'] = 0;
        foreach ($cons->result_array() as $row) {
            if ($row['con_enabled'] == 1) {
                $this->data['contratos_vigentes']++;
            }
        }
        if ($tabla_pk) {
            $this->data[$tabla] = $this->basic->get_where($tabla, array(), $order, '', '50');
        }
        if ($tabla == 'clientes' || $tabla == 'cuentas_corrientes') {
            $this->data[$tabla] = $this->basic->get_where($tabla, array(), $order, '', '50');
        }
        if ($tabla == 'creditos') {
            $response['html'] = $this->load->view('manager/' . 'transacciones' . '/buscar_fila_' . $tabla, $this->data, TRUE);
        } else if ($tabla == 'debitos') {
            $response['html'] = $this->load->view('manager/' . 'transacciones' . '/buscar_fila_' . $tabla, $this->data, TRUE);
        } else {
            $response['html'] = $this->load->view('manager/' . $tabla . '/buscar_fila_' . $tabla, $this->data, TRUE);
        }
        echo json_encode($response);
    }

    function buscar_concepto($table, $tipo = false, $tableo = false, $inq = false, $prop = false, $needle = false, $id_input = false) {
        $response['periodos'] = '';
        $x_input = preg_replace("/[^0-9]/", "", $id_input);
        $contrato = null;
        $inq = urldecode($inq);
        $response['entro'] = 0;
        $response['alq'] = 0;
        $response['lot'] = 0;
        $prop = urldecode($prop);
        if ($needle != false) {
            if ($table == 'conceptos') {
                /* Obtiene una lista de los pagos, si es que $table es pagos */
                $this->data[$table] = $this->basic->get_where($table, array('conc_id' => $needle, 'conc_tipo' => $tipo));
                $concepto = $this->basic->get_where($table, array('conc_id' => $needle, 'conc_tipo' => $tipo))->row();
                $response['id'] = $needle;
                $servis = $this->db->query('SELECT DISTINCT serv_concepto FROM servicios');
                $lista_servicios = '';
                foreach ($servis->result_array() as $row) {
                    $lista_servicios .= ' ' . $row['serv_concepto'];
                }
                $rtdo = strpos($lista_servicios, $concepto->conc_desc);
                if ($rtdo != FALSE) {
                    $contrato = $this->basic->get_where('contratos', array('con_enabled' => 1, 'con_prop' => $prop, 'con_inq' => $inq))->row_array();
                }
                if ($concepto->conc_desc == 'Loteo' || $concepto->conc_desc == 'Comision' || $concepto->conc_desc == 'Alquiler N' || $concepto->conc_desc == 'Alquiler' || $concepto->conc_desc == 'Alquiler Comercial' || $concepto->conc_desc == 'Alquiler Comercial N') {
                    if ($concepto->conc_desc != 'Comision') {
                        $contrato = $this->basic->get_where('contratos', array('con_enabled' => 1, 'con_tipo' => $concepto->conc_desc, 'con_prop' => $prop, 'con_inq' => $inq))->row_array();
                    } else {
                        $contrato = $this->basic->get_where('contratos', array('con_enabled' => 1, 'con_prop' => $prop, 'con_inq' => $inq))->row_array();
                    }
                }
                if ($contrato != null) {
                    $response['alq'] = 1;
                    $response['entro'] = 1;
                    $monto_intereses = 0;
                    $monto_todo = 0;
                    $id_input_new = $x_input;
                    $response['js'] = '$(".periodos").removeClass("alert alert-danger");';
                    // CONCEPTO EXPENSAS, MOSTRAR TODAS LAS DEUDAS
                    $contrato_usado = $contrato['con_usado'];
                    if ($contrato_usado) {
                        $lista_servicios = '';
                        foreach ($servis->result_array() as $row) {
                            $lista_servicios .= ' ' . $row['serv_concepto'];
                        }
                        if (strpos($lista_servicios, $concepto->conc_desc) !== FALSE) {
                            if ($tableo != 1) {
                                $response['js'] .= "$('#relleno').empty();$('.periodos').empty();";
                            }
                            $servicios = $this->basic->get_where('servicios', array('serv_contrato' => $contrato['con_id']));
                            $deudas_serv = array();
                            foreach ($servicios->result_array() as $row) {
                                $last_pay_serv = false;
                                //Obtendre los ultimos pagos de los servicios del contrato
                                if ($row['serv_accion'] == 'Pagar' && $row['serv_concepto'] == $concepto->conc_desc) {
                                    $last_pay_serv = $this->get_last_payment($contrato, $row['serv_concepto']);
                                    $deuda = $this->get_deudas_serv($last_pay_serv, $contrato);
                                    if (count($deuda) > 0) {
                                        for ($i = 0; $i < count($deuda); $i++) {
                                            $deuda_fecha = '01-' . $this->get_nro_mes($deuda[$i]['mes']) . '-' . $deuda[$i]['ano'];
                                            $ano_last = preg_replace("/[^0-9 (),.]/", "", $last_pay_serv['cred_mes_alq']);
                                            $ano_last = trim($ano_last);
                                            $mes_last = preg_replace("/[^^A-Za-z (),.]/", "", $last_pay_serv['cred_mes_alq']);
                                            $mes_last = trim($mes_last);
                                            $mes_last = $this->get_nro_mes($mes_last);
                                            if ($ano_last != $last_pay_serv['cred_mes_alq']) {
                                                $fecha_last_pay = '01-' . $mes_last . '-' . $ano_last;
                                            } else {
                                                $ano_last = explode('-', $last_pay_serv['cred_fecha']);
                                                $ano_last = $ano_last[2];
                                                $fecha_last_pay = '01-' . $mes_last . '-' . $ano_last;
                                            }
                                            $fecha_last_pay = strtotime($fecha_last_pay);
                                            $deuda_fecha = strtotime($deuda_fecha);
                                            if ($fecha_last_pay >= $deuda_fecha) {
                                                $deuda[$i] = array();
                                            } else {
                                                array_push($deudas_serv, $deuda[$i]);
                                            }
                                        }
                                    }
                                }
                            }
                            $serv_mes = false;
                            if (!(count($deudas_serv) > 0)) {
                                $serv_mes = true;
                                foreach ($servicios->result_array() as $row) {
                                    if ($row['serv_accion'] == 'Pagar' && $row['serv_concepto'] == $concepto->conc_desc) {
                                        $last_pay_serv = $this->get_last_payment($contrato, $row['serv_concepto']);
                                        $meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
                                        $flag = true;
                                        $mes_alq = '';
                                        if ($last_pay_serv != false) {
                                            reset($meses);
                                            $mes_last = preg_replace("/[^^A-Za-z (),.]/", "", $last_pay_serv['cred_mes_alq']);
                                            $mes_last = trim($mes_last);
                                            $ano_last = preg_replace("/[^0-9 (),.]/", "", $last_pay_serv['cred_mes_alq']);
                                            $ano_last = trim($ano_last);
                                            $mes_last = $this->get_nro_mes($mes_last);
                                        }
                                        if (($mes_last + 1) > 12) {
                                            $mes_prox_cred = 1;
                                            $ano_prox_cred = $ano_last + 1;
                                        } else {
                                            $mes_prox_cred = $mes_last + 1;
                                            $ano_prox_cred = $ano_last;
                                        }
                                        $mes_alq = $this->get_mes($mes_prox_cred);
                                        $deuda = array(
                                            'mes' => $mes_alq,
                                            'dias_mora' => 0,
                                            'concepto' => $row['serv_concepto'],
                                            'ano' => $ano_prox_cred,
                                            'monto' => 0,
                                            'saldo_cuenta' => 0,
                                            'intereses' => 0
                                        );
                                        if ($concepto == 'Expensas') {
                                            $mes_debido = '00-' . $mes_prox_cred . '-' . $ano_prox_cred;
                                            $deuda = $this->calcular_intereses_expensas($deuda, $contrato, $mes_debido, Date('00-' . $mes_prox_cred . '-' . $ano_prox_cred));
                                        }
                                        array_push($deudas_serv, $deuda);
                                    }
                                }
                            }
                            $dimensiones = $this->countdim($deudas_serv);
                            if ($dimensiones == 2) {
                                // No se enconraron deudas en los servicios del contrato
                                $response['js'] .= $this->armar_vista_serv($id_input_new, $deudas_serv, $contrato);
                            } else if ($dimensiones != 1) {
                                // Se arma la vista para las deudas encontradas
                                $response['js'] .= $this->armar_vista($id_input_new, $deudas_serv, $contrato);
                            }
                        }
                        //CONCEPTO ALQUILER o LOTEO, MOSTRAR TODAS LAS DEUDAS MAS LOS SERVICIOS ADEUDADOS
                        if (strpos($concepto->conc_desc, 'Loteo') !== FALSE || strpos($concepto->conc_desc, 'Alquiler') !== FALSE && $tableo != 1) {
                            $response['js'] .= "$('#relleno').empty();$('.periodos').empty();";
                            $last_pay = false;
                            $deudas_inquilino = array();
                            $last_pay = $this->get_last_payment($contrato, $concepto->conc_desc);
//                            echo '<pre>';
//                            print_r($last_pay);
//                            echo '</pre>';
                            $deuda = $this->get_deudas($last_pay, $contrato);
//                            echo '<pre>';
//                            print_r($deuda);
//                            echo '</pre>';
                            if (count($deuda) > 0) {
                                for ($i = 0; $i < count($deuda); $i++) {
                                    $deuda_fecha = '01-' . $this->get_nro_mes($deuda[$i]['mes']) . '-' . $deuda[$i]['ano'];
                                    $mes_last = preg_replace("/[^^A-Za-z (),.]/", "", $last_pay['cred_mes_alq']);
                                    $mes_last = trim($mes_last);
                                    $ano_last = preg_replace("/[^0-9 (),.]/", "", $last_pay['cred_mes_alq']);
                                    $ano_last = trim($ano_last);
                                    $mes_last = $this->get_nro_mes($mes_last);
                                    if ($ano_last != $last_pay['cred_mes_alq']) {
                                        $fecha_last_pay = '01-' . $mes_last . '-' . $ano_last;
                                    } else {
                                        $ano_last = explode('-', $last_pay['cred_fecha']);
                                        $ano_last = $ano_last[2];
                                        $fecha_last_pay = '01-' . $mes_last . '-' . $ano_last;
                                    }
                                    $fecha_last_pay = strtotime($fecha_last_pay);
                                    $deuda_fecha = strtotime($deuda_fecha);
                                    if ($fecha_last_pay >= $deuda_fecha) {
                                        if ($deuda[$i]['saldo_cuenta'] == 0) {
                                            $deuda[$i] = array();
                                        } else {
                                            $deudas_inquilino[] = $deuda[$i];
                                        }
                                    } else {
                                        $deudas_inquilino[] = $deuda[$i];
                                    }
                                }
                            }
//                            echo '<pre>';
//                            print_r($deudas_inquilino);
//                            echo '</pre>';
                            // SERVICIOS
                            $servicios = $this->basic->get_where('servicios', array('serv_contrato' => $contrato['con_id']));
                            $deudas_serv = array();
                            foreach ($servicios->result_array() as $row) {
                                //Obtendre los ultimos pagos de los servicios del contrato
                                if ($row['serv_accion'] == 'Pagar') {
                                    $last_pay_serv = $this->get_last_payment($contrato, $row['serv_concepto']);
                                    if (empty($last_pay_serv)) {
                                        $last_pay_serv = $row['serv_concepto'];
                                    }
                                    $deuda = $this->get_deudas_serv($last_pay_serv, $contrato);
                                    if (count($deuda) > 0) {
                                        //Elimina las deudas si estan mal traidas porq son del mes y ya estan opagadas
                                        for ($i = 0; $i < count($deuda); $i++) {
                                            $deuda_fecha = '01-' . $this->get_nro_mes($deuda[$i]['mes']) . '-' . $deuda[$i]['ano'];
                                            $ano_last = preg_replace("/[^0-9 (),.]/", "", $last_pay_serv['cred_mes_alq']);
                                            $ano_last = trim($ano_last);
                                            $mes_last = preg_replace("/[^^A-Za-z (),.]/", "", $last_pay_serv['cred_mes_alq']);
                                            $mes_last = trim($mes_last);
                                            $mes_last = $this->get_nro_mes($mes_last);
                                            if ($ano_last != $last_pay_serv['cred_mes_alq']) {
                                                $fecha_last_pay = '01-' . $mes_last . '-' . $ano_last;
                                            } else {
                                                $ano_last = explode('-', $last_pay_serv['cred_fecha']);
                                                $ano_last = $ano_last[2];
                                                $fecha_last_pay = '01-' . $mes_last . '-' . $ano_last;
                                            }
                                            $fecha_last_pay = strtotime($fecha_last_pay);
                                            $deuda_fecha = strtotime($deuda_fecha);
                                            if ($fecha_last_pay >= $deuda_fecha) {
                                                $deuda[$i] = array();
                                            } else {
                                                array_push($deudas_serv, $deuda[$i]);
                                            }
                                        }
                                    }
                                }
                            }
                            $serv_mes = false;
                            if (!(count($deudas_serv) > 0)) {
                                // No hay deudas se traen los pagos para el mes siguiente
                                $serv_mes = true;
                                foreach ($servicios->result_array() as $row) {
                                    if ($row['serv_accion'] == 'Pagar') {
                                        $last_pay_serv = $this->get_last_payment($contrato, $row['serv_concepto']);
                                        $meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
                                        $flag = true;
                                        $mes_alq = '';
                                        if ($last_pay != false) {
                                            reset($meses);
                                            $ano_last = preg_replace("/[^0-9 (),.]/", "", $last_pay_serv['cred_mes_alq']);
                                            $ano_last = trim($ano_last);
                                            $mes_last = preg_replace("/[^^A-Za-z (),.]/", "", $last_pay_serv['cred_mes_alq']);
                                            $mes_last = trim($mes_last);
                                            $mes_last = $this->get_nro_mes($mes_last);
                                            if ($ano_last != $last_pay_serv['cred_mes_alq']) {
                                                $fecha_last_pay = '01-' . $mes_last . '-' . $ano_last;
                                            } else {
                                                $ano_last = explode('-', $last_pay_serv['cred_fecha']);
                                                $ano_last = $ano_last[2];
                                                $fecha_last_pay = '01-' . $mes_last . '-' . $ano_last;
                                            }
                                        }
                                        if (($mes_last + 1) > 12) {
                                            $mes_prox_cred = 1;
                                            $ano_prox_cred = $ano_last + 1;
                                        } else {
                                            $mes_prox_cred = $mes_last + 1;
                                            $ano_prox_cred = $ano_last;
                                        }
                                        $mes_alq = $this->get_mes($mes_prox_cred);
                                        $deuda = array(
                                            'mes' => $mes_alq,
                                            'dias_mora' => 0,
                                            'concepto' => $row['serv_concepto'],
                                            'ano' => $ano_prox_cred,
                                            'monto' => 0,
                                            'saldo_cuenta' => 0,
                                            'intereses' => 0
                                        );
                                        if ($concepto == 'Expensas') {
                                            $mes_debido = '00-' . $mes_prox_cred . '-' . $ano_prox_cred;
                                            $deuda = $this->calcular_intereses_expensas($deuda, $contrato, $mes_debido, Date('00-' . $mes_prox_cred . '-' . $ano_prox_cred));
                                        }
                                        array_push($deudas_serv, $deuda);
                                    }
                                }
                            }
                            if ($serv_mes) {
                                //Elimina las deudas si estan mal traidas porq son del mes y ya estan opagadas
                                for ($i = 0; $i < count($deudas_serv); $i++) {
                                    $last_pay_serv = $this->get_last_payment($contrato, $deudas_serv[$i]['concepto']);
                                    $deuda_fecha = '01-' . $this->get_nro_mes($deudas_serv[$i]['mes']) . '-' . $deudas_serv[$i]['ano'];
                                    $ano_last = preg_replace("/[^0-9 (),.]/", "", $last_pay_serv['cred_mes_alq']);
                                    $ano_last = trim($ano_last);
                                    $mes_last = preg_replace("/[^^A-Za-z (),.]/", "", $last_pay_serv['cred_mes_alq']);
                                    $mes_last = trim($mes_last);
                                    $mes_last = $this->get_nro_mes($mes_last);
                                    if ($ano_last != $last_pay_serv['cred_mes_alq']) {
                                        $fecha_last_pay = '01-' . $mes_last . '-' . $ano_last;
                                    } else {
                                        $ano_last = explode('-', $last_pay_serv['cred_fecha']);
                                        $ano_last = $ano_last[2];
                                        $fecha_last_pay = '01-' . $mes_last . '-' . $ano_last;
                                    }
                                    $fecha_last_pay = strtotime($fecha_last_pay);
                                    $deuda_fecha = strtotime($deuda_fecha);
                                    if ($fecha_last_pay >= $deuda_fecha) {
                                        $deudas_serv[$i] = array();
                                    }
                                }
                            }
                            if (count($deudas_inquilino) > 0) {

                                for ($x = 0; $x < count($deudas_inquilino); $x++) {

                                    $monto_todo += $deudas_inquilino[$x]['monto'];
                                    $monto_intereses += $deudas_inquilino[$x]['intereses'];

                                    if ($id_input_new == 1) {
//                                        echo '<pre>';
//                                        print_r($deudas_inquilino[$x]);
//                                        echo '<pre>';
                                        $row = "$('#concepto1').attr('readonly','true');$('#domicilio1').attr('readonly','true');
                                    $('#mes1').val('" . $deudas_inquilino[$x]['mes'] . ' ' . $deudas_inquilino[$x]['ano'] . "');$('#domicilio1').val('" . $contrato['con_domi'] . "');";
                                        $row .= "$('#mes1').autocomplete({source: meses});$('#monto1').val('" . $deudas_inquilino[$x]['monto'] . "');";
                                        if ($deudas_inquilino[$x]['saldo_cuenta'] == 1) {
                                            $row .= '$("#cred_tipo_pago option[value=Saldo]").attr("selected","selected");';
                                        }
                                        if ($deudas_inquilino[$x]['dias_mora'] != 0) {
                                            $response['js'] .= '$("#interes1").remove();
                                            jQuery("<input/>", {
                                                id: "interes1",
                                                onkeyup:"recalcular()",
                                                onblur:"recalcular()",
                                                onclick:"unlock(1)",
                                                name: "interes1",
                                                type: "text",
                                                autocomplete: "off",
                                                readonly: true,
                                                style : "cursor:pointer;margin-right: 5px;font-size: 16px;width: 110px;float: left;",
                                                "class": "form-control ui-autocomplete-input",
                                                placeholder: "Dias de Interes"                 
                                        }).appendTo("#bloque1");';
                                            $response['js'] .= '$("#interes1").hover(function(){
                                            $("#tooltipInt").css("display","block");
                                            },function(){
                                            $("#tooltipInt").css("display","none");
                                        });$("#interes1").val("' . $deudas_inquilino[$x]['dias_mora'] . ' dias mora");
                                        jQuery("<input/>", {
                                                id: "interes_calculado1",
                                                name: "interes_calculado1",
                                                type: "text",
                                                autocomplete: "off",
                                                value: "' . ($deudas_inquilino[$x]['intereses']) . '",                                  
                                                readonly: true,
                                                style : "margin-right: 5px;font-size: 16px;width: 110px;float: left;",
                                                "class": "form-control ui-autocomplete-input",
                                                placeholder: "Dias de Interes"                 
                                        }).appendTo("#bloque1");';
                                        }
                                        if ($contrato['con_iva_alq'] == 'Si' && $concepto->conc_desc == 'Alquiler Comercial') {
                                            $row .= " jQuery('<input/>', {
                                                        readonly: true,
                                                        id: 'iva_calculado'+" . $id_input_new . ",
                                                        name: 'iva_calculado'+" . $id_input_new . ",
                                                        type: 'text',
                                                        autocomplete: 'off',
                                                        value: '" . $deudas_inquilino[$x]['monto'] * 0.21 . "',
                                                        readonly: true,
                                                        style : 'margin-right: 5px;font-size: 16px;width: 110px;float: left;',
                                                        'class': 'form-control ui-autocomplete-input',
                                                        placeholder: 'IVA/Alquiler'                 
                                                }).appendTo('#bloque'+" . $id_input_new . ");";
                                        }
                                    } else {
                                        $row = "jQuery('<div/>', {
                                            id: 'bloque'+" . $id_input_new . ",
                                            'class' : 'bloque'
                                            }).appendTo('#relleno').hide().fadeIn(700);    

                                            jQuery('<input/>', {
                                                id: 'auto_conc_id'+" . $id_input_new . ",
                                                name: 'auto_conc_id'+" . $id_input_new . ",
                                                type: 'hidden',
                                                autocomplete: 'off',           
                                                value: '" . $concepto->conc_id . "'           
                                            }).appendTo('#bloque'+" . $id_input_new . ");

                                            jQuery('<input/>', {
                                                    id: 'concepto'+" . $id_input_new . ",
                                                    name: 'concepto'+" . $id_input_new . ",
                                                    type: 'text',
                                                    readonly: true,
                                                    onblur:'validar('+" . $id_input_new . "+')',
                                                    value: '" . $concepto->conc_desc . "',     
                                                    onkeyup:'validar('+" . $id_input_new . "+')',
                                                    style : 'margin-right: 5px;font-size: 16px;width: 290px;float: left;',
                                                    'class': 'form-control ui-autocomplete-input',
                                                    placeholder: 'Concepto'                 
                                                }).appendTo('#bloque'+" . $id_input_new . ");

                                                jQuery('<input/>', {
                                                    readonly: false,
                                                    id: 'monto'+" . $id_input_new . ",
                                                    name: 'monto'+" . $id_input_new . ",
                                                    type: 'text',
                                                    onkeyup:'recalcular()',
                                                    onblur:'recalcular()',
                                                    value: '" . $deudas_inquilino[$x]['monto'] . "',
                                                    autocomplete: 'off',
                                                    style : 'margin-right: 5px;font-size: 16px;width: 110px;float: left;',
                                                    'class': 'form-control ui-autocomplete-input',
                                                    placeholder: 'Monto'                 
                                                }).appendTo('#bloque'+" . $id_input_new . ");

                                                jQuery('<input/>', {
                                                    id: 'mes'+" . $id_input_new . ",
                                                    name: 'mes'+" . $id_input_new . ",
                                                    type: 'text',
                                                    value: '" . $deudas_inquilino[$x]['mes'] . ' ' . $deudas_inquilino[$x]['ano'] . "',
                                                    style : 'margin-right: 5px;font-size: 16px;width: 120px;float: left;',
                                                    'class': 'form-control ui-autocomplete-input',
                                                    placeholder: 'Mes'                 
                                                }).appendTo('#bloque'+" . $id_input_new . ");
                                                    
                                                $('#mes'+" . $id_input_new . ").autocomplete({source: meses});                                        
                                                    jQuery('<input/>', {
                                                    id: 'domicilio'+" . $id_input_new . ",
                                                    name: 'domicilio'+" . $id_input_new . ",
                                                    type: 'text',
                                                    value: '" . $contrato['con_domi'] . "',
                                                    style : 'margin-right: 5px;font-size: 16px;width: 230px;float: left;',
                                                    'class': 'form-control ui-autocomplete-input',
                                                    placeholder: 'Domicilio Inmueble'                 
                                                }).appendTo('#bloque'+" . $id_input_new . ");

                                                jQuery('<input/>', {
                                                        readonly: true,
                                                        id: 'interes'+" . $id_input_new . ",
                                                        name: 'interes'+" . $id_input_new . ",
                                                        type: 'text',       
                                                        onkeyup:'recalcular()',
                                                        onclick:'unlock(" . $id_input_new . ")',
                                                        onblur:'recalcular()',
                                                        autocomplete: 'off',
                                                        value: '" . $deudas_inquilino[$x]['dias_mora'] . " dias mora',
                                                        readonly: true,
                                                        style : 'cursor:pointer;margin-right: 5px;font-size: 16px;width: 110px;float: left;',
                                                        'class': 'form-control ui-autocomplete-input',
                                                        placeholder: 'Dias de Interes'                 
                                                }).appendTo('#bloque'+" . $id_input_new . ");";

                                        $row .= "jQuery('<input/>', {
                                                        readonly: true,
                                                        id: 'interes_calculado'+" . $id_input_new . ",
                                                        name: 'interes_calculado'+" . $id_input_new . ",
                                                        type: 'text',
                                                        autocomplete: 'off',
                                                        value: '" . ($deudas_inquilino[$x]['dias_mora'] * $contrato['con_punitorio'] * $deudas_inquilino[$x]['monto']) . "',
                                                        readonly: true,
                                                        style : 'margin-right: 5px;font-size: 16px;width: 110px;float: left;',
                                                        'class': 'form-control ui-autocomplete-input',
                                                        placeholder: 'Monto Interes'                 
                                                }).appendTo('#bloque'+" . $id_input_new . ");";

                                        if ($contrato['con_iva_alq'] == 'Si' && $concepto->conc_desc == 'Alquiler Comercial') {
                                            $row .= " jQuery('<input/>', {
                                                        readonly: true,
                                                        id: 'iva_calculado'+" . $id_input_new . ",
                                                        name: 'iva_calculado'+" . $id_input_new . ",
                                                        type: 'text',
                                                        autocomplete: 'off',
                                                        value: '" . $deudas_inquilino[$x]['monto'] * 0.21 . "',
                                                        readonly: true,
                                                        style : 'margin-right: 5px;font-size: 16px;width: 110px;float: left;',
                                                        'class': 'form-control ui-autocomplete-input',
                                                        placeholder: 'IVA/Alquiler'                 
                                                }).appendTo('#bloque'+" . $id_input_new . ");";
                                        }
                                        $row .= "
                                                jQuery('<span/>', {
                                                    id: 'span'+" . $id_input_new . ",
                                                    onclick: 'removeElement('+" . $id_input_new . "+')',
                                                    style: 'height: 34px;',
                                                    disabled: true,
                                                    'class' : 'btn btn-default btn-lg'
                                                }).appendTo('#bloque'+" . $id_input_new . ");

                                                jQuery('<a/>', {
                                                    'class' : 'glyphicon glyphicon-minus-sign',
                                                    style : 'text-decoration: none; margin-top: -3px;'
                                                }).appendTo('#span'+" . $id_input_new . ");
                                                ";
                                    }
                                    $response['js'] .= $row;
                                    $id_input_new++;
                                }
                                $id_input_new--;
                            } else {
                                $last_pay = $this->get_last_payment($contrato, $concepto->conc_desc);
                                $meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
                                $flag = true;
                                $mes_alq = '';
                                reset($meses);
                                if ($last_pay != false) {
                                    $ano_last = preg_replace("/[^0-9 (),.]/", "", $last_pay['cred_mes_alq']);
                                    $ano_last = trim($ano_last);
                                    $mes_last = preg_replace("/[^^A-Za-z (),.]/", "", $last_pay['cred_mes_alq']);
                                    $mes_last = trim($mes_last);
                                    $mes_last = $this->get_nro_mes($mes_last);
                                    if ($ano_last != $last_pay['cred_mes_alq']) {
                                        $fecha_last_pay = '01-' . $mes_last . '-' . $ano_last;
                                    } else {
                                        $ano_last = explode('-', $last_pay['cred_fecha']);
                                        $ano_last = $ano_last[2];
                                        $fecha_last_pay = '01-' . $mes_last . '-' . $ano_last;
                                    }
                                    if (($mes_last + 1) > 12) {
                                        $mes_prox_cred = 1;
                                        $ano_prox_cred = $ano_last + 1;
                                    } else {
                                        $mes_prox_cred = $mes_last + 1;
                                        $ano_prox_cred = $ano_last;
                                    }
                                    $mes_alq = $this->get_mes($mes_prox_cred);
                                    $mes_debido = '00-' . $mes_prox_cred . '-' . $ano_prox_cred;
                                }
                                // No se encontraron deudas de alquileres o loteos
                                $periodos = $this->basic->get_where('periodos', array('per_contrato' => $contrato['con_id']), 'per_id');
                                $monto = $this->calcular_monto($mes_debido, $periodos);
                                $monto_todo += $monto;
//                                print_r($monto);
//                                print_r($mes_alq . $ano_prox_cred);
//                                $id_input_new++;
                                $row_unica = "$('#concepto1').attr('readonly','true');$('#domicilio1').attr('readonly','true');$('#monto1').val('" . $monto . "');
                                    $('#mes1').val('" . $mes_alq . ' ' . $ano_prox_cred . "');$('#domicilio1').val('" . $contrato['con_domi'] . "');$('#concepto1').val('" . $contrato['con_tipo'] . "');";
                                $row_unica .= "$('#mes1').autocomplete({source: meses});";
                                if ($contrato['con_iva_alq'] == 'Si' && $concepto->conc_desc == 'Alquiler Comercial') {
                                    $row_unica .= " jQuery('<input/>', {
                                                        readonly: true,
                                                        id: 'iva_calculado1',
                                                        name: 'iva_calculado1',
                                                        type: 'text',
                                                        autocomplete: 'off',
                                                        value: '" . $monto * 0.21 . "',
                                                        readonly: true,
                                                        style : 'margin-right: 5px;font-size: 16px;width: 110px;float: left;',
                                                        'class': 'form-control ui-autocomplete-input',
                                                        placeholder: 'IVA/Alquiler'                 
                                                }).appendTo('#bloque1');
                                                ";
                                }
                                $response['js'] .= $row_unica;
                            }
                            $dimensiones = $this->countdim($deudas_serv);

                            $armo_serv = false;
                            if ($dimensiones == 2) {
                                $armo_serv = true;
                                // No se enconraron deudas en los servicios del contrato
//                                print_r('1');
                                $id_input_new++;
                                $response['js'] .= $this->armar_vista_serv($id_input_new, $deudas_serv, $contrato);
                            } else if ($dimensiones != 1) {
                                $armo_serv = true;
//                                print_r('2');
                                $id_input_new++;
                                $response['js'] .= $this->armar_vista($id_input_new, $deudas_serv, $contrato);
                                // Se arma la vista para las deudas encontradas
                            }
                            if (!$armo_serv) {
                                //el contrato no posee servicios a cobrar
//                                $id_input_new--;
                                $response['js'] .= '$("#cant_bloques").val("' . $id_input_new . '");';
                                $response['js'] .= '$("#span"+' . $id_input_new . ').removeAttr("disabled");';
                                $response['js'] .= 'x = ' . $id_input_new . ';';
                                $response['js'] .= 'cant = ' . $id_input_new . ';';
                            }

                            // Fijo la cantidad de bloque que va tener la nueva vista
                            // HELL YEAH!
                        }
                    } else {
                        if ($contrato['con_tolerancia'] <= Date('d')) {
                            $response['js'] .= '
                                    jQuery("<input/>", {
                                    id: "interes"+' . $id_input_new . ',
                                    name: "interes"+' . $id_input_new . ',
                                    type: "text",
                                    autocomplete: "off",
                                    onkeyup:"recalcular()",
                                    onclick:"unlock(' . $id_input_new . ')",
                                    onblur:"recalcular()",                                           
                                    style : "margin-right: 5px;font-size: 16px;width: 110px;float: left;",
                                    "class": "form-control ui-autocomplete-input",
                                    placeholder: "Dias de Interes"                 
                                }).appendTo("#bloque"+' . $id_input_new . ');';
                            $response['js'] .= '$("#interes"+' . $id_input_new . ').hover(function(){
                                    $("#tooltipInt").css("display","block");
                                    },function(){
                                    $("#tooltipInt").css("display","none");
                                });$("#interes"+' . $id_input_new . ').val("' . Date('d') . ' dias mora");
                                jQuery("<input/>", {
                                        id: "interes_calculado"+' . $id_input_new . ',
                                        name: "interes_calculado"+' . $id_input_new . ',
                                        type: "text",
                                        autocomplete: "off",
                                        value: "",                                  
                                        readonly: true,
                                        style : "margin-right: 5px;font-size: 16px;width: 110px;float: left;",
                                        "class": "form-control ui-autocomplete-input",
                                        placeholder: "Monto Interes"                 
                                }).appendTo("#bloque"+' . $id_input_new . ');recalcular();';
                        }
                        if ($contrato['con_iva_alq'] == 'Si' && $concepto->conc_desc == 'Alquiler Comercial') {
                            $row = " $('#concepto" . $id_input_new . "').attr('readonly','true');
                                jQuery('<input/>', {
                                    readonly: true,
                                    id: 'iva_calculado'+" . $id_input_new . ",
                                    name: 'iva_calculado'+" . $id_input_new . ",
                                    type: 'text',
                                    autocomplete: 'off',
                                    value: '',
                                    readonly: true,
                                    style : 'margin-right: 5px;font-size: 16px;width: 110px;float: left;',
                                    'class': 'form-control ui-autocomplete-input',
                                    placeholder: 'IVA/Alquiler'                 
                          }).appendTo('#bloque'+" . $id_input_new . ");recalcular();";
                            $response['js'] .= $row;
                        }
                        $row = '$("#cant_bloques").val("' . $id_input_new . '");';
                        $row .= '$("#span"+' . $id_input_new . ').removeAttr("disabled");';
                        $row .= 'x = ' . $id_input_new . ';';
                        $row .= 'cant = ' . $id_input_new . ';';
                        $response['js'] .= $row;
                    }
                    if (strpos($concepto->conc_desc, 'Comision') !== FALSE) {
                        // existe contrato, usado o no usado entra aca cuando es comision
                        $periodos = $this->basic->get_where('periodos', array('per_contrato' => $contrato['con_id']), 'per_id');
                        if ($contrato['con_iva'] == 'Si') {
                            $row_unica = "$('#domicilio" . $id_input_new . "').val('" . $contrato['con_domi'] . "');
                                $('#domicilio" . $id_input_new . "').attr('readonly','true');
                                $('#concepto" . $id_input_new . "').attr('readonly','true');
                                        jQuery('<input/>', {
                                            readonly: true,
                                            id: 'iva_calculado'+" . $id_input_new . ",
                                            name: 'iva_calculado'+" . $id_input_new . ",
                                            type: 'text',
                                            autocomplete: 'off',
                                            value: '',
                                            readonly: true,
                                            style : 'margin-right: 5px;font-size: 16px;width: 110px;float: left;',
                                            'class': 'form-control ui-autocomplete-input',
                                            placeholder: 'IVA/Comision'                 
                                        }).appendTo('#bloque'+" . $id_input_new . ");";
                            $row_unica .= "$('#mes" . $id_input_new . "').autocomplete({source: meses});recalcular();";
                            $response['js'] .= $row_unica;
                        }
                    }
//                    if ($contrato['con_iva_alq'] == 'Si' && $concepto->conc_desc == 'Alquiler Comercial' && $contrato_usado) {
//                        $row = "$('#domicilio" . $id_input_new . "').val('" . $contrato['con_domi'] . "');
//                                $('#domicilio" . $id_input_new . "').attr('readonly','true');
//                                $('#concepto" . $id_input_new . "').attr('readonly','true');";
//                        $row .= " $('#concepto" . $id_input_new . "').attr('readonly', 'true');
//                                jQuery('<input/>', {
//                                readonly: true,
//                                id: 'iva_calculado'+" . $id_input_new . ",
//                                name: 'iva_calculado'+" . $id_input_new . ",
//                                type: 'text',
//                                autocomplete: 'off',
//                                value: '',
//                                readonly: true,
//                                style : 'margin-right: 5px;font-size: 16px;width: 110px;float: left;',
//                                'class': 'form-control ui-autocomplete-input',
//                                placeholder: 'IVA/Alquiler'
//                                }).appendTo('#bloque' + " . $id_input_new . ");recalcular();
//                        ";
//                        $response['js'] .= $row;
//                    }
//                    $id_input_new--;
//                    print_r($response['js']);

                    $response['js'] .= '$("#monto").val("' . $monto_todo . '");';
                    $response['js'] .= '$("#intere").val("' . $monto_intereses . '");';
                    $total = $monto_todo + $monto_intereses;
                    $response['js'] .= '$("#total_todo").val("' . $total . '");';
                    $response['js'] .= '$("#montos").fadeOut().fadeIn(700);';
                    $response['js'] .= 'recalcular();$("#monto1").blur();';
                    $this->data['porc'] = $contrato['con_porc'];
                    $this->data['iva'] = $contrato['con_iva'];
                    $this->data['iva_alq'] = $contrato['con_iva_alq'];
                    $this->data['punitorio'] = $contrato['con_punitorio'];
                    $this->data['periodos'] = $this->basic->get_where('periodos', array('per_contrato' => $contrato['con_id']));
                    $this->data['servicios'] = $this->basic->get_where('servicios', array('serv_contrato' => $contrato['con_id']));
                    $this->data['pintar'] = $this->fecha_periodo_actual($this->data['periodos']);
                    $response['periodos'] = $this->load->view('manager/transacciones/periodos_serv', $this->data, TRUE);
                } else {
                    if ($rtdo != false || $concepto->conc_desc == 'Loteo' || $concepto->conc_desc == 'Comision' || $concepto->conc_desc == 'Alquiler N' || $concepto->conc_desc == 'Alquiler' || $concepto->conc_desc == 'Alquiler Comercial' || $concepto->conc_desc == 'Alquiler Comercial N') {
                        $response['js'] = '$(".periodos").addClass("alert alert-danger");';
                        $response['periodos'] = 'No existe contrato que vincule a ' . $prop . ' y ' . $inq . ', o el mismo esta VENCIDO';
                    } else {
                        $response['js'] = '$(".periodos").removeClass("alert alert-danger");';
                    }
                }
            }
        } else {
            $this->data[$table] = $this->basic->get_all($table);
        }
        $response['html'] = $this->load->view('manager/' . $table . '/buscar_fila_' . $table, $this->data, TRUE);
        echo json_encode($response);
    }

    function countdim($array) {
        if (is_array(reset($array))) {
            $return = $this->countdim(reset($array)) + 1;
        } else {
            $return = 1;
        }
        return $return;
    }

    function has_next($array) {
        if (is_array($array)) {
            if (next($array) === false) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    function armar_vista($id_input_new, $deudas_inquilino, $contrato) {
        $row = '';
        for ($x = 0; $x < count($deudas_inquilino); $x++) {
            for ($y = 0; $y < count($deudas_inquilino[$x]); $y++) {
                if (!empty($deudas_inquilino[$x][$y]) || !empty($deudas_inquilino[$x])) {
                    $conc = $this->basic->get_where('conceptos', array('conc_desc' => $deudas_inquilino[$x][$y]['concepto']))->row_array();
                    if ($id_input_new == 1) {
                        $row = "$('#concepto" . $id_input_new . "').attr('readonly','true');$('#domicilio" . $id_input_new . "').attr('readonly','true');$('#monto" . $id_input_new . "').val('" . $deudas_inquilino[$x][$y]['monto'] . "');
                    $('#mes" . $id_input_new . "').val('" . $deudas_inquilino[$x][$y]['mes'] . ' ' . $deudas_inquilino[$x][$y]['ano'] . "');$('#domicilio" . $id_input_new . "').val('" . $contrato['con_domi'] . "');";
                        $row .= "$('#mes1').autocomplete({source: meses});";
                        if ($deudas_inquilino[$x][$y]['dias_mora'] != 0) {
                            $row .= '$("#interes' . $id_input_new . '").remove();
                                    jQuery("<input/>", {
                                    id: "interes' . $id_input_new . '",
                                    name: "interes' . $id_input_new . '",
                                    onkeyup:"recalcular()",
                                    onblur:"recalcular()",
                                    onclick:"unlock(' . $id_input_new . ')",
                                    type: "text",
                                    autocomplete: "off",
                                    readonly: true,
                                    style : "cursor:pointer;margin-right: 5px;font-size: 16px;width: 110px;float: left;",
                                    "class": "form-control ui-autocomplete-input",
                                    placeholder: "Dias de Interes"                 
                                }).appendTo("#bloque' . $id_input_new . '");';
                            $row .= '$("#interes' . $id_input_new . '").hover(function(){
                                    $("#tooltipInt").css("display","block");
                                    },function(){
                                    $("#tooltipInt").css("display","none");
                                });$("#interes' . $id_input_new . '").val("' . $deudas_inquilino[$x][$y]['dias_mora'] . ' dias mora");
                                   

                                jQuery("<input/>", {
                                        id: "interes_calculado1",
                                        name: "interes_calculado1",
                                        type: "text",
                                        autocomplete: "off",
                                        value: "' . ($deudas_inquilino[$x][$y]['dias_mora'] * $contrato['con_punitorio'] * $deudas_inquilino[$x][$y]['monto']) . '",                                  
                                        readonly: true,
                                        style : "margin-right: 5px;font-size: 16px;width: 110px;float: left;",
                                        "class": "form-control ui-autocomplete-input",
                                        placeholder: "Dias de Interes"                 
                                }).appendTo("#bloque1");';
                        }
                        if ($contrato['con_iva_alq'] == 'Si' && $conc == 'Alquiler Comercial') {
                            $row .= '
                                jQuery("<input/>", {
                                        id: "iva_calculado1",
                                        name: "iva_calculado1",
                                        type: "text",
                                        autocomplete: "off",
                                        value: "' . $deudas_inquilino[$x][$y]['monto'] * 0.21 . '",                                  
                                        readonly: true,
                                        style : "margin-right: 5px;font-size: 16px;width: 110px;float: left;",
                                        "class": "form-control ui-autocomplete-input",
                                        placeholder: "IVA/Alquiler"                 
                                }).appendTo("#bloque1");';
                        }
                    } else {
                        $row .= "$('#bloque" . $id_input_new . "').empty();";
                        $row .= "jQuery('<div/>', {
                                    id: 'bloque'+" . $id_input_new . ",
                                    'class' : 'bloque'
                                    }).appendTo('#relleno').hide().fadeIn(700);    
                            
                                    jQuery('<input/>', {
                                        id: 'auto_conc_id'+" . $id_input_new . ",
                                        name: 'auto_conc_id'+" . $id_input_new . ",
                                        type: 'hidden',
                                        autocomplete: 'off',           
                                        value: '" . $conc['conc_id'] . "'           
                                    }).appendTo('#bloque'+" . $id_input_new . ");

                                    jQuery('<input/>', {
                                            id: 'concepto'+" . $id_input_new . ",
                                            name: 'concepto'+" . $id_input_new . ",
                                            type: 'text',
                                            readonly: true,
                                            onblur:'validar('+" . $id_input_new . "+')',
                                            value: '" . $deudas_inquilino[$x][$y]['concepto'] . "',     
                                            onkeyup:'validar('+" . $id_input_new . "+')',
                                            style : 'margin-right: 5px;font-size: 16px;width: 290px;float: left;',
                                            'class': 'form-control ui-autocomplete-input',
                                            placeholder: 'Concepto'                 
                                        }).appendTo('#bloque'+" . $id_input_new . ");

                                        jQuery('<input/>', {
                                            readonly: false,
                                            id: 'monto'+" . $id_input_new . ",
                                            name: 'monto'+" . $id_input_new . ",
                                            type: 'text',
                                            onkeyup:'recalcular()',
                                            onblur:'recalcular()',
                                            autocomplete: 'off',
                                            style : 'margin-right: 5px;font-size: 16px;width: 110px;float: left;',
                                            'class': 'form-control ui-autocomplete-input',
                                            placeholder: 'Monto'                 
                                        }).appendTo('#bloque'+" . $id_input_new . ");

                                        jQuery('<input/>', {
                                            id: 'mes'+" . $id_input_new . ",
                                            name: 'mes'+" . $id_input_new . ",
                                            type: 'text',
                                            value: '" . $deudas_inquilino[$x][$y]['mes'] . ' ' . $deudas_inquilino[$x][$y]['ano'] . "',
                                            style : 'margin-right: 5px;font-size: 16px;width: 120px;float: left;',
                                            'class': 'form-control ui-autocomplete-input',
                                            placeholder: 'Mes'                 
                                        }).appendTo('#bloque'+" . $id_input_new . ");
                                        $('#mes'+" . $id_input_new . ").autocomplete({source: meses});
                                        jQuery('<input/>', {
                                            readonly: true,
                                            id: 'domicilio'+" . $id_input_new . ",
                                            name: 'domicilio'+" . $id_input_new . ",
                                            type: 'text',
                                            value: '" . $contrato['con_domi'] . "',
                                            style : 'margin-right: 5px;font-size: 16px;width: 230px;float: left;',
                                            'class': 'form-control ui-autocomplete-input',
                                            placeholder: 'Domicilio Inmueble'                 
                                        }).appendTo('#bloque'+" . $id_input_new . ");";

                        if ($contrato['con_iva_alq'] == 'Si' && $conc == 'Alquiler Comercial') {
                            $row .= '
                                jQuery("<input/>", {
                                        id: "iva_calculado+"' . $id_input_new . ',
                                        name: "iva_calculado+"' . $id_input_new . ',
                                        type: "text",
                                        autocomplete: "off",
                                        value: "' . $deudas_inquilino[$x][$y]['monto'] * 0.21 . '",                                  
                                        readonly: true,
                                        style : "margin-right: 5px;font-size: 16px;width: 110px;float: left;",
                                        "class": "form-control ui-autocomplete-input",
                                        placeholder: "IVA/Alquiler"                 
                                }).appendTo("#bloque"+' . $id_input_new . ');';
                        }

                        if ($deudas_inquilino[$x][$y]['concepto'] == 'Expensas') {
                            if ($deudas_inquilino[$x][$y]['dias_mora'] != 0) {
                                $row .= "

                                        jQuery('<input/>', {
                                                readonly: true,
                                                id: 'interes'+" . $id_input_new . ",
                                                name: 'interes'+" . $id_input_new . ",
                                                type: 'text',
                                                onkeyup:'recalcular()',
                                                onblur:'recalcular()',
                                                onclick:'unlock(" . $id_input_new . ")',
                                                autocomplete: 'off',
                                                value: '" . $deudas_inquilino[$x][$y]['dias_mora'] . " dias mora',
                                                readonly: true,
                                                style : 'cursor:pointer;margin-right: 5px;font-size: 16px;width: 110px;float: left;',
                                                'class': 'form-control ui-autocomplete-input',
                                                placeholder: 'Dias de Interes'                 
                                        }).appendTo('#bloque'+" . $id_input_new . ");
                                            
                                        jQuery('<input/>', {
                                            readonly: true,
                                            id: 'interes_calculado'+" . $id_input_new . ",
                                            name: 'interes_calculado'+" . $id_input_new . ",
                                            type: 'text',
                                            autocomplete: 'off',
                                            value: '" . ($deudas_inquilino[$x][$y]['dias_mora'] * $contrato['con_punitorio'] * $deudas_inquilino[$x][$y]['monto']) . "',
                                            readonly: true,
                                            style : 'margin-right: 5px;font-size: 16px;width: 110px;float: left;',
                                            'class': 'form-control ui-autocomplete-input',
                                            placeholder: 'Dias de Interes'                 
                                        }).appendTo('#bloque'+" . $id_input_new . ");
                                    ";
                            }
                        }
                        $row .= "
                                        jQuery('<span/>', {
                                            id: 'span'+" . $id_input_new . ",
                                            onclick: 'removeElement('+" . $id_input_new . "+')',
                                            style: 'height: 34px;',
                                            disabled: true,
                                            'class' : 'btn btn-default btn-lg'
                                        }).appendTo('#bloque'+" . $id_input_new . ");

                                        jQuery('<a/>', {
                                            'class' : 'glyphicon glyphicon-minus-sign',
                                            style : 'text-decoration: none; margin-top: -3px;'
                                        }).appendTo('#span'+" . $id_input_new . ");";
                    }
                    $id_input_new++;
                }
            }
        }
        $id_input_new = $id_input_new - 1;
        $row .= '$("#cant_bloques").val("' . $id_input_new . '");';
        $row .= '$("#span"+' . $id_input_new . ').removeAttr("disabled");';
        $row .= 'x = ' . $id_input_new . ';';
        $row .= 'cant = ' . $id_input_new . ';';
        return $row;
    }

    function armar_vista_serv($id_input_new, $deudas_inquilino, $contrato) {
        $row = '';
        for ($x = 0; $x < count($deudas_inquilino); $x++) {
            if (!empty($deudas_inquilino[$x])) {
                $conc = $this->basic->get_where('conceptos', array('conc_desc' => $deudas_inquilino[$x]['concepto']))->row_array();
                if ($id_input_new == 1) {
                    $row = "$('#concepto1').attr('readonly','true');$('#domicilio1').attr('readonly','true');$('#monto1').val('" . $deudas_inquilino[$x]['monto'] . "');
                    $('#mes1').val('" . $deudas_inquilino[$x]['mes'] . ' ' . $deudas_inquilino[$x]['ano'] . "');$('#domicilio1').val('" . $contrato['con_domi'] . "');";
                    $row .= "$('#mes1').autocomplete({source: meses}); ";
                    if ($deudas_inquilino[$x]['dias_mora'] != 0) {
                        $row .= '$("#interes1").remove();
                                    jQuery("<input/>", {
                                    id: "interes1",
                                    name: "interes1",
                                    onkeyup:"recalcular()",
                                    onblur:"recalcular()",
                                    onclick:"unlock(1)",
                                    type: "text",
                                    autocomplete: "off",
                                    readonly: true,
                                    style : "cursor:pointer;margin-right: 5px;font-size: 16px;width: 110px;float: left;",
                                    "class": "form-control ui-autocomplete-input",
                                    placeholder: "Dias de Interes"                 
                                }).appendTo("#bloque1");';
                        $row .= '$("#interes1").hover(function(){
                                    $("#tooltipInt").css("display","block");
                                    },function(){
                                    $("#tooltipInt").css("display","none");
                                });$("#interes1").val("' . $deudas_inquilino[$x]['dias_mora'] . ' dias mora");
                                   
                                jQuery("<input/>", {
                                        id: "interes_calculado1",
                                        name: "interes_calculado1",
                                        type: "text",
                                        autocomplete: "off",
                                        value: "' . ($deudas_inquilino[$x]['dias_mora'] * $contrato['con_punitorio'] * $deudas_inquilino[$x]['monto']) . '",                                  
                                        readonly: true,
                                        style : "margin-right: 5px;font-size: 16px;width: 110px;float: left;",
                                        "class": "form-control ui-autocomplete-input",
                                        placeholder: "Dias de Interes"                 
                                }).appendTo("#bloque1");';
                    }

                    if ($contrato['con_iva_alq'] == 'Si' && $conc == 'Alquiler Comercial') {
                        $row .= '
                                jQuery("<input/>", {
                                        id: "iva_calculado1",
                                        name: "iva_calculado1",
                                        type: "text",
                                        autocomplete: "off",
                                        value: "' . $deudas_inquilino[$x][$y]['monto'] * 0.21 . '",                                  
                                        readonly: true,
                                        style : "margin-right: 5px;font-size: 16px;width: 110px;float: left;",
                                        "class": "form-control ui-autocomplete-input",
                                        placeholder: "IVA/Alquiler"                 
                                }).appendTo("#bloque1");';
                    }
                } else {

                    $row .= "$('#bloque" . $id_input_new . "').empty();";
                    $row .= "jQuery('<div/>', {
                                    id: 'bloque'+" . $id_input_new . ",
                                    'class' : 'bloque'
                                    }).appendTo('#relleno').hide().fadeIn(700);    
                            
                                    jQuery('<input/>', {
                                        id: 'auto_conc_id'+" . $id_input_new . ",
                                        name: 'auto_conc_id'+" . $id_input_new . ",
                                        type: 'hidden',
                                        autocomplete: 'off',           
                                        value: '" . $conc['conc_id'] . "'           
                                    }).appendTo('#bloque'+" . $id_input_new . ");

                                    jQuery('<input/>', {
                                            id: 'concepto'+" . $id_input_new . ",
                                            name: 'concepto'+" . $id_input_new . ",
                                            type: 'text',
                                            readonly: true,
                                            onblur:'validar('+" . $id_input_new . "+')',
                                            value: '" . $deudas_inquilino[$x]['concepto'] . "',     
                                            onkeyup:'validar('+" . $id_input_new . "+')',
                                            style : 'margin-right: 5px;font-size: 16px;width: 290px;float: left;',
                                            'class': 'form-control ui-autocomplete-input',
                                            placeholder: 'Concepto'                 
                                        }).appendTo('#bloque'+" . $id_input_new . ");

                                        jQuery('<input/>', {
                                            readonly: false,
                                            id: 'monto'+" . $id_input_new . ",
                                            name: 'monto'+" . $id_input_new . ",
                                            type: 'text',
                                            onkeyup:'recalcular()',
                                            onblur:'recalcular()',
                                            autocomplete: 'off',
                                            style : 'margin-right: 5px;font-size: 16px;width: 110px;float: left;',
                                            'class': 'form-control ui-autocomplete-input',
                                            placeholder: 'Monto'                 
                                        }).appendTo('#bloque'+" . $id_input_new . ");

                                        jQuery('<input/>', {
                                            id: 'mes'+" . $id_input_new . ",
                                            name: 'mes'+" . $id_input_new . ",
                                            type: 'text',
                                            value: '" . $deudas_inquilino[$x]['mes'] . ' ' . $deudas_inquilino[$x]['ano'] . "',
                                            style : 'margin-right: 5px;font-size: 16px;width: 120px;float: left;',
                                            'class': 'form-control ui-autocomplete-input',
                                            placeholder: 'Mes'                 
                                        }).appendTo('#bloque'+" . $id_input_new . ");
                                        $('#mes'+" . $id_input_new . ").autocomplete({source: meses}); 
                                        jQuery('<input/>', {
                                            readonly: true,
                                            id: 'domicilio'+" . $id_input_new . ",
                                            name: 'domicilio'+" . $id_input_new . ",
                                            type: 'text',
                                            value: '" . $contrato['con_domi'] . "',
                                            style : 'margin-right: 5px;font-size: 16px;width: 230px;float: left;',
                                            'class': 'form-control ui-autocomplete-input',
                                            placeholder: 'Domicilio Inmueble'                 
                                        }).appendTo('#bloque'+" . $id_input_new . ");";
                    if ($contrato['con_iva_alq'] == 'Si' && $conc == 'Alquiler Comercial') {
                        $row .= '
                                jQuery("<input/>", {
                                        id: "iva_calculado+"' . $id_input_new . ',
                                        name: "iva_calculado+"' . $id_input_new . ',
                                        type: "text",
                                        autocomplete: "off",
                                        value: "' . $deudas_inquilino[$x]['monto'] * 0.21 . '",                                  
                                        readonly: true,
                                        style : "margin-right: 5px;font-size: 16px;width: 110px;float: left;",
                                        "class": "form-control ui-autocomplete-input",
                                        placeholder: "IVA/Alquiler"                 
                                }).appendTo("#bloque"+' . $id_input_new . ');';
                    }
                    if ($deudas_inquilino[$x]['concepto'] == 'Expensas' && $deudas_inquilino[$x]['dias_mora'] != 0) {
                        $row .= "jQuery('<input/>', {
                                                readonly: true,
                                                id: 'interes'+" . $id_input_new . ",
                                                name: 'interes'+" . $id_input_new . ",
                                                type: 'text',
                                                onkeyup:'recalcular()',
                                                onblur:'recalcular()',
                                                onclick:'unlock(" . $id_input_new . ")',
                                                autocomplete: 'off',
                                                value: '" . $deudas_inquilino[$x]['dias_mora'] . " dias mora',
                                                readonly: true,
                                                style : 'cursor:pointer;margin-right: 5px;font-size: 16px;width: 110px;float: left;',
                                                'class': 'form-control ui-autocomplete-input',
                                                placeholder: 'Dias de Interes'                 
                                        }).appendTo('#bloque'+" . $id_input_new . ");
                                            
                                        jQuery('<input/>', {
                                            readonly: true,
                                            id: 'interes_calculado'+" . $id_input_new . ",
                                            name: 'interes_calculado'+" . $id_input_new . ",
                                            type: 'text',
                                            autocomplete: 'off',
                                            value: '" . ($deudas_inquilino[$x]['dias_mora'] * $contrato['con_punitorio'] * $deudas_inquilino[$x]['monto']) . "',
                                            readonly: true,
                                            style : 'margin-right: 5px;font-size: 16px;width: 110px;float: left;',
                                            'class': 'form-control ui-autocomplete-input',
                                            placeholder: 'Dias de Interes'                 
                                        }).appendTo('#bloque'+" . $id_input_new . ");
                                    ";
                    }
                    $row .= "
                                        jQuery('<span/>', {
                                            id: 'span'+" . $id_input_new . ",
                                            onclick: 'removeElement('+" . $id_input_new . "+')',
                                            style: 'height: 34px;',
                                            disabled: true,
                                            'class' : 'btn btn-default btn-lg'
                                        }).appendTo('#bloque'+" . $id_input_new . ");

                                        jQuery('<a/>', {
                                            'class' : 'glyphicon glyphicon-minus-sign',
                                            style : 'text-decoration: none; margin-top: -3px;'
                                        }).appendTo('#span'+" . $id_input_new . ");";
                }
                $id_input_new++;
            }
        }
        $id_input_new = $id_input_new - 1;
        $row .= '$("#cant_bloques").val("' . $id_input_new . '");';
        $row .= '$("#span"+' . $id_input_new . ').removeAttr("disabled");';
        $row .= 'x = ' . $id_input_new . ';';
        $row .= 'cant = ' . $id_input_new . ';';
        return $row;
    }

    function get_deudas($last_payment, $con) {
        $periodos = $this->basic->get_where('periodos', array('per_contrato' => $con['con_id']), 'per_id');
        $deuda_acum = array();
        if ($last_payment['cred_tipo_pago'] == 'A Cuenta') {
            $mes_last = preg_replace("/[^^A-Za-z (),.]/", "", $last_payment['cred_mes_alq']);
            $mes_last = trim($mes_last);
            $ano_last = preg_replace("/[^0-9 (),.]/", "", $last_payment['cred_mes_alq']);
            $ano_ultimo_pago = trim($ano_last);
            $mes_ultimo_pago = $this->get_nro_mes($mes_last);
            $abono_fecha = '10' . '-' . $mes_ultimo_pago . '-' . $ano_ultimo_pago;
            $monto_periodo = 0;
            foreach ($periodos->result_array() as $periodo) {
                $agregar = $this->comp_fecha($abono_fecha, $periodo['per_inicio'], $periodo['per_fin']);
                if ($agregar == '11') {
                    $monto_periodo = $periodo['per_monto'];
                }
            }
            $creditos_pagados_a_cuenta = $this->basic->get_where('creditos', array('cred_mes_alq' => $last_payment['cred_mes_alq'], 'cred_tipo_pago' => 'A Cuenta', 'cred_cc' => $con['con_prop'], 'cred_depositante' => $con['con_inq']));
            $pagado_a_cuenta = 0;
            if ($creditos_pagados_a_cuenta->num_rows() > 0) {
                foreach ($creditos_pagados_a_cuenta->result_array() as $row) {
                    if (strpos($row['cred_concepto'], 'Alquiler') !== FALSE || strpos($row['cred_concepto'], 'Loteo') !== FALSE)
                        $pagado_a_cuenta += $row['cred_monto'];
                }
            }
//            print_r($monto_periodo);
//            print_r($pagado_a_cuenta);
            $diferencia_a_saldar = $monto_periodo - $pagado_a_cuenta;
            $deuda = array(
                'mes' => $mes_last,
                'dias_mora' => 0,
                'ano' => $ano_ultimo_pago,
                'saldo_cuenta' => 1,
                'monto' => $diferencia_a_saldar,
                'intereses' => 0
            );

            $mes_debido = '00-' . $mes_ultimo_pago . '-' . $ano_ultimo_pago;
            $deuda = $this->calcular_intereses($deuda, $con, $mes_debido, $periodos, Date('d-m-Y'));
            array_push($deuda_acum, $deuda);
        }
        $primer_periodo = $periodos->first_row('array');
        $meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
        if ($last_payment != false) {
            $mes_last = preg_replace("/[^^A-Za-z (),.]/", "", $last_payment['cred_mes_alq']);
            $mes_last = trim($mes_last);
            $mes_alq = $mes_last;
            $ano_last = preg_replace("/[^0-9 (),.]/", "", $last_payment['cred_mes_alq']);
            $ano_ultimo_pago = trim($ano_last);
            $mes_ultimo_pago = $this->get_nro_mes($mes_last);
        } else {
            $fecha_ultimo_pago = explode('-', $primer_periodo['per_inicio']);
            $mes_ultimo_pago = $fecha_ultimo_pago[1] - 1;
            $ano_ultimo_pago = $fecha_ultimo_pago[2];
            if ($mes_ultimo_pago == 0) {
                $mes_ultimo_pago == 12;
                $ano_ultimo_pago = $fecha_ultimo_pago[2] - 1;
            }
            $mes_alq = $this->get_mes($mes_ultimo_pago);
        }
        while (current($meses) != $mes_alq) {
            next($meses);
        }
        next($meses); //Me muevo un elemento mas en el array de meses para empezar con el primer mes debido
        if (key($meses) == false) {
            $cont_mes_inicial = 0;
        } else {
            $cont_mes_inicial = key($meses);
        }
        $ano_nuevo = false;
        if (($cont_mes_inicial + 1) == 1) {
            $ano_nuevo = true;
            $ano_deuda = $ano_ultimo_pago + 1;
        } else {
            $ano_deuda = $ano_ultimo_pago;
        }
        $deuda = array();
        $a = 0;
        $entro = false;
        $mes_hoy_letras = $this->get_mes(Date('m'));
        $fecha_stop = $mes_ultimo_pago . '-' . $ano_ultimo_pago;
        $fecha_stop_comp = '00-' . $fecha_stop;
        $fecha_stop_comp = strtotime($fecha_stop_comp);
        $fecha_hoy = strtotime('00-' . Date('m-Y'));
        if ($fecha_hoy >= $fecha_stop_comp) {
            for ($i = $cont_mes_inicial; $fecha_stop != Date('m-Y'); ($i == 11 ? $i = 0 : $i++)) {
                //crea el arreglo de meses que debe hasta la $fecha
                $corriente = $this->get_nro_mes($meses[$i]);
                if ($corriente != Date('m') || $ano_deuda != Date('Y')) {
                    $entro = true;
                    $a = $cont_mes_inicial + 1;
                    if ($a <= 12) {
                        $deuda = array(
                            'mes' => $meses[$i],
                            'dias_mora' => 0,
                            'ano' => $ano_deuda,
                            'saldo_cuenta' => 0,
                            'monto' => 0,
                            'intereses' => 0,
                        );
                        $cont_mes_inicial++;
                    } else {
                        $cont_mes_inicial = 1;
                        $ano_deuda++;
                        $deuda = array(
                            'mes' => $meses[$i],
                            'dias_mora' => 0,
                            'ano' => $ano_deuda,
                            'saldo_cuenta' => 0,
                            'monto' => 0,
                            'intereses' => 0,
                        );
                    }
                    $mes_debido = '00-' . $corriente . '-' . $ano_deuda;
                    $deuda['monto'] = $this->calcular_monto($mes_debido, $periodos);
                    $deuda = $this->calcular_intereses($deuda, $con, $mes_debido, $periodos, Date('d-m-Y'));
                    array_push($deuda_acum, $deuda);
                }
                $fecha_stop = $corriente . '-' . $ano_deuda;
            }
//            echo 'deuda acum <pre>';
//            print_r($deuda_acum);
//            echo '<pre>';
        }
        // Este bloque fuera del for calcula un mes de deuda mas si es que el dia en el que se ejecuta
        // el informe se esta propasando el limite de tolerancia de mora
        $dia_informe = Date('d');
        if ($dia_informe > $con['con_tolerancia']) {
            $deuda = array(
                'mes' => $mes_hoy_letras,
                'dias_mora' => 0,
                'ano' => Date('Y'),
                'monto' => 0,
                'saldo_cuenta' => 0,
                'intereses' => 0
            );
            $mes_debido = '00-' . Date('m') . '-' . Date('Y');
            $deuda['monto'] = $this->calcular_monto($mes_debido, $periodos);
            $deuda = $this->calcular_intereses($deuda, $con, $mes_debido, $periodos, Date('d-m-Y'));
            array_push($deuda_acum, $deuda);
        }
//        }
//        echo '<pre>';
//        print_r($deuda_acum);
//        echo '</pre>';
        return $deuda_acum;
    }

    function calcular_intereses_expensas($deuda, $contrato, $mes_debido, $fecha_informe) {
        $fecha_informe_exp = explode('-', $fecha_informe);
        $dia_informe = $fecha_informe_exp[0];
        // Obtengo la cantidad de dias de mora
        $dias_de_mora = $this->calcular_dias_mora($mes_debido, $fecha_informe);
        if ($dias_de_mora > $contrato['con_tolerancia']) {
            if ($dia_informe <= $contrato['con_tolerancia']) {
                // Unicamente si la fecha en la que se solicita el contrato sobrepasa la tolerancia tambien se calculan los intereses
                // para tal mes
//                $dias_de_mora = $dias_de_mora - $dia_informe;
            }
            $deuda['dias_mora'] = $dias_de_mora;
        }
        return $deuda;
    }

    function get_monto($deudas) {
        $monto = 0;
        for ($x = 0; $x < count($deudas); $x++) {
            $monto += $deudas[$x]['monto'];
        }
        return $monto;
    }

    function get_deudas_serv($last_payment, $con) {
        $deuda_acum = array();
        $periodos = $this->basic->get_where('periodos', array('per_contrato' => $con['con_id']), 'per_id');
        $meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
        $primer_periodo = $periodos->first_row('array');
        if (is_array($last_payment)) {
            $mes_last = preg_replace("/[^^A-Za-z (),.]/", "", $last_payment['cred_mes_alq']);
            $mes_last = trim($mes_last);
            $ano_last = preg_replace("/[^0-9 (),.]/", "", $last_payment['cred_mes_alq']);
            $ano_ultimo_pago = trim($ano_last);
            $mes_ultimo_pago = $this->get_nro_mes($mes_last);
            $mes_alq = $mes_last;
            $concepto = $last_payment['cred_concepto'];
        } else {
            $fecha_ultimo_pago = explode('-', $primer_periodo['per_inicio']);
            $mes_ultimo_pago = $fecha_ultimo_pago[1] - 1;
            $ano_ultimo_pago = $fecha_ultimo_pago[2];
            if ($mes_ultimo_pago == 0) {
                $mes_ultimo_pago == 12;
                $ano_ultimo_pago = $fecha_ultimo_pago[2] - 1;
            }
            $mes_alq = $this->get_mes($mes_ultimo_pago);
            $concepto = $last_payment;
        }
        reset($meses);
        while (current($meses) != $mes_alq) {
            next($meses);
        }
        next($meses); //Me muevo un elemento mas en el array de meses para empezar con el primer mes debido
        if (key($meses) == false) {
            $cont_mes_inicial = 0;
        } else {
            $cont_mes_inicial = key($meses);
        }
        $ano_nuevo = false;
        if (($cont_mes_inicial + 1) == 1) {
            $ano_nuevo = true;
            $ano_deuda = $ano_ultimo_pago + 1;
        } else {
            $ano_deuda = $ano_ultimo_pago;
        }
        $deuda = array();
        $a = 0;
        $mes_hoy_letras = $this->get_mes(Date('m'));
        $fecha_stop = $mes_ultimo_pago . '-' . $ano_ultimo_pago;
        $entro = false;
        $fecha_stop_comp = '00-' . $fecha_stop;
        $fecha_stop_comp = strtotime($fecha_stop_comp);
        $fecha_hoy = strtotime('00-' . Date('m-Y'));
        if ($fecha_hoy >= $fecha_stop_comp) {
            for ($i = $cont_mes_inicial; $fecha_stop != Date('m-Y'); ($i == 11 ? $i = 0 : $i++)) {
                //crea el arreglo de meses que debe hasta la $fecha
                $corriente = $this->get_nro_mes($meses[$i]);
                if ($corriente != Date('m') || $ano_deuda != Date('Y')) {
                    $a = $cont_mes_inicial + 1;
                    $entro = true;
                    if ($a <= 12) {
                        $deuda = array(
                            'mes' => $meses[$i],
                            'dias_mora' => 0,
                            'concepto' => $concepto,
                            'ano' => $ano_deuda,
                            'monto' => 0,
                            'saldo_cuenta' => 0,
                            'intereses' => 0,
                        );
                        $cont_mes_inicial++;
                    } else {
                        $cont_mes_inicial = 1;
                        $ano_deuda++;
                        $deuda = array(
                            'mes' => $meses[$i],
                            'dias_mora' => 0,
                            'concepto' => $concepto,
                            'ano' => $ano_deuda,
                            'saldo_cuenta' => 0,
                            'monto' => 0,
                            'intereses' => 0,
                        );
                    }
                    $mes_debido = '00-' . $corriente . '-' . $ano_deuda;
                    if ($concepto == 'Expensas') {
                        $deuda = $this->calcular_intereses_expensas($deuda, $con, $mes_debido, Date('d-m-Y'));
                    }
                    array_push($deuda_acum, $deuda);
                }
                $fecha_stop = $corriente . '-' . $ano_deuda;
            }
        }
        // Este bloque fuera del for calcula un mes de deuda mas si es que el dia en el que se ejecuta
        // el informe se esta propasando el limite de tolerancia de mora
        $dia_informe = Date('d');
        if ($dia_informe > $con['con_tolerancia']) {
            $deuda = array(
                'mes' => $mes_hoy_letras,
                'dias_mora' => 0,
                'saldo_cuenta' => 0,
                'concepto' => $concepto,
                'ano' => Date('Y'),
                'monto' => 0,
                'intereses' => 0
            );
            if ($concepto == 'Expensas') {
                $mes_debido = '00-' . Date('m') . '-' . Date('Y');
                $deuda = $this->calcular_intereses_expensas($deuda, $con, $mes_debido, Date('d-m-Y'));
            }
            array_push($deuda_acum, $deuda);
        }
//        }
        return $deuda_acum;
    }

    function fecha_periodo_actual($periodos) {
        /* Me devuelve el obj Periodo que corresponde a la fecha actual,
         *  para pintar su fila en la tabla de periodos */
        $id = 0;
        foreach ($periodos->result_array() as $periodo) {
            $agregar = $this->comp_fecha(date('d-m-Y'), $periodo['per_inicio'], $periodo['per_fin']);
            if ($agregar == '11') {
                $id = $periodo['per_id'];
                return $id;
            }
        }
    }

    function buscar_concepto_contrato($table, $id = false) {
        if ($id != false) {
            $concepto = $this->basic->get_where($table, array('conc_id' => $id))->row_array();
        } else {
            $concepto = $this->basic->get_all($table);
        }
        $response['id'] = $concepto['conc_id'];
        $response['html'] = $concepto['conc_desc'];
        echo json_encode($response);
    }

    function buscar_concepto_c($table, $op = false, $id = false) {
        if ($id != false) {
            if ($table == 'conceptos') {
                $concepto = $this->basic->get_where($table, array('conc_tipo' => $op, 'conc_id' => $id))->row_array();
            }
        } else {
            $concepto = $this->basic->get_all($table);
        }
        $response['id'] = $concepto['conc_id'];
        $response['html'] = $concepto['conc_desc'];
        echo json_encode($response);
    }

    function buscar_concepto_serv($table, $cc = false, $id = false) {
        if ($id != false) {
            if ($table == 'conceptos') {
                $concepto = $this->basic->get_where($table, array('conc_cc' => $cc, 'conc_id' => $id))->row_array();
            }
        } else {
            $concepto = $this->basic->get_all($table);
        }
        $response['id'] = $concepto['conc_id'];
        $response['html'] = $concepto['conc_desc'];
        echo json_encode($response);
    }

    function buscar_cliente($table, $id = false) {
        if ($id != false) {
            if ($table == 'clientes') {
                /* Obtiene una lista de los clientes, si es que $table es clientes */
                $cliente = $this->basic->get_where($table, array('client_id' => $id))->row();
                $contrato = $this->basic->get_where('contratos', array('con_inq' => $cliente->client_name, 'con_enabled' => 1))->row();
                $response['prop'] = null;
                if ($contrato != null) {
                    $response['prop'] = $contrato->con_prop;
                } else {
                    $response['js'] = '$(".periodos").addClass("alert alert-danger");';
                    $response['periodos'] = 'No existe contrato que vincule al inquilino ' . $cliente->client_name . ', o el mismo esta VENCIDO';
                }
                $response['id'] = $cliente->client_id;
            }
        } else {
            $cliente = $this->basic->get_all($table);
        }
        $response['html'] = $cliente->client_name;
        echo json_encode($response);
    }

    function buscar_prop($table, $id = false) {
        $response['id'] = 0;
        if ($id != false) {
            if ($table == 'cuentas_corrientes') {
                /* Obtiene una lista de los clientes, si es que $table es clientes */
                $cc = $this->basic->get_where($table, array('cc_id' => $id))->row();
                $response['id'] = $cc->cc_id;
                $response['saldo'] = $cc->cc_saldo + $cc->cc_varios;
            }
        } else {
            $cc = $this->basic->get_all($table);
        }
        $response['html'] = $cc->cc_prop;
        echo json_encode($response);
    }

    function buscar_contrato($table, $id = false) {
        $cons = $this->basic->get_where('contratos', array(), 'con_prop', '');
        $this->data['contratos_vigentes'] = 0;
        foreach ($cons->result_array() as $row) {
            if ($row['con_enabled'] == 1) {
                $this->data['contratos_vigentes']++;
            }
        }
        if ($id != false) {
            if ($table == 'cuentas_corrientes') {
                $prop = $this->basic->get_where($table, array('cc_id' => $id))->row();
                $contratos = $this->basic->get_where('contratos', array('con_prop' => $prop->cc_prop));
            }
        } else {
            $contratos = $this->basic->get_all('contratos');
        }
        $this->data['contratos'] = $contratos;
        $response['html'] = $this->load->view('manager/contratos/buscar_fila_contratos', $this->data, TRUE);
        echo json_encode($response);
    }

    function buscar_propiedad_prop($table, $id_propiet = false) {
        if ($id_propiet != false) {
            if ($table == 'propiedades') {
                /* Obtiene una lista de los clientes, si es que $table es clientes */
                $prop = $this->basic->get_where($table, array('prop_id' => $id_propiet))->row();
            }
        } else {
            $prop = $this->basic->get_all($table);
        }
        $response['html'] = $prop->prop_dom;
        echo json_encode($response);
    }

    function buscar_propiedad($table, $id = false) {
        if ($id != false) {
            if ($table == 'cuentas_corrientes') {
                $prop = $this->basic->get_where($table, array('cc_id' => $id))->row();
                $propiedad = $this->basic->get_where('propiedades', array('prop_prop' => $prop->cc_prop));
            }
        } else {
            $propiedad = null;
        }
        $this->data['propiedades'] = $propiedad;
        $response['html'] = $this->load->view('manager/propiedades/buscar_fila_propiedades', $this->data, TRUE);
        echo json_encode($response);
    }

    function buscar_cc($table, $id = false) {
        if ($id != false) {
            if ($table == 'cuentas_corrientes') {
                /* Obtiene una lista de los clientes, si es que $table es clientes */
                $cc = $this->basic->get_where($table, array('cc_id' => $id))->row();
            }
        } else {
            $cc = $this->basic->get_all($table);
        }
        $response['html'] = $cc->cc_prop;
        echo json_encode($response);
    }

    /*
     * filtrar_pago en 3 versiones. Son las funciones que realizan el filtro de los pagos de la vista "pagos".
     * Lo hice en 3 metodos diferentes segun los parametros que se pasen, segun las combinaciones que haga el cliente
     * usa una u otra funcion. Lo hice de esta forma porque en la url, al pasarle unicamente por ejemplo el 3er parametro,
     * sin el 1` y el 2`, éste por alguna razon al recibirse en el controlador, se veia almacenado no en la 3er variable,
     * sino en la primera, como que siempre se corria. Y hacia mal la busqueda porque guardaba en $desde, 
     * el nombre de un jugador por ejemplo.
     */

    function filtrar_cred_1($concepto) {
        $concepto = str_replace('.', ',', $concepto);
        $concepto = urldecode($concepto);
        $this->data['creditos'] = $this->basic->get_where('creditos', array('cred_concepto' => $concepto));
        $response['html'] = $this->load->view('manager/transacciones/buscar_fila_creditos', $this->data, TRUE);
        echo json_encode($response);
    }

    function filtrar_cred_2($cc, $concepto) {
        $concepto = str_replace('.', ',', $concepto);
        $concepto = urldecode($concepto);
        $cc = str_replace('.', ',', $cc);
        $cc = urldecode($cc);
        $this->data['creditos'] = $this->basic->get_where('creditos', array('cred_concepto' => $concepto, 'cred_cc' => $cc));
        $response['html'] = $this->load->view('manager/transacciones/buscar_fila_creditos', $this->data, TRUE);
        echo json_encode($response);
    }

    function filtrar_cred_3($desde = FALSE, $hasta = FALSE, $concepto = FALSE) {
        $concepto = str_replace('.', ',', $concepto);
        $concepto = urldecode($concepto);
        $creditos = $this->basic->get_where('creditos', array('cred_concepto' => $concepto));
        $agregar = 0;
        $suma = 0;
        $array = array();
        foreach ($creditos->result_array() as $row) {
            $agregar = $this->comp_fecha($row['cred_fecha'], $desde, $hasta);
            if ($agregar == '11') {
                $suma += $row['cred_monto'];
                array_push($array, $row);
                $agregar = 0;
            }
        }
//        print_r($suma);
        $this->data['creditos'] = $array;
        $response['html'] = $this->load->view('manager/transacciones/buscar_fila_creditos_arr', $this->data, TRUE);
        echo json_encode($response);
    }

    function filtrar_cred_4($cc, $desde = FALSE, $hasta = FALSE, $concepto = FALSE) {
        $concepto = str_replace('.', ',', $concepto);
        $concepto = urldecode($concepto);
        $cc = str_replace('.', ',', $cc);
        $cc = urldecode($cc);
        $creditos = $this->basic->get_where('creditos', array('cred_concepto' => $concepto, 'cred_cc' => $cc));
        $agregar = 0;
        $array = array();
        foreach ($creditos->result_array() as $row) {
            $agregar = $this->comp_fecha($row['cred_fecha'], $desde, $hasta);
            if ($agregar == '11') {
                array_push($array, $row);
                $agregar = 0;
            }
        }
        $this->data['creditos'] = $array;
        $response['html'] = $this->load->view('manager/transacciones/buscar_fila_creditos_arr', $this->data, TRUE);
        echo json_encode($response);
    }

    function filtrar_deb_1($concepto) {
        $concepto = str_replace('.', ',', $concepto);
        $concepto = urldecode($concepto);
        $this->data['debitos'] = $this->basic->get_where('debitos', array('deb_concepto' => $concepto));
        $response['html'] = $this->load->view('manager/transacciones/buscar_fila_debitos', $this->data, TRUE);
        echo json_encode($response);
    }

    function filtrar_deb_2($cc, $concepto) {
        $concepto = str_replace('.', ',', $concepto);
        $concepto = urldecode($concepto);
        $cc = str_replace('.', ',', $cc);
        $cc = urldecode($cc);
        $this->data['debitos'] = $this->basic->get_where('debitos', array('deb_concepto' => $concepto, 'deb_cc' => $cc));
        $response['html'] = $this->load->view('manager/transacciones/buscar_fila_debitos', $this->data, TRUE);
        echo json_encode($response);
    }

    function filtrar_deb_3($desde = FALSE, $hasta = FALSE, $concepto = FALSE) {
        $concepto = str_replace('.', ',', $concepto);
        $concepto = urldecode($concepto);
        $debitos = $this->basic->get_where('debitos', array('deb_concepto' => $concepto));
        $agregar = 0;
        $suma = 0;
        $array = array();
        foreach ($debitos->result_array() as $row) {
            $agregar = $this->comp_fecha($row['deb_fecha'], $desde, $hasta);
            if ($agregar == '11') {
                $suma += $row['deb_monto'];
                array_push($array, $row);
                $agregar = 0;
            }
        }
//        print_r($suma);
        $this->data['debitos'] = $array;
        $response['html'] = $this->load->view('manager/transacciones/buscar_fila_debitos_arr', $this->data, TRUE);
        echo json_encode($response);
    }

    function filtrar_deb_4($cc, $desde = FALSE, $hasta = FALSE, $concepto = FALSE) {
        $concepto = str_replace('.', ',', $concepto);
        $concepto = urldecode($concepto);
        $cc = str_replace('.', ',', $cc);
        $cc = urldecode($cc);
        $debitos = $this->basic->get_where('debitos', array('deb_concepto' => $concepto, 'deb_cc' => $cc));
        $agregar = 0;
        $array = array();
        foreach ($debitos->result_array() as $row) {
            $agregar = $this->comp_fecha($row['deb_fecha'], $desde, $hasta);
            if ($agregar == '11') {
                array_push($array, $row);
                $agregar = 0;
            }
        }
        $this->data['debitos'] = $array;
        $response['html'] = $this->load->view('manager/transacciones/buscar_fila_debitos_arr', $this->data, TRUE);
        echo json_encode($response);
    }

    function filtrar_pago3($cc = FALSE, $desde = FALSE, $hasta = FALSE) {
        $cc = str_replace('.', ',', $cc);
        $cc = urldecode($cc);
        $creditos = $this->basic->get_where('creditos', array('cred_cc' => $cc));
        $agregar = 0;
        $array = array();
        foreach ($creditos->result_array() as $row) {
            $agregar = $this->comp_fecha($row['cred_fecha'], $desde, $hasta);
            if ($agregar == '11') {
                array_push($array, $row);
                $agregar = 0;
            }
        }
        $this->data['creditos'] = $array;
        $response['html'] = $this->load->view('manager/transacciones/buscar_fila_creditos_arr', $this->data, TRUE);
        echo json_encode($response);
    }

    function filtrar_pago2_aca($prop) {
        $prop = str_replace('.', ',', $prop);
        $prop = urldecode($prop);
        $this->data['creditos'] = $this->basic->get_where('creditos', array('cred_cc' => $prop));
        $response['html'] = $this->load->view('manager/transacciones/buscar_fila_creditos', $this->data, TRUE);
        echo json_encode($response);
    }

    function filtrar_pago($desde, $hasta) {
        $creditos = $this->basic->get_all('creditos');
        $agregar = 0;
        $array = array();
        foreach ($creditos->result_array() as $row) {
            $agregar = $this->comp_fecha($row['cred_fecha'], $desde, $hasta);
            if ($agregar == '11') {
                array_push($array, $row);
                $agregar = 0;
            }
        }
        $this->data['creditos'] = $array;
        $response['html'] = $this->load->view('manager/transacciones/buscar_fila_creditos_arr', $this->data, TRUE);
        echo json_encode($response);
    }

    function filtrar_debito3($cc = FALSE, $desde = FALSE, $hasta = FALSE) {
        $cc = str_replace('.', ',', $cc);
        $cc = urldecode($cc);
        $debitos = $this->basic->get_where('debitos', array('deb_cc' => $cc));
        $agregar = 0;
        $array = array();
        foreach ($debitos->result_array() as $row) {
            $agregar = $this->comp_fecha($row['deb_fecha'], $desde, $hasta);
            if ($agregar == '11') {
                array_push($array, $row);
                $agregar = 0;
            }
        }
        $this->data['debitos'] = $array;
        $response['html'] = $this->load->view('manager/transacciones/buscar_fila_debitos_arr', $this->data, TRUE);
        echo json_encode($response);
    }

    function filtrar_debito_2($prop) {
        $prop = str_replace('.', ',', $prop);
        $prop = urldecode($prop);
        $this->data['debitos'] = $this->basic->get_where('debitos', array('deb_cc' => $prop));
        $response['html'] = $this->load->view('manager/transacciones/buscar_fila_debitos', $this->data, TRUE);
        echo json_encode($response);
    }

    function filtrar_debito($desde, $hasta) {
        $debitos = $this->basic->get_all('debitos');
        $agregar = 0;
        $array = array();
        foreach ($debitos->result_array() as $row) {
            $agregar = $this->comp_fecha($row['deb_fecha'], $desde, $hasta);
            if ($agregar == '11') {
                array_push($array, $row);
                $agregar = 0;
            }
        }
        $this->data['debitos'] = $array;
        $response['html'] = $this->load->view('manager/transacciones/buscar_fila_debitos_arr', $this->data, TRUE);
        echo json_encode($response);
    }

    function comp_fecha($fecha, $desde, $hasta) {
        /*
         * Algoritmo para comparar entre fechas varchar, si una de ellas esta entre un rango
         */
        $dagre = 0;
        $hagre = 0;
        $desde = explode('-', $desde);
        $hasta = explode('-', $hasta);
        $f_desig = explode('-', $fecha);
        $resta_ano_des_desde = $f_desig[2] - $desde[2];
        $resta_mes_des_desde = $f_desig[1] - $desde[1];
        $resta_dia_des_desde = $f_desig[0] - $desde[0];
        //comprara fecha inferior
        if ($resta_ano_des_desde > 0) {
            $dagre = 1;
        } else {
            if ($resta_ano_des_desde == 0) {
                if ($resta_mes_des_desde > 0) {
                    $dagre = 1;
                } else {
                    if ($resta_mes_des_desde == 0) {
                        if ($resta_dia_des_desde >= 0) {
                            $dagre = 1;
                        }
                    }
                }
            }
        }
//si fecha inferior se cumple, se comprara superior
        if ($dagre) {
            $resta_ano_des_hasta = $f_desig[2] - $hasta[2];
            $resta_mes_des_hasta = $f_desig[1] - $hasta[1];
            $resta_dia_des_hasta = $f_desig[0] - $hasta[0];
            if ($resta_ano_des_hasta < 0) {
                $hagre = 1;
            } else {
                if ($resta_ano_des_hasta == 0) {
                    if ($resta_mes_des_hasta == 0) {
                        if ($resta_dia_des_hasta <= 0) {
                            $hagre = 1;
                        }
                    } else {
                        if ($resta_mes_des_hasta < 0) {
                            $hagre = 1;
                        }
                    }
                }
            }
        }
        $agregar = $dagre . $hagre;
        /*
         * Si la fecha se encuentra en el rango, la funcion devuelve el nro 11
         */
        return $agregar;
    }

}


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
        $this->load->helper(array('ckeditor', 'img'));
        $this->load->library(array('form_validation', 'session'));
        $this->load->model(array('basic'));
        $response = array('error' => 0, 'success' => 0, 'js' => '', 'html' => '');
        $this->form_validation->set_error_delimiters('', '\\n');

        if (!$this->session->userdata('logged_in') && strpos(current_url(), 'login') === FALSE) {
            echo "<script>window.top.location.href = '" . site_url('login') . "';</script>";
        }
    }

    function index() {
        $this->data['sections_list'] = $this->basic->get_all('sections', 'sect_name_es');
        $this->data['section'] = $this->basic->get_where('sections', array('sect_uri' => 'home'))->row();
        $this->data['head'] = $this->load->view('partials/head', '', TRUE);
        $this->data['menu'] = $this->load->view('partials/header', $this->data, TRUE);
        $this->data['content'] = '<span class="sel"><h1>Seleccione una sección a administrar </h1>
        <p>Gestione designacioes, arbitros y aranceles</p></span>';
        $this->load->view('manager/man_view', $this->data);
    }

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

    public function section($uri) {
        $this->data['uri'] = $uri;
        $this->load_similar_content($uri);
        $this->load->view('default', $this->data);
    }

    function admin() {
        $this->data['user'] = $this->basic->get_where('man_users', array('username' => 'admin'))->row();
        $this->load_similar_content('admin');
        $this->data['content'] = $this->load->view('manager/users', $this->data, TRUE);
        $this->load->view('default', $this->data);
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

    function arbitros() {
        $this->load_similar_content('arbitros');
        $this->data['arbitros'] = $this->basic->get_all('arbitros');
        $this->data['content'] = $this->load->view('manager/arbitros/arbitros', $this->data, TRUE);
        $this->load->view('default', $this->data);
    }

    function designaciones() {
        $this->load_similar_content('designaciones');
        $this->data['designaciones'] = $this->basic->get_all('designaciones');
        $this->data['content'] = $this->load->view('manager/designaciones/designaciones', $this->data, TRUE);
        $this->load->view('default', $this->data);
    }

    function recaudaciones() {
        $this->load_similar_content('recaudaciones');
        $this->data['recaudaciones'] = $this->basic->get_all('recaudaciones');
        $this->data['content'] = $this->load->view('manager/recaudaciones/recaudaciones', $this->data, TRUE);
        $this->load->view('default', $this->data);
    }

    function aranceles() {
        $this->load_similar_content('aranceles');
        $this->data['aranceles'] = $this->basic->get_all('aranceles');
        $this->data['content'] = $this->load->view('manager/aranceles/aranceles', $this->data, TRUE);
        $this->load->view('default', $this->data);
    }

    function login($msg = '') {
        $this->data['head'] = $this->load->view('partials/head', '', TRUE);
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

    function informe_designacion($id) {
        $response['js'] = 'window.location.href="' . site_url('show_informe') . '/' . $id . '"';
        echo json_encode($response);
    }

    function informe_juez($id) {
        $response['js'] = 'window.location.href="' . site_url('show_informe_j') . '/' . $id . '"';
        echo json_encode($response);
    }

    function show_informe_j($id) {
        $this->data['id'] = $id;
        $this->data['head'] = $this->load->view('partials/head', $this->data, TRUE);
        $this->data['header'] = $this->load->view('partials/header', $this->data, TRUE);
        $this->data['footer'] = $this->load->view('partials/footer', $this->data, TRUE);
        $this->data['content'] = $this->load->view('manager/arbitros/form_informe', $this->data, TRUE);
        $this->load->view('default', $this->data);
    }

    function show_informe_juez($id) {
        $this->form_validation->set_rules('fecha_inferior', 'Fecha de Comienzo', "required|trim");
        $this->form_validation->set_rules('fecha_superior', 'Fecha de Fin', "required|trim");
        if ($this->form_validation->run() == TRUE) {
            $this->data['juez'] = $this->basic->get_where('arbitros', array('arb_id' => $id))->row();
            $this->data['jornadas'] = $this->basic->get_all('jornada');
            $this->data['aranceles'] = $this->basic->get_all('aranceles');
            $agregar = 0;
            $desig = $this->basic->get_all('designaciones');
            $i = 1;
            $array = array();
            foreach ($desig->result_array() as $row) {
                print_r('<br>Vuelta num ' . $i . '<br>');
                $agregar = $this->comp_fecha($row, $this->input->post('fecha_inferior'), $this->input->post('fecha_superior'));
                
                if ($agregar) {
                    print('<br>Resultado p agregar de ' . $row['des_local'] . ' vs ' . $row['des_visita'] . ' fue ' . 'TRUE' . '<br>');
                    array_push($array, $row);
                }else{
                    print('<br>Resultado p agregar de ' . $row['des_local'] . ' vs ' . $row['des_visita'] . ' fue ' . 'FALSE' . '<br>');
                }
                $i++;
            }
            $this->data['designaciones'] = $array;
            $response['html'] = $this->load->view('manager/arbitros/informe_juez', $this->data, TRUE);
        } else {
            $response['js'] = "$('.msg_display').css('display','block');$('.msg_display').addClass('alert-error')";
            $response['html'] = validation_errors();
            $response['error'] = '1';
        }
        echo json_encode($response);
    }

    function comp_fecha($des, $desde, $hasta) {
        $agregar = false;
        $dagre = false;
        $hagre = false;
        $desde = explode('-', $desde);
        $hasta = explode('-', $hasta);
        $f_desig = explode('-', $des['des_date']);

        $resta_ano_des_desde = $f_desig[2] - $desde[2];
        $resta_mes_des_desde = $f_desig[1] - $desde[1];
        $resta_dia_des_desde = $f_desig[0] - $desde[0];
        if ($resta_ano_des_desde > 0) {
            $dagre = true;
        } else if ($resta_ano_des_desde == 0) {
            if ($resta_mes_des_desde > 0) {
                $dagre = true;
            } else if ($resta_mes_des_desde == 0) {
                if ($resta_dia_des_desde >= 0) {
                    $dagre = true;
                }
            }
        }
        if ($dagre) {
            $resta_ano_des_hasta = $f_desig[2] - $hasta[2];
            $resta_mes_des_hasta = $f_desig[1] - $hasta[1];
            $resta_dia_des_hasta = $f_desig[0] - $hasta[0];
            if ($resta_ano_des_hasta < 0) {
                $hagre = true;
            } else if ($resta_ano_des_hasta == 0) {
                if ($resta_mes_des_hasta == 0) {
                    if ($resta_dia_des_hasta <= 0) {
                        $hagre = true;
                    }
                } else if ($resta_mes_des_hasta < 0) {
                    $hagre = true;
                }
            }
        }
        if ($dagre && hagre) {
            $agregar = true;
        }
        return $agregar;
    }

    function show_informe($id) {
        $this->data['row'] = $this->basic->get_where('designaciones', array('des_id' => $id))->row();
        $this->data['partidos'] = $this->basic->get_where('jornada', array('jor_des' => $id));
        $this->data['aranceles'] = $this->basic->get_all('aranceles');
        $this->data['head'] = $this->load->view('partials/head', $this->data, TRUE);
        $this->data['header'] = $this->load->view('partials/header', $this->data, TRUE);
        $this->data['footer'] = $this->load->view('partials/footer', $this->data, TRUE);
        $this->data['content'] = $this->load->view('manager/designaciones/informe', $this->data, TRUE);
        $this->load->view('default', $this->data);
    }

    function load_edit_desig($id = false) {
        if ($id) {
            $this->data['arbitros'] = $this->basic->get_all('arbitros');
            $this->data['row'] = $this->basic->get_where('designaciones', array('des_id' => $id))->row();
            $this->data['id'] = $id;
            $this->data['partidos'] = $this->basic->get_where('jornada', array('jor_des' => $id));
            $response['js'] = "$('.contenedor_centro').css('width','82%')";
            $response['html'] = $this->load->view('manager/designaciones/designar', $this->data, TRUE);
        } else {
            $this->data['partidos'] = null;
            $this->data['row'] = null;
            $this->data['cates'] = $this->basic->get_all('aranceles');
            $response['js'] = "$('.contenedor_centro').css('width','82%')";
            $response['html'] = $this->load->view('manager/designaciones/form_designaciones', $this->data, TRUE);
        }
        echo json_encode($response);
    }

    function designar() {
        $this->data['arbitros'] = $this->basic->get_all('arbitros');
        $response['html'] = $this->load->view('manager/designaciones/designar_nu', $this->data, TRUE);
        echo json_encode($response);
    }

    function save_desig() {
        $this->form_validation->set_rules('des_date', 'fecha del encuentro', "required|trim");
        $this->form_validation->set_rules('des_fecha', 'fecha correspondiente al campeonato', "required|trim");
        if ($this->form_validation->run() == TRUE) {
            //Guardo la designacion
            if (!$this->input->post('des_id')) {
                $data = array(
                    'des_date' => $this->input->post('des_date'),
                    'des_fecha' => $this->input->post('des_fecha'),
                    'des_local' => $this->input->post('des_local'),
                    'des_visita' => $this->input->post('des_visita'),
                    'des_pagado' => $this->input->post('des_pagado'),
                );
            } else {
                $data = array(
                    'des_id' => $this->input->post('des_id'),
                    'des_date' => $this->input->post('des_date'),
                    'des_fecha' => $this->input->post('des_fecha'),
                    'des_local' => $this->input->post('des_local'),
                    'des_visita' => $this->input->post('des_visita'),
                    'des_pagado' => $this->input->post('des_pagado'),
                );
            }
            $des_id = $this->basic->save('designaciones', 'des_id', $data);
            $i = 1;
            //Guardo los partidos de la jornada de esa designacion anteriormente guardada
            while ($i < $this->input->post('cant_partidos')) {
                if (!$this->input->post('jor_id' . $i)) {
                    $data = array(
                        'jor_des' => $des_id,
                        'jor_pri_juez' => $this->input->post('jor_pri_juez' . $i),
                        'jor_sec_juez' => $this->input->post('jor_sec_juez' . $i),
                        'jor_cate' => $this->input->post($i),
                    );
                } else {
                    $data = array(
                        'jor_id' => $this->input->post('jor_id' . $i),
                        'jor_des' => $des_id,
                        'jor_pri_juez' => $this->input->post('jor_pri_juez' . $i),
                        'jor_sec_juez' => $this->input->post('jor_sec_juez' . $i),
                        'jor_cate' => $this->input->post($i),
                    );
                }
                $this->basic->save('jornada', 'jor_id', $data);
                $i++;
            }
            if ($this->input->post('des_pagado') == 1) {
                //Si el 10% se deposito se lo acumula a la recaudacion anual
                $recaudacion_anual = $this->basic->get_where('recaudaciones', array('rec_ano' => date('Y')))->row_array();
                $jornadas = $this->basic->get_where('jornada', array('jor_des' => $des_id));
                $monto = 0;
                foreach ($jornadas->result() as $row) {
                    $arancel = $this->basic->get_where('aranceles', array('aran_cate' => $row->jor_cate))->row();
                    $sumar = $this->comprobar_juez($row);
                    if ($sumar == 1) {
                        $monto = $monto + ($arancel->aran_price);
                    }
                    if ($sumar == 2) {
                        $monto = $monto + ($arancel->aran_price) * 2;
                    }
                }
                $recaudacion_anual['rec_monto'] += $monto * 0.10;
                $this->basic->save('recaudaciones', 'rec_id', $recaudacion_anual);
            }
            $response['js'] = 'window.top.location.href="' . site_url('designaciones') . '"';
        } else {
            $response['js'] = " $('.msg_display').css('display','block');$('.msg_display').addClass('alert-error')";
            $response['html'] = validation_errors();
            $response['error'] = '1';
        }

        echo json_encode($response);
    }

    function comprobar_juez($jornada) {
        $sumar = 2;
        $arbitro = $jornada->jor_pri_juez;
        //Verifica que el primer juez este exento de pagar el 10%
        if ($arbitro == 'Dilkin Samuel' || $arbitro == 'Figueredo Victor' || $arbitro == 'Moncada Emanuel' || $arbitro == 'Proenza Gonzalo' || $arbitro == 'Duarte Matias' || $arbitro == 'Duarte Cristian') {
            $sumar = 1;
            $arbitro2 = $jornada->jor_sec_juez;
            if ($arbitro2 == 'Dilkin Samuel' || $arbitro2 == 'Figueredo Victor' || $arbitro2 == 'Moncada Emanuel' || $arbitro2 == 'Proenza Gonzalo' || $arbitro2 == 'Duarte Matias' || $arbitro2 == 'Duarte Cristian') {
                $sumar = 0;
            }
        }
        $arbitro2 = $jornada->jor_sec_juez;
        //Verifica que el sec juez este exento de pagar el 10%
        if ($arbitro2 == 'Dilkin Samuel' || $arbitro2 == 'Figueredo Victor' || $arbitro2 == 'Moncada Emanuel' || $arbitro2 == 'Proenza Gonzalo' || $arbitro2 == 'Duarte Matias' || $arbitro2 == 'Duarte Cristian') {
            $sumar = 1;
            $arbitro = $jornada->jor_pri_juez;
            if ($arbitro == 'Dilkin Samuel' || $arbitro == 'Figueredo Victor' || $arbitro == 'Moncada Emanuel' || $arbitro == 'Proenza Gonzalo' || $arbitro == 'Duarte Matias' || $arbitro == 'Duarte Cristian') {
                $sumar = 0;
            }
        }
        return $sumar;
    }

    function del_desig($id) {
        $user = $this->basic->get_where('designaciones', array('des_id' => $id));
        if ($user->num_rows > 0) {
            $this->basic->del('designaciones', 'des_id', $id);
            $this->basic->del('jornada', 'jor_des', $id);
            $response['html'] = t('Designacion eliminada');
            echo json_encode($response);
        }
    }

    function load_edit_recaudacion($id = false) {
        if ($id) {
            $this->data['row'] = $this->basic->get_where('recaudaciones', array('rec_id' => $id))->row();
            $this->data['id'] = $id;
        }
        $response['html'] = $this->load->view('manager/recaudaciones/form_recaudaciones', $this->data, TRUE);
        $response['js'] = "$('.contenedor_centro').css('width','82%');";
        echo json_encode($response);
    }

    function del_recaudacion($id) {
        $user = $this->basic->get_where('recaudaciones', array('rec_id' => $id));
        if ($user->num_rows > 0) {
            $this->basic->del('recaudaciones', 'rec_id', $id);
            $response['html'] = t('Arancel eliminado');
            echo json_encode($response);
        }
    }

    function save_recaudacion() {
        $this->form_validation->set_rules('rec_ano', 'año', "required|trim");
        $this->form_validation->set_rules('rec_monto', 'monto', "required|trim");
        if ($this->form_validation->run() == TRUE) {
            if (!$this->input->post('rec_id'))
                $this->input->post();
            $this->basic->save('recaudaciones', 'rec_id', $this->input->post());
            $response['js'] = 'window.top.location.href="' . site_url('recaudaciones') . '"';
        } else {
            $response['html'] = validation_errors();
            $response['error'] = '1';
        }

        echo json_encode($response);
    }

    function load_edit_aranceles($id = false) {
        if ($id) {
            $this->data['row'] = $this->basic->get_where('aranceles', array('aran_id' => $id))->row();
            $this->data['id'] = $id;
        }
        $response['html'] = $this->load->view('manager/aranceles/form_aranceles', $this->data, TRUE);
        $response['js'] = "$('.contenedor_centro').css('width','82%');";
        echo json_encode($response);
    }

    function del_aranceles($id) {
        $user = $this->basic->get_where('aranceles', array('aran_id' => $id));
        if ($user->num_rows > 0) {
            $this->basic->del('aranceles', 'aran_id', $id);
            $response['html'] = t('Arancel eliminado');
            echo json_encode($response);
        }
    }

    function save_aranceles() {
        $this->form_validation->set_rules('aran_cate', 'nombre', "required|trim");
        $this->form_validation->set_rules('aran_price', 'nombre', "required|trim");
        if ($this->form_validation->run() == TRUE) {
            if (!$this->input->post('aran_id'))
                $this->input->post();
            $this->basic->save('aranceles', 'aran_id', $this->input->post());
            $response['js'] = 'window.top.location.href="' . site_url('aranceles') . '"';
        } else {
            $response['html'] = validation_errors();
            $response['error'] = '1';
        }

        echo json_encode($response);
    }

    function load_edit_arbitros($id = false) {
        if ($id) {
            $this->data['row'] = $this->basic->get_where('arbitros', array('arb_id' => $id))->row();
            $this->data['id'] = $id;
        }
        $response['html'] = $this->load->view('manager/arbitros/form_arbitros', $this->data, TRUE);
        $response['js'] = "$('.contenedor_centro').css('width','82%');";
        echo json_encode($response);
    }

    function del_arbitros($id) {
        $user = $this->basic->get_where('arbitros', array('arb_id' => $id));
        if ($user->num_rows > 0) {
            $this->basic->del('arbitros', 'arb_id', $id);
            $response['html'] = t('Arbitro eliminado');
            echo json_encode($response);
        }
    }

    function save_arbitros() {
        $this->form_validation->set_rules('arb_name', 'nombre', "required|trim");
        if ($this->form_validation->run() == TRUE) {
            if (!$this->input->post('arb_id'))
                $this->input->post();
            $this->basic->save('arbitros', 'arb_id', $this->input->post());
            $response['js'] = 'window.top.location.href="' . site_url('arbitros') . '"';
        } else {
            $response['html'] = validation_errors();
            $response['error'] = '1';
        }

        echo json_encode($response);
    }

    function del_file($id, $table, $table_pk, $table_file, $thumbs = false) {
        $file_path = './' . urldecode($this->input->post('path')) . '/';
        $file = $this->basic->get_where($table, array($table_pk => $id))->row_array();
        if (is_file($file_path . $file[$table_file])) {
            unlink($file_path . $file[$table_file]);
            if ($thumbs)
                unlink($file_path . 'thumbs/' . $file[$table_file]);
        }
        $this->basic->save($table, $table_pk, array($table_pk => $id, $table_file => ''));
        $response['html'] = 'Archivo eliminado';
        $response['js'] = "$('#section_image_container').html('archivo no encontrado')";
        echo json_encode($response);
    }

    function del_user($id) {
        $user = $this->basic->get_where('users', 'user_id', $id);
        if ($user->num_rows > 0) {
            $this->basic->del('users', 'user_id', $id);
            echo 'Usuario eliminado';
        }
    }

    function save_section_head($section, $lang) {
        $this->form_validation->set_rules('sect_head_title_' . $lang, 'Title ' . strtoupper($lang), "required|trim");
        $this->form_validation->set_rules('sect_head_keywords_' . $lang, 'Keywords ' . strtoupper($lang), "required|trim");
        $this->form_validation->set_rules('sect_head_description_' . $lang, 'Description ' . strtoupper($lang), "required|trim");

        if ($this->form_validation->run() == TRUE) {
            $_POST['sect_id'] = $section;
            $data = $this->basic->save('sections', 'sect_id', $this->input->post());
            echo 'Saved correctly';
        } else
            echo '@ERROR@ ' . validation_errors();
    }

    function save_section($section, $lang) {
        $this->form_validation->set_rules('sect_name_' . $lang, 'Title ' . strtoupper($lang), "required|trim");
        $this->form_validation->set_rules('sect_content_' . $lang, 'Content ' . strtoupper($lang), "required|trim");

        if ($this->form_validation->run() == TRUE) {
            $_POST['sect_id'] = $section;
            $data = $this->basic->save('sections', 'sect_id', $this->input->post());
            echo 'Saved correctly';
        } else
            echo '@ERROR@ ' . validation_errors();
    }

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
        if ($table == 'gallery') {
            $this->banners();
        }
        if ($table == 'news') {
            $this->news();
        }
    }

    function to_edit($id, $table, $table_pk, $partial) {
        $data['edit'] = $this->basic->get_where($table, array($table_pk => $id))->row();
        $this->load->view('manager/partials/' . $partial, $data);
    }

}


<?php

/*
 * Project: Cleanbox
 * Document: Timeline
 * 
 * @author Manevi Matias Alejandro 
 * Informatic Engineer - Web Developer
 * 
 * Contact: manevimatias@gmail.com
 * 
 * All rights reserved ®
 */

class Timeline extends CI_Controller {

    public function index() {
    	$this->data['particular_head'] = $this->load->view('particular_heads/timeline', '', TRUE);

        $this->data['timeline'] = TimelineService::get();
    
        $this->data['content'] = $this->load->view('timeline/timeline', $this->data, TRUE);

        $this->load->view('layout', $this->data);
    }

    public function property($property_id){
    	$this->data['particular_head'] = $this->load->view('particular_heads/timeline', '', TRUE);
    	
        $this->data['timeline'] = TimelineService::get($property_id);
    	
        $this->data['property'] = $this->basic->get_where('propiedades', array('prop_id' => $property_id))->row_array();
    	
    	$this->data['content'] = $this->load->view('timeline/timeline', $this->data, TRUE);

    	$this->load->view('layout', $this->data);
    }
}

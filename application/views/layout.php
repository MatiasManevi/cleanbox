<html>
    <head>
        <?php echo $this->load->view('head', '', TRUE); ?>
        <?php echo isset($particular_head) ? $particular_head : ''; ?>
    </head>
    <body>    
        <?php echo $this->load->view('modals', '', TRUE); ?>
        <?php echo $this->load->view('alerts', '', TRUE); ?>
        <?php echo $this->load->view('header', '', TRUE); ?>
        
        <div class="container-fluid _container">

            <?php echo isset($content) ? $content : ''; ?>
            
            <div class="_loading_list"></div>
            
            <?php echo isset($daily_cash) ? $daily_cash : ''; ?>

            <?php echo isset($home_maintenances) ? $home_maintenances : ''; ?>

            <?php echo isset($codes_generator) && $this->session->userdata('username') == 'admin' ? $codes_generator : ''; ?>
        </div>
    </body>
</html>
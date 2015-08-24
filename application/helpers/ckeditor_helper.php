<?php

/*
  Document   : ckeditor_helper
  Created on : 24-feb-2011, 20:19:35
  Author     : Rodrigo E. Torres
  Sobrelaweb Web Developer
  rtorres@sobrelaweb.com | torresrodrigoe@gmail.com
 */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function form_ckeditor($data) {
    return '<script type="text/javascript">
        if (CKEDITOR.instances["' . $data['id'] . '"]) {
            CKEDITOR.remove(CKEDITOR.instances["' . $data['id'] . '"]);
         }
         CKEDITOR.config.width=590;
         CKEDITOR.config.resize_maxWidth=650;
         CKEDITOR.config.height=70;
         CKEDITOR.config.resize_maxHeight=600;
         CKEDITOR.config.uiColor = \'#333333\';
         CKEDITOR.config.toolbar =
        [
            [\'Source\',\'Styles\',\'Format\',\'Font\',\'FontSize\',\'PasteText\',\'PasteFromWord\'],
            [\'Bold\',\'Italic\',\'Underline\',\'Strike\',\'-\',\'SpellChecker\',
            \'JustifyLeft\',\'JustifyCenter\',\'JustifyRight\',\'JustifyBlock\'],
            [\'TextColor\', \'-\',\'NumberedList\',\'BulletedList\',\'-\',
            \'Link\',\'Unlink\',\'Anchor\',\'Image\',\'Flash\',\'MediaEmbed\',\'RemoveFormat\']
        ];
         CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
         CKEDITOR.replace("' . $data['id'] . '");
     </script>';
}

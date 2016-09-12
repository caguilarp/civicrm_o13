<?php

require_once 'CRM/Contact/BAO/PCP.php';
require_once 'CRM/PCP/BAO/PCP.php';
require_once 'CRM/Core/Page.php';

class CRM_Extension_Page_Pcpc extends CRM_Core_Page {
  public function run() {
    
   $ContacId = CRM_Utils_Request::retrieve('cid', 'Integer', $this);

   $pcplist = CRM_Contact_BAO_PCP::getPCP($ContacId);
   $this->assign('rows', $pcplist); 

    parent::run();
  }
}

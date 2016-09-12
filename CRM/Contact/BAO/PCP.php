<?php

require_once 'CRM/PCP/BAO/PCP.php';

class CRM_Contact_BAO_PCP extends CRM_PCP_DAO_PCP {

    public function __construct() {
        parent::__construct();
    }
    
    public static function getPCP(
        $contactId,
        $count = FALSE)
    {
    
        
    
        if ($count) {
            $sql = 'SELECT COUNT(*) FROM civicrm_pcp WHERE civicrm_pcp.contact_id = %1';
        }
        else {
            $sql = 'SELECT 
                        \'civicrm/contribute/transact\'  as url,
                        pcp.page_id,
                        pcp.title,
                        cp.title as page_title,
                        pcp.status_id,
                        pcp.goal_amount,
                        sum(cc.total_amount) as total,
                        sum(case when cc.total_amount is null then 0 else 1 end) as contributors
                    FROM 
                      civicrm_pcp pcp
                        inner join civicrm_contribution_page cp
                            on pcp.page_id = cp.id
                                and pcp.page_type = \'contribute\'
                        LEFT JOIN civicrm_contribution_soft cs 
                            ON  pcp.id = cs.pcp_id 
                        LEFT JOIN civicrm_contribution cc 
                            ON  cs.contribution_id = cc.id
                    WHERE cc.contribution_status_id = 1 
                        AND cc.is_test = 0
                        AND pcp.contact_id = %1 
                    GROUP BY pcp.page_id,
                        pcp.status_id,
                        pcp.goal_amount
                    UNION
                    SELECT 
                        \'civicrm/event/register\'  as url,
                        pcp.page_id,
                        pcp.title,
                        cp.title as page_title,
                        pcp.status_id,
                        pcp.goal_amount,
                        sum(cc.total_amount) as total,
                        sum(case when cc.total_amount is null then 0 else 1 end) as contributors
                    FROM 
                      civicrm_pcp pcp
                        inner join civicrm_event cp
                            on pcp.page_id = cp.id
                                and pcp.page_type = \'event\'
                        LEFT JOIN civicrm_contribution_soft cs 
                            ON  pcp.id = cs.pcp_id 
                        LEFT JOIN civicrm_contribution cc 
                            ON  cs.contribution_id = cc.id
                    WHERE cc.contribution_status_id = 1 
                        AND cc.is_test = 0
                        AND (cp.is_template IS NULL OR cp.is_template != 1)
                        AND pcp.contact_id = %1 
                    GROUP BY pcp.page_id,
                        pcp.status_id,
                        pcp.goal_amount
                    ';
        }
        
        $params = array(1 => array($contactId, 'Integer'));
        
        if ($count) {
            $result = CRM_Core_DAO::singleValueQuery($sql, $params);
            return $result;
        }
        else {
            $dao = CRM_Core_DAO::executeQuery($sql, $params);
            $values = array();
	    $status = CRM_PCP_BAO_PCP::buildOptions('status_id', 'create');

            while ($dao->fetch()) {
		$id = $dao->page_id;
            	$values[$id]['page_id'] = $dao->page_id;
   	 	$values[$id]['url'] = $dao->url.'?reset=1&id='.$id;                
		$values[$id]['status_id'] = $status[$dao->status_id];
                $values[$id]['title'] = $dao->title;
                $values[$id]['page_title'] = $dao->page_title;
                $values[$id]['goal_amount'] = $dao->goal_amount;
                $values[$id]['total'] = $dao->total;
                $values[$id]['contributors'] = $dao->contributors;
            }
            return $values;
        }
    }

}

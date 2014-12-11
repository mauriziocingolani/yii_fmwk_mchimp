<?php

/**
 * ===============================
 * Wrapper per MailChimp API v2 wrapper
 * 
 * Contributors:
 * Michael Minor <me@pixelbacon.com>
 * Lorna Jane Mitchell, github.com/lornajane
 * 
 * Author Drew McLellan <drew.mclellan@gmail.com> 
 * Version 1.1.1
 * 
 * ===============================
 * 
 * @author Maurizio Cingolani <mauriziocingolani74@gmail.com>
 * @version 1.0
 */
class CMailChimp extends MailChimp {

    /**
     * 
     */
    public function __construct($api_key, $list_id) {
        parent::__construct($api_key, $list_id);
    }

    public function listsGetList() {
        $lists = $this->call('lists/list', array());
        foreach ($lists['data'] as $list) ://Identifico la lista
            if ($list['id'] === $this->list_id)
            break;
        endforeach;
        return $list;
    }

    public function listsMemberInfo($email) {
        return $this->call('lists/member-info', array(
                    'id' => $this->list_id,
                    'emails' => array(array('email' => $email)),
        ));
    }

    public function listsSubscribe($data) {
        return $this->call('lists/subscribe', array(
                    'id' => $this->list_id,
                    'email' => array(
                        'email' => $data->email,
                    ),
                    'double_optin' => true,
                    'merge_vars' => array(
                        'FNAME' => $data->fname,
                        'LNAME' => $data->lname,
                    ),
        ));
    }

}

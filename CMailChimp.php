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
 * @version 1.0.2
 */
class CMailChimp extends MailChimp {

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

    /**
     * Aggiunge un nominativo alla lista.
     * Parametri impostabili tramite l'oggetto passato come parametro:
     * <ul>
     * <li>fname: nome </li>
     * <li>lname: cognome </li>
     * <li>email: indirizzo email </li>
     * <li>double_optin: true per richiedere iscrizione per email, false per iscrivere direttamente</li>
     * </li>
     * In aggiunta ai parametri standard è possibile impostare anche altri campi, passati tramite
     * il parametro $optionalFields in formato {NOME_CAMPO}=>{valore}. 
     * @param mixed $data Oggetto con i parametri
     * @param array $optionalFields Campi aggiuntivi (nel formato FIELD=>valore)
     * @return type Array con i risultati, oppure false in caso di errore
     */
    public function listsSubscribe($data, array $optionalFields = null) {
        $vars = array('FNAME' => $data->fname, 'LNAME' => $data->lname);
        if (is_array($optionalFields) && count($optionalFields) > 0)
            $vars = array_merge($vars, $optionalFields);
        return $this->call('lists/subscribe', array(
                    'id' => $this->list_id,
                    'email' => array(
                        'email' => $data->email,
                    ),
                    'double_optin' => $data->double_optin,
                    'merge_vars' => $vars,
        ));
    }

}

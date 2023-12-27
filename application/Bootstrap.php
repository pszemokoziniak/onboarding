<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected function _initDatabase() {
        
        $config = new Zend_Config($this->getApplication()->getOptions(), true);
        $db = Zend_Db::factory($config->database);
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        Zend_Registry::set('db', $db);
        Zend_Db_Table_Abstract::setDefaultAdapter($db);
         
        return $db;
    }
}


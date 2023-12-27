<?php

class InvestmentController extends Zend_Controller_Action
{

    private $db;
    public function init()
    {
        $this->db = Zend_Registry::get('db');
    }

    public function indexAction()
    {
        $this->view->investments = $this->db->fetchAll(
            (new Zend_Db_Table('investment'))->select()->setIntegrityCheck(false)
                ->from(['i' => 'investment'], ['*'])
                ->join(['r' => 'realestate'], 'r.investment_id = i.id', [
                    'realestate_count' => 'count(r.id)',
                    'min_price' => 'min(r.price)',
                    'max_price' => 'max(r.price)',
                    'min_area' => 'min(r.area)',
                    'max_area' => 'max(r.area)',

                ])
                ->group('i.id')
        );
    }

    public function detailsAction()
    {
        $investmentId = $this->getParam('id');

        $this->view->investment = (new Zend_Db_Table('investment'))->find($investmentId)[0];

        $this->view->realestates = (new Zend_Db_Table('realestate'))->fetchAll([
            'investment_id = ?' => $investmentId
        ]);
    }
}

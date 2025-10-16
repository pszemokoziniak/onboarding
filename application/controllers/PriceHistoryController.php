<?php
class PriceHistoryController extends Zend_Controller_Action
{
    /** @var Zend_Db_Adapter_Abstract */
    private $db;

    public function init()
    {
        $this->db = Zend_Registry::get('db');
    }

    public function indexAction()
    {
        $req = $this->getRequest();

        $number    = trim((string)$req->getParam('number', ''));
        $invIds    = (array)$req->getParam('investment_id', []);
        $statusIds = (array)$req->getParam('status_id', []);
        $typeIds   = (array)$req->getParam('type_id', []);
        $dateFrom  = trim((string)$req->getParam('date_from', ''));
        $dateTo    = trim((string)$req->getParam('date_to', ''));
        $page      = max(1, (int)$req->getParam('page', 1));
        $limit     = 25;
        $offset    = ($page - 1) * $limit;

        $select = $this->db->select()
            ->from(['v' => new Zend_Db_Expr('vw_realestate_price_history')], [
                'history_id',
                'changed_at',
                'realestate_id',
                'realestate_number',
                'investment_name',
                'type_name',
                'status_name',
                'old_price',
                'new_price',
                'old_mkw_price',
                'new_mkw_price'
            ])
            ->order('changed_at DESC')
            ->limit($limit, $offset);

        // Filtry
        if ($number !== '') {
            $select->where('v.realestate_number LIKE ?', '%' . $number . '%');
        }
        if (!empty($invIds)) {
            $select->where('v.investment_name IN (?)', $invIds);
        }
        if (!empty($statusIds)) {
            $select->where('v.status_name IN (?)', $statusIds);
        }
        if (!empty($typeIds)) {
            $select->where('v.type_name IN (?)', $typeIds);
        }
        if ($dateFrom !== '') {
            $select->where('v.changed_at >= ?', $dateFrom . ' 00:00:00');
        }
        if ($dateTo !== '') {
            $select->where('v.changed_at <= ?', $dateTo . ' 23:59:59');
        }

        $rows = $this->db->fetchAll($select);

        // Dane do selectów (słowniki)
        $investments = $this->db->fetchCol($this->db->select()->from('investment', ['name'])->order('name'));
        $statuses    = $this->db->fetchCol($this->db->select()->from('realestate_status', ['name'])->order('name'));
        $types       = $this->db->fetchCol($this->db->select()->from('realestate_type', ['name'])->order('name'));

        $this->view->filters = [
            'number'     => $number,
            'investment' => $invIds,
            'status'     => $statusIds,
            'type'       => $typeIds,
            'date_from'  => $dateFrom,
            'date_to'    => $dateTo,
            'page'       => $page,
            'limit'      => $limit
        ];
        $this->view->options = [
            'investments' => $investments,
            'statuses'    => $statuses,
            'types'       => $types
        ];
        $this->view->rows = $rows;
    }
}

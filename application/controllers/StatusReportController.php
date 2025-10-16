<?php
class StatusReportController extends Zend_Controller_Action
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

        // Dane szczegółowe (lista lokali z filtrami)
        $select = $this->db->select()
            ->from(['r' => 'realestate'], [
                'id', 'number', 'price'
            ])
            ->joinLeft(['i' => 'investment'], 'i.id = r.investment_id', ['investment_name' => 'i.name'])
            ->joinLeft(['t' => 'realestate_type'], 't.id = r.type_id', ['type_name' => 't.name'])
            ->joinLeft(['s' => 'realestate_status'], 's.id = r.status_id', ['status_name' => 's.name'])
            ->order(['s.name ASC', 'i.name ASC', 't.name ASC', 'r.number ASC']);

        if ($number !== '') {
            $select->where('r.number LIKE ?', '%' . $number . '%');
        }
        if (!empty($invIds)) {
            $select->where('i.name IN (?)', $invIds);
        }
        if (!empty($statusIds)) {
            $select->where('s.name IN (?)', $statusIds);
        }
        if (!empty($typeIds)) {
            $select->where('t.name IN (?)', $typeIds);
        }

        $rows = $this->db->fetchAll($select);

        // Agregacja wg statusu (po filtrach)
        $countSelect = $this->db->select()
            ->from(['r' => 'realestate'], [])
            ->joinLeft(['s' => 'realestate_status'], 's.id = r.status_id', [])
            ->joinLeft(['i' => 'investment'], 'i.id = r.investment_id', [])
            ->joinLeft(['t' => 'realestate_type'], 't.id = r.type_id', [])
            ->columns(['status_name' => 's.name', 'cnt' => new Zend_Db_Expr('COUNT(*)')])
            ->group('s.name')
            ->order('s.name ASC');

        if ($number !== '') {
            $countSelect->where('r.number LIKE ?', '%' . $number . '%');
        }
        if (!empty($invIds)) {
            $countSelect->where('i.name IN (?)', $invIds);
        }
        if (!empty($statusIds)) {
            $countSelect->where('s.name IN (?)', $statusIds);
        }
        if (!empty($typeIds)) {
            $countSelect->where('t.name IN (?)', $typeIds);
        }

        $summary = $this->db->fetchAll($countSelect);

        // Słowniki do filtrów
        $investments = $this->db->fetchCol($this->db->select()->from('investment', ['name'])->order('name'));
        $statuses    = $this->db->fetchCol($this->db->select()->from('realestate_status', ['name'])->order('name'));
        $types       = $this->db->fetchCol($this->db->select()->from('realestate_type', ['name'])->order('name'));

        $this->view->filters = [
            'number'     => $number,
            'investment' => $invIds,
            'status'     => $statusIds,
            'type'       => $typeIds
        ];
        $this->view->options = [
            'investments' => $investments,
            'statuses'    => $statuses,
            'types'       => $types
        ];
        $this->view->summary = $summary;
        $this->view->rows = $rows;
    }
}
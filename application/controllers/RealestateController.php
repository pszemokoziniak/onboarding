<?php

class RealestateController extends Zend_Controller_Action
{
    public function editAction()
    {
        $realestateId = $this->getParam('id');

        $this->view->realestate = (new Zend_Db_Table('realestate'))->fetchRow([
            'id = ?' => $realestateId
        ]);

        $form = new Zend_Form();
        $form
            ->setAction('/realestate/edit/id/' . $realestateId)
            ->setMethod('post');

        $form
            ->addElement(
                (
                    new Zend_Form_Element_Select(
                        'status_id',
                        [
                            'label' => 'Status'
                        ]
                    )
                )
                    ->setRequired(true)
                    ->addMultiOptions(
                        [
                            '1' => 'Dostępne',
                            '2' => 'Rezerwacja',
                            '3' => 'Sprzedane'
                        ]
                    )
            )
            ->addElement(
                (
                    new Zend_Form_Element_Text(
                        'price',
                        [
                            'label' => 'Cena'
                        ]
                    )
                )
                    ->setRequired(true)
            )
            ->addElement('submit', 'save', array('label' => 'Zapisz'));

        if (
            $this->getRequest()->isPost()
            && $form->isValid($this->getAllParams())
        ) {
            (new Zend_Db_Table('realestate'))->update(
                [
                    'status_id' => $form->getValue('status_id'),
                    'price' => $form->getValue('price')
                ],
                [
                    'id = ?' => $realestateId
                ]

            );

            $this->redirect('/investment/details/id/' . $this->view->realestate->investment_id);
        } else {
            $form->populate([
                'status_id' => $this->view->realestate->status_id,
                'price' => $this->view->realestate->price
            ]);
        }

        $this->view->form = $form;
    }
}

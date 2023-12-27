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

        if ($this->getRequest()->isPost() && $form->isValid($this->getAllParams())) {
            (new Zend_Db_Table('realestate'))->update(
                [
                    'price' => $form->getValue('price')
                ],
                [
                    'id = ?' => $realestateId
                ]

            );

            $this->redirect('/investment/details/id/' . $this->view->realestate->investment_id);
        } else {
            $form->populate([
                'price' => $this->view->realestate->price
            ]);
        }

        $this->view->form = $form;
    }
}

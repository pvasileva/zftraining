<?php
namespace Album\Controller;

use Album\Form\AlbumForm;
use Album\Model\Album;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AlbumController extends AbstractActionController
{
    protected $albumTable;

    public function indexAction()
    {
        return new ViewModel(array(
            'albums' => $this->getAlbumTable()->fetchAll(),
        ));
    }

    public function addAction()
    {
        // create form with label "Add"
        // Note: same form will be used for edit
        $form = new AlbumForm();
        $form->get('submit')->setValue('Add');

        // check if the form is submitted and there are POST params
        $request = $this->getRequest();
        if ($request->isPost()) {

            // Set the form’s input filter from an album instance.
            // We then set the posted data to the form and check to see
            // if it is valid using the isValid() member function of the form.
            $album = new Album();
            $form->setInputFilter($album->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {

                // If the form is valid, then we grab the data
                // from the form and store to the model
                $album->exchangeArray($form->getData());
                $this->getAlbumTable()->saveAlbum($album);

                // Redirect to list of albums
                return $this->redirect()->toRoute('album');
            }
        }
        return array('form' => $form);

    }

    public function editAction()
    {
    }

    public function deleteAction()
    {
    }

    // METHODS
    public function getAlbumTable()
    {
        if (!$this->albumTable) {
            $sm = $this->getServiceLocator();
            $this->albumTable = $sm->get('Album\Model\AlbumTable');
        }
        return $this->albumTable;
    }
}

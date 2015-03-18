<?php
namespace Album\Controller;

use Album\Form\AlbumForm;
use Album\Model\Album;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AlbumController extends AbstractActionController
{
    // entity manager
    protected $em;

    public function indexAction()
    {
        $data = $this->getEntityManager()
            ->getRepository('Album\Entity\Album')->findAll();

        return new ViewModel(array(
            'albums' => $data,
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
            $album = new \Album\Entity\Album();

            $form->setInputFilter($album->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {

                // If the form is valid, then we grab the data
                // from the form and store to the entity
                // save
                $album->exchangeArray($form->getData());
                $this->getEntityManager()->persist($album);
                $this->getEntityManager()->flush();

                // Redirect to list of albums
                return $this->redirect()->toRoute('album');
            }
        }
        return array('form' => $form);
    }

    public function editAction()
    {
        // TODO: Make all that to fucking work
        // get id from route
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('album', array(
                'action' => 'add'
            ));
        }

        // Get the Album with the specified id.  An exception is thrown
        // if it cannot be found, in which case go to the index page.
        try {
            $album = $this->getEntityManager()->find('\Album\Entity\Album', $id);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('album', array(
                'action' => 'index'
            ));
        }

        // create form with Edit title (same form for Add)
        $form  = new AlbumForm();
        $form->bind($album);
        $form->get('submit')->setAttribute('value', 'Edit');

        // get POST params
        // check if they are valid and save the data if they are
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($album->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getAlbumTable()->saveAlbum($album);

                // Redirect to list of albums
                return $this->redirect()->toRoute('album');
            }
        }

        // if there are mistakes or the form is not submitted
        // send id and the form to the view
        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function deleteAction()
    {
        // get the id from route
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('album');
        }

        // if the form is submitted and there is POST param
        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            // if the delete is confirmed - delete the album
            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getAlbumTable()->deleteAlbum($id);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('album');
        }

        // if no POST data go to the view
        return array(
            'id'    => $id,
            'album' => $this->getAlbumTable()->getAlbum($id)
        );
    }

    // METHODS
    public function getEntityManager()
    {
        if (null === $this->em) {
            $this->em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        }
        return $this->em;
    }

}

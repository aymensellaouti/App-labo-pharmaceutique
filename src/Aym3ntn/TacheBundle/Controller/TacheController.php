<?php

namespace Aym3ntn\TacheBundle\Controller;

use Aym3ntn\TacheBundle\Entity\LieuTache;
use Aym3ntn\TacheBundle\Entity\ssTypeTache;
use Aym3ntn\UserBundle\Entity\Note;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Aym3ntn\TacheBundle\Entity\Tache;
use Aym3ntn\TacheBundle\Form\TacheType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Tache controller.
 *
 */
class TacheController extends Controller
{

    /**
     * Lists all Tache entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('TacheBundle:Tache')->findAll();


        return $this->render('TacheBundle:Tache:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new Tache entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Tache();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $entity->setSsType($em->getRepository('TacheBundle:ssTypeTache')->findOneById($request->get('ssTypeTache')));

            if( in_array('ROLE_DELG', $this->getUser()->getRoles()) ){
                $entity->setOwner($this->getUser());
                $entity->setStatus(1);
            }
            $em->persist($entity);

            $em->flush();

            $secteur = $em->getRepository('MedecinBundle:Secteur')->findOneByUser($this->getUser());
            $note = new Note(2, 'Demande de validation d\'une tâche.', $secteur, $entity->getId(), 'demande tache', $this->getUser()->getId(), false);

            $em->persist($note);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_task_show', array('id' => $entity->getId())));
        }

        return $this->render('TacheBundle:Tache:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Tache entity.
     *
     * @param Tache $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Tache $entity)
    {
        $form = $this->createForm(new TacheType(), $entity, array(
            'action' => $this->generateUrl('admin_task_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Tache entity.
     *
     */
    public function newAction()
    {
        $this->accessControl('ROLE_DELG');

        $entity = new Tache();
        $form   = $this->createCreateForm($entity);

        $em = $this->getDoctrine()->getManager();

        $form_lieu = $this->createFormBuilder()
            ->add('Etiquette')
            ->add('Adresse')
            ->add('Ville')
            ->add('Telephone')
            ->add('Convention')
        ;

        return $this->render('TacheBundle:Tache:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'form_lieu' => $form_lieu->getForm()->createView()
        ));
    }

    /**
     * Finds and displays a Tache entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TacheBundle:Tache')->find($id);
var_dump($entity);die;
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tache entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('TacheBundle:Tache:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Tache entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TacheBundle:Tache')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tache entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('TacheBundle:Tache:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Tache entity.
    *
    * @param Tache $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Tache $entity)
    {
        $form = $this->createForm(new TacheType(), $entity, array(
            'action' => $this->generateUrl('admin_task_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Tache entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TacheBundle:Tache')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tache entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_task_edit', array('id' => $id)));
        }

        return $this->render('TacheBundle:Tache:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Tache entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('TacheBundle:Tache')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Tache entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_task'));
    }

    /**
     * Creates a form to delete a Tache entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAttribute('style','display:inline-block;')
            ->setAction($this->generateUrl('admin_task_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Supprimer', 'attr' => array(
                'class' => 'btn btn-danger btn-block margin-bottom',
            )))
            ->getForm()
        ;
    }


    public function ajouterLieuAjaxAction(Request $request){

        $em = $this->getDoctrine()->getManager();

        $etiquette = $request->get('etiquette');
        $adr = $request->get('adr');
        $ville = $request->get('ville');
        $tel = $request->get('tel');
        $convention = $request->get('convention');

        $lieu = new LieuTache();

        $lieu->setEtiquette($etiquette);
        $lieu->setAdresse($adr);
        $lieu->setConvention($convention);
        $lieu->setTel($tel);
        $lieu->setVille($ville);

        # TODO: Check if this place existe already or not

        $em->persist($lieu);
        $em->flush();

        $response = new Response();

        $lieuArray = $lieu->getId().','. $lieu->getEtiquette();


        $response->setContent($lieuArray);

        return $response;
    }

    public function accessControl($role){
        $um = $this->get('fos_user.user_manager');

        if( !in_array($role, $this->getUser()->getRoles()) ){
            throw new AccessDeniedException('You don\'t have the right to enter this page, bro!');
        }
    }

    public function chargerSousTypeAjaxAction(Request $request){
        $em = $this->getDoctrine()->getManager();

        $typeTache = $request->get('typeTache');

        $ssTypeTache = $em->getRepository('TacheBundle:ssTypeTache')->findBy(array('type'=>$typeTache));
        $aa = '';
        foreach( $ssTypeTache as $key => $stt ){
            $aa = $aa.$stt->getId().','.$stt->getEtiquette().',';
        }

        $response = new Response();
        $response->setContent($aa);

        return $response;
    }
}

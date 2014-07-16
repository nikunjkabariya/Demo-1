<?php

namespace A2lix\DemoTranslationBundle\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template,
    Symfony\Component\HttpFoundation\Request;
use A2lix\DemoTranslationBundle\Entity\Product;
use A2lix\DemoTranslationBundle\Form\ProductType;
use A2lix\I18nDoctrineBundle\Annotation\I18nDoctrine as A2lixI18nDoctrine;

/**
 * @Route("/product")
 */
class ProductController extends Controller
{
    /**
     * Show one/list
     *
     * @Route("/", defaults={"id"=""}, name="backend_product")
     * @Route("/{id}/show", name="backend_product_show")
     * @Template
     */
    public function indexAction($id = null)
    {
        $em = $this->getDoctrine()->getManager();

        if ($id) {
            if (!$entity = $em->getRepository('A2lixDemoTranslationBundle:Product')->find($id)) {
                throw $this->createNotFoundException();
            }

            $deleteForm = $this->createForm('delete', $entity, array(
                'action' => $this->generateUrl('backend_product_delete', array('id' => $id))
            ));

            return $this->render("A2lixDemoTranslationBundle:Backend\\Product:show.html.twig", array(
                'entity'     => $entity,
                'deleteForm' => $deleteForm->createView()
            ));
        }

        return array(
            'entities' => $em->getRepository('A2lixDemoTranslationBundle:Product')->findAll()
        );
    }

    /**
     * New/Edit
     * 
     * @A2lixI18nDoctrine
     * @Route("/new", defaults={"id"=""}, name="backend_product_new")
     * @Route("/{id}/edit", name="backend_product_edit")
     * @Template
     */
    public function editAction(Request $request, $_route, $id = null)
    {
        $em = $this->getDoctrine()->getManager();

        if ($id) {
            if (!$entity = $em->getRepository('A2lixDemoTranslationBundle:Product')->find($id)) {
                throw $this->createNotFoundException();
            }
            $deleteForm = $this->createForm('delete', $entity, array(
                'action' => $this->generateUrl('backend_product_delete', array('id' => $id))
            ));

        } else {
            $entity = new Product();
        }

        $editForm = $this->createForm(new ProductType(), $entity, array(
            'action' => $this->generateUrl($_route, $id ? array('id' => $id) : array())
        ));
        if ($editForm->handleRequest($request)->isSubmitted() && $editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('backend_product_show', array('id' => $entity->getId())));
        }

        return array(
            'entity'   => $entity,
            'editForm' => $editForm->createView()
        ) + ($id ? array('deleteForm' => $deleteForm->createView()) : array());
    }

    /**
     * Delete
     *
     * @Route("/{id}/delete", name="backend_product_delete", options={"i18n" = false})
     * @Method("POST")
     */
    public function deleteAction(Request $request, Product $entity)
    {
        $deleteForm = $this->createForm('delete', $entity);

        if ($deleteForm->handleRequest($request)->isSubmitted() && $deleteForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($entity);
            $em->flush();

            $this->get('bc_bootstrap.flash')->success('Deleted!');
        }

        return $this->redirect($this->generateUrl('backend_product'));
    }
}
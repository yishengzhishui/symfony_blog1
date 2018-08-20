<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Blog;
use AppBundle\Form\Type\ItemFilterType;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;



/**
 * Blog controller.
 *
 * @Route("blog")
 */
class BlogController extends Controller
{
    /**
     * Lists all blog entities.
     *
     * @Route("/test", name="blog_test")
     * @Method("GET")
     */
    public function testFilterAction(Request $request)
    {
        // initialize a query builder
        $filterBuilder = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Blog')
            ->createQueryBuilder('b');

        $form = $this->get('form.factory')->create(ItemFilterType::class);

        if ($request->query->has($form->getName())) {
            // manually bind values from the request
            $form->submit($request->query->get($form->getName()));

            // build the query from the given form object
            $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($form, $filterBuilder);
        }

        $query = $filterBuilder->getQuery();
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1),10);

        return $this->render('blog/test.html.twig', array(
            'form' => $form->createView(),
            'blogs' => $pagination
        ));
    }


    /**
     * Lists all blog entities.
     *
     * @Route("/", name="blog_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('AppBundle:Blog')->createQueryBuilder('b');
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($qb, $request->query->getInt('page', 1),5);

        return $this->render('blog/index.html.twig', array(
            'blogs' => $pagination,
        ));
    }

    /**
     * Creates a new blog entity.
     *
     * @Route("/new", name="blog_new")
     * @IsGranted("ROLE_USER")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $blog = new Blog();
        $form = $this->createForm('AppBundle\Form\BlogType', $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $blog->setUser($this->getUser());
            $em->persist($blog);
            $em->flush();
            $this->addFlash('success', 'blog.created_successfully');

            return $this->redirectToRoute('blog_show', array('id' => $blog->getId()));
        }

        return $this->render('blog/new.html.twig', array(
            'blog' => $blog,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a blog entity.
     *
     * @Route("/{id}", name="blog_show")
     * @IsGranted("ROLE_USER")
     * @Method("GET")
     */
    public function showAction(Blog $blog)
    {
        $deleteForm = $this->createDeleteForm($blog);

        return $this->render('blog/show.html.twig', array(
            'blog' => $blog,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing blog entity.
     *
     * @Route("/{id}/edit", name="blog_edit")
     * @IsGranted("edit", subject="blog", message="Blogs can only be edited by their authors.")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Blog $blog)
    {
        $originalTags = new ArrayCollection();

        // Create an ArrayCollection of the current Tag objects in the database
        foreach ($blog->getTags() as $tag) {
            $originalTags->add($tag);
        }

        $deleteForm = $this->createDeleteForm($blog);
        $editForm = $this->createForm('AppBundle\Form\BlogType', $blog);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            // remove the relationship between the tag and the Task
            foreach ($originalTags as $tag) {
                if (false === $blog->getTags()->contains($tag)) {
                    // remove the Task from the Tag
                    $tag->getBlogs()->removeElement($blog);
                    $em->persist($tag);
                }
            }
            $em->persist($blog);
            $em->flush();

            $this->addFlash('success', 'blog.updated_successfully');

            return $this->redirectToRoute('blog_edit', array('id' => $blog->getId()));
        }

        return $this->render('blog/edit.html.twig', array(
            'blog' => $blog,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a blog entity.
     *
     * @Route("/{id}", name="blog_delete")
     * @IsGranted("delete", subject="blog", message="Blogs can only be deleted by their authors.")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Blog $blog)
    {
        $form = $this->createDeleteForm($blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($blog);
            $em->flush();
        }

        return $this->redirectToRoute('blog_index');
    }

    /**
     * Creates a form to delete a blog entity.
     *
     * @param Blog $blog The blog entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Blog $blog)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('blog_delete', array('id' => $blog->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

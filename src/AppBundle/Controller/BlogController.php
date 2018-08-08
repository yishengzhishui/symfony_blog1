<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Blog;
use AppBundle\Entity\Tag;
use AppBundle\Service\FileUploader;
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
     * @Route("/", name="blog_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $qb = $em->getRepository('AppBundle:Blog')->createQueryBuilder('b');
        $paginator = $this->get('knp_paginator');
        $paginator = $paginator->paginate($qb, $request->query->getInt('page', 1),5);

        return $this->render('blog/index.html.twig', array(
            'blogs' => $paginator,
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

            $file = $blog->getPhoto();
            $fileName2 = md5(uniqid()).'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('photos_directory'),
                $fileName2
            );

            $blog->setPhoto($fileName2);

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

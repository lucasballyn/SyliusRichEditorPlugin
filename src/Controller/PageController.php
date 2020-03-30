<?php

namespace MonsieurBiz\SyliusRichEditorPlugin\Controller;

use MonsieurBiz\SyliusRichEditorPlugin\Entity\Page;
use MonsieurBiz\SyliusRichEditorPlugin\Form\Type\PageType;
use MonsieurBiz\SyliusRichEditorPlugin\Repository\PageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    public function index(PageRepository $pageRepository): Response
    {
        return $this->render("@MonsieurBizSyliusRichEditorPlugin/Admin/Page/index.html.twig", [
            'pages' => $pageRepository->findAll(),
        ]);
    }

    public function new(Request $request): Response
    {
        $page = new Page();
        $form = $this->createForm(PageType::class, $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($page);
            $entityManager->flush();

            return $this->redirectToRoute('monsieurbiz_sylius_rich_editor_page_index');
        }

        return $this->render('@MonsieurBizSyliusRichEditorPlugin/Admin/Page/new.html.twig', [
            'page' => $page,
            'form' => $form->createView(),
        ]);
    }

    public function show(Page $page): Response
    {
        return $this->render('@MonsieurBizSyliusRichEditorPlugin/Admin/Page/show.html.twig', [
            'page' => $page,
        ]);
    }

    public function edit(Request $request, Page $page): Response
    {
        $form = $this->createForm(PageType::class, $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('monsieurbiz_sylius_rich_editor_page_index');
        }

        return $this->render('@MonsieurBizSyliusRichEditorPlugin/Admin/Page/edit.html.twig', [
            'page' => $page,
            'form' => $form->createView(),
        ]);
    }

    public function delete(Request $request, Page $page): Response
    {
        if ($this->isCsrfTokenValid('delete' . $page->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($page);
            $entityManager->flush();
        }

        return $this->redirectToRoute('monsieurbiz_sylius_rich_editor_page_index');
    }

    public function getPageForSlug(Request $request, Page $page)
    {
        return $this->render('@MonsieurBizSyliusRichEditorPlugin/Page/show.html.twig', [
            'page' => $page,
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/post", name="post_")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $posts = $this->getDoctrine()->getRepository(Post::class)->findAll();

        return $this->render('post/index.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/user", name="user")
     */
    public function create()
    {
        return $this->render('post/create.html.twig');
    }

    /**
     * @Route("/save", name="save")
     */
    public function save(Request $request)
    {
        $data = $request->request->all();

        $post = new Post();
        $post->setTitle($data['title']);
        $post->setDescription($data['description']);
        $post->setSlug($data['slug']);
        $post->setContent($data['content']);
        $post->setCreatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));
        $post->setUpdatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));

        //Obtém o Manager do Doctrine
        $doctrine = $this->getDoctrine()->getManager();

        //Inicia a persistência dos dados no banco, prepara a operação
        $doctrine->persist($post);
        //Concretiza a operacão
        $doctrine->flush();//Dado da Sessão
        $this->addFlash('success', 'Post criado com sucesso');
        return $this->redirectToRoute('post_index');

    }

    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function edit($id)
    {
        // Camada Repository permite fazer queries e chamadas dentro do DB
        $post = $this->getDoctrine()
                    ->getRepository(Post::class)
                    ->find($id);

        return $this->render('post/edit.html.twig', [
            'post' => $post
        ]);
    }

    /**
     * @Route("/update/{id}", name="update")
     */
    public function update(Request $request, $id)
    {
        $data = $request->request->all();

        $post = $this->getDoctrine()->getRepository(Post::class)->find($id);
        $post->setTitle($data['title']);
        $post->setDescription($data['description']);
        $post->setSlug($data['slug']);
        $post->setContent($data['content']);
        $post->setUpdatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));

        //Obtém o Manager do Doctrine
        $doctrine = $this->getDoctrine()->getManager();

        //Concretiza a operacão
        $doctrine->flush();
        //Dado da Sessão
        $this->addFlash('success', 'Post atualizado com sucesso');
        return $this->redirectToRoute('post_index');
    }
    /**
     * @Route("/remove/{id}", name="remove")
     */
    public function remove($id)
    {
        $post = $this->getDoctrine()->getRepository(Post::class)->find($id);

        $manager = $this->getDoctrine()->getManager();
        //Delete
        $manager->remove($post);
        $manager->flush();

        //Dado da Sessão
        $this->addFlash('success', 'Post removido com sucesso');
        return $this->redirectToRoute('post_index');
    }
}

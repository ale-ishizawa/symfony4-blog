<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use Cassandra\Date;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/posts", name="post_")
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
     * @Route("/create", name="create")
     */
    public function create(Request $request)
    {
        $post = new Post();
        //Criar o Form
        $form = $this->createForm(PostType::class, $post);
        //Recebo o form via request
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            ///Obtém os dados do form (PostType.php)
            $post = $form->getData();
            $post->setCreatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));
            $post->setUpdatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));

            //Persistindo no banco
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($post);
            $manager->flush();
            $this->addFlash('success', 'Post criado com sucesso!');
            return $this->redirectToRoute('post_index');
        }

        return $this->render('post/create.html.twig', [
            //envio o formulário com os helpers para o template
            'form' => $form->createView()
        ]);
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
    public function edit(Request $request, $id)
    {
        // Camada Repository permite fazer queries e chamadas dentro do DB
        $post = $this->getDoctrine()->getRepository(Post::class)->find($id);

        //Cria o Form
        $form = $this->createForm(PostType::class, $post);
        //Recebo o form via request
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            //Obtém os dados do form (PostType)
            $post->setUpdatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));

            //Persistindo no banco
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();

            $this->addFlash('success', 'Post editado com sucesso!');
            return $this->redirectToRoute('post_edit', ['id' => $id]);
        }

        return $this->render('post/edit.html.twig', [
            //envio o formulário com os helpers para o template
            'form' => $form->createView()
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

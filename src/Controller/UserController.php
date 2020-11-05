<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/users", name="user_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        return $this->render('user/index.html.twig', ['users' => $users]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request)
    {
        $user = new User();
        //Cria o formulário
        $form = $this->createForm(UserType::class, $user);
        //Recebo o form via request
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            //Obtém os dados do form (UserType.php)
            $user = $form->getData();
            $user->setCreatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));
            $user->setUpdatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));

            //Persistindo no banco
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success', 'Usuário criado com sucesso!');
            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/create.html.twig', [
            //envio o formulário com os helpers para o template
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function edit(Request $request, $id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        //Cria o formulário
        $form = $this->createForm(UserType::class, $user);
        //Recebo o form via request
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            //Obtém os dados do form (UserType.php)
            $user = $form->getData();
            $user->setUpdatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));

            //Persistindo no banco
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();

            $this->addFlash('success', 'Usuário editado com sucesso!');
            return $this->redirectToRoute('user_edit', ['id' => $id]);
        }

        return $this->render('user/edit.html.twig', [
            //envio o formulário com os helpers para o template
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/remove/{id}", name="remove")
     */
    public function remove($id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        //Removendo do banco
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($user);
        $manager->flush();
        $this->addFlash('success', 'Usuário removido com sucesso!');
        return $this->redirectToRoute('user_index');
    }

}

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
     * @Route("/create", name="create")
     */
    public function create(Request $request)
    {
        $user = new User();
        //Cria o formulário
        $form = $this->createForm(UserType::class, $user);
        //Recebe o form vindo do front-end via Request
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
            return $this->redirectToRoute('user_create');
        }

        return $this->render('user/index.html.twig', [
            //envio o formulário com os helpers para o template
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/edit", name="edit")
     */
    public function edit(Request $request)
    {
        $user = new User();
        //Cria o formulário
        $form = $this->createForm(UserType::class, $user);
        //Recebe o form vindo do front-end via Request
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
            return $this->redirectToRoute('user_create');
        }

        return $this->render('user/index.html.twig', [
            //envio o formulário com os helpers para o template
            'form' => $form->createView()
        ]);
    }

}

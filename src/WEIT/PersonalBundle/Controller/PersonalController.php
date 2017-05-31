<?php

namespace WEIT\PersonalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\httpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WEIT\PersonalBundle\Entity\Personal;
use WEIT\PersonalBundle\Form\PersonalType;
use Symfony\Component\Validator\Constraints as Assert; //validacion
use Symfony\Component\Form\FormError;

class PersonalController extends Controller
{
            public function homeAction()
            {
                return $this->render('WEITPersonalBundle:Personal:home.html.twig');
            }
    public function indexAction()
    {
            $em = $this->getDoctrine()->getManager();
            $personal = $em->getRepository('WEITPersonalBundle:Personal')->findAll();
            $deleteFormAjax = $this->createCustomForm(':USER_ID', 'DELETE', 'weit_personal_delete');

            return $this->render('WEITPersonalBundle:Personal:index.html.twig', array('personal' => $personal, 'delete_form_ajax' => $deleteFormAjax->createView()));
    }

    public function agregarAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        if(!$user->getCrear())
        {
            throw $this->createAccessDeniedException();
        }
        $user = new Personal();
            $form = $this->createCreateForm($user);

            return $this->render('WEITPersonalBundle:Personal:agregar.html.twig', array('form' => $form->createView()));
    }

    private function createCreateForm(Personal $entity)
        {
            $form = $this->createForm(new PersonalType(), $entity, array('action' => $this->generateUrl('weit_personal_crear'), 'method' => 'POST'));
            return $form;
        }

        public function crearAction(Request $request)
        {
            $personal = new Personal();
            $form = $this->createCreateForm($personal);
            $form->handleRequest($request);

            if($form->isValid())
            {
                $password = $form->get('password')->getData();
                $passwordConstraint = new Assert\NotBlank();
                $errorList = $this->get('validator')->validate($password, $passwordConstraint);

                if(count($errorList) == 0)
                {
                    $encoder = $this->container->get('security.password_encoder');
                    $encoded = $encoder->encodePassword($personal, $password);
                    $personal->setPassword($encoded);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($personal);
                    $em->flush();

                    $this->addFlash('mensaje', 'El usuario ha sido creado');
                    return $this->redirectToRoute('weit_personal_index');
                }
                else
                {
                    $errorMessage = new FormError($errorList[0]->getMessage());
                    $form->get('password')->addError($errorMessage);
                }
        }
        return $this->render('WEITPersonalBundle:Personal:agregar.html.twig', array('form' => $form->createView()));
        }

        public function editarAction($id)
        {
            $user = $this->get('security.context')->getToken()->getUser();
            if(!$user->getEditar())
            {
                throw $this->createAccessDeniedException();
            }
            $em = $this->getDoctrine()->getManager();
            $personal = $em->getRepository('WEITPersonalBundle:Personal')->find($id);
            if(!$personal)
            {
                $messageException = $this->get('translator')->trans('Usuario no encontrado');
                throw $this->createNotFoundException($messageException);
            }
            $form = $this->createEditForm($personal);

            return $this->render('WEITPersonalBundle:Personal:editar.html.twig', array('personal' => $personal, 'form' => $form->createView()));
        }

        private function createEditForm(Personal $entity)
        {
        $form = $this->createForm(new PersonalType(), $entity, array('action' => $this->generateUrl('weit_personal_update', array('id' => $entity->getId())), 'method' => 'PUT'));
            return $form;
        }

        public function updateAction($id, Request $request)
        {
            $em = $this->getDoctrine()->getManager();
            $personal = $em->getRepository('WEITPersonalBundle:Personal')->find($id);

            if(!$personal)
            {
                $messageException = $this->get('translator')->trans('User not found');
                throw $this->createNotFoundException($messageException);
            }

            $form = $this->createEditForm($personal);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid())
            {
                $password = $form->get('password')->getData();
                if(!empty($password))
                {
                        $encoder = $this->container->get('security.password_encoder');
                        $encoded = $encoder->encodePassword($personal, $password);
                        $personal->setPassword($encoded);
                }
                else
                {
                        $recoverPass = $this->recoverPass($id);
                        $personal->setPassword($recoverPass[0]['password']);
                }

                if($form->get('role')->getData() == 'ROLE_ADMIN')
                {
                    $personal->setIsActive(1);
                }

                $em->flush();

                $successMessage = $this->get('translator')->trans('The user has been modified');
                $this->addFlash('mensaje', $successMessage);
                return $this->redirectToRoute('weit_personal_index', array('id' => $personal->getId()));
            }

            return $this->render('WEITPersonalBundle:Personal:editar.html.twig', array('user' => $personal, 'form' => $form->createView()));
    }

    public function recoverPass($id)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT u.password
            FROM WEITPersonalBundle:Personal u
            WHERE u.id = :id'
        )->setParameter('id', $id);

        $currentPass = $query->getResult();

        return $currentPass;
    }

    public function verAction($id)
    {
            $repository = $this->getDoctrine()->getRepository('WEITPersonalBundle:Personal');
            $personal = $repository->find($id);

            if(!$personal)
            {
                $messageException = $this->get('translator')->trans('User not found');
                throw $this->createNotFoundException($messageException);
            }

            $deleteForm = $this->createCustomForm($personal->getId(), 'DELETE', 'weit_personal_delete');

            return $this->render('WEITPersonalBundle:Personal:ver.html.twig', array('personal' => $personal, 'delete_form' => $deleteForm->createView()));
        }

        public function createCustomForm($id, $method, $route){
        return $this->createFormBuilder()
            ->setAction($this->generateUrl($route, array('id' => $id)))
            ->setMethod($method)
            ->getForm();
    }

    public function deleteAction(Request $request, $id)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        if(!$user->getBorrar())
        {
            throw $this->createAccessDeniedException();
        }
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('WEITPersonalBundle:Personal')->find($id);

        if(!$user)
            {
                $messageException = $this->get('translator')->trans('User not found');
                throw $this->createNotFoundException($messageException);
            }

            $allUsers = $em->getRepository('WEITPersonalBundle:Personal')->findAll();
            $countUsers = count($allUsers);

            //$form = $this->createDeleteForm($user);
            $form = $this->createCustomForm($user->getId(), 'DELETE', 'weit_personal_delete');
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid())
            {
                $res = $this->deleteUser($user->getRole(), $em, $user);

                $this->addFlash($res['alert'], $res['message']);
                return $this->redirectToRoute('weit_personal_index');
            }
    }

    private function deleteUser($role, $em, $user)
    {
        if($role == 'ROLE_COMERCIAL')
        {
            $em->remove($user);
            $em->flush();

            $message = $this->get('translator')->trans('The user has been deleted');
            $removed = 1;
            $alert = 'mensaje';
        }
        elseif($role == 'ROLE_ADMIN')
        {
            $message = $this->get('translator')->trans('The user could not be deleted');
            $removed = 0;
            $alert = 'error';
        }
        return array('removed' => $removed, 'message' => $message, 'alert' => $alert);
    }
}

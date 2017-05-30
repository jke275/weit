<?php

namespace WEIT\ClientesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\httpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WEIT\ClientesBundle\Entity\Clientes;
use WEIT\ClientesBundle\Form\ClientesType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ClientesController extends Controller
{
	public function indexAction()
	{
		$em = $this->getDoctrine()->getManager();
    		$personal = $em->getRepository('WEITClientesBundle:Clientes')->findAll();
    		//$deleteFormAjax = $this->createCustomForm(':USER_ID', 'DELETE', 'weit_personal_delete');

    		return $this->render('WEITClientesBundle:Clientes:index.html.twig', array('personal' => $personal/*, 'delete_form_ajax' => $deleteFormAjax->createView()*/));
	}

	public function verAction($id)
   	{
	    	$repository = $this->getDoctrine()->getRepository('WEITClientesBundle:Clientes');
	    	$clientes = $repository->find($id);

	    	if(!$clientes)
        	{
            	$messageException = $this->get('translator')->trans('Cliente no encontrado');
            	throw $this->createNotFoundException($messageException);
        	}

        	$deleteForm = $this->createCustomForm($clientes->getId(), 'DELETE', 'weit_clientes_delete');

        	return $this->render('WEITClientesBundle:Clientes:ver.html.twig', array('personal' => $clientes, 'delete_form' => $deleteForm->createView()));
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
		$user = $em->getRepository('WEITClientesBundle:Clientes')->find($id);

		if(!$user)
        	{
            	$messageException = $this->get('translator')->trans('User not found');
            	throw $this->createNotFoundException($messageException);
        	}

        	//$form = $this->createDeleteForm($user);
        	$form = $this->createCustomForm($user->getId(), 'DELETE', 'weit_personal_delete');
        	$form->handleRequest($request);

        	if($form->isSubmitted() && $form->isValid())
        	{

        		if($request->isXMLHttpRequest())
        		{
        			$res = $this->deleteUser($em, $user);

        			return new Response(
        				json_encode(array('removed' =>$res['removed'], 'message' => $res['message'], 'countUsers' => $countUsers)),
        				200,
        				array('Content-Type' => 'application/json')
        			);
        		}

        		$res = $this->deleteUser($em, $user);

            	$this->addFlash($res['alert'], $res['message']);
            	return $this->redirectToRoute('weit_clientes_index');
        	}
	}

	private function deleteUser($em, $user)
	{
			$em->remove($user);
			$em->flush();

			$message = $this->get('translator')->trans('El cliente ha sido borrado');
			$removed = 1;
			$alert = 'mensaje';
		return array('removed' => $removed, 'message' => $message, 'alert' => $alert);
	}

    	public function editarAction($id)
    	{
    		$user = $this->get('security.context')->getToken()->getUser();
		if(!$user->getEditar())
		{
			throw $this->createAccessDeniedException();
		}
        	$em = $this->getDoctrine()->getManager();
        	$personal = $em->getRepository('WEITClientesBundle:Clientes')->find($id);
        	if(!$personal)
        	{
            	$messageException = $this->get('translator')->trans('Usuario no encontrado');
            	throw $this->createNotFoundException($messageException);
        	}
        	$form = $this->createEditForm($personal);

        	return $this->render('WEITClientesBundle:Clientes:editar.html.twig', array('personal' => $personal, 'form' => $form->createView()));
    	}

    	private function createEditForm(Clientes $entity)
    	{
       	$form = $this->createForm(new ClientesType(), $entity, array('action' => $this->generateUrl('weit_clientes_update', array('id' => $entity->getId())), 'method' => 'PUT'));
        	return $form;
    	}

    	public function updateAction($id, Request $request)
    	{
    		$em = $this->getDoctrine()->getManager();
        	$clientes = $em->getRepository('WEITClientesBundle:Clientes')->find($id);

        	if(!$clientes)
        	{
            	$messageException = $this->get('translator')->trans('User not found');
            	throw $this->createNotFoundException($messageException);
        	}

        	$form = $this->createEditForm($clientes);
        	$form->handleRequest($request);

        	if($form->isSubmitted() && $form->isValid())
        	{
            	$em->flush();

            	$successMessage = $this->get('translator')->trans('The user has been modified');
            	$this->addFlash('mensaje', $successMessage);
            	return $this->redirectToRoute('weit_clientes_index', array('id' => $clientes->getId()));
        	}
        	return $this->render('WEITClientesBundle:Clientes:editar.html.twig', array('user' => $clientes, 'form' => $form->createView()));
	}

	public function agregarAction()
	{
		$user = $this->get('security.context')->getToken()->getUser();
		if(!$user->getCrear())
		{
			throw $this->createAccessDeniedException();
		}
		$user = new Clientes();
        	$form = $this->createCreateForm($user);

        	return $this->render('WEITClientesBundle:Clientes:agregar.html.twig', array('form' => $form->createView()));
	}

	private function createCreateForm(Clientes $entity)
    	{
        	$form = $this->createForm(new ClientesType(), $entity, array('action' => $this->generateUrl('weit_clientes_crear'), 'method' => 'POST'));
        	return $form;
    	}

    	public function crearAction(Request $request)
    	{
        	$personal = new Clientes();
        	$form = $this->createCreateForm($personal);
        	$form->handleRequest($request);

        	if($form->isSubmitted() && $form->isValid())
        	{
        		$em = $this->getDoctrine()->getManager();
			$em->persist($personal);
	            $em->flush();

	            $this->addFlash('mensaje', 'El usuario ha sido creado');
            	return $this->redirectToRoute('weit_clientes_index');

            	/*if(count($errorList) == 0)
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
	            }*/
		}
		return $this->render('WEITClientesBundle:Clientes:agregar.html.twig', array('form' => $form->createView()));
    	}
}

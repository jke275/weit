<?php

namespace WEIT\EspectacularesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use WEIT\EspectacularesBundle\Entity\Espectaculares;
use WEIT\EspectacularesBundle\Entity\Ventas;
use WEIT\EspectacularesBundle\Form\EspectacularesType;
use WEIT\EspectacularesBundle\Form\VentasType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\httpFoundation\Request;

class EspectacularesController extends Controller
{
	public function indexAction()
	{
		$em = $this->getDoctrine()->getManager();
    		$espectaculares = $em->getRepository('WEITEspectacularesBundle:Espectaculares')->findAll();
    		//$deleteFormAjax = $this->createCustomForm(':USER_ID', 'DELETE', 'weit_personal_delete');

    		return $this->render('WEITEspectacularesBundle:Espectaculares:index.html.twig', array('espectaculares' => $espectaculares/*, 'delete_form_ajax' => $deleteFormAjax->createView()*/));
	}

	public function agregarAction()
	{
		$user = $this->get('security.context')->getToken()->getUser();
		if(!$user->getCrear())
		{
			throw $this->createAccessDeniedException();
		}
		$user = new Espectaculares();
        	$form = $this->createCreateForm($user);

        	return $this->render('WEITEspectacularesBundle:Espectaculares:agregar.html.twig', array('form' => $form->createView()));
	}

	private function createCreateForm(Espectaculares $entity)
    	{
        	$form = $this->createForm(new EspectacularesType(), $entity, array('action' => $this->generateUrl('weit_espectaculares_crear'), 'method' => 'POST'));
        	return $form;
    	}

	public function crearAction(Request $request)
    	{
        	$personal = new Espectaculares();
        	$form = $this->createCreateForm($personal);
        	$form->handleRequest($request);

        	if($form->isSubmitted() && $form->isValid())
        	{
        		$em = $this->getDoctrine()->getManager();
			$em->persist($personal);
	            $em->flush();

	            $this->addFlash('mensaje', 'El espectacular ha sido creado');
            	return $this->redirectToRoute('weit_espectaculares_index');
		}
		return $this->render('WEITEspectacularesBundle:Espectaculares:agregar.html.twig', array('form' => $form->createView()));
    	}

    	public function verAction($id)
   	{
	    	$repository = $this->getDoctrine()->getRepository('WEITEspectacularesBundle:Espectaculares');
	    	$espectacular = $repository->find($id);

	    	if(!$espectacular)
        	{
            	$messageException = $this->get('translator')->trans('Cliente no encontrado');
            	throw $this->createNotFoundException($messageException);
        	}

        	$deleteForm = $this->createCustomForm($espectacular->getId(), 'DELETE', 'weit_espectaculares_delete');

        	return $this->render('WEITEspectacularesBundle:Espectaculares:ver.html.twig', array('espectacular' => $espectacular, 'delete_form' => $deleteForm->createView()));
    	}

    	public function createCustomForm($id, $method, $route){
		return $this->createFormBuilder()
			->setAction($this->generateUrl($route, array('id' => $id)))
			->setMethod($method)
			->getForm();
	}

	public function editarAction($id)
    	{
    		$user = $this->get('security.context')->getToken()->getUser();
		if(!$user->getEditar())
		{
			throw $this->createAccessDeniedException();
		}
        	$em = $this->getDoctrine()->getManager();
        	$personal = $em->getRepository('WEITEspectacularesBundle:Espectaculares')->find($id);
        	if(!$personal)
        	{
            	$messageException = $this->get('translator')->trans('Espectacular no encontrado');
            	throw $this->createNotFoundException($messageException);
        	}
        	$form = $this->createEditForm($personal);

        	return $this->render('WEITEspectacularesBundle:Espectaculares:editar.html.twig', array('personal' => $personal, 'form' => $form->createView()));
    	}

    	private function createEditForm(Espectaculares $entity)
    	{
       	$form = $this->createForm(new EspectacularesType(), $entity, array('action' => $this->generateUrl('weit_espectaculares_update', array('id' => $entity->getId())), 'method' => 'PUT'));
        	return $form;
    	}

    	public function updateAction($id, Request $request)
    	{
    		$em = $this->getDoctrine()->getManager();
        	$espectacular = $em->getRepository('WEITEspectacularesBundle:Espectaculares')->find($id);

        	if(!$espectacular)
        	{
            	$messageException = $this->get('translator')->trans('Espectacular no encontrado');
            	throw $this->createNotFoundException($messageException);
        	}

        	$form = $this->createEditForm($espectacular);
        	$form->handleRequest($request);

        	if($form->isSubmitted() && $form->isValid())
        	{
            	$em->flush();

            	$successMessage = $this->get('translator')->trans('Espectacular actualizado');
            	$this->addFlash('mensaje', $successMessage);
            	return $this->redirectToRoute('weit_espectaculares_index', array('id' => $espectacular->getId()));
        	}
        	return $this->render('WEITEspectacularesBundle:Espectaculares:editar.html.twig', array('user' => $espectacular, 'form' => $form->createView()));
	}

	public function deleteAction(Request $request, $id)
	{
		$user = $this->get('security.context')->getToken()->getUser();
		if(!$user->getBorrar())
		{
			throw $this->createAccessDeniedException();
		}
		$em = $this->getDoctrine()->getManager();
		$user = $em->getRepository('WEITEspectacularesBundle:Espectaculares')->find($id);

		if(!$user)
        	{
            	$messageException = $this->get('translator')->trans('Espectacular no encontrado');
            	throw $this->createNotFoundException($messageException);
        	}

        	//$form = $this->createDeleteForm($user);
        	$form = $this->createCustomForm($user->getId(), 'DELETE', 'weit_espectaculares_delete');
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
            	return $this->redirectToRoute('weit_espectaculares_index');
        	}
	}

	private function deleteUser($em, $user)
	{
			$em->remove($user);
			$em->flush();

			$message = $this->get('translator')->trans('El espectacular ha sido borrado');
			$removed = 1;
			$alert = 'mensaje';
		return array('removed' => $removed, 'message' => $message, 'alert' => $alert);
	}

	public function disponiblesAction($cliente)
	{
		$em = $this->getDoctrine()->getManager();
		$query = $em->createQuery(
			'SELECT u
			FROM WEITEspectacularesBundle:Espectaculares u
			WHERE u.isActive = :id AND u.estado = :estado'
		);
		$query->setParameter('id', true);
		$query->setParameter('estado', 'LIBRE');

		$currentPass = $query->getResult();

		return $this->render('WEITEspectacularesBundle:Espectaculares:disponibles.html.twig', array('users' => $currentPass, 'cliente' => $cliente));
	}

    	public function comprarAction($cliente, $espec)
	{
		$em = $this->getDoctrine()->getManager();
		$espectacular = $em->getRepository('WEITEspectacularesBundle:Espectaculares')->find($espec);
		$datos = $em->getRepository('WEITClientesBundle:Clientes')->find($cliente);
		$user = new Ventas();
        	$form = $this->ventasCreateForm($user, $espectacular->getId(), $datos->getId());

        	return $this->render('WEITEspectacularesBundle:Espectaculares:comprar.html.twig', array('cliente' => $datos, 'espectacular' => $espectacular , 'form' => $form->createView()));
	}

	private function ventasCreateForm(Ventas $entity, $espec, $cliente)
    	{
        	$form = $this->createForm(new VentasType($espec, $cliente), $entity, array('action' => $this->generateUrl('weit_espectaculares_pagar', array('espec' => $espec, 'cliente' => $cliente)), 'method' => 'POST'));
        	return $form;
    	}

    	public function pagarAction(Request $request, $espec, $cliente)
    	{
    		$ventas = new Ventas();
        	$form = $this->ventasCreateForm($ventas, $espec, $cliente);
        	$form->handleRequest($request);

        	if($form->isSubmitted() && $form->isValid())
        	{
        		$em = $this->getDoctrine()->getManager();
			$query = $em->createQuery(
				'SELECT u
				FROM WEITEspectacularesBundle:Espectaculares u
				WHERE u.id = :id'
			);
			$query->setParameter('id', $espec);
        		$espectacular = $query->getResult()[0];
        		$ventas->setEspectaculares($espectacular);

        		$query2 = $em->createQuery(
				'SELECT u
				FROM WEITClientesBundle:Clientes u
				WHERE u.id = :id'
			);
			$query2->setParameter('id', $cliente);
        		$clienteId = $query2->getResult()[0];
        		$ventas->setClientes($clienteId);


        		$qb = $em->createQueryBuilder();
			$q = $qb->update('WEITEspectacularesBundle:Espectaculares', 'u')
			        ->set('u.estado', '?1')
			        ->where('u.id = ?2')
			        ->setParameter(1, 'VENDIDO')
			        ->setParameter(2, $espectacular->getId())
			        ->getQuery();
			$p = $q->execute();


			$em->persist($ventas);
			$em->flush();

	            $this->addFlash('mensaje', 'La compra ha sido realizada');
            		return $this->redirectToRoute('weit_espectaculares_vendidos');
		}
		return $this->render('WEITEspectacularesBundle:Espectaculares:comprar.html.twig', array('form' => $form->createView()));
    	}

    	public function vendidosAction()
    	{
    		$em = $this->getDoctrine()->getManager();


		$dql = "SELECT
		    a, md
		    FROM WEITEspectacularesBundle:Ventas md
		        INNER JOIN WEITEspectacularesBundle:Espectaculares a WITH md = a.id";

		//$query = $em->createQuery( $dql )->getResult();



              //$espectaculares = $em->createQuery($dql);
    		$espectaculares = $em->getRepository('WEITEspectacularesBundle:Ventas')->findAll();
              //$v = $users->execute();

    		//$em = $this->getDoctrine()->getManager();
    		//$espectaculares = $em->getRepository('WEITEspectacularesBundle:Ventas')->findAll();
    		//$deleteFormAjax = $this->createCustomForm(':USER_ID', 'DELETE', 'weit_personal_delete');

    		return $this->render('WEITEspectacularesBundle:Espectaculares:vendidos.html.twig', array('espectaculares' => $espectaculares/*, 'delete_form_ajax' => $deleteFormAjax->createView()*/));
    	}
}

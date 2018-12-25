<?php

namespace App\Acme\AccountBundle\Controller\Admin;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
//use Sonata\AdminBundle\Controller\CRUDController as Controller;
//use App\Acme\AccountBundle\Controller\Admin\AccountAdminController as Controller;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\HttpFoundation\Request;
//use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use App\Acme\AccountBundle\Entity\Account;
use App\Acme\AccountBundle\Form\AccountType;
use Doctrine\Common\Collections\ArrayCollection;
use App\Acme\CoreBundle\Model\Type;
use App\Acme\AccountBundle\Model\Accounts;
use App\Acme\AccountBundle\Model\AccountUtil;
use Symfony\Component\Form\Forms;
use App\Acme\AccountBundle\Form\BatchType;

/**
 * Account controller.
 *
 * @Route("/admin/reports")
 */
class ReportsController extends Controller {

    /**
     * Lists all Account entities.
     * 
     * @Method("GET")
     * @Template()
     */
    public function listAction() {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppAcmeAccountBundle:Account')->findAll();

        return $this->render("AppAcmeAccountBundle:Reports:info.html.twig", array(
                    'entities' => $entities,
//                    'form' => $form->createView(),
        ));
        throw new \Exception('sdf');
    }

    /**
     * List action.
     *
     * @throws AccessDeniedException If access is not granted
     *
     * @return Response
     */
    public function listsAction() {

        $request = $this->getRequest();

//        $this->admin->checkAccess('list');

        $preResponse = $this->preList($request);
        if (null !== $preResponse) {
            return $preResponse;
        }

        if ($listMode = $request->get('_list_mode')) {
            $this->admin->setListMode($listMode);
        }

        $datagrid = $this->admin->getDatagrid();
        $formView = $datagrid->getForm()->createView();

        // set the theme for the current Admin Form
//        $this->setFormTheme($formView, $this->admin->getFilterTheme());
// throw new \Exception('sdf');
        return $this->renderWithExtraParams($this->admin->getTemplate('list'), [
                    'action' => 'lists',
                    'form' => $formView,
                    'datagrid' => $datagrid,
                    'csrf_token' => $this->getCsrfToken('sonata.batch'),
                    'export_formats' => $this->has('sonata.admin.admin_exporter') ?
                            $this->get('sonata.admin.admin_exporter')->getAvailableFormats($this->admin) :
                            $this->admin->getExportFormats(),
                        ], null);
    }

    public function ajaxlistsAction() {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppAcmeAccountBundle:Ledger')->findBy(array(), array('id' => 'DESC'), 10);
//        throw new \Exception('sdf');
        return $this->render("AppAcmeAccountBundle:Account:ajax/_ajaxlists.html.twig", array(
                    'entities' => $entities,
//                    'form' => $form->createView(),
        ));
    }

    /**
     * List action.
     *
     * @throws AccessDeniedException If access is not granted
     *
     * @return Response
     */
    public function istatementAction() {
        $em = $this->getDoctrine()->getManager();
        $accObj = new Accounts($em);
        $fy = $accObj->getFiscalYear();
        $reportIndex = $accObj->getIncomeStatement();
        $data = array();
        $subTotal = 0;
        $tax = 0;
        $netincome = 0;
//        $data = $this->istatementCalculation($reportIndex, $subTotal, $tax, $netincome);
        foreach ($reportIndex as $e) {
            $total = 0;
            $acts1 = $accObj->getAccountByHead($e->getAccountHead()->getCode());
            $gtitle = $e->getGroupTitle() ? : "";
            foreach ($acts1 as $ac) {
                if ($ac->getCode() == AccountUtil::CODE_PAYROLL_TAXES || $ac->getCode() == AccountUtil::CODE_FIT_TAXES) {
                    $tax += $ac->getBalance();
                    continue;
                }
                $data[$ac->getCode()]['gtitle'] = $gtitle;
                $data[$ac->getCode()]['name'] = $ac->getTitle();
                $data[$ac->getCode()]['balance'] = $ac->getBalance();
                $total += $ac->getBalance();
                $subTotal += $ac->getBalance();
            }
            $data[$ac->getCode()]['total'] = $total;
        }
        $netincome = $subTotal + $tax;

//        \Doctrine\Common\Util\Debug::dump($data);
//        throw new \Exception($netincome);
        return $this->render("AppAcmeAccountBundle:Reports:istatement.html.twig", array(
                    'data' => $data,
                    'entities' => $reportIndex,
                    'fy' => $fy->getTitle(),
                    'subtotal' => $subTotal,
                    'tax' => $tax,
                    'netincome' => $netincome,
        ));
    }

    public function istatementCalculation($reportIndex, &$subTotal, &$tax, &$netincome) {
        
    }

    /**
     * List action.
     *
     * @throws AccessDeniedException If access is not granted
     *
     * @return Response
     */
    public function balancesheetAction() {
        $em = $this->getDoctrine()->getManager();
        $accObj = new Accounts($em);
        $fy = $accObj->getFiscalYear();
        $results = $accObj->getBalanceSheet();
        $income_total = $results[0];
        $accounts = $results[1];
        $codes = $results[2];
        $data = array();
//        $data = AccountUtil::getIncomeStatementArray();
        $total = 0;
        $subTotal = 0;

//        \Doctrine\Common\Util\Debug::dump($accounts);
//        throw new \Exception(sizeof($codes).", ".$income_total);

        foreach ($accounts as $ac) {
            foreach ($ac->getAccountGroup() as $a) {
//                throw new \Exception($a->getTitle() . " - " . $a->getCode());
                if (in_array($a->getGroups()->getCode(), $codes)) {
                    $ac_code = $a->getGroups()->getCode();
                    $gtitle = $a->getGroups()->getTitle();
                }
            }
//                  \Doctrine\Common\Util\Debug::dump($ac->getAccountGroup());
//                throw new \Exception($ac_code);
//                if(!isset($ac->getAccountGroup()->getCode()))
//            $i++;
//                if($i == 2)
//                     throw new \Exception($ac->getAccountGroup());

            $data[$ac->getCode()]['code'] = $ac_code;
            $data[$ac->getCode()]['gtitle'] = $gtitle;
            $data[$ac->getCode()]['name'] = $ac->getTitle();
            $data[$ac->getCode()]['balance'] = $ac->getBalance();
            $total += $ac->getBalance();
            $subTotal += $ac->getBalance();
        }
//        if ($ac_code == AccountUtil::ACCOUNT_GROUP_REVINUE) {
            $ac_code = AccountUtil::ACCOUNT_GROUP_REVINUE ;
            $data[$ac_code]['code'] = $ac_code;
            $data[$ac_code]['gtitle'] = $gtitle;
            $data[$ac_code]['name'] = "Retained Earnings";
            $data[$ac_code]['balance'] = $income_total;
            $total += $income_total;
            $subTotal += $income_total;
//        }
//        \Doctrine\Common\Util\Debug::dump($data);
//        throw new \Exception('sdf');
        return $this->render("AppAcmeAccountBundle:Reports:bsheet.html.twig", array(
                    'data' => $data,
//                    'entities' => $reportIndex,
                    'fy' => $fy->getTitle(),
                    'subtotal' => $subTotal,
        ));
    }

//
//    /**
//     * Creates a new Account entity.
//     *
//     * #Route("/", name="account_create")
//     * @Method("POST")
//     * @Template("AdminAccountBundle:Account:new.html.twig")
//     */
//    public function create1Action(Request $request) {
//        $entity = new Account();
//        $form = $this->createCreateForm($entity);
//        $form->handleRequest($request);
//
//        if ($form->isValid()) {
//            $em = $this->getDoctrine()->getManager();
//            $em->persist($entity);
//            $em->flush();
//
//            if ($form->get('saveAndAdd')->isClicked()) {
//                return $this->redirect($this->generateUrl('account_create'));
//            } else {
////                return $this->redirect($this->generateUrl('acme_admin_account_edit', array('id' => $entity->getId())));
//            }
//        }
//
//        return $this->render("AcmeAccountBundle:Account:create.html.twig", array(
//                    'entity' => $entity,
//                    'form' => $form->createView(),
//        ));
//    }
//
//    /**
//     * Creates a form to create a Account entity.
//     *
//     * @param Account $entity The entity
//     *
//     * @return \Symfony\Component\Form\Form The form
//     */
//    private function createCreateForm(Account $entity) {
//        throw new \Exception('drgt');
//        $form = $this->createForm(new AccountType(), $entity, array(
//            'action' => $this->generateUrl('admin_acme_account_accounts_create'),
//            'method' => 'POST',
//        ));
//
//        $form->add('submit', 'submit', array('label' => 'Create'));
//
//        return $form;
//    }
//    
//
//    /**
//     * Displays a form to create a new  entity.
//     *
//     * #Route("/new", name="account_new")
//     * @Method("GET")
//     * @Template()
//     */
//    public function createAction(Request $request = null) {
//
////        $r = $this->getDoctrine()->getManager()->getRepository('AcmeAccountBundle:AccountGroup')->findAll();
////        \Doctrine\Common\Util\Debug::dump($r[0]->getFinancialHead()[1]->getId());
////        throw new \Exception(sizeof($r));
//
//        if (false === $this->admin->isGranted('CREATE')) {
//            throw new AccessDeniedException();
//        }
//
//        $entity = new Account();
//        $map = new \App\Acme\AccountBundle\Entity\AccountMapping();
////        $entity->getAccountMapping()->add($map);
//
//        $form = $this->createCreateForm($entity);
//
//        return $this->render("AcmeAccountBundle:Account:create.html.twig", array(
//                    'entity' => $entity,
//                    'form' => $form->createView(),
//        ));
//    }
//
//    /**
//     * Finds and displays a Account entity.
//     *
//     * #Route("/{id}", name="account_show")
//     * @Method("GET")
//     * @Template()
//     */
//    public function showAction($id = null, Request $request = null) {
//        $em = $this->getDoctrine()->getManager();
//
//        $entity = $em->getRepository('AcmeAccountBundle:Account')->find($id);
//
//        if (!$entity) {
//            throw $this->createNotFoundException('Unable to find Account entity.');
//        }
//
//        $deleteForm = $this->createDeleteForm($id);
//
//        return $this->render("AcmeAccountBundle:Account:show.html.twig", array(
//                    'entity' => $entity,
//                    'delete_form' => $deleteForm->createView(),
//        ));
//    }
//    /**
//     * Displays a form to edit an existing Account entity.
//     *
//     * @Route("/{id}/edit", name="account_edit")
//     * @Method("GET")
//     * @Template()
//     */
//    public function editAction($id = null, Request $request = null) {
//        $em = $this->getDoctrine()->getManager();
//
//        $entity = $em->getRepository('AcmeAccountBundle:Account')->find($id);
//
//        if (!$entity) {
//            throw $this->createNotFoundException('Unable to find Account entity.');
//        }
//
//        $editForm = $this->createEditForm($entity);
//        $deleteForm = $this->createDeleteForm($id);
//
//        return $this->render("AcmeAccountBundle:Account:edit.html.twig", array(
//                    'entity' => $entity,
//                    'form' => $editForm->createView(),
//                    'delete_form' => $deleteForm->createView(),
//        ));
//    }
//
//    /**
//     * Creates a form to edit a Account entity.
//     *
//     * @param Account $entity The entity
//     *
//     * @return \Symfony\Component\Form\Form The form
//     */
//    private function createEditForm(Account $entity) {
//        $form = $this->createForm(new AccountType(), $entity, array(
//            'action' => $this->generateUrl('acme_admin_account_account_update', array('id' => $entity->getId())),
//            'method' => 'PUT',
//        ));
//
//        $form->add('submit', 'submit', array('label' => 'Update'));
//
//        return $form;
//    }
//
//    /**
//     * Edits an existing Account entity.
//     *
//     * #Route("/{id}", name="account_update")
//     * @Method("PUT")
//     * @Template("AcmeAccountBundle:Account:edit.html.twig")
//     */
//    public function updateAction(Request $request, $id) {
//        $em = $this->getDoctrine()->getManager();
//
//        $entity = $em->getRepository('AcmeAccountBundle:Account')->find($id);
//
//        if (!$entity) {
//            throw $this->createNotFoundException('Unable to find Account entity.');
//        }
//
//        $originalMap = new ArrayCollection();
//        foreach ($entity->getAccountMapping() as $map) {
//            $originalMap->add($map);
//        }
//
//        $deleteForm = $this->createDeleteForm($id);
//        $editForm = $this->createEditForm($entity);
//        $editForm->handleRequest($request);
//
//        if ($editForm->isValid()) {
//
//            foreach ($originalMap as $map) {
//                if (false === $entity->getAccountMapping()->contains($map)) {
//
//                    $map->setMappingAccount(null);
//                    $em->persist($map);
//                    $em->remove($map);
//                }
//            }
//            $em->persist($entity);
//
//            $em->flush();
//            $session = $this->getRequest()->getSession();
//            $session->getFlashBag()->add('notice', 'You have updated successfully!');
//
//            return $this->redirect($this->generateUrl('acme_admin_account_account_edit', array('id' => $id)));
//        }
//
//        return $this->render("AcmeAccountBundle:Account:edit.html.twig", array(
//                    'entity' => $entity,
//                    'form' => $editForm->createView(),
//                    'delete_form' => $deleteForm->createView(),
//        ));
//    }
//
//    /**
//     * Creates a form to delete a Account entity by id.
//     *
//     * @param mixed $id The entity id
//     *
//     * @return \Symfony\Component\Form\Form The form
//     */
//    private function createDeleteForm($id) {
//        return $this->createFormBuilder()
//                        ->setAction($this->generateUrl('acme_admin_account_account_delete', array('id' => $id)))
//                        ->setMethod('DELETE')
//                        ->add('submit', 'submit', array('label' => 'Delete'))
//                        ->getForm()
//        ;
//    }
}

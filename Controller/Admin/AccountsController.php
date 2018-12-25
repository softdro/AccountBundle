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
 * @Route("/admin/account")
 */
class AccountsController extends Controller {
//    private $em;
//
//    public function __construct(EntityManager $entityManager) {
//        $this->em = $entityManager;
//    }

    /**
     * Lists all Account entities.
     * 
     * @Method("GET")
     * @Template()
     */
    public function listAction() {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppAcmeAccountBundle:Account')->findAll();
//        throw new \Exception('sdf');
        return $this->render("AppAcmeAccountBundle:Account:info.html.twig", array(
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
    public function paymentAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $accObj = new Accounts($em);

        $batch = new \App\Acme\AccountBundle\Entity\Batch();
//        $ah = AccountUtil::ACCOUNT_HEAD_CASH_BANK;
//        $firstAccount = $accObj->getAccountByHead($ah);
//        $entity->setFirstAccount($firstAccount);

        $form = $this->createForm(new \App\Acme\AccountBundle\Form\Type\BatchTransactionType(), $batch, array(
            'action' => $this->generateUrl('admin_acme_account_accounts_payment'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));
        $form->handleRequest($request);

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
//            $em->persist($entity);
            $f_amount = $form['first_amount']->getData();
            $des = $form['description']->getData();
//            $s_amount = $form['second_amount']->getData();
//            throw new \Exception($f_amount." ");
            $session = $this->getRequest()->getSession();
//            $session->getFlashBag()->add('notice', "You have transacted successfully by amount $f_amount");
            $this->getRequest()->getSession()->getFlashBag()->add("success", "You have transacted successfully by amount $f_amount");
            if ($des == null)
                $des = "TRN: $f_amount";
            $accObj->entryBatchAccount($batch, $f_amount, null, $des);

            $em->flush();

//            return $this->redirect($this->generateUrl('admin_acme_account_accounts_payment', array('id' => $entity->getId())));
        }

        return $this->render("AppAcmeAccountBundle:Account:payment.html.twig", array(
                    'entity' => $batch,
                    'form' => $form->createView(),
        ));
    }

    /**
     * List action.
     *
     * @throws AccessDeniedException If access is not granted
     *
     * @return Response
     */
    public function receiveAction() {
        $em = $this->getDoctrine()->getManager();
        $accObj = new Accounts($em);

        $entities = $accObj->getIncomeStatement();
        $data = array();
        $data = AccountUtil::getIncomeStatementArray();
        $total = 0;

//        \Doctrine\Common\Util\Debug::dump($data);
        foreach ($entities as $e) {
//            throw new \Exception($e->getAccount()->getTitle());

            if (!isset($data[$e->getAccount()->getCode()]["balance"])) {
//                $data[$e->getAccount()->getCode()]["balance"] = "0";
//                $data[$e->getAccount()->getCode()]["name"] = "";
            }
//            $data[$e->getAccount()->getCode()]["name"] = $e->getAccount()->getTitle();
            if ($e->getAccount()->getAccountHead()->getCode() == AccountUtil::ACCOUNT_HEAD_EXPENSES)
                $data[AccountUtil::CODE_EXPENSE]["balance"] = $data[AccountUtil::CODE_EXPENSE]["balance"] + $e->getCredit() - $e->getDebit();
            else
                $data[$e->getAccount()->getCode()]["balance"] = $data[$e->getAccount()->getCode()]["balance"] + $e->getCredit() - $e->getDebit();


            $data[AccountUtil::TEXT_BEFORE_TAX]["balance"] = $data[AccountUtil::TEXT_BEFORE_TAX]["balance"] + $data[$e->getAccount()->getCode()]["balance"];
        }

        $data[AccountUtil::TEXT_AFTER_TAX]["balance"] = 1.15 * $data[AccountUtil::TEXT_BEFORE_TAX]["balance"];

//        \Doctrine\Common\Util\Debug::dump($data);
//        throw new \Exception('sdf');
        return $this->render("AppAcmeAccountBundle:Account:receive.html.twig", array(
                    'data' => $data,
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
    public function investmentAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $accObj = new Accounts($em);

        $entity = new \App\Acme\AccountBundle\Entity\ShareAccount();
//        $ah = AccountUtil::ACCOUNT_HEAD_CASH_BANK;
//        $firstAccount = $accObj->getAccountByHead($ah);
//        $entity->setFirstAccount($firstAccount);

        $form = $this->createForm(new \App\Acme\AccountBundle\Form\Type\InvestmentType(), $entity, array(
            'action' => $this->generateUrl('admin_acme_account_accounts_investment'),
            'method' => 'POST',
            'empty_data' => ' ',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $des = "";
            $em = $this->getDoctrine()->getManager();
            $amount = $form['amount']->getData();
//            $form->get('investment')->setData($amount);
//            $form->getData()->setInvestment(8000);
            $percentage = $form['percentage']->getData();
            $em->persist($entity);

//            throw new \Exception($amount . ", " . $form['payment']->getData() . ", " . $form['percentage']->getData());
            $session = $this->getRequest()->getSession();
            $this->getRequest()->getSession()->getFlashBag()->add("success", "You have transacted successfully by amount $amount");
            if ($des == null)
                $des = "Investment by : $amount";

            $sec_acc = $em->getRepository("AppAcmeAccountBundle:Account")->findOneBy(array('code' => AccountUtil::CODE_OWNERS_CAPITAL));
//            throw new \Exception($sec_acc);
            $batch = new \App\Acme\AccountBundle\Entity\Batch();
            $batch->setFirstAccount($form['payment']->getData());
            $batch->setFirstCr(FALSE);
            $batch->setSecondAccount($sec_acc);
            $batch->setSecondCr(TRUE);

            $accObj->entryBatchAccount($batch, $amount, null, $des);

            $accObj->updatePersonAccount($form['account']->getData(), $amount, AccountUtil::Cr, $des);

            $em->flush();
        }
        return $this->render("AppAcmeAccountBundle:Account:investment.html.twig", array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    public function ajaxInvestmentAction(Request $request) {
        $id = $request->get('id');
//        $name1 = $request->query->get('id');  // get
//        $name = $request->request->get('id');   // post
//        throw new \Exception($id. ", ".$name . ", " . $name1);
        $em = $this->getDoctrine()->getManager();
        $accObj = new Accounts($em);
        $acc = $em->getRepository('AppAcmeAccountBundle:Account')->find($id);
//          throw new \Exception(sizeof($acc));
        if ($acc) {
            $entity = $em->getRepository('AppAcmeAccountBundle:ShareAccount')->findOneBy(array('account' => $acc));
        } else {
            $entity = new \App\Acme\AccountBundle\Entity\ShareAccount();
            $entity->setInvestment(0);
            $entity->setPercentage(0);
        }
//        throw new \Exception(sizeof($entity));
        $previous = '0';
        if (sizeof($entity) < 1) {
            $entity = new \App\Acme\AccountBundle\Entity\ShareAccount();
            $entity->setAccount($acc);
            $entity->setInvestment(0);
        } else {
            $previous = $entity->getInvestment();
        }

        $form = $this->createForm(new \App\Acme\AccountBundle\Form\Type\InvestmentType(), $entity, array(
            'action' => $this->generateUrl('admin_acme_account_accounts_investment'),
            'method' => 'POST',
            'empty_data' => $previous,
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));
        return $this->render("AppAcmeAccountBundle:Account:ajax/_ajaxInvestment.html.twig", array(
                    'entities' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * List action.
     *
     * @throws AccessDeniedException If access is not granted
     *
     * @return Response
     */
    public function transferAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $accObj = new Accounts($em);

        $batch = new \App\Acme\AccountBundle\Entity\Batch();
//        $ah = AccountUtil::ACCOUNT_HEAD_CASH_BANK;
//        $firstAccount = $accObj->getAccountByHead($ah);
//        $entity->setFirstAccount($firstAccount);

        $form = $this->createForm(new \App\Acme\AccountBundle\Form\Type\BatchTransferType(), $batch, array(
            'action' => $this->generateUrl('admin_acme_account_accounts_payment'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));
        $form->handleRequest($request);

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
//            $em->persist($entity);
            $f_amount = $form['first_amount']->getData();
            $des = $form['description']->getData();
//            $s_amount = $form['second_amount']->getData();
//            throw new \Exception($f_amount." ");
            $session = $this->getRequest()->getSession();
//            $session->getFlashBag()->add('notice', "You have transacted successfully by amount $f_amount");
            $this->getRequest()->getSession()->getFlashBag()->add("success", "You have transacted successfully by amount $f_amount");
            if ($des == null)
                $des = "TRN: $f_amount";
            $accObj->entryBatchAccount($batch, $f_amount, null, $des);

            $em->flush();

//            return $this->redirect($this->generateUrl('admin_acme_account_accounts_payment', array('id' => $entity->getId())));
        }

        return $this->render("AppAcmeAccountBundle:Account:transfer.html.twig", array(
                    'entity' => $batch,
                    'form' => $form->createView(),
        ));
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

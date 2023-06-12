<?php

/*
 * This file is part of the "Shared Project Timesheets Bundle" for Kimai.
 * All rights reserved by Fabian Vetter (https://vettersolutions.de).
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace KimaiPlugin\SharedProjectTimesheetsBundle\Controller;

use App\Controller\AbstractController;
use App\Repository\Query\BaseQuery;
use App\Utils\DataTable;
use App\Utils\PageSetup;
use KimaiPlugin\SharedProjectTimesheetsBundle\Entity\SharedProjectTimesheet;
use KimaiPlugin\SharedProjectTimesheetsBundle\Form\SharedProjectFormType;
use KimaiPlugin\SharedProjectTimesheetsBundle\Model\RecordMergeMode;
use KimaiPlugin\SharedProjectTimesheetsBundle\Repository\SharedProjectTimesheetRepository;
use KimaiPlugin\SharedProjectTimesheetsBundle\Service\ManageService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/shared-project-timesheets')]
#[IsGranted('shared_projects')]
class ManageController extends AbstractController
{
    public function __construct(
        private SharedProjectTimesheetRepository $shareProjectTimesheetRepository,
        private ManageService $manageService
    ) {
    }

    #[Route(path: '', name: 'manage_shared_project_timesheets', methods: ['GET'])]
    public function index(): Response
    {
        $query = new BaseQuery();

        $sharedProjects = $this->shareProjectTimesheetRepository->findAllSharedProjects($query);

        $table = new DataTable('shared_project_timesheets_manage', $query);
        $table->setPagination($sharedProjects);
        $table->setReloadEvents('kimai.sharedProject');

        $table->addColumn('name', ['class' => 'alwaysVisible', 'orderBy' => false]);
        $table->addColumn('url', ['class' => 'alwaysVisible', 'orderBy' => false]);
        $table->addColumn('password', ['class' => 'd-none', 'orderBy' => false]);
        $table->addColumn('record_merge_mode', ['class' => 'd-none text-center w-min', 'orderBy' => false, 'title' => 'shared_project_timesheets.manage.table.record_merge_mode']);
        $table->addColumn('entry_user_visible', ['class' => 'd-none text-center w-min', 'orderBy' => false, 'title' => 'shared_project_timesheets.manage.table.entry_user_visible']);
        $table->addColumn('entry_rate_visible', ['class' => 'd-none text-center w-min', 'orderBy' => false, 'title' => 'shared_project_timesheets.manage.table.entry_rate_visible']);
        $table->addColumn('annual_chart_visible', ['class' => 'd-none text-center w-min', 'orderBy' => false, 'title' => 'shared_project_timesheets.manage.table.annual_chart_visible']);
        $table->addColumn('monthly_chart_visible', ['class' => 'd-none text-center w-min', 'orderBy' => false, 'title' => 'shared_project_timesheets.manage.table.monthly_chart_visible']);

        $table->addColumn('actions', ['class' => 'actions alwaysVisible']);

        $page = new PageSetup('shared_project_timesheets.manage.title');
        $page->setActionName('shared_projects');
        $page->setDataTable($table);

        return $this->render('@SharedProjectTimesheets/manage/index.html.twig', [
            'page_setup' => $page,
            'dataTable' => $table,
            'RecordMergeMode' => RecordMergeMode::getModes(),
        ]);
    }

    #[Route(path: '/create', name: 'create_shared_project_timesheets', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $sharedProject = new SharedProjectTimesheet();

        $form = $this->createForm(SharedProjectFormType::class, $sharedProject, [
            'method' => 'POST',
            'action' => $this->generateUrl('create_shared_project_timesheets')
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->manageService->create($sharedProject, $form->get('password')->getData());
                $this->flashSuccess('action.update.success');

                return $this->redirectToRoute('manage_shared_project_timesheets');
            } catch (\Exception $e) {
                $this->flashUpdateException($e);
            }
        }

        return $this->render('@SharedProjectTimesheets/manage/edit.html.twig', [
            'entity' => $sharedProject,
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/{projectId}/{shareKey}', name: 'update_shared_project_timesheets', methods: ['GET', 'POST'])]
    public function update(string $projectId, string $shareKey, Request $request): Response
    {
        if ($projectId == null || $shareKey == null) {
            throw $this->createNotFoundException('Project not found');
        }

        /** @var SharedProjectTimesheet $sharedProject */
        $sharedProject = $this->shareProjectTimesheetRepository->findOneBy(['project' => $projectId, 'shareKey' => $shareKey]);
        if ($sharedProject === null) {
            throw $this->createNotFoundException('Given project not found');
        }

        // Store data in temporary SharedProjectTimesheet object
        $form = $this->createForm(SharedProjectFormType::class, $sharedProject, [
            'method' => 'POST',
            'action' => $this->generateUrl('update_shared_project_timesheets', ['projectId' => $projectId, 'shareKey' => $shareKey])
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->manageService->update($sharedProject, $form->get('password')->getData());
                $this->flashSuccess('action.update.success');

                return $this->redirectToRoute('manage_shared_project_timesheets');
            } catch (\Exception $e) {
                $this->flashUpdateException($e);
            }
        } elseif (!$form->isSubmitted()) {
            if (!empty($sharedProject->getPassword())) {
                $form->get('password')->setData(ManageService::PASSWORD_DO_NOT_CHANGE_VALUE);
            }
        }

        return $this->render('@SharedProjectTimesheets/manage/edit.html.twig', [
            'entity' => $sharedProject,
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/{projectId}/{shareKey}/remove', name: 'remove_shared_project_timesheets', methods: ['GET', 'POST'])]
    public function remove(Request $request): Response
    {
        $projectId = $request->get('projectId');
        $shareKey = $request->get('shareKey');

        if ($projectId == null || $shareKey == null) {
            throw $this->createNotFoundException('Project not found');
        }

        /** @var SharedProjectTimesheet $sharedProject */
        $sharedProject = $this->shareProjectTimesheetRepository->findOneBy(['project' => $projectId, 'shareKey' => $shareKey]);
        if (!$sharedProject || $sharedProject->getProject() === null || $sharedProject->getShareKey() === null) {
            throw $this->createNotFoundException('Given project not found');
        }

        try {
            $this->shareProjectTimesheetRepository->remove($sharedProject);
            $this->flashSuccess('action.delete.success');
        } catch (\Exception $ex) {
            $this->flashDeleteException($ex);
        }

        return $this->redirectToRoute('manage_shared_project_timesheets');
    }
}

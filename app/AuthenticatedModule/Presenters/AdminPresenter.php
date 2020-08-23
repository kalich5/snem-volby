<?php

declare(strict_types=1);

namespace App\AuthenticatedModule;

use App\AuthenticatedModule\Components\CandidatesBox;
use App\AuthenticatedModule\Components\PublishResult;
use App\AuthenticatedModule\Components\VotingTimeForm;
use App\AuthenticatedModule\Factories\ICandidatesBoxFactory;
use App\AuthenticatedModule\Factories\IPublishResultFactory;
use App\AuthenticatedModule\Factories\IVotingTimeFormFactory;
use Model\Candidate\Commands\SaveCandidates;
use Model\Candidate\ReadModel\Queries\CandidatesCountQuery;
use Model\Commission\Commands\SaveCommissionMembers;
use Model\Commission\ReadModel\Queries\CommissionMembersCountQuery;
use Model\Delegate\Commands\SaveDelegates;
use Model\Delegate\ReadModel\Queries\DelegatesCountQuery;
use Model\Delegate\ReadModel\Queries\VotedDelegatesCountQuery;

class AdminPresenter extends BasePresenter
{
    private IPublishResultFactory $publishResultFactory;
    private IVotingTimeFormFactory $votingTimeFormFactory;
    private ICandidatesBoxFactory $candidatesBoxFactory;

    public function __construct(
        IPublishResultFactory $publishResultFactory,
        IVotingTimeFormFactory $votingTimeFormFactory,
        ICandidatesBoxFactory $candidatesBoxFactory
    ) {
        parent::__construct();
        $this->publishResultFactory  = $publishResultFactory;
        $this->votingTimeFormFactory = $votingTimeFormFactory;
        $this->candidatesBoxFactory  = $candidatesBoxFactory;
    }

    public function startup() : void
    {
        parent::startup();

        if ($this->userService->isSuperUser()) {
            $this->template->setParameters([
                'candidatesCount' => $this->queryBus->handle(new CandidatesCountQuery()),
                'delegatesCount' => $this->queryBus->handle(new DelegatesCountQuery()),
                'votedDelegatesCount' => $this->queryBus->handle(new VotedDelegatesCountQuery()),
                'commissionMembersCount' => $this->queryBus->handle(new CommissionMembersCountQuery()),
            ]);

            return;
        }

        $this->flashMessage('Nemáte oprávnění přistupovat ke stránce!', 'danger');
        $this->redirect(':Homepage:');
    }

    public function handleSaveDelegates() : void
    {
        if ($this->queryBus->handle(new DelegatesCountQuery()) === 0) {
            $this->commandBus->handle(new SaveDelegates());
        }
        $this->redirect('this');
    }

    public function handleSaveCommissionMembers() : void
    {
        if ($this->queryBus->handle(new CommissionMembersCountQuery()) === 0) {
            $this->commandBus->handle(new SaveCommissionMembers());
        }
        $this->redirect('this');
    }

    public function handleSaveCandidates() : void
    {
        $this->commandBus->handle(new SaveCandidates());
        $this->redirect('this');
    }

    public function createComponentVotingTimeForm() : VotingTimeForm
    {
        return $this->votingTimeFormFactory->create();
    }

    protected function createComponentPublishResult() : PublishResult
    {
        return $this->publishResultFactory->create();
    }

    protected function createComponentCandidatesBox() : CandidatesBox
    {
        return $this->candidatesBoxFactory->create();
    }
}

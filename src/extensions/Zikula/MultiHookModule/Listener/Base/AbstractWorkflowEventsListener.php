<?php

/**
 * MultiHook.
 *
 * @copyright Zikula Team (Zikula)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Zikula Team <info@ziku.la>.
 * @see https://ziku.la
 * @version Generated by ModuleStudio 1.4.0 (https://modulestudio.de).
 */

declare(strict_types=1);

namespace Zikula\MultiHookModule\Listener\Base;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;
use Symfony\Component\Workflow\Event\GuardEvent;
use Zikula\Bundle\CoreBundle\Doctrine\EntityAccess;
use Zikula\MultiHookModule\Entity\Factory\EntityFactory;
use Zikula\MultiHookModule\Helper\PermissionHelper;

/**
 * Event handler implementation class for workflow events.
 *
 * @see /src/docs/Workflows/WorkflowEvents.md
 */
abstract class AbstractWorkflowEventsListener implements EventSubscriberInterface
{
    /**
     * @var EntityFactory
     */
    protected $entityFactory;
    
    /**
     * @var PermissionHelper
     */
    protected $permissionHelper;
    
    public function __construct(
        EntityFactory $entityFactory,
        PermissionHelper $permissionHelper
    ) {
        $this->entityFactory = $entityFactory;
        $this->permissionHelper = $permissionHelper;
    }
    
    public static function getSubscribedEvents()
    {
        return [
            'workflow.guard' => ['onGuard', 5],
            'workflow.leave' => ['onLeave', 5],
            'workflow.transition' => ['onTransition', 5],
            'workflow.enter' => ['onEnter', 5],
            'workflow.entered' => ['onEntered', 5],
            'workflow.completed' => ['onCompleted', 5],
            'workflow.announce' => ['onAnnounce', 5]
        ];
    }
    
    /**
     * Listener for the `workflow.guard` event.
     *
     * Occurs before a transition is started and when testing which transitions are available.
     * Validates whether the transition is allowed or not.
     * Allows to block it by calling `$event->setBlocked(true);`.
     *
     * This event is also triggered for each workflow individually, so you can react only to the events
     * of a specific workflow by listening to `workflow.<workflow_name>.guard` instead.
     * You can even listen to some specific transitions or states for a specific workflow
     * using `workflow.<workflow_name>.guard.<state_name>`.
     *
     * You can access general data available in the event.
     *
     * The event name:
     *     `echo 'Event: ' . $event->getName();`
     *
     * Access the entity: `$entity = $event->getSubject();`
     * Access the marking: `$marking = $event->getMarking();`
     * Access the transition: `$transition = $event->getTransition();`
     * Access the workflow name: `$workflowName = $event->getWorkflowName();`
     * Example for preventing a transition:
     *     `if (!$event->isBlocked()) {
     *         $event->setBlocked(true);
     *     }`
     * Example with providing a reason:
     *     `$event->addTransitionBlocker(
     *         new TransitionBlocker('You can not this because that.')
     *     );`
     */
    public function onGuard(GuardEvent $event): void
    {
        /** @var EntityAccess $entity */
        $entity = $event->getSubject();
        if (!$this->isEntityManagedByThisBundle($entity) || !method_exists($entity, 'get_objectType')) {
            return;
        }
        
        $permissionLevel = ACCESS_READ;
        $transitionName = $event->getTransition()->getName();
        
        $hasApproval = false;
        
        switch ($transitionName) {
            case 'defer':
            case 'submit':
                $permissionLevel = $hasApproval ? ACCESS_COMMENT : ACCESS_EDIT;
                break;
            case 'update':
            case 'reject':
            case 'accept':
            case 'publish':
            case 'unpublish':
            case 'archive':
            case 'trash':
            case 'recover':
                $permissionLevel = ACCESS_EDIT;
                break;
            case 'approve':
            case 'demote':
                $permissionLevel = ACCESS_ADD;
                break;
            case 'delete':
                $permissionLevel = ACCESS_DELETE;
                break;
        }
        
        if (!$this->permissionHelper->hasEntityPermission($entity, $permissionLevel)) {
            // no permission for this transition, so disallow it (without a reason)
            $event->setBlocked(true);
        
            return;
        }
    }
    
    /**
     * Listener for the `workflow.leave` event.
     *
     * Occurs after a subject has left it's current state.
     * Carries the marking with the initial places.
     *
     * This event is also triggered for each workflow individually, so you can react only to the events
     * of a specific workflow by listening to `workflow.<workflow_name>.leave` instead.
     * You can even listen to some specific transitions or states for a specific workflow
     * using `workflow.<workflow_name>.leave.<state_name>`.
     *
     * You can access general data available in the event.
     *
     * The event name:
     *     `echo 'Event: ' . $event->getName();`
     *
     * Access the entity: `$entity = $event->getSubject();`
     * Access the marking: `$marking = $event->getMarking();`
     * Access the transition: `$transition = $event->getTransition();`
     * Access the workflow name: `$workflowName = $event->getWorkflowName();`
     */
    public function onLeave(Event $event): void
    {
        /** @var EntityAccess $entity */
        $entity = $event->getSubject();
        if (!$this->isEntityManagedByThisBundle($entity) || !method_exists($entity, 'get_objectType')) {
            return;
        }
    }
    
    /**
     * Listener for the `workflow.transition` event.
     *
     * Occurs before starting to transition to the new state.
     * Carries the marking with the current places.
     *
     * This event is also triggered for each workflow individually, so you can react only to the events
     * of a specific workflow by listening to `workflow.<workflow_name>.transition` instead.
     * You can even listen to some specific transitions or states for a specific workflow
     * using `workflow.<workflow_name>.transition.<state_name>`.
     *
     * You can access general data available in the event.
     *
     * The event name:
     *     `echo 'Event: ' . $event->getName();`
     *
     * Access the entity: `$entity = $event->getSubject();`
     * Access the marking: `$marking = $event->getMarking();`
     * Access the transition: `$transition = $event->getTransition();`
     * Access the workflow name: `$workflowName = $event->getWorkflowName();`
     */
    public function onTransition(Event $event): void
    {
        /** @var EntityAccess $entity */
        $entity = $event->getSubject();
        if (!$this->isEntityManagedByThisBundle($entity) || !method_exists($entity, 'get_objectType')) {
            return;
        }
    }
    
    /**
     * Listener for the `workflow.enter` event.
     *
     * Occurs before the subject enters into the new state and places are updated.
     * This means the marking of the subject is not yet updated with the new places.
     *
     * This event is also triggered for each workflow individually, so you can react only to the events
     * of a specific workflow by listening to `workflow.<workflow_name>.enter` instead.
     * You can even listen to some specific transitions or states for a specific workflow
     * using `workflow.<workflow_name>.enter.<state_name>`.
     *
     * You can access general data available in the event.
     *
     * The event name:
     *     `echo 'Event: ' . $event->getName();`
     *
     * Access the entity: `$entity = $event->getSubject();`
     * Access the marking: `$marking = $event->getMarking();`
     * Access the transition: `$transition = $event->getTransition();`
     * Access the workflow name: `$workflowName = $event->getWorkflowName();`
     */
    public function onEnter(Event $event): void
    {
        /** @var EntityAccess $entity */
        $entity = $event->getSubject();
        if (!$this->isEntityManagedByThisBundle($entity) || !method_exists($entity, 'get_objectType')) {
            return;
        }
    }
    
    /**
     * Listener for the `workflow.entered` event.
     *
     * Occurs after the subject has entered into the new state.
     * Carries the marking with the new places.
     * This is a good place to flush data in Doctrine based on the entity not being updated yet.
     *
     * This event is also triggered for each workflow individually, so you can react only to the events
     * of a specific workflow by listening to `workflow.<workflow_name>.entered` instead.
     * You can even listen to some specific transitions or states for a specific workflow
     * using `workflow.<workflow_name>.entered.<state_name>`.
     *
     * You can access general data available in the event.
     *
     * The event name:
     *     `echo 'Event: ' . $event->getName();`
     *
     * Access the entity: `$entity = $event->getSubject();`
     * Access the marking: `$marking = $event->getMarking();`
     * Access the transition: `$transition = $event->getTransition();`
     * Access the workflow name: `$workflowName = $event->getWorkflowName();`
     */
    public function onEntered(Event $event): void
    {
        /** @var EntityAccess $entity */
        $entity = $event->getSubject();
        if (!$this->isEntityManagedByThisBundle($entity) || !method_exists($entity, 'get_objectType')) {
            return;
        }
    }
    
    /**
     * Listener for the `workflow.completed` event.
     *
     * Occurs after the subject has completed a transition.
     *
     * This event is also triggered for each workflow individually, so you can react only to the events
     * of a specific workflow by listening to `workflow.<workflow_name>.completed` instead.
     * You can even listen to some specific transitions or states for a specific workflow
     * using `workflow.<workflow_name>.completed.<state_name>`.
     *
     * You can access general data available in the event.
     *
     * The event name:
     *     `echo 'Event: ' . $event->getName();`
     *
     * Access the entity: `$entity = $event->getSubject();`
     * Access the marking: `$marking = $event->getMarking();`
     * Access the transition: `$transition = $event->getTransition();`
     * Access the workflow name: `$workflowName = $event->getWorkflowName();`
     */
    public function onCompleted(Event $event): void
    {
        /** @var EntityAccess $entity */
        $entity = $event->getSubject();
        if (!$this->isEntityManagedByThisBundle($entity) || !method_exists($entity, 'get_objectType')) {
            return;
        }
    }
    
    /**
     * Listener for the `workflow.announce` event.
     *
     * Triggered for each place that now is available for the subject.
     *
     * This event is also triggered for each workflow individually, so you can react only to the events
     * of a specific workflow by listening to `workflow.<workflow_name>.announce` instead.
     * You can even listen to some specific transitions or states for a specific workflow
     * using `workflow.<workflow_name>.announce.<state_name>`.
     *
     * You can access general data available in the event.
     *
     * The event name:
     *     `echo 'Event: ' . $event->getName();`
     *
     * Access the entity: `$entity = $event->getSubject();`
     * Access the marking: `$marking = $event->getMarking();`
     * Access the transition: `$transition = $event->getTransition();`
     * Access the workflow name: `$workflowName = $event->getWorkflowName();`
     */
    public function onAnnounce(Event $event): void
    {
        /** @var EntityAccess $entity */
        $entity = $event->getSubject();
        if (!$this->isEntityManagedByThisBundle($entity) || !method_exists($entity, 'get_objectType')) {
            return;
        }
    }
    
    /**
     * Checks whether this listener is responsible for the given entity or not.
     *
     * @param EntityAccess $entity The given entity
     */
    protected function isEntityManagedByThisBundle($entity): bool
    {
        if (!($entity instanceof EntityAccess)) {
            return false;
        }
    
        $entityClassParts = explode('\\', get_class($entity));
    
        if ('DoctrineProxy' === $entityClassParts[0] && '__CG__' === $entityClassParts[1]) {
            array_shift($entityClassParts);
            array_shift($entityClassParts);
        }
    
        return 'Zikula' === $entityClassParts[0] && 'MultiHookModule' === $entityClassParts[1];
    }
}

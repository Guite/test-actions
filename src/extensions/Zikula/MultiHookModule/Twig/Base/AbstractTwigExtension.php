<?php

/**
 * MultiHook.
 *
 * @copyright Zikula Team (Zikula)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Zikula Team <info@ziku.la>.
 *
 * @see https://ziku.la
 *
 * @version Generated by ModuleStudio 1.5.0 (https://modulestudio.de).
 */

declare(strict_types=1);

namespace Zikula\MultiHookModule\Twig\Base;

use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Zikula\Bundle\CoreBundle\Doctrine\EntityAccess;
use Zikula\Bundle\CoreBundle\Translation\TranslatorTrait;
use Zikula\ExtensionsModule\Api\ApiInterface\VariableApiInterface;
use Zikula\MultiHookModule\Helper\EntityDisplayHelper;
use Zikula\MultiHookModule\Helper\ListEntriesHelper;
use Zikula\MultiHookModule\Helper\WorkflowHelper;

/**
 * Twig extension base class.
 */
abstract class AbstractTwigExtension extends AbstractExtension
{
    use TranslatorTrait;
    
    /**
     * @var VariableApiInterface
     */
    protected $variableApi;
    
    /**
     * @var EntityDisplayHelper
     */
    protected $entityDisplayHelper;
    
    /**
     * @var WorkflowHelper
     */
    protected $workflowHelper;
    
    /**
     * @var ListEntriesHelper
     */
    protected $listHelper;
    
    public function __construct(
        TranslatorInterface $translator,
        VariableApiInterface $variableApi,
        EntityDisplayHelper $entityDisplayHelper,
        WorkflowHelper $workflowHelper,
        ListEntriesHelper $listHelper
    ) {
        $this->setTranslator($translator);
        $this->variableApi = $variableApi;
        $this->entityDisplayHelper = $entityDisplayHelper;
        $this->workflowHelper = $workflowHelper;
        $this->listHelper = $listHelper;
    }
    
    public function getFunctions()
    {
        return [
            new TwigFunction('zikulamultihookmodule_objectTypeSelector', [$this, 'getObjectTypeSelector']),
            new TwigFunction('zikulamultihookmodule_templateSelector', [$this, 'getTemplateSelector']),
        ];
    }
    
    public function getFilters()
    {
        return [
            new TwigFilter('zikulamultihookmodule_listEntry', [$this, 'getListEntry']),
            new TwigFilter('zikulamultihookmodule_formattedTitle', [$this, 'getFormattedEntityTitle']),
            new TwigFilter('zikulamultihookmodule_objectState', [$this, 'getObjectState'], ['is_safe' => ['html']]),
        ];
    }
    
    /**
     * The zikulamultihookmodule_objectState filter displays the name of a given object's workflow state.
     * Examples:
     *    {{ item.workflowState|zikulamultihookmodule_objectState }}        {# with visual feedback #}
     *    {{ item.workflowState|zikulamultihookmodule_objectState(false) }} {# no ui feedback #}.
     */
    public function getObjectState(string $state = 'initial', bool $uiFeedback = true): string
    {
        $stateInfo = $this->workflowHelper->getStateInfo($state);
    
        $result = $stateInfo['text'];
        if (true === $uiFeedback) {
            $result = '<span class="badge badge-' . $stateInfo['ui'] . '">' . $result . '</span>';
        }
    
        return $result;
    }
    
    /**
     * The zikulamultihookmodule_listEntry filter displays the name
     * or names for a given list item.
     * Example:
     *     {{ entity.listField|zikulamultihookmodule_listEntry('entityName', 'fieldName') }}.
     */
    public function getListEntry(
        string $value,
        string $objectType = '',
        string $fieldName = '',
        string $delimiter = ', '
    ): string {
        if ((empty($value) && '0' !== $value) || empty($objectType) || empty($fieldName)) {
            return $value;
        }
    
        return $this->listHelper->resolve($value, $objectType, $fieldName, $delimiter);
    }
    
    
    
    
    /**
     * The zikulamultihookmodule_formattedTitle filter outputs a formatted title for a given entity.
     * Example:
     *     {{ myPost|zikulamultihookmodule_formattedTitle }}.
     */
    public function getFormattedEntityTitle(EntityAccess $entity): string
    {
        return $this->entityDisplayHelper->getFormattedTitle($entity);
    }
}

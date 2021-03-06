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

namespace Zikula\MultiHookModule\HookProvider\Base;

use Symfony\Contracts\Translation\TranslatorInterface;
use Zikula\Bundle\HookBundle\Category\FilterHooksCategory;
use Zikula\Bundle\HookBundle\Hook\FilterHook;
use Zikula\Bundle\HookBundle\HookProviderInterface;

/**
 * Base class for filter hooks provider.
 */
abstract class AbstractFilterHooksProvider implements HookProviderInterface
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function getOwner(): string
    {
        return 'ZikulaMultiHookModule';
    }
    
    public function getCategory(): string
    {
        return FilterHooksCategory::NAME;
    }
    
    public function getTitle(): string
    {
        return $this->translator->trans('Multi hook filter hooks provider', [], 'hooks');
    }
    
    public function getAreaName(): string
    {
        return 'provider.zikulamultihookmodule.filter_hooks.multihook';
    }

    public function getProviderTypes(): array
    {
        return [
            FilterHooksCategory::TYPE_FILTER => ['applyFilter'],
        ];
    }

    /**
     * Filters the given data.
     */
    public function applyFilter(FilterHook $hook): void
    {
        $hook->setData(
            $hook->getData()
            . '<p>'
            . $this->translator->trans('This is a dummy addition by a generated filter provider.', [], 'hooks')
            . '</p>'
        );
    }
}

<?php

declare(strict_types=1);

/**
 * MultiHook.
 *
 * @copyright Zikula Team (Zikula)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Zikula Team <info@ziku.la>.
 * @link https://ziku.la
 * @version Generated by ModuleStudio 1.4.0 (https://modulestudio.de).
 */

namespace Zikula\MultiHookModule\HookSubscriber\Base;

use Zikula\Bundle\HookBundle\Category\FilterHooksCategory;
use Zikula\Bundle\HookBundle\HookSubscriberInterface;
use Zikula\Common\Translator\TranslatorInterface;

/**
 * Base class for filter hooks subscriber.
 */
abstract class AbstractEntryFilterHooksSubscriber implements HookSubscriberInterface
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
        return $this->translator->__('Entry filter hooks subscriber');
    }
    
    public function getAreaName(): string
    {
        return 'subscriber.zikulamultihookmodule.filter_hooks.entries';
    }

    public function getEvents(): array
    {
        return [
            FilterHooksCategory::TYPE_FILTER => 'zikulamultihookmodule.filter_hooks.entries.filter'
        ];
    }
}
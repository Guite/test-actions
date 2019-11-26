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

namespace Zikula\MultiHookModule\Base;

use Zikula\MultiHookModule\Listener\EntityLifecycleListener;
use Zikula\MultiHookModule\Menu\MenuBuilder;

/**
 * Events definition base class.
 */
abstract class AbstractMultiHookEvents
{
    /**
     * The zikulamultihookmodule.itemactionsmenu_pre_configure event is thrown before the item actions
     * menu is built in the menu builder.
     *
     * The event listener receives an
     * Zikula\MultiHookModule\Event\ConfigureItemActionsMenuEvent instance.
     *
     * @see MenuBuilder::createItemActionsMenu()
     * @var string
     */
    public const MENU_ITEMACTIONS_PRE_CONFIGURE = 'zikulamultihookmodule.itemactionsmenu_pre_configure';
    
    /**
     * The zikulamultihookmodule.itemactionsmenu_post_configure event is thrown after the item actions
     * menu has been built in the menu builder.
     *
     * The event listener receives an
     * Zikula\MultiHookModule\Event\ConfigureItemActionsMenuEvent instance.
     *
     * @see MenuBuilder::createItemActionsMenu()
     * @var string
     */
    public const MENU_ITEMACTIONS_POST_CONFIGURE = 'zikulamultihookmodule.itemactionsmenu_post_configure';
    /**
     * The zikulamultihookmodule.entry_post_load event is thrown when entries
     * are loaded from the database.
     *
     * The event listener receives an
     * Zikula\MultiHookModule\Event\FilterEntryEvent instance.
     *
     * @see EntityLifecycleListener::postLoad()
     * @var string
     */
    public const ENTRY_POST_LOAD = 'zikulamultihookmodule.entry_post_load';
    
    /**
     * The zikulamultihookmodule.entry_pre_persist event is thrown before a new entry
     * is created in the system.
     *
     * The event listener receives an
     * Zikula\MultiHookModule\Event\FilterEntryEvent instance.
     *
     * @see EntityLifecycleListener::prePersist()
     * @var string
     */
    public const ENTRY_PRE_PERSIST = 'zikulamultihookmodule.entry_pre_persist';
    
    /**
     * The zikulamultihookmodule.entry_post_persist event is thrown after a new entry
     * has been created in the system.
     *
     * The event listener receives an
     * Zikula\MultiHookModule\Event\FilterEntryEvent instance.
     *
     * @see EntityLifecycleListener::postPersist()
     * @var string
     */
    public const ENTRY_POST_PERSIST = 'zikulamultihookmodule.entry_post_persist';
    
    /**
     * The zikulamultihookmodule.entry_pre_remove event is thrown before an existing entry
     * is removed from the system.
     *
     * The event listener receives an
     * Zikula\MultiHookModule\Event\FilterEntryEvent instance.
     *
     * @see EntityLifecycleListener::preRemove()
     * @var string
     */
    public const ENTRY_PRE_REMOVE = 'zikulamultihookmodule.entry_pre_remove';
    
    /**
     * The zikulamultihookmodule.entry_post_remove event is thrown after an existing entry
     * has been removed from the system.
     *
     * The event listener receives an
     * Zikula\MultiHookModule\Event\FilterEntryEvent instance.
     *
     * @see EntityLifecycleListener::postRemove()
     * @var string
     */
    public const ENTRY_POST_REMOVE = 'zikulamultihookmodule.entry_post_remove';
    
    /**
     * The zikulamultihookmodule.entry_pre_update event is thrown before an existing entry
     * is updated in the system.
     *
     * The event listener receives an
     * Zikula\MultiHookModule\Event\FilterEntryEvent instance.
     *
     * @see EntityLifecycleListener::preUpdate()
     * @var string
     */
    public const ENTRY_PRE_UPDATE = 'zikulamultihookmodule.entry_pre_update';
    
    /**
     * The zikulamultihookmodule.entry_post_update event is thrown after an existing new entry
     * has been updated in the system.
     *
     * The event listener receives an
     * Zikula\MultiHookModule\Event\FilterEntryEvent instance.
     *
     * @see EntityLifecycleListener::postUpdate()
     * @var string
     */
    public const ENTRY_POST_UPDATE = 'zikulamultihookmodule.entry_post_update';
    
}
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

namespace Zikula\MultiHookModule\Event\Base;

use Zikula\MultiHookModule\Entity\EntryEntity;

/**
 * Event base class for filtering entry processing.
 */
abstract class AbstractEntryPrePersistEvent
{
    /**
     * @var EntryEntity Reference to treated entity instance
     */
    protected $entry;

    public function __construct(EntryEntity $entry)
    {
        $this->entry = $entry;
    }

    public function getEntry(): EntryEntity
    {
        return $this->entry;
    }
}

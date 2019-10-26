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

namespace Zikula\MultiHookModule\Collector;

use Zikula\Common\MultiHook\EntryProviderInterface;

/**
 * Entry provider collector implementation class.
 */
class EntryProviderCollector
{
    /**
     * List of service objects
     * @var array
     */
    private $providers;

    /**
     * @param EntryProviderInterface[] $providers
     */
    public function __construct(iterable $providers)
    {
        $this->providers = [];
        foreach ($providers as $provider) {
            $this->add($provider);
        }
    }

    /**
     * Adds an entry provider to the collection.
     */
    public function add(EntryProviderInterface $provider): void
    {
        $id = str_replace('\\', '_', get_class($provider));

        $this->providers[$id] = $provider;
    }

    /**
     * Returns an entry provider from the collection by service.id.
     */
    public function get(string $id): ?EntryProviderInterface
    {
        return $this->providers[$id] ?? null;
    }

    /**
     * Returns all providers in the collection.
     *
     * @return EntryProviderInterface[]
     */
    public function getAll(): iterable
    {
        $this->sortProviders();

        return $this->providers;
    }

    /**
     * Returns all active providers in the collection.
     *
     * @return EntryProviderInterface[]
     */
    public function getActive(): iterable
    {
        return array_filter($this->getAll(), static function(EntryProviderInterface $item) {
            return $item->isActive();
        });
    }

    /**
     * Sorts available providers by their title.
     */
    private function sortProviders(): void {
        $providers = $this->providers;
        usort($providers, static function(EntryProviderInterface $a, EntryProviderInterface $b) {
            return strcmp($a->getTitle(), $b->getTitle());
        });
        $this->providers = $providers;
    }
}

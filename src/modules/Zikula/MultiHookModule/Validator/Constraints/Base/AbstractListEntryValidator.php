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

namespace Zikula\MultiHookModule\Validator\Constraints\Base;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Zikula\Common\Translator\TranslatorInterface;
use Zikula\Common\Translator\TranslatorTrait;
use Zikula\MultiHookModule\Helper\ListEntriesHelper;

/**
 * List entry validator.
 */
abstract class AbstractListEntryValidator extends ConstraintValidator
{
    use TranslatorTrait;

    /**
     * @var ListEntriesHelper
     */
    protected $listEntriesHelper;

    public function __construct(TranslatorInterface $translator, ListEntriesHelper $listEntriesHelper)
    {
        $this->setTranslator($translator);
        $this->listEntriesHelper = $listEntriesHelper;
    }

    public function setTranslator(TranslatorInterface $translator): void
    {
        $this->translator = $translator;
    }

    public function validate($value, Constraint $constraint)
    {
        if (null === $value) {
            return;
        }

        if ('workflowState' === $constraint->propertyName && in_array($value, ['initial', 'deleted'], true)) {
            return;
    	}

        $listEntries = $this->listEntriesHelper->getEntries($constraint->entityName, $constraint->propertyName);
        $allowedValues = [];
        foreach ($listEntries as $entry) {
            $allowedValues[] = $entry['value'];
        }

        if (!$constraint->multiple) {
            // single-valued list
            if ('' !== $value && !in_array($value, $allowedValues/*, true*/)) {
                $this->context->buildViolation(
                    $this->__f('The value "%value%" is not allowed for the "%property%" property.', [
                        '%value%' => $value,
                        '%property%' => $constraint->propertyName
                    ])
                )->addViolation();
            }

            return;
        }

        // multi-values list
        $selected = explode('###', $value);
        foreach ($selected as $singleValue) {
            if ('' === $singleValue) {
                continue;
            }
            if (!in_array($singleValue, $allowedValues/*, true*/)) {
                $this->context->buildViolation(
                    $this->__f('The value "%value%" is not allowed for the "%property%" property.', [
                        '%value%' => $singleValue,
                        '%property%' => $constraint->propertyName
                    ])
                )->addViolation();
            }
        }

        $count = count($selected);

        if (null !== $constraint->min && $count < $constraint->min) {
            $this->context->buildViolation(
                $this->translator->transChoice('You must select at least "%limit%" choice.|You must select at least "%limit%" choices.', $count, [
                    '%limit%' => $constraint->min
                ], 'zikulamultihookmodule')
            )->addViolation();
        }
        if (null !== $constraint->max && $count > $constraint->max) {
            $this->context->buildViolation(
                $this->translator->transChoice('You must select at most "%limit%" choice.|You must select at most "%limit%" choices.', $count, [
                    '%limit%' => $constraint->max
                ], 'zikulamultihookmodule')
            )->addViolation();
        }
    }
}

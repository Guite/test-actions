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

namespace Zikula\MultiHookModule\Controller\Base;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Zikula\Bundle\CoreBundle\Controller\AbstractController;
use Psr\Log\LoggerInterface;
use Zikula\UsersModule\Api\ApiInterface\CurrentUserApiInterface;
use Zikula\MultiHookModule\Entity\Factory\EntityFactory;

/**
 * Ajax controller base class.
 */
abstract class AbstractAjaxController extends AbstractController
{
    
    /**
     * Changes a given flag (boolean field) by switching between true and false.
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     */
    public function toggleFlag(
        Request $request,
        LoggerInterface $logger,
        EntityFactory $entityFactory,
        CurrentUserApiInterface $currentUserApi
    ): JsonResponse {
        if (!$request->isXmlHttpRequest()) {
            return $this->json($this->trans('Only ajax access is allowed!'), Response::HTTP_BAD_REQUEST);
        }
        
        if (!$this->hasPermission('ZikulaMultiHookModule::Ajax', '::', ACCESS_EDIT)) {
            throw new AccessDeniedException();
        }
        
        $objectType = $request->request->getAlnum('ot', 'entry');
        $field = $request->request->getAlnum('field');
        $id = $request->request->getInt('id');
        
        if (
            0 === $id
            || ('entry' !== $objectType)
            || ('entry' === $objectType && !in_array($field, ['active'], true))
        ) {
            return $this->json($this->trans('Error: invalid input.'), JsonResponse::HTTP_BAD_REQUEST);
        }
        
        // select data from data source
        $repository = $entityFactory->getRepository($objectType);
        $entity = $repository->selectById($id, false);
        if (null === $entity) {
            return $this->json($this->trans('No such item.'), JsonResponse::HTTP_NOT_FOUND);
        }
        
        // toggle the flag
        $entity[$field] = !$entity[$field];
        
        // save entity back to database
        $entityFactory->getEntityManager()->flush();
        
        $logArgs = [
            'app' => 'ZikulaMultiHookModule',
            'user' => $currentUserApi->get('uname'),
            'field' => $field,
            'entity' => $objectType,
            'id' => $id,
        ];
        $logger->notice('{app}: User {user} toggled the {field} flag the {entity} with id {id}.', $logArgs);
        
        // return response
        return $this->json([
            'id' => $id,
            'state' => $entity[$field],
            'message' => $this->trans('The setting has been successfully changed.'),
        ]);
    }
}

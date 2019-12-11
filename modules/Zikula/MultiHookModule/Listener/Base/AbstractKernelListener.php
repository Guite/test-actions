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
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Event handler base class for Symfony kernel events.
 */
abstract class AbstractKernelListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST        => ['onRequest', 5],
            KernelEvents::CONTROLLER     => ['onController', 5],
            KernelEvents::VIEW           => ['onView', 5],
            KernelEvents::RESPONSE       => ['onResponse', 5],
            KernelEvents::FINISH_REQUEST => ['onFinishRequest', 5],
            KernelEvents::TERMINATE      => ['onTerminate', 5],
            KernelEvents::EXCEPTION      => ['onException', 5]
        ];
    }
    
    /**
     * Listener for the `kernel.request` event.
     *
     * Occurs after the request handling has started.
     *
     * If possible you can return a Response object directly (for example showing a "maintenance mode" page).
     *     `$event->setResponse(new Response('This site is currently not active!'));`
     *
     * The first listener returning a response stops event propagation.
     *
     * Also you can initialise variables and inject information into the request attributes.
     *     `$testMessage = 'Hello from multi hook app';
     *     $event->getRequest()->attributes->set('ZikulaMultiHookModule_test', $testMessage);`
     *
     * Example from Symfony: the RouterListener determines controller and information about arguments.
     *
     * You can access general data available in the event.
     *
     * The event name:
     *     `echo 'Event: ' . $event->getName();`
     *
     * The current request's type: `MASTER_REQUEST` or `SUB_REQUEST`.
     * If a listener should only be active for the master request,
     * be sure to check that at the beginning of your method.
     *     `if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST) {
     *         return;
     *     }`
     *
     * The kernel instance handling the current request:
     *     `$kernel = $event->getKernel();`
     *
     * The currently handled request:
     *     `$request = $event->getRequest();`
     *
     */
    public function onRequest(GetResponseEvent $event): void
    {
    }
    
    /**
     * Listener for the `kernel.controller` event.
     *
     * Occurs after routing has been done and the controller has been selected.
     *
     * You can initialise things requiring the controller and/or routing information.
     *
     * Also you can change the controller before it is executed to any PHP callable.
     *     `$event->setController($controller);`
     *
     * You may check for certain controller types (or implemented interface types!).
     * For example imagine an interface named SpecialFlaggedController.
     * The passed $controller passed can be either a class or a Closure.
     * If it is a class, it comes in array format.
     *
     *     `if (!is_array($controller)) {
     *         return;
     *     }
     *
     *     if ($controller[0] instanceof SpecialFlaggedController) {
     *         ...
     *     }`
     *
     * Example from Symfony: the ParamConverterListener performs reflection and type conversion.
     *
     * You can access general data available in the event.
     *
     * The event name:
     *     `echo 'Event: ' . $event->getName();`
     *
     * The current request's type: `MASTER_REQUEST` or `SUB_REQUEST`.
     * If a listener should only be active for the master request,
     * be sure to check that at the beginning of your method.
     *     `if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST) {
     *         return;
     *     }`
     *
     * The kernel instance handling the current request:
     *     `$kernel = $event->getKernel();`
     *
     * The currently handled request:
     *     `$request = $event->getRequest();`
     *
     */
    public function onController(FilterControllerEvent $event): void
    {
    }
    
    /**
     * Listener for the `kernel.view` event.
     *
     * Occurs only if the controller did not return a Response object.
     *
     * You can convert the controller's return value into a Response object.
     * This is useful for own view layers.
     *     `$val = $event->getControllerResult();
     *     $response = new Response();`
     *     ... customise the response using the return value
     *     `$event->setResponse($response);`
     *
     * The first listener returning a response stops event propagation.
     *
     * Example from Symfony: TemplateListener renders Twig templates with returned arrays.
     *
     * You can access general data available in the event.
     *
     * The event name:
     *     `echo 'Event: ' . $event->getName();`
     *
     * The current request's type: `MASTER_REQUEST` or `SUB_REQUEST`.
     * If a listener should only be active for the master request,
     * be sure to check that at the beginning of your method.
     *     `if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST) {
     *         return;
     *     }`
     *
     * The kernel instance handling the current request:
     *     `$kernel = $event->getKernel();`
     *
     * The currently handled request:
     *     `$request = $event->getRequest();`
     *
     */
    public function onView(GetResponseForControllerResultEvent $event): void
    {
    }
    
    /**
     * Listener for the `kernel.response` event.
     *
     * Occurs after a response has been created and returned to the kernel.
     *
     * You can modify or replace the response object, including http headers,
     * cookies, and so on. Of course you can also amend the actual content by
     * for example injecting some custom JavaScript code.
     *
     * Of course you can use request attributes you set in `onKernelRequest`
     * or `onKernelController` or other events happened before.
     *     `$response = $event->getResponse();`
     *     ... modify the response object
     *     `$testMessage = $event->getRequest()->attributes->get('ZikulaMultiHookModule_test');`
     *     now `$testMessage` should be: `'Hello from multi hook app'`
     *
     * Examples from Symfony:
     *    - ContextListener: serialises user data into session for next request
     *    - WebDebugToolbarListener: injects the web debug toolbar
     *    - ResponseListener: updates the content type according to the request format
     *
     * You can access general data available in the event.
     *
     * The event name:
     *     `echo 'Event: ' . $event->getName();`
     *
     * The current request's type: `MASTER_REQUEST` or `SUB_REQUEST`.
     * If a listener should only be active for the master request,
     * be sure to check that at the beginning of your method.
     *     `if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST) {
     *         return;
     *     }`
     *
     * The kernel instance handling the current request:
     *     `$kernel = $event->getKernel();`
     *
     * The currently handled request:
     *     `$request = $event->getRequest();`
     *
     */
    public function onResponse(FilterResponseEvent $event): void
    {
    }
    
    /**
     * Listener for the `kernel.finish_request` event.
     *
     * Occurs after processing a request has been completed.
     * Called after a normal response as well as after an exception was thrown.
     *
     * You can cleanup things here which are not directly related to the response.
     *
     * You can access general data available in the event.
     *
     * The event name:
     *     `echo 'Event: ' . $event->getName();`
     *
     * The current request's type: `MASTER_REQUEST` or `SUB_REQUEST`.
     * If a listener should only be active for the master request,
     * be sure to check that at the beginning of your method.
     *     `if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST) {
     *         return;
     *     }`
     *
     * The kernel instance handling the current request:
     *     `$kernel = $event->getKernel();`
     *
     * The currently handled request:
     *     `$request = $event->getRequest();`
     *
     */
    public function onFinishRequest(FinishRequestEvent $event): void
    {
    }
    
    /**
     * Listener for the `kernel.terminate` event.
     *
     * Occurs before the system is shutted down.
     *
     * You can perform any bigger tasks which can be delayed until the Response
     * has been served to the client. One example is sending some spooled emails.
     *
     * Example from Symfony: SwiftmailerBundle with memory spooling activates an
     * EmailSenderListener which delivers emails created during the request.
     *
     * You can access general data available in the event.
     *
     * The event name:
     *     `echo 'Event: ' . $event->getName();`
     *
     * The current request's type: `MASTER_REQUEST` or `SUB_REQUEST`.
     * If a listener should only be active for the master request,
     * be sure to check that at the beginning of your method.
     *     `if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST) {
     *         return;
     *     }`
     *
     * The kernel instance handling the current request:
     *     `$kernel = $event->getKernel();`
     *
     * The currently handled request:
     *     `$request = $event->getRequest();`
     *
     */
    public function onTerminate(PostResponseEvent $event): void
    {
    }
    
    /**
     * Listener for the `kernel.exception` event.
     *
     * Occurs whenever an exception is thrown. Handles (different types
     * of) exceptions and creates a fitting Response object for them.
     *
     * You can inject custom error handling for specific error types.
     *
     *     `$exception = $event->getException();
     *     if ($exception instanceof MySpecialException || $exception instanceof MySpecialExceptionInterface) {
     *         $response = new Response();
     *         $message = sprintf('multi hook App Error says: %s with code: %s', $exception->getMessage(), $exception->getCode());
     *         $response->setContent($message);`
     *
     *         HttpExceptionInterface is a special type of exception that holds the status code and header details
     *         `if ($exception instanceof HttpExceptionInterface) {
     *             $response->setStatusCode($exception->getStatusCode());
     *             $response->headers->replace($exception->getHeaders());
     *         } else {
     *             $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
     *         }
     *
     *         $event->setResponse($response);`
     *     }
     *
     * You can alternatively set a new Exception.
     *     `$exception = new \Exception('Some special exception');
     *     $event->setException($exception);`
     *
     * You can access general data available in the event.
     *
     * The event name:
     *     `echo 'Event: ' . $event->getName();`
     *
     * The current request's type: `MASTER_REQUEST` or `SUB_REQUEST`.
     * If a listener should only be active for the master request,
     * be sure to check that at the beginning of your method.
     *     `if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST) {
     *         return;
     *     }`
     *
     * The kernel instance handling the current request:
     *     `$kernel = $event->getKernel();`
     *
     * The currently handled request:
     *     `$request = $event->getRequest();`
     *
     */
    public function onException(GetResponseForExceptionEvent $event): void
    {
    }
}

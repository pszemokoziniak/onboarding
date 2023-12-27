<?php

// Multi-instance app name
defined('APPLICATION_NAME')
    || define('APPLICATION_NAME', getenv('SUBDOMAIN') ?: false);

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../../application'));

defined('ROOT_PATH')
    || define('ROOT_PATH', realpath(dirname(__FILE__) . '/..../'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', getenv('APPLICATION_ENV'));

// Define the path to the public
defined('PUBLIC_PATH')
    || define('PUBLIC_PATH', realpath(dirname(__FILE__)) . '/..');

// public files location
defined('FILE_PATH')
    || define('FILE_PATH', APPLICATION_NAME ? PUBLIC_PATH . '/files/apps/' . APPLICATION_NAME : PUBLIC_PATH . '/files');

require_once '../../vendor/autoload.php';
//Add library directory to the include_path
set_include_path(implode(PATH_SEPARATOR, array(
    dirname(dirname(__FILE__)) . '/../library',
    get_include_path(),
)));
// -----------------------------------------------------------------------------
// --- ERROR HANDLER
// -----------------------------------------------------------------------------
error_reporting(((E_ALL) & ~(E_NOTICE | E_STRICT | E_WARNING | E_DEPRECATED | E_USER_DEPRECATED | E_USER_NOTICE | E_USER_WARNING)));
ini_set('display_errors', 1);

// @codingStandardsIgnoreLine
class BootstrapErrorHandler
{
    public $isAppBootstrap = false;

    public function handle($errno, $errstr = null, $errfile = null, $errline = null, $errcontext = null)
    {
        $event = array('timestamp' => date('c'));
        $config = Zend_Controller_Front::getInstance()->getParam('config');
        if ($errno instanceof \Throwable) {
            $event['message']   = str_replace($config->database->params->password, '', $errno->getMessage());
            $event['errno']     = $errno->getCode();
            $event['file']      = $errno->getFile();
            $event['line']      = $errno->getLine();
            $event['context']   = str_replace($config->database->params->password, '', $errno . '');
            $event['priorityName'] = $errno->getCode();
            $event['priority']  = $errno->getCode();
        } else if (false == $this->isAppBootstrap) {
            $event['message']   = str_replace($config->database->params->password, '', $errstr);
            $event['errno']     = $errno;
            $event['file']      = $errfile;
            $event['line']      = $errline;
            $event['context']   = str_replace($config->database->params->password, '', $errcontext);
            $event['priorityName'] = $errno;
            $event['priority']  = $errno;
        } else {
            return;
        }

        $filePath = APPLICATION_PATH . '/data/log/';
        $filePath .= date('d-m-Y') . '.log';
        require_once 'BV/Log/Writer/Stream.php';
        $stream = new BV_Log_Writer_Stream($filePath, 'a+');
        $stream->write($event);

        if (false == $this->isAppBootstrap) {
            $stream->shutdown();
            http_response_code(500);
            echo file_get_contents('error_service_unavailable.html', true);
            die;
        }
    }
}
// $bootstrapErrorHandler = new BootstrapErrorHandler();
// set_error_handler(array($bootstrapErrorHandler, "handle"), ((E_ALL) & ~(E_NOTICE | E_STRICT | E_WARNING | E_DEPRECATED | E_USER_DEPRECATED | E_USER_NOTICE | E_USER_WARNING)));
// set_exception_handler(array($bootstrapErrorHandler, "handle"));

// $bootstrapErrorHandler->isAppBootstrap = true;
// -----------------------------------------------------------------------------
// --- ERROR HANDLER
// -----------------------------------------------------------------------------

// Zend_Application
require_once '../../library/BV/Application.php';
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new BV_Application(APPLICATION_ENV, [
    'ini' => [
        APPLICATION_PATH . '/configs/application.ini',
        APPLICATION_PATH . '/configs/clientzone.ini'
    ]
], true);

define('IS_CLIENTZONE_BOOTSTRAP', true);
$app = $application->bootstrap();
$app->getBootstrap()->addResource('clientzone');
$pathData = explode('?', $_SERVER['REQUEST_URI']);
$module = explode('/', trim($_SERVER['REQUEST_URI'], '/'))[0];

$transactionContext = new \Sentry\Tracing\TransactionContext();
$transactionContext->setName($module);
$transactionContext->setOp($pathData[0]);
$transaction = \Sentry\startTransaction($transactionContext);

$app->run();

$transaction->finish();

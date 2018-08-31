<?php
namespace KPrzemyslaw;

use KPrzemyslaw\Exceptions;

/**
 * Class KPController
 * @package KPrzemyslaw
 */
class KPController extends KPControllerAbstract
{
    const CONTROLLER_ACTION_TEMPLATE = '\KPrzemyslaw\Actions\KP%sController';

    /** @var KPRequest $request */
    private $request = null;

    /** @var KPResponse $response */
    private $response = null;

    /** @var \KPrzemyslaw\Actions\KPControllerInterface $action */
    protected $action = null;

    /** @var KPSession $session */
    private $session = null;

    /** @var array $config */
    private $config = [];

    /** @var \PDO $dbResource */
    private $dbResource = null;

    /** @var KPUser $user */
    private $user = null;

    private $result = null;

    /**
     * KPControllerAction constructor.
     */
    public function __construct()
    {
    }

    private function prepareRequest()
    {
        $this->request = new KPRequest();
        $this->request->parseUrl();
        //$this->request->startDataSecurity();
    }

    private function prepareResponse()
    {
        $this->response = new KPResponse();
    }

    private function prepareSession()
    {
        $this->session = new KPSession();
    }

    private function prepareUser()
    {
        $this->user = new KPUser(
            $this->dbResource,
            $this->session
        );
    }

    public function importSettings()
    {
        $confJsonFile = new KPConfigure();
        $confJsonFile->loadData();

        $this->config = array_merge(
            $this->config,
            ['db' => $confJsonFile->getData('db')]
        );
    }

    /**
     * http://php.net/manual/en/ref.pdo-mysql.php
     */
    public function initializePdo()
    {
        try {
            $this->dbResource = new \PDO(
                sprintf(
                    'mysql:host=%s;dbname=%s;port=%d',
                    $this->config['db']['host'],
                    $this->config['db']['dbname'],
                    $this->config['db']['port']
                ),
                $this->config['db']['username'],
                $this->config['db']['passwd']
            );
            $this->dbResource->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->dbResource->exec('SET CHARACTER SET utf8');
            $this->dbResource->exec('SET NAMES utf8');
        } catch(\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function prepareAction()
    {
        $this->prepareRequest();
        $this->prepareResponse();
        $this->prepareSession();
        $this->initializePdo();
        $this->prepareUser();

        $classActionName = sprintf(
            'Bundles\%s\%s\%s',
            $this->request->bundleName,
            $this->request->controllerName,
            $this->request->actionName
        );

        $this->action = new $classActionName(
            $this->request,
            $this->session,
            $this->dbResource,
            $this->user
        );
    }

    public function run()
    {
        $this->result = $this->action->run();
    }

    public function show()
    {
        echo json_encode($this->result);
    }
}

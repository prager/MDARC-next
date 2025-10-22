<?php

namespace App\Controllers;

use App\Models\Login_model;
use App\Models\Staff_model;
use App\Models\User_model;
use App\Models\Master_model;
use App\Models\Data_model;
use App\Models\TMembers_model;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = ['url'];

    /** Preloaded models (nullable to avoid uninitialized typed properties) */
    protected ?Login_model  $login_mod  = null;
    protected ?Staff_model  $staff_mod  = null;
    protected ?User_model  $user_mod  = null;
    protected ?Master_model  $master_mod  = null;
    protected ?Data_model  $data_mod  = null;
    protected ?TMembers_model $mems_mod = null;

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        $this->login_mod  = model(Login_model::class);
        $this->staff_mod  = model(Staff_model::class);
        $this->user_mod  = model(User_model::class);
        $this->master_mod  = model(Master_model::class);
        $this->data_mod  = model(Data_model::class);
        $this->mems_mod = model(TMembers_model::class);

        // E.g.: $this->session = service('session');
    }
}

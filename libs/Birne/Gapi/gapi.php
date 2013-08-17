<?php


use Nette\InvalidStateException;
use Nette\Utils\Strings;
use Nette\Application\UI;

namespace Birne\Gapi;

require_once 'src/Google_Client.php';
require_once 'src/contrib/Google_AnalyticsService.php';


class Gapi extends \Nette\Application\UI\Control {
        
    private $token;
    private $code;
    private $analytics;
    
     /** @persistent */
    public $lang;

    /** @var NetteTranslator\Gettext */
    protected $translator;

    public function setParent(\Nette\ComponentModel\IContainer $parent = NULL, $name = NULL) {
        parent::setParent($parent, $name);
    }

    

    public function setGAPI($gapisession, $code = NULL) {
            
           // unset($_SESSION['access_token']);
            $this->token = $gapisession;

            $client = new \Google_Client();
            $client->setApplicationName('Birnex');

            // Visit //code.google.com/apis/console?api=analytics to generate your
            // client id, client secret, and to register your redirect uri.
            $client->setClientId('972136424126-sapavicddib7bqhljp04gftm48a36m11.apps.googleusercontent.com');
            $client->setClientSecret('gxkfZbDBM4cIUFPAKqhimZaZ');
            $client->setRedirectUri('http://localhost/platon/www/smart-panel/modules');
            $client->setDeveloperKey('AIzaSyAICFV8fFpWser_tTd2P5pSA0lzk_zOrps');
            $client->setScopes(array('https://www.googleapis.com/auth/analytics.readonly'));

            // Magic. Returns objects from the Analytics Service instead of associative arrays.
            $client->setUseObjects(true);
            
            
            
            if (isset($_GET['code'])) {
              $this->code = $_GET['code'];
              $client->authenticate();
              $this->token = $client->getAccessToken();
              $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
             // header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
            } 
            
            if (isset($this->token)) {
              $client->setAccessToken($this->token);

            }
            
            if (!$client->getAccessToken()) {
              $authUrl = $client->createAuthUrl();
              print "<a class='btn btn-primary' href='$authUrl'>Activate Module</a>";

            } 
            
                else {

                 
                $this->analytics = new \Google_AnalyticsService($client);
                
            }
            
        }
        
        public function getAnalytics() {
            return $this->analytics;
        }
        
        public function getGapiParam() {
            return array($this->token, $this->code);
        }

        public function respond($params) {
                // $id = $this->getFirstprofileId($this->analytics);
                $result = $this->getResults($this->analytics, $params);

                return $this->printResults($result);
        }

        public function getFirstprofileId(&$analytics) {
          $accounts = $analytics->management_accounts->listManagementAccounts();

          if (count($accounts->getItems()) > 0) {
            $items = $accounts->getItems();
            $firstAccountId = $items[1]->getId();

            $webproperties = $analytics->management_webproperties
                ->listManagementWebproperties($firstAccountId);

            if (count($webproperties->getItems()) > 0) {
              $items = $webproperties->getItems();
              $firstWebpropertyId = $items[0]->getId();

              $profiles = $analytics->management_profiles
                  ->listManagementProfiles($firstAccountId, $firstWebpropertyId);

              if (count($profiles->getItems()) > 0) {
                $items = $profiles->getItems();
                
                return $items[0]->getId();

              } else {
                throw new Exception('No profiles found for this user.');
              }
            } else {
              throw new Exception('No webproperties found for this user.');
            }
          } else {
            throw new Exception('No accounts found for this user.');
          }
        }

        public function getResults($analytics, $params) {

        return $analytics->data_ga->get(
               $params[0],
               $params[1],
               $params[2],
               $params[3],
               $params[4]);


        }

        public function printResults(&$results) {
          if (count($results->getRows()) > 0) {
            $profileName = $results->getProfileInfo()->getProfileName();
            $rows = $results->getRows();
            $visits = $rows[0][0];

            return $rows;

          } else {
            return '<p>No results found.</p>';
          }
        }

}
<?php


namespace Birne\Gapi;

require_once 'src/Google_Client.php';
require_once 'src/contrib/Google_AnalyticsService.php';


class Gapi {
        
    private $token;
    private $analytics;

    public function __construct($session) {
            
            
            $this->token = $session;


            $client = new \Google_Client();
            $client->setApplicationName('Hello Analytics API Sample');

            // Visit //code.google.com/apis/console?api=analytics to generate your
            // client id, client secret, and to register your redirect uri.
            $client->setClientId('972136424126-sapavicddib7bqhljp04gftm48a36m11.apps.googleusercontent.com');
            $client->setClientSecret('gxkfZbDBM4cIUFPAKqhimZaZ');
            $client->setRedirectUri('http://localhost/');
            $client->setDeveloperKey('AIzaSyAICFV8fFpWser_tTd2P5pSA0lzk_zOrps');
            $client->setScopes(array('https://www.googleapis.com/auth/analytics.readonly'));

            // Magic. Returns objects from the Analytics Service instead of associative arrays.
            $client->setUseObjects(true);

            if (isset($_GET['code'])) {
              $client->authenticate();
              $this->token = $client->getAccessToken();
              $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
              header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
            }

          
            
            if (isset($this->token)) {
              $client->setAccessToken($this->token);
            }


            
            
            if (!$client->getAccessToken()) {
              $authUrl = $client->createAuthUrl();
              print "<a class='login' href='$authUrl'>Connect Me!</a>";

            } 
            
                else {

                 
                $this->analytics = new \Google_AnalyticsService($client);
            }
            
        }

        protected function runMainDemo() {
          try {

            // Step 2. Get the user's first profile ID.
            $profileId = getFirstProfileId($this->analytics);

            if (isset($profileId)) {

              // Step 3. Query the Core Reporting API.
              $results = getResults($this->analytics, $profileId);

              // Step 4. Output the results.
              printResults($results);
            }

          } catch (apiServiceException $e) {
            // Error from the API.
            print 'There was an API error : ' . $e->getCode() . ' : ' . $e->getMessage();

          } catch (Exception $e) {
            print 'There wan a general error : ' . $e->getMessage();
          }
        }

        function getFirstprofileId(&$analytics) {
          $accounts = $this->analytics->management_accounts->listManagementAccounts();

          if (count($accounts->getItems()) > 0) {
            $items = $accounts->getItems();
            $firstAccountId = $items[3]->getId();

            $webproperties = $this->analytics->management_webproperties
                ->listManagementWebproperties($firstAccountId);

            if (count($webproperties->getItems()) > 0) {
              $items = $webproperties->getItems();
              $firstWebpropertyId = $items[0]->getId();

              $profiles = $this->analytics->management_profiles
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

        function getResults($analytics, $profileId) {

            $optParams = array(
                'dimensions' => 'ga:source',
                'max-results' => '100');


            $metrics = "ga:visits";

            return $this->analytics->data_ga->get(
               'ga:' . $profileId,
               '2012-03-03',
               '2013-03-03',
               $metrics,
                    $optParams);


        }

        function printResults(&$results) {
          if (count($results->getRows()) > 0) {
            $profileName = $results->getProfileInfo()->getProfileName();
            $rows = $results->getRows();
            $visits = $rows[0][0];

            print "<p>First profile found: $profileName</p>";
            print "<p>Total visits: $visits</p>";

          } else {
            print '<p>No results found.</p>';
          }
        }

}
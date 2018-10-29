<?php
namespace App\Classes;

use Illuminate\Support\Facades\Log;

class ZohoCrmConnect
{
    protected $zoho_account_client;
    protected $zoho_crm_client;

    public function __construct()
    {
        $this->connect();
    }

    private function connect()
    {
        $this->zoho_account_client = new \GuzzleHttp\Client([
            'base_uri' => config('zoho.ZOHO_ACCOUNT_BASE_URL'),
            'timeout' => 0,
        ]);

        $this->zoho_crm_client = new \GuzzleHttp\Client([
            'base_uri' => config('zoho.ZOHO_CRM_BASE_URL'),
            'timeout' => 0,
        ]);
    }

    public function getAccessToken()
    {
        $session_file_path = config('zoho.ZOHO_SESSION_FILE');
        $fsize = file_exists($session_file_path) ? filesize($session_file_path) : 0;

        $f_handle = fopen($session_file_path, 'r');
        $content = fgets($f_handle);
        fclose($f_handle);

        $session = strlen($content) > 0 ? json_decode($content) : null;

        $is_expired = $session != null ? (time() >= $session->expired) : true;

        Log::info('SIZE: '. var_export($fsize, true));

        Log::info('SESSION: '. var_export($content, true));

        $options = [
            'http_errors' => true,
            'query' => [
                'refresh_token' => config('zoho.ZOHO_APP_REFRESH_CODE'),
                'client_id' => config('zoho.ZOHO_APP_CLIENT_ID'),
                'client_secret' => config('zoho.ZOHO_APP_CLIENT_SECRET'),
                'grant_type' => 'refresh_token',
            ],
        ];

        try {
            if ($session == null || $is_expired) {
                $session = new \stdClass;

                $response = $this->zoho_account_client->request('POST', '/oauth/v2/token', $options);
                $status_code = $response->getStatusCode();
                $retry = (int) config('zoho.ZOHO_API_GET_ACCESS_TOKEN_RETRY_ATTEMPT');
                $retry_count = 0;
                $obj = null;

                while ($status_code != 200 || $retry_count < $retry) {
                    $retry_count++;
                    $response = $this->zoho_account_client->request('POST', '/oauth/v2/token', $options);
                    $status_code = $response->getStatusCode();
                    $obj = json_decode($response->getBody());
                    if ($status_code == 200 && !property_exists($obj, 'error')) {
                        $retry_count = $retry;
                    }
                    
                    Log::info('Get access token attempt: '. $retry_count. "\n". 'Status code: '. $status_code. "\n". ' - OBJ: ' . var_export($obj, true));
                }

                $session->access_token = $obj->access_token;
                $session->expired = time() + $obj->expires_in;

                Log::info('New session '. $session->access_token);
                Log::info('Expired at '. date('Y-m-d H:i:s', $session->expired));

                $f_handle = fopen($session_file_path, 'w');
                fwrite($f_handle, json_encode($session));
                fclose($f_handle);

                return $status_code == 200 ? $obj : false;
            }
            else {
                Log::info('From session '. $session->access_token);
                Log::info('Expired at '. date('Y-m-d H:i:s', $session->expired));
                return $session;
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return false;
        }

    }

    public function getAllRecords($module)
    {
        try {
            $records = [];
            if ($module !== '') {
                $uri = '/crm/v2/' . $module . '?page=%d&per_page=%d';
                $access_token = $this->getAccessToken();
                if ($access_token == false || !is_object($access_token) || !property_exists($access_token, 'access_token')) {
                    Log::error(var_export($access_token, true));
                    return false;
                }

                $options = [
                    'http_errors' => true,
                    'headers' => [
                        'Authorization' => 'Zoho-oauthtoken ' . $access_token->access_token,
                    ],
                ];

                $rec_per_page = 200;
                $page = 1;
                $record_count = $rec_per_page;

                while ($record_count <= $rec_per_page && $record_count > 0) {
                    $endpoint = sprintf($uri, $page, $rec_per_page);
                    $response = $this->zoho_crm_client->request('GET', $endpoint, $options);
                    $page++;
                    if ($response->getStatusCode() == 200) {
                        $data = json_decode($response->getBody());
                        $record_count = isset($data->data) ? count($data->data) : 0;
                        $records = $record_count > 0 ? array_merge($records, $data->data) : $records;
                        usleep(500);
                    } else if ($response->getStatusCode() == 204) {
                        break;
                    } else {
                        return false;
                    }
                }

                return $records;
            } else {
                return false;
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return false;
        }

    }

    public function getRecordById($module, $id)
    {
        $record = [];
        if ($module !== '' && $id !== '') {
            $uri = '/crm/v2/' . $module . '/' . $id;
            $access_token = $this->getAccessToken();
            if ($access_token == false || !is_object($access_token) || !property_exists($access_token, 'access_token')) {
                Log::error(var_export($access_token, true));
                return false;
            }

            $options = [
                'http_errors' => true,
                'headers' => [
                    'Authorization' => 'Zoho-oauthtoken ' . $access_token->access_token,
                ],
            ];

            $response = $this->zoho_crm_client->request('GET', $uri, $options);
            if ($response->getStatusCode() == 200) {
                $data = json_decode($response->getBody());
                $record = $data->data[0];
                return $record;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function search($module, $field, $value)
    {
        $records = [];
        if ($module !== '' && $field !== '') {
            $uri = '/crm/v2/' . $module . '/search';
            $access_token = $this->getAccessToken();

            $options = [
                'http_errors' => true,
                'query' => [
                    $field => $value,
                ],
                'headers' => [
                    'Authorization' => 'Zoho-oauthtoken ' . $access_token->access_token,
                ],
            ];

            $response = $this->zoho_crm_client->request('GET', $uri, $options);

            if ($response->getStatusCode() == 200) {

                $data = json_decode($response->getBody());
                $records = $data->data;
                Log::info(var_export($records, true));
                return $records;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function searchRecordByEmail($module, $email)
    {
        return $this->search($module, 'email', $email);
    }

    public function searchRecordByPhone($module, $phone)
    {
        return $this->search($module, 'phone', $phone);
    }

    public function upsertRecord($module, $data)
    {
        try {
            if ($module !== '' && $data !== null) {
                $uri = '/crm/v2/' . $module;
                $access_token = $this->getAccessToken();

                if ($access_token == false || !is_object($access_token) || !property_exists($access_token, 'access_token')) {
                    Log::error(var_export($access_token, true));
                    return false;
                }

                if (count($data['data']) > 100) {
                    $error = 'data too large';
                    throw new Exception($error);
                    return false;
                }

                $options = [
                    'http_errors' => true,
                    'json' => $data,
                    'headers' => [
                        'Authorization' => 'Zoho-oauthtoken ' . $access_token->access_token,
                    ],
                ];

                $method = isset($data['data'][0]['id']) ? 'PUT' : 'POST';
                $response = $this->zoho_crm_client->request($method, $uri, $options);

                if ($response->getStatusCode() == 201) {
                    $data = json_decode($response->getBody());
                    $record = $data->data[0];
                    return $record;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            Log::debug('DATA: ' . var_export($data, true));
            return false;
        }
    }

    public function deleteRecord($module, $IDs = [])
    {
        try {
            if ($module !== '' && is_array($IDs)) {
                $params = '?ids=';
                if (count($IDs)) {
                    $params .= implode(',', $IDs);
                }
                $uri = '/crm/v2/' . $module . $params;
                $access_token = $this->getAccessToken();

                $options = [
                    'http_errors' => true,
                    'json' => $data,
                    'headers' => [
                        'Authorization' => 'Zoho-oauthtoken ' . $access_token->access_token,
                    ],
                ];

                $response = $this->zoho_crm_client->request('DELETE', $uri, $options);
                return $response->getStatusCode();
            }

        } catch (Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }
}

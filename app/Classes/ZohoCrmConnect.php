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
                }

                $session->access_token = $obj->access_token;
                $session->expired = time() + $obj->expires_in_sec;

                $f_handle = fopen($session_file_path, 'w');
                fwrite($f_handle, json_encode($session));
                fclose($f_handle);

                return $status_code == 200 ? $obj : false;
            } else {
                Log::info('From session ' . $session->access_token);
                Log::info('Expired at ' . date('Y-m-d H:i:s', $session->expired));
                return $session;
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    public function getAllRecords($module, $fields = '', $page_limit = 0)
    {
        try {
            $records = [];
            if ($module !== '') {
                $uri = '/crm/v2/' . $module . '?page=%d&per_page=%d';
                $uri .= $fields != '' ? '&fields=' . $fields : '';
                $access_token = $this->getAccessToken();
                if ($access_token == false || !is_object($access_token) || !property_exists($access_token, 'access_token')) {
                    Log::error(var_export($access_token, true));
                    return false;
                }

                $options = [
                    'http_errors' => true,
                    'headers' => [
                        'Authorization' => 'Zoho-oauthtoken ' . $access_token->access_token,
                        'Content-Type' => 'application/json',
                    ],
                ];

                $rec_per_page = config('zoho.ZOHO_API_MAX_RECORDS_PER_PAGE');
                $page = 1;
                $record_count = $rec_per_page;

                while ($record_count <= $rec_per_page && $record_count > 0) {

                    $endpoint = sprintf($uri, $page, $rec_per_page);
                    $response = $this->zoho_crm_client->request('GET', $endpoint, $options);

                    if ($response->getStatusCode() == 200) {
                        $data = json_decode($response->getBody());
                        $record_count = isset($data->data) ? count($data->data) : 0;
                        $records = $record_count > 0 ? array_merge($records, $data->data) : $records;
                        usleep(5000);
                    } else if ($response->getStatusCode() == 204) {
                        break;
                    } else {
                        return false;
                    }

                    $page++;
                    if ($page_limit > 0 && $page >= $page_limit) {
                        break;
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

    public function search($module, $field = '', $value = '', $criteria = '')
    {
        $page_limit = 0;
        try {
            $records = [];
            if ($module !== '') {
                $uri = '/crm/v2/' . $module . '/search?page=%d&per_page=%d';
                $uri .= $criteria != '' ? '&criteria=' . $criteria : '';
                $uri .= $field != '' ? '&' . $field . '=' . $value : '';

                $access_token = $this->getAccessToken();
                if ($access_token == false || !is_object($access_token) || !property_exists($access_token, 'access_token')) {
                    Log::error(var_export($access_token, true));
                    return false;
                }

                $options = [
                    'http_errors' => true,
                    'headers' => [
                        'Authorization' => 'Zoho-oauthtoken ' . $access_token->access_token,
                        'Content-Type' => 'application/json',
                    ],
                ];

                $rec_per_page = config('zoho.ZOHO_API_MAX_RECORDS_PER_PAGE');
                $page = 1;
                $record_count = $rec_per_page;

                while ($record_count <= $rec_per_page && $record_count > 0) {

                    $endpoint = sprintf($uri, $page, $rec_per_page);
                    $response = $this->zoho_crm_client->request('GET', $endpoint, $options);

                    if ($response->getStatusCode() == 200) {
                        $data = json_decode($response->getBody());
                        $record_count = isset($data->data) ? count($data->data) : 0;
                        $records = $record_count > 0 ? array_merge($records, $data->data) : $records;
                        usleep(5000);
                    } else if ($response->getStatusCode() == 204) {
                        break;
                    } else {
                        return false;
                    }

                    $page++;
                    if ($page_limit > 0 && $page >= $page_limit) {
                        break;
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
                $uri = '/crm/v2/' . $module . '/upsert';
                $access_token = $this->getAccessToken();

                if ($access_token == false || !is_object($access_token) || !property_exists($access_token, 'access_token')) {
                    Log::error(var_export($access_token, true));
                    return false;
                }

                if (count($data['data']) > 100) {
                    $error = 'data too large';
                    Log::error('$error: '. $error);
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

                $method = 'POST';
                $response = $this->zoho_crm_client->request($method, $uri, $options);

                if ($response->getStatusCode() == 201 || $response->getStatusCode() == 200) {
                    $data = json_decode($response->getBody());
                    $record = $data->data[0]->code;
                    return $record;
                } else {
                    Log::error($response->getStatusCode() . ' - '. $response->getBody());
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

    public function getUsers()
    {
        $module = config('zoho.MODULES.ZOHO_MODULE_USERS');

        try {
            $records = [];
            if ($module !== '') {

                $uri = '/crm/v2/' . $module . '?type=AllUsers';

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
                    $records = array_merge($records, $data->users);
                } else {
                    return false;
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

    public function getRelatedList($module, $id, $related)
    {
        try {
            $record = [];
            if ($module !== '' && $id !== '' && $related != '') {
                $uri = '/crm/v2/' . $module . '/' . $id . '/' . $related;
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
                    $records = $data->data;
                    return $records;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (GuzzleHttp\Exception\ClientException $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    public function sync(array $crm_data, array $local_data, array &$insert_list, array &$update_list, String $fillter_field = '', String $fillter_value = '')
    {
        if (count($crm_data) == 0) {
            return false;
        }
        $updated_id = [];
        $insert_list = [];
        $update_list = [];

        $local_exists = count($local_data) > 0;
        if ($local_exists) {
            foreach ($crm_data as $crm_obj) {
                $filltered = ($fillter_field != '') ? data_get($crm_obj, $fillter_field) : null;
                if ($filltered != null && $filltered != $fillter_value) {
                    continue;
                }

                foreach ($local_data as $local_obj) {
                    if ($local_obj->crm_id == $crm_obj->id) {
                        $update_list[$local_obj->id] = $crm_obj;
                        array_push($updated_id, $crm_obj->id);
                        break;
                    }
                }
            }

            foreach ($crm_data as $crm_obj) {
                $filltered = ($fillter_field != '') ? data_get($crm_obj, $fillter_field) : null;
                if ($filltered != null && $filltered != $fillter_value) {
                    continue;
                }

                if (!in_array($crm_obj->id, $updated_id)) {
                    array_push($insert_list, $crm_obj);
                }
            }
        }

        // foreach ($crm_data as $crm_obj) {
        //     $filltered = ($fillter_field != '') ? data_get($crm_obj, $fillter_field) : null;
        //     if ($filltered != null && $filltered != $fillter_value) {
        //         continue;
        //     }

        //     if ($local_exists) {
        //         $updated = false;
        //         foreach ($update_list as $update_obj) {
        //             if ($update_obj->id == $crm_obj->id) {
        //                 $updated = true;
        //                 break;
        //             }
        //         }

        //         if (!$updated) {
        //             array_push($insert_list, $crm_obj);
        //         }

        //     } else {
        //         array_push($insert_list, $crm_obj);
        //     }
        // }
        return true;
    }
}

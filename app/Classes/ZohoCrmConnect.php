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
        $options = [
            'http_errors' => true,
            'query' => [
                'refresh_token' => config('zoho.ZOHO_APP_REFRESH_CODE'),
                'client_id' => config('zoho.ZOHO_APP_CLIENT_ID'),
                'client_secret' => config('zoho.ZOHO_APP_CLIENT_SECRET'),
                'grant_type' => 'refresh_token',
            ],
        ];

        $response = $this->zoho_account_client->request('POST', '/oauth/v2/token', $options);
        if ($response->getStatusCode() == 200) {
            $data = json_decode($response->getBody());
            return $data;
        } else {
            return false;
        }
    }

    public function getAllRecords($module)
    {
        $records = [];
        if ($module !== '') {
            $uri = '/crm/v2/' . $module;
            $access_token = $this->getAccessToken();

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
    }

    public function getRecordById($module, $id)
    {
        $record = [];
        if ($module !== '' && $id !== '') {
            $uri = '/crm/v2/' . $module . '/' . $id;
            $access_token = $this->getAccessToken();

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

                $options = [
                    'http_errors' => true,
                    'json' => $data,
                    'headers' => [
                        'Authorization' => 'Zoho-oauthtoken ' . $access_token->access_token,
                    ],
                ];

                Log::info(var_export(json_decode(json_encode($data)), true));
                $method = isset($data['data'][0]['id']) ? 'PUT' : 'POST';
                $response = $this->zoho_crm_client->request($method, $uri, $options);
                Log::info('$response->getStatusCode() -> ' . var_export($response->getStatusCode(), true));
                Log::info('$response->getBody() -> ' . var_export(json_decode($response->getBody()), true));

                if ($response->getStatusCode() == 201) {
                    $data = json_decode($response->getBody());
                    $record = $data->data[0];
                    Log::info('RESULT - ' .var_export(json_encode($data), true));
                    return $record;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }
}

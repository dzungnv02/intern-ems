<?php
return [
    'ZOHO_ACCOUNT_BASE_URL' => env('ZOHO_ACCOUNT_BASE_URL', 'https://accounts.zoho.com'),
    'ZOHO_CRM_BASE_URL' => env('ZOHO_CRM_BASE_URL', 'https://www.zohoapis.com'),
    'ZOHO_APP_REFRESH_CODE' => env('ZOHO_CRM_REFRESH_CODE', '1000.507014a2e89deceb7a6f7f01c583b14f.5cc3776fbae0cd1a62b24a1a628371a6'),
    'ZOHO_APP_CLIENT_ID' => env('ZOHO_CRM_CLIENT_ID', '1000.79WBYRQ5XDKI81127J5K63S6ZRJMZ8'),
    'ZOHO_APP_CLIENT_SECRET' => env('ZOHO_CRM_SECRET', 'e24348d2b3e867e9dfc4b6352cde4caeedbd517a09'),
    'MODULES' => [
            'ZOHO_MODULE_LEADS'=>'Leads',
            'ZOHO_MODULE_ACCOUNTS'=>'Accounts',
            'ZOHO_MODULE_PARENTS'=>'Contacts',
            'ZOHO_MODULE_STUDENTS'=>'Deals',
            'ZOHO_MODULE_EMS_CLASS' => 'Products',
            'ZOHO_MODULE_SALESORDERS'=>'Sales_Orders',
            'ZOHO_MODULE_SMS_TEMPLATE' => 'misms.SMS_Template',
            'ZOHO_MODULE_INVOICE' => 'Invoices',
            'ZOHO_MODULE_SCORE' => 'Score_Criteria_Builder',
            'ZOHO_MODULE_EMS_TEACHER' => 'EPS_Giao_vien',
            'ZOHO_MODULE_EMS_BRANCH' => 'EPS_Trung_tam',
            'ZOHO_MODULE_EMS_STAFF' => 'EPS_Nhan_vien',
            'ZOHO_MODULE_PARENTS_X' => 'Ph_huynh_X_Accounts'
    ],
    'MAPPING' => [
        'ZOHO_MODULE_EMS_TEACHER' => [
            'Email' => 'email',
            'Qu_c_t_ch' => 'nationality',
            'i_n_tho_i_kh_c' => '',
            'Name' => 'name',
            'Tr_nh' => '',
            'Phone' => 'mobile',
            'Record_Image' => '',
            'Tag' => '',
            'EMS_ID' => 'id'
        ]
    ]
];
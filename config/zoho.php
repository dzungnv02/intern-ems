<?php
return [
    'ZOHO_ACCOUNT_BASE_URL' => env('ZOHO_ACCOUNT_BASE_URL', 'https =>//accounts.zoho.com'),
    'ZOHO_CRM_BASE_URL' => env('ZOHO_CRM_BASE_URL', 'https =>//www.zohoapis.com'),
    'ZOHO_APP_REFRESH_CODE' => env('ZOHO_CRM_REFRESH_CODE', '1000.751383c44d1114427e801b2262f43553.d9338768f3e34aa406d8a51aaa6e2e86'),
    'ZOHO_APP_CLIENT_ID' => env('ZOHO_CRM_CLIENT_ID', '1000.79WBYRQ5XDKI81127J5K63S6ZRJMZ8'),
    'ZOHO_APP_CLIENT_SECRET' => env('ZOHO_CRM_SECRET', 'e24348d2b3e867e9dfc4b6352cde4caeedbd517a09'),
    'ZOHO_API_GET_ACCESS_TOKEN_RETRY_ATTEMPT' => env('ZOHO_API_GET_ACCESS_TOKEN_RETRY_ATTEMPT', '4'),
    'ZOHO_SESSION_FILE' => storage_path('app/zoho') . '/zoho_session',
    'ZOHO_API_MAX_RECORDS_PER_PAGE' => 200,
    'MODULES' => [
        'ZOHO_MODULE_EMS_TEACHER' => 'EMS_TEACHER',
        'ZOHO_MODULE_EMS_BRANCH' => 'EMS_BRANCH',
        'ZOHO_MODULE_EMS_STAFF' => 'EMS_STAFF',
        'ZOHO_MODULE_EMS_CLASS' => 'Products',
        'ZOHO_MODULE_STUDENTS' => 'Deals',
        'ZOHO_MODULE_STUDENTS_CONTACTS' => 'Deals',
        'ZOHO_MODULE_STUDENTS_TRACKING' => 'Deals',
        'ZOHO_MODULE_PARENTS' => 'Accounts',
        'ZOHO_MODULE_USERS' => 'users',
    ],
    'MAPPING' => [
        'ZOHO_MODULE_EMS_TEACHER' => [
            'id' => 'crm_id',
            'Email' => 'email',
            'nationality' => 'nationality',
            'Name' => 'name',
            'level' => 'certificate',
            'Phone' => 'mobile',
            'Owner' => 'crm_owner',
        ],
        'ZOHO_MODULE_EMS_BRANCH' => [
            'id' => 'crm_id',
            'phone' => 'phone_2',
            'address' => 'address',
            'Email' => 'email',
            'Name' => 'branch_name',
            'Secondary_Email' => 'email_2',
            'Hotline' => 'phone_1',
            
        ],
        'ZOHO_MODULE_EMS_STAFF' => [
            'id' => 'crm_id',
            //'title' => '',
            'address' => 'address',
            'Email' => 'email',
            'Name' => 'name',
            'Phone' => 'phone_number',
            'Hotline' => 'phone_1',
            'Owner' => 'crm_owner',
        ],

        'ZOHO_MODULE_STUDENTS' => [
            'id' => 'crm_id',
            'Owner' => 'crm_owner', //json_encode
            'Email' => 'email',
            'birthyear' => 'birthyear',
            'English_Name' => 'e_name',
            'register_branch' => 'crm_register_branch', //json_encode
            'Deal_Name' => 'name',
            'first_register_branch' => 'first_register_branch',
            'student_code' => 'student_code',
            'birthday' => 'birthday',
            'Description' => 'register_note',
            //'student_name' => 'name',
            'address' => 'address',
            'mobile' => 'mobile',
            'register_date' => 'register_date',
            'dependent_staff' => 'crm_dependent_staff', //json_encode
            'deposit_date' => 'deposit_date',
            'branch_transfer_date' => 'branch_transfer_date',
            'class_joining_date' => 'class_joining_date',
            'tutor_fee_expired_date' => 'tutor_fee_expired_date',
            'current_class' => 'current_class',
            'current_school' => 'current_school',
            'deposit_amount' => 'deposit_amount',
            'withdrawal_date' => 'withdrawal_date',
            'Contact_Name' => 'crm_contact', //json_encode
            'assessment' => [
                'assessment_status' => '',
                'assessment_date' => 'assessment_date',
                'assessment_teacher' => 'teacher_id',
                'assessment_result' => 'assessment_result',
                'trial_status' => '',
                'trial_start_date' => 'trial_start_date',
                'trial_class' => 'trial_class_id',
            ]
        ],

        'ZOHO_MODULE_PARENTS' => [
            'id' => 'crm_id',
            'Account_Name' => 'fullname',
            'Email' => 'email',
            'Phone' => 'phone',
            'other_phone' => 'other_phone',
            'working_phone' => 'working_phone',
            'working_place' => 'working_place',
            'address' => 'address',
            'parent_role' => 'parent_role',
            'facebook' => 'facebook',
            'register_branch' => 'crm_register_branch',
            'Owner' => 'crm_owner',
        ],

        'ZOHO_MODULE_EMS_CLASS' => [
            'id' => 'crm_id',
            'Product_Name' => 'name',
            'teacher' => 'crm_teacher',
            'course_name' => 'course_name',
            'Product_Active' => 'status',
            'L_ch_h_c_trong_tu_n' => 'schedule',
            'Description' => 'note',
            'start_date' => 'start_date',
        ],
        'ZOHO_MODULE_EMS_BRANCH' => [
            'id' => 'crm_id',
            'Owner' => 'crm_owner_name',
            'Name' => 'branch_name',
            'address' => 'address',
            'Hotline' => 'phone_1',
            'phone' => 'phone_2',
            'Email' => 'email',
            'Secondary_Email' => 'email_2',
        ],
    ],
    'RELATED_LIST' => [
        'Accounts' => 'Deals',
        'Contacts' => [
            'Deals'
        ],
        'Deals' => [
            'Products', 'Staff_list'
        ]
    ]

];

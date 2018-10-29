<?php
return [
    'ZOHO_ACCOUNT_BASE_URL' => env('ZOHO_ACCOUNT_BASE_URL', 'https =>//accounts.zoho.com'),
    'ZOHO_CRM_BASE_URL' => env('ZOHO_CRM_BASE_URL', 'https =>//www.zohoapis.com'),
    'ZOHO_APP_REFRESH_CODE' => env('ZOHO_CRM_REFRESH_CODE', '1000.507014a2e89deceb7a6f7f01c583b14f.5cc3776fbae0cd1a62b24a1a628371a6'),
    'ZOHO_APP_CLIENT_ID' => env('ZOHO_CRM_CLIENT_ID', '1000.79WBYRQ5XDKI81127J5K63S6ZRJMZ8'),
    'ZOHO_APP_CLIENT_SECRET' => env('ZOHO_CRM_SECRET', 'e24348d2b3e867e9dfc4b6352cde4caeedbd517a09'),
    'MODULES' => [
            'ZOHO_MODULE_LEADS'=>'Leads',
            'ZOHO_MODULE_PARENTS'=>'Accounts',
            'ZOHO_MODULE_CONTACTS'=>'Contacts',
            'ZOHO_MODULE_STUDENTS'=>'Deals',
            'ZOHO_MODULE_EMS_CLASS' => 'Products',
            'ZOHO_MODULE_SALESORDERS'=>'Sales_Orders',
            'ZOHO_MODULE_SMS_TEMPLATE' => 'misms.SMS_Template',
            'ZOHO_MODULE_INVOICE' => 'Invoices',
            'ZOHO_MODULE_SCORE' => 'Score_Criteria_Builder',
            'ZOHO_MODULE_EMS_TEACHER' => 'EPS_Giao_vien',
            'ZOHO_MODULE_EMS_BRANCH' => 'EPS_Trung_tam',
            'ZOHO_MODULE_EMS_STAFF' => 'EPS_Nhan_vien',
    ],
    'MAPPING' => [
        'ZOHO_MODULE_EMS_TEACHER' => [
            'Email' => 'email',
            'Qu_c_t_ch' => 'nationality',
            'i_n_tho_i_kh_c' => '',
            'Name' => 'name',
            'Tr_nh' => 'certificate',
            'Phone' => 'mobile',
            'Record_Image' => '',
            'Tag' => '',
            'EMS_ID' => 'id',
            'EMS_SYNC_TIME' => ''
        ],
        'ZOHO_MODULE_EMS_BRANCH' => [
            'i_n_tho_i' => 'phone_2',
            'a_ch_trung_t_m' => 'address',
            'Email' => 'email',
            'EMS_SYNC_TIME' => '',
            'EMS_ID' => 'id',
            'Name' => 'branch_name',
            'Secondary_Email' => 'email_2',
            'Hotline' => 'phone_1',
            'id' => 'crm_id'
        ],
        'ZOHO_MODULE_STUDENTS' => [
            'Owner' => [
                'id' => 'branch.crm_id'
            ],
            'Contact_Name'=>  [
                'id'=>  'student_guardian.crm_id'
            ],
            
            'i_n_tho_i'=>  'mobile',
            'Email'=>  'email',
            'EMS_ID'=>  '',
            'M_h_c_sinh'=>  'student_code',
            'id'=>  'crm_id',
            'EMS_SYNC_TIME'=>  '',
            'Ng_y_sinh_con'=>  'birthday',
            'N_m_sinh_con'=>  'birthyear',
            'H_t_n_con'=>  'name',

            // 'Ng_y_ng_k'=>  '',
            // 'L_p_EMS'=>  null,
            // 'Ng_y_t_c_c'=>  null,
            // 'Ng_y_h_c_th'=>  'entry_asignment.trial_start_date',
            // 'Sales_Cycle_Duration'=>  4,
            // 'Ng_i_ph_tr_ch'=>  null,
            // 'Ng_y_nh_p_h_c'=>  null,
            // 'Trung_t_m_ng_k_l_n_u1'=>  'Hoàng Quốc Việt',
            // 'Ng_y_l_m_assessment'=>  '2018-10-31T17=> 00=> 00+07=> 00',
            // 'Trung_t_m'=>  'branch.address',
            // 'K_t_qu_assessment'=>  null,
            // 'Prediction_Score'=>  null,
            //'T_nh_tr_ng_th_c_hi_n_assessment'=>  '',
            //'T_nh_tr_ng_h_c_th'=>  'Chưa học thử',
            //'Gi_o_vi_n_assessment'=>  'teachers.name',
            //'a_ch_trung_t_m1'=>  'branch.address',
            //'L_p_h_c_th'=>  '',
            //'Stage'=>  'Book Ass.',
            //'Trung_t_m_ng_k'=>  null,
            //'M_h_c_sinh_test'=>  'ICR109791',
            //'Ng_y_assessment'=>  null,
            //'S_ti_n_t_c_c'=>  null,
            //'L_p1'=>  null,
            //'Gi_o_vi_n_l_m_assessment'=>  'entry_asignment.teacher_id',
            //'Ng_y_h_t_h_c_ph'=>  null,
            //'Level'=>  null,
            //'Ng_y_Withdrawal'=>  null,
            //'Ng_y_chuy_n_trung_t_m'=>  null,
            //'Tr_ng_ang_h_c'=>  null,
            //'Closing_Date'=>  '2018-10-31',
            //'N'=>  null,
            //'Record_Image'=>  null,
        ],
    ]
];

// 'Owner' => {
//     'name' => 'I Can Read Hà Nội',
//     'id' => '2666159000000122009'
// ],
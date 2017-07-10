<?php
/**
 * Created by Cao Jiayuan.
 * Date: 17-5-8
 * Time: 下午4:29
 */
return [
  'dms' => [
    'pub_key' => env('AODIAN_DMS_PUB_KEY', 'pub_1b06ff807e6860ba007d01c509be9546'),
    'sub_key' => env('AODIAN_DMS_SUB_KEY', 'sub_6724f1d77a703dce2eda1fba6204d226'),
    's_key'   => env('AODIAN_DMS_S_KEY', 's_5e9e155eaadd1953dd059f1e7be091d6')
  ],
  'lss' => [
    'user_id' => env('AODIAN_LSS_USER_ID', '29765')
  ]
];
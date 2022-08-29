<?php
/*
   *   created by : Sagar Thokal
   *   Created On : 10-Feb-2022
   *   Uses :  To display message on admin panel
*/
return [
    'UTF8_ENABLED' => TRUE,
    'DIMENTIONS' =>  [
        "BANNER" => "1200X500 pixels (3:2), Max Size 500Kb and .jpg format image",
        "CATEGORY" => "500X500 pixels (1:1), Max Size 500Kb and .jpg format image",
        "SUB_CATEGORY" => "500X500 pixels (1:1), Max Size 500Kb and .jpg format image",
        "COMPANY" => "500X500 pixels (1:1), Max Size 500Kb and .jpg format image",
        "NEWS" => "1200X800 pixels (3:2), Max Size 500Kb and .jpg format image",
        "PRODUCT_FORM" => "1200X800 pixels (3:2), Max Size 500Kb and .jpg format image",
        "PACKAGING_MACHINE" => "1200X800 pixels (3:2), Max Size 500Kb and .jpg format image",
        "PACKAGING_TREATMENT" => "1200X800 pixels (3:2), Max Size 500Kb and .jpg format image",
        "PRODUCT" => "500X500 pixels (1:1), Max Size 500Kb and .jpg format image",
        "ORDER_PAYMENT" => "1200X800 pixels (3:2), Max Size 500Kb and .jpg format image",
        "WHATSAPP_FILE" => "500X500 pixels (1:1), Max Size 500Kb and .jpg format image",
        "NOTIFICATION" => "1200X800 pixels (3:2), Max Size 500Kb and .jpg format image",
        'GSTCERTIFICATE' => '2MB .pdf, .jpeg, .jpg, .png format file'
    ],
    'SIZE' =>  [
        "BANNER" => "500000",
        "CATEGORY" => "500000",
        "SUB_CATEGORY" => "500000",
        "COMPANY" => "500000",
        "NEWS" => "500000",
        "PRODUCT_FORM" => "500000",
        "PACKAGING_MACHINE" => "500000",
        "PACKAGING_TREATMENT" => "500000",
        "PRODUCT" => "500000",
        "ORDER_PAYMENT" => "500000",
        "WHATSAPP_FILE" => "500000",
        "NOTIFICATION" => "500000"
    ],
    'PLATFORM' => ['ios', 'android', 'web'],
    'MAX_IMAGE_SIZE' => '2048',
    'VISITING_CARD_IMAGE_SIZE' => '500',
    'GST_NO_VALIDATION' => '^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$^',
    'MAX_VENDOR_ADDRESS_COUNT' => '10',
    'MAX_USER_ADDRESS_COUNT' => '10',
    'TEST_SMS_API' => 'LyzXxi-fREC0WvhfAeqaPA==',
    'TEMP_URL_EXP_HOUR' => '48',
    'TEMP_URL_EXP_DAYS_FOR_INVOICE' => '365',
    'MONTH_TO_MULTIPLY_SHELF_LIFE' => '30',
];

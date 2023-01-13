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
        "BANNER" => "10240",
        "CATEGORY" => "5120",
        "SUB_CATEGORY" => "5120",
        "COMPANY" => "5120",
        "NEWS" => "5120",
        "PRODUCT_FORM" => "5120",
        "PACKAGING_MACHINE" => "5120",
        "PACKAGING_TREATMENT" => "5120",
        "PRODUCT" => "5120",
        "ORDER_PAYMENT" => "5120",
        "WHATSAPP_FILE" => "5120",
        "NOTIFICATION" => "5120"
    ],
    'PLATFORM' => ['ios', 'android', 'web'],
    'MAX_IMAGE_SIZE' => '2048',
    'VISITING_CARD_IMAGE_SIZE' => '500',
    'GST_NO_VALIDATION' => '^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$^',
    'MAX_VENDOR_ADDRESS_COUNT' => '10',
    'MAX_USER_ADDRESS_COUNT' => '10',
    'TEST_SMS_API' => 'LzqUPBE3QfK_PmZXgf4DCA==',
    'TEMP_URL_EXP_HOUR' => '48',
    'TEMP_URL_EXP_DAYS_FOR_INVOICE' => '365',
    'MONTH_TO_MULTIPLY_SHELF_LIFE' => '30',
    'DEFAULT_SHELF_LIFE' => '0',
    'DEFAULT_SHELF_LIFE_UNIT' => 'days',
    'TEST_CUSTOMER_FCM_SERVER_KEY' => 'AAAAkZP2gI4:APA91bG7dkIo1fq6CFuwBgCNlghO2v1U5gZAPsZ3BcuvjSUdOID_5dCVqELiypl2TK9WBDc88pbcM7teTOiWLGc17z4obf52CqRvsV17VoxRXmZxs94SR02Lh27HsyCVhRQ27DES6RUh',
    'TEST_VENDOR_FCM_SERVER_KEY' => 'AAAAcZ9N3YA:APA91bEt98gXVq50VcizK9gXBT7ybMJUVzlUajRBussUhl9bU2fH1I_s_uATynRl709J3O8I1xW9z6UXk59LDzR0xo1YGKuomxrW4PMEf37GkTvZIVaxj5OkdabOecgTX6Dmb13rxafF',
    'PROD_FCM_SERVER_KEY' => 'days',
    'DEFAULT_THUMB_IMAGE_WIDTH' => '110',
    'DEFAULT_THUMB_IMAGE_HEIGHT' => '130',
    'BANNER_THUMB_IMAGE_WIDTH' => '100',
    'BANNER_THUMB_IMAGE_HEIGHT' => '70',
];

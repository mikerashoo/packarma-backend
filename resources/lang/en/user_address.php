<?php

return [

    /*
    |--------------------------------------------------------------------------
    | User Address Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'address_not_found' => 'Please reselect your address',
    'billing_address_not_found' => 'Please reselect your billing address',
    'shipping_address_not_found' => 'Please reselect your shipping address',
    'deleted_successfully' => 'Address Deleted Successfully',
    'id_required' => 'Address Id Required',
    'my_address_updated_successfully' => 'Address Updated Successfully',
    'my_address_created_successfully' => 'Address Created Successfully',
    'address_entry_limit_reached' => 'You can not add more than '.config('global.MAX_USER_ADDRESS_COUNT').' addresses',
    'gst_number_already_exist' => 'Entered GST Number is already registered with us',
    'if_user_type_billing_gst_number_required' => 'GST number is required for address type billing',
    'user_billing_or_shipping_address_is_required' => 'Please Select Billing or Shipping Address',
    'user_billing_address_is_required' => 'Please select billing address',
    'user_shipping_address_is_required' => 'Please select shipping address'
];

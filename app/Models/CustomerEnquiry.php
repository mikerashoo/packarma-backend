<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerEnquiry extends Model
{
    use HasFactory;
    /**
        * Developed By : Pradyumn Dwivedi
        * Created On : 04-mar-2022
        * uses : to get data of user in customer enquiry table
    */   
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
        * Developed By : Pradyumn Dwivedi
        * Created On : 04-mar-2022
        * uses : to get data of vendor in customer enquiry table
    */   
    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor');
    }

    /**
        * Developed By : Pradyumn Dwivedi
        * Created On : 04-mar-2022
        * uses : to get data of city in customer enquiry table
    */   
    public function city()
    {
        return $this->belongsTo('App\Models\City');
    }

    /**
        * Developed By : Pradyumn Dwivedi
        * Created On : 04-mar-2022
        * uses : to get data of state in customer enquiry table
    */   
    public function state()
    {
        return $this->belongsTo('App\Models\State');
    }

    /**
        * Developed By : Pradyumn Dwivedi
        * Created On : 04-mar-2022
        * uses : to get data of sub country in customer enquiry table
    */   
    public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }

    /**
        * Developed By : Pradyumn Dwivedi
        * Created On : 04-mar-2022
        * uses : to get data of product in customer enquiry table
    */   
    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    /**
        * Developed By : Pradyumn Dwivedi
        * Created On : 04-mar-2022
        * uses : to get data of category in customer enquiry table
    */   
    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    /**
        * Developed By : Pradyumn Dwivedi
        * Created On : 04-mar-2022
        * uses : to get data of sub category in customer enquiry table
    */   
    public function sub_category()
    {
        return $this->belongsTo('App\Models\SubCategory');
    }

    /**
        * Developed By : Pradyumn Dwivedi
        * Created On : 04-mar-2022
        * uses : to get data of packing type in customer enquiry table
    */   
    public function product_form()
    {
        return $this->belongsTo('App\Models\ProductForm');
    }

    /**
        * Developed By : Pradyumn Dwivedi
        * Created On : 04-mar-2022
        * uses : to get data of packing type in customer enquiry table
    */   
    public function packing_type()
    {
        return $this->belongsTo('App\Models\PackingType');
    }

    /**
        * Developed By : Pradyumn Dwivedi
        * Created On : 04-mar-2022
        * uses : to get data of vendor quotation in customer enquiry table
    */   
    public function vendor_quotation()
    {
        return $this->belongsTo('App\Models\VendorQuotation');
    }

    /**
        * Developed By : Pradyumn Dwivedi
        * Created On : 04-mar-2022
        * uses : to get data of packaging machine in customer enquiry table
    */   
    public function packaging_machine()
    {
        return $this->belongsTo('App\Models\PackagingMachine');
    }

    /**
        * Developed By : Pradyumn Dwivedi
        * Created On : 04-mar-2022
        * uses : to get data of storage condition  in customer enquiry table
    */   
    public function storage_condition()
    {
        return $this->belongsTo('App\Models\StorageCondition');
    }

    /**
        * Developed By : Pradyumn Dwivedi
        * Created On : 04-mar-2022
        * uses : to get data of packaging treatment in customer enquiry table
    */   
    public function packaging_treatment()
    {
        return $this->belongsTo('App\Models\PackagingTreatment');
    }

    /**
        * Developed By : Pradyumn Dwivedi
        * Created On : 04-mar-2022
        * uses : to get data of vendor in customer enquiry table
    */   
    public function warehouse()
    {
        return $this->belongsTo('App\Models\Vendorwarehouse');
    }
}

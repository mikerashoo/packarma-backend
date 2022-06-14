<table cellspacing="0" cellpadding="2" border="0" style="font-size: small">
    
    <tr>
        <td width="30%" style="border-left-color: rgb(207, 2, 2); border-top-color:rgb(207, 2, 2);text-align:center;">
      <img src="{{$no_image}}" alt="" width="80" height="80">
        </td>
        <td width="70%" style="border-left-color: black; border-right-color: rgb(207, 2, 2); border-top-color:rgb(207, 2, 2);"><br>
            <h3>&nbsp;&nbsp;&nbsp;{{$data->vendor_company_name}}</h3><br>
            &nbsp;&nbsp;{{$data->vendor_address}}<br>
            &nbsp;&nbsp;<b>Tel:</b>{{$data->phone}} &nbsp;&nbsp; <b>Email ID:</b>{{$data->vendor_email}}
        </td>
    </tr>
    <tr>
        <td width="35%" style="border-left-color: rgb(207, 2, 2); border-top-color:black; "><b>Registration Type:</b> {{!empty($data->gstin) ? 'Registered' :'Not Registered';}}</td>
        <td width="30%" style="border-left-color: black;border-top-color: black;border-right-color: black;"><b>GST Registered:</b> {{!empty($data->gstin) ? 'Yes' :'No';}} </td>
        <td width="35%" style="border-right-color: rgb(207, 2, 2);border-top-color: black;"><b>GSTIN:</b> {{$data->gstin ?? '' ;}}</td>
    </tr>
    <tr>
        <td width="100%" style="text-align:center; border-bottom-color:black; border-left-color: rgb(207, 2, 2);border-top-color: black;border-right-color: rgb(207, 2, 2); background-color:#8ab4f8;"><h1>Invoice Cum Bill of Supply</h1></td>
    </tr>
    <tr>
        <td width="33.33%" style="border-left-color: rgb(207, 2, 2);border-top-color: black;">Invoice No: 2019-20/0252624</td>
        <td width="33.33%" style="border-left-color: black;border-top-color: black;border-right-color: black;">2611936</td>
        <td width="33.33%" style="border-right-color: rgb(207, 2, 2);border-top-color: black;">State: Maharashtra</td>
    </tr>
    <tr>
        <td width="33.33%" style="border-left-color: rgb(207, 2, 2);border-top-color: black;">Invoice Date: {{$invoice_date}}</td>
        <td width="33.33%" style="border-left-color: black;border-top-color: black;border-right-color: black;"></td>
        <td width="33.33%" style="border-right-color: rgb(207, 2, 2);border-top-color: black;">State Code: IN-MH</td>
    </tr>
     <tr>
        <td width="25%" style="border-left-color: rgb(207, 2, 2);border-top-color: black;">Order No: 10418136</td>
        <td width="25%" style="border-left-color: black;border-top-color: black;border-right-color: black;">Sub Order No: 1041813601</td>
        <td width="25%" style="border-top-color: black;">Order Date: {{$order_date}} </td>
        <td width="25%" style="border-right-color: rgb(207, 2, 2);border-top-color: black; border-left-color: black;">DOD: {{$delivery_date}}</td>
    </tr>
    <tr>
        <td width="50%" style="border-left-color: rgb(207, 2, 2);border-top-color: black; border-right-color: black; border-bottom-color: black; text-align:center; background-color:#8ab4f8;"><b>Bill to Party</b></td>
        <td width="50%" style="border-right-color: rgb(207, 2, 2);border-top-color: black; text-align:center; border-bottom-color: black; background-color:#8ab4f8;"><b>Ship to Party</b></td>
    </tr>
     <tr>
        <td width="50%" style="border-left-color: rgb(207, 2, 2);border-top-color: black; border-right-color: black;">Name: {{$billing_data->username ??''}} </td>
        <td width="50%" style="border-right-color: rgb(207, 2, 2);border-top-color: black;">Name: {{$shipping_data->username ??''}}</td>
    </tr>
     <tr>
        <td width="50%" style="border-left-color: rgb(207, 2, 2);border-top-color: black; border-right-color: black;">Address: {{$billing_data->address_line1 ??''}} ,{{$billing_data->address_line2 ??''}} </td>
        <td width="50%" style="border-right-color: rgb(207, 2, 2);border-top-color: black;">Address: {{$shipping_data->address_line1 ??''}} ,{{$shipping_data->address_line2 ??''}}</td>
    </tr>
     <tr>
        <td width="50%" style="border-left-color: rgb(207, 2, 2);border-top-color: black; border-right-color: black;">GSTIN/UIN: {{$billing_data->gst_no ??''}} </td>
        <td width="50%" style="border-right-color: rgb(207, 2, 2);border-top-color: black;">GSTIN: NA</td>
    </tr>
     <tr>
        <td width="50%" style="border-left-color: rgb(207, 2, 2);border-top-color: black; border-right-color: black;">State: {{$billing_data->state_name ??''}}     Code: {{$billing_data->country_name ??''}} - {{$billing_data->state_name ??''}}</td>
        <td width="50%" style="border-right-color: rgb(207, 2, 2);border-top-color: black;">State: {{$shipping_data->state_name ??''}}     Code: {{$shipping_data->country_name ??''}} - {{$shipping_data->state_name ??''}}</td>
    </tr>
    <tr>
        <td width="5%" rowspan="2" style="border-left-color: rgb(207, 2, 2);border-top-color: black; border-bottom-color: black; border-right-color: black; font-size: 7px; text-align:center; background-color:#8ab4f8;">Sr.No</td>
        <td width="9%" rowspan="2" style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center; background-color:#8ab4f8;">Production Description</td>
        <td width="6%" rowspan="2" style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center; background-color:#8ab4f8;">HSN CODE</td>
        <td width="5%" rowspan="2" style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center; background-color:#8ab4f8;">UOM</td>
        <td width="5%" rowspan="2" style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center; background-color:#8ab4f8;">QTY</td>
        <td width="7%" rowspan="2" style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center; background-color:#8ab4f8;">Rate</td>
        <td width="7%" rowspan="2" style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center; background-color:#8ab4f8;">Amount</td>
        <td width="8%" rowspan="2" style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center; background-color:#8ab4f8;">Discount /Adj</td>
        <td width="7%" rowspan="2" style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center; background-color:#8ab4f8;">Taxable Value</td>
        <td width="11%" colspan="2" style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center; background-color:#8ab4f8;">CGST</td>
        <td width="11%" colspan="2" style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center; background-color:#8ab4f8;">SGST</td>
        <td width="11%" colspan="2" style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center; background-color:#8ab4f8;">IGST</td>
        <td width="8%" rowspan="2" style="border-right-color: rgb(207, 2, 2);border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center; background-color:#8ab4f8;">Total</td>
    </tr>
     <tr>
        <td style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center; background-color:#8ab4f8;">Rate</td>
        <td style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center; background-color:#8ab4f8;">Amount</td>
        <td style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center; background-color:#8ab4f8;">Rate</td>
        <td style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center; background-color:#8ab4f8;">Amount</td>
        <td style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center; background-color:#8ab4f8;">Rate</td>
        <td style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center; background-color:#8ab4f8;">Amount</td>
    </tr>

    <tr>
        <td width="5%" style="border-left-color: rgb(207, 2, 2);border-top-color: black; border-bottom-color: black; border-right-color: black; font-size: 7px; text-align:center;">1</td>
        <td width="9%" style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">{{$data->product_description ?? ''}}</td>
        <td width="6%" style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">{{$data->hsn_code ?? '996819'}}</td>
        <td width="5%" style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">{{$data->unit_symbol ?? ''}}</td>
        <td width="5%" style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">{{$data->product_quantity ?? ''}}</td>
        <td width="7%" style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">{{$data->mrp ?? ''}}</td>
        <td width="7%" style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">{{$data->sub_total ?? ''}}</td>
        <td width="8%" style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">{{$data->discount ?? 0}}</td>
        <td width="7%" style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">{{$data->gst_amount ?? ''}}</td>
        <td width="5.5%" style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">{{$cgst ?? ''}}</td>
        <td width="5.5%" style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">{{$cgst_amount ?? ''}}</td>
        <td width="5.5%" style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">{{$sgst ?? ''}}</td>
        <td width="5.5%" style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">{{$sgst_amount ?? ''}}</td>
        <td width="5.5%" style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">{{$igst ?? ''}}</td>
        <td width="5.5%" style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">{{$igst_amount ?? ''}}</td>
        <td width="8%" style="border-right-color: rgb(207, 2, 2);border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;"> {{$data->grand_total ?? ''}}</td>
    </tr>

     <tr>
        <td width="5%" style="border-left-color: rgb(207, 2, 2);border-top-color: black; border-bottom-color: black; border-right-color: black; font-size: 7px; text-align:center;">2</td>
        <td width="9%" style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">Delivery Charge</td>
        <td width="6%" style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">{{$data->hsn_code ?? '996819'}}</td>
        <td width="5%" style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">{{$data->unit_symbol ?? ''}}</td>
        <td width="5%" style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">{{$data->product_quantity ?? ''}}</td>
        <td width="7%" style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">{{$data->freight_amount ?? 0}}</td>
        <td width="7%" style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">{{$data->freight_amount ?? 0}}</td>
        <td width="8%" style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">{{$data->discount ?? 0}}</td>
        <td width="7%" style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">{{$dc_tax_val}}</td>
        <td width="5.5%" style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">{{$dc_cgst}}</td>
        <td width="5.5%" style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">{{$dc_cgst_amount}}</td>
        <td width="5.5%" style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">{{$dc_sgst}}</td>
        <td width="5.5%" style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">{{$dc_sgst_amount}}</td>
        <td width="5.5%" style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">{{$dc_igst}}</td>
        <td width="5.5%" style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">{{$dc_igst_amount}}</td>
        <td width="8%" style="border-right-color: rgb(207, 2, 2);border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">{{$dc_amount??0}}</td>
    </tr>

       <tr>
        <td width="25%" style="border-left-color: rgb(207, 2, 2);border-top-color: black; border-bottom-color: black; border-right-color: black; font-size: 7px; text-align:center;"><b>Grand Total</b></td>
        <td width="5%" style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;"></td>
        <td width="7%" style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;"></td>
        <td width="7%" style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">{{number_format(($data->sub_total ?? 0) + ($data->freight_amount ?? 0) , 2)}}</td>
        <td width="8%" style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">{{number_format(($data->discount ?? 0) + ($data->discount ?? 0) , 2)}}</td>
        <td width="7%" style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">{{number_format(($data->gst_amount ?? 0) + ($dc_tax_val ?? 0) , 2)}}</td>
        <td width="5.5%" style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">{{number_format(($cgst ?? 0) + ($dc_cgst ?? 0) , 2)}}</td>
        <td width="5.5%" style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">{{number_format(($cgst_amount ?? 0) + ($dc_cgst_amount ?? 0) , 2)}}</td>
        <td width="5.5%" style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">{{number_format(($sgst ?? 0) + ($dc_sgst ?? 0) , 2)}}</td>
        <td width="5.5%" style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">{{number_format(($sgst_amount ?? 0) + ($dc_sgst_amount ?? 0) , 2)}}</td>
        <td width="5.5%" style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">{{number_format(($igst ?? 0) + ($dc_igst ?? 0) , 2)}}</td>
        <td width="5.5%" style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">{{number_format(($igst_amount ?? 0) + ($dc_igst_amount ?? 0) , 2)}}</td>
        <td width="8%" style="border-right-color: rgb(207, 2, 2);border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">{{number_format(($data->grand_total ?? 0) + ($dc_amount ?? 0) , 2)}}</td>
    </tr>
    <tr>
        <td width="100%" style="text-align:center; border-bottom-color:black; border-left-color: rgb(207, 2, 2);border-top-color: black;border-right-color: rgb(207, 2, 2);"><b>Total Invoice Amount (in words): {{$in_words}}</b></td>
    </tr>
      <tr>
        <td width="33.33%" style="border-left-color: rgb(207, 2, 2);border-top-color: black;">Bank Details</td>
        <td width="33.33%" style="border-left-color: black;border-top-color: black;border-right-color: black;">2611936</td>
        <td width="33.33%" style="border-right-color: rgb(207, 2, 2);border-top-color: black;">State: Maharashtra</td>
    </tr>
     <tr>
        <td width="33.33%" style="border-left-color: rgb(207, 2, 2);border-top-color: black;">Bank A/C:</td>
        <td width="33.33%" style="border-left-color: black;border-top-color: black;border-right-color: black;"></td>
        <td width="33.33%" style="border-right-color: rgb(207, 2, 2);border-top-color: black;">Ceritified that the particular given above are true and correct</td>
    </tr>
     <tr>
        <td width="33.33%" style="border-left-color: rgb(207, 2, 2);border-top-color: black; border-right-color: black;">Bank IFSC:</td>
        <td width="33.33%" style="border-left-color: border-right-color: black;"></td>
        <td width="33.33%" style="border-right-color: rgb(207, 2, 2);border-top-color: black; border-left-color: black; text-align:center">For Ferns N Petals Pvt. Ltd.</td>
    </tr>
     <tr>
        <td width="33.33%" style="border-left-color: rgb(207, 2, 2);border-top-color: black; border-bottom-color: rgb(207, 2, 2);border-right-color: black;text-align:center"> <br><br><br><br> Terms & Conditions</td>
        <td width="33.33%" style="border-left-color: border-right-color: black; border-bottom-color: rgb(207, 2, 2);text-align:center"><br><br><br><br>Common Seal</td>
        <td width="33.33%" style="border-right-color: rgb(207, 2, 2); border-left-color: black; border-bottom-color: rgb(207, 2, 2); text-align:center"><br><br><br><br>Authorized Signatory</td>
    </tr>
    <tr>
        <td width="90%" style="border-top-color: rgb(207, 2, 2); font-size:9px;">This is a computer generated invoice does not require signature</td>
        <td width="10%" style="border-top-color: rgb(207, 2, 2); font-size:9px;">E. & O. E.</td>
    </tr>
</table>
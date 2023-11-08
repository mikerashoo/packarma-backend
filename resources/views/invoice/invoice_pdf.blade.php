<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
    .table_row {
        font-size: 9px;
    }
</style>

<table cellspacing="0" cellpadding="4" border="0">

    <tr class="table_row">
        <td width="30%" style="border-left-color: rgb(207, 2, 2); border-top-color:rgb(207, 2, 2);text-align:center;">
            <img src="{{ $no_image }}" alt="" width="80" height="80">
        </td>
        <td width="70%"
            style="border-left-color: black; border-right-color: rgb(207, 2, 2); border-top-color:rgb(207, 2, 2);"><br>
            <h3>&nbsp;&nbsp;&nbsp;{{ $invoice->user->email }}</h3><br>
            &nbsp;&nbsp;{{ $invoice->address->billing_address ?? ($invoice->address->address_name ?? '') }} <br><br>
            &nbsp;&nbsp;<b>Tel:</b>{{ $invoice->address->mobile_no }} &nbsp;&nbsp; <b>Email
                ID:</b>{{ $invoice->user->email }}

            <h3 style="color: #BAD530; margin-left: 10px">GST and PAn and CIN</h3>
        </td>
    </tr>
    <tr class="table_row">
        <td width="35%" style="border-left-color: rgb(207, 2, 2); border-top-color:black; "><b>Registration Type:</b>
            {{ !empty($invoice->gstin) ? 'Registered' : 'Not Registered' }}</td>
        <td width="30%" style="border-left-color: black;border-top-color: black;border-right-color: black;"><b>GST
                Registered:</b> {{ !empty($invoice->gstin) ? 'Yes' : 'No' }} </td>
        <td width="35%" style="border-right-color: rgb(207, 2, 2);border-top-color: black;"><b>GSTIN:</b>
            {{ $invoice->gstin ?? '' }}</td>
    </tr>
    <tr>
        <td width="100%"
            style="text-align:center; border-bottom-color:black; border-left-color: rgb(207, 2, 2);border-top-color: black;border-right-color: rgb(207, 2, 2); background-color:#bdd8f0;">
            <h1>Invoice Cum Bill of Supply</h1>
        </td>
    </tr>
    <tr class="table_row">
        <td width="33.33%"
            style="border-left-color: rgb(207, 2, 2);border-top-color: black; border-right-color: black;">Invoice No:
            {{ $financialYear }}/{{ $invoice->id }}</td>
        <td width="33.33%"></td>
        <td width="33.33%"
            style="border-right-color: rgb(207, 2, 2);border-top-color: black; border-left-color: black;">State:
            {{ $invoice->address->state_name }}</td>
    </tr>
    <tr class="table_row">
        <td width="33.33%"
            style="border-left-color: rgb(207, 2, 2);border-top-color: black; border-right-color: black;">Invoice Date:
            {{ $invoiceDate }}</td>
        <td width="33.33%"></td>
        <td width="33.33%"
            style="border-right-color: rgb(207, 2, 2);border-top-color: black; border-left-color: black; border-bottom-color: black;">
            State Code:
            IN-MH</td>
    </tr>

    <tr>
        <td width="50%"
            style="border-left-color: rgb(207, 2, 2);border-top-color: black; border-right-color: black; border-bottom-color: black; text-align:center; background-color:#bdd8f0;">
            <b>Bill to Party</b>
        </td>
    </tr>
    <tr class="table_row">
        <td width="50%"
            style="border-left-color: rgb(207, 2, 2);border-top-color: black; border-right-color: black;">Name:
            {{ $invoice->user->name ?? '' }} </td>
    </tr>
    <tr class="table_row">

        <td width="50%"
            style="border-left-color: rgb(207, 2, 2);border-top-color: black; border-right-color: black;">Address:
            {{ $invoice->address->billing_address ?? '' }} </td>
    </tr>
    <tr class="table_row">
        <td width="50%"
            style="border-left-color: rgb(207, 2, 2);border-top-color: black; border-right-color: black;">GSTIN/UIN:
            {{ $invoice->address->gstin ?? '' }} </td>
        {{-- <td width="50%" style="border-right-color: rgb(207, 2, 2);border-top-color: black;">GSTIN: NA</td> --}}
    </tr>
    <tr class="table_row">
        <td width="50%"
            style="border-left-color: rgb(207, 2, 2);border-top-color: black; border-bottom-color: black; border-right-color: black;">
            State:
            {{ $invoice->address->state_name ?? '' }} Code: {{ $invoice->address->country_name ?? '' }} -
            {{ $invoice->address->state_name ?? '' }}</td>
        {{-- <td width="50%" style="border-right-color: rgb(207, 2, 2);border-top-color: black;">State:
            {{ $shipping_data->state_name ?? '' }} Code: {{ $shipping_data->country_name ?? '' }} -
            {{ $shipping_data->state_name ?? '' }}</td> --}}
    </tr>
    <tr>
        <td width="5%" rowspan="2"
            style="border-left-color: rgb(207, 2, 2);border-top-color: black; border-bottom-color: black; border-right-color: black; font-size: 7px; text-align:center; background-color:#bdd8f0;">
            Sr.No</td>
        <td width="9%" rowspan="2"
            style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; vertical-align: middle; background-color:#bdd8f0;">
            Production Description</td>
        <td width="6%" rowspan="2">
        </td>
        <td width="5%" rowspan="2">
        </td>
        <td width="5%" rowspan="2">
        </td>
        <td width="7%" rowspan="2">
        </td>
        <td width="7%" rowspan="2"
            style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center; background-color:#bdd8f0;">
            Amount</td>
        <td width="8%" rowspan="2"
            style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center; background-color:#bdd8f0;">
            Discount /Adj</td>
        <td width="7%" rowspan="2"
            style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center; background-color:#bdd8f0;">
            Taxable Value</td>
        <td width="11%" colspan="2"
            style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center; background-color:#bdd8f0;">
            CGST</td>
        <td width="11%" colspan="2"
            style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center; background-color:#bdd8f0;">
            SGST</td>
        <td width="11%" colspan="2"
            style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center; background-color:#bdd8f0;">
            IGST</td>
        <td width="8%" rowspan="2"
            style="border-right-color: rgb(207, 2, 2);border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center; background-color:#bdd8f0;">
            Total</td>
    </tr>
    <tr>
        <td
            style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center; background-color:#bdd8f0;">
            Rate</td>
        <td
            style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center; background-color:#bdd8f0;">
            Amount</td>
        <td
            style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center; background-color:#bdd8f0;">
            Rate</td>
        <td
            style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center; background-color:#bdd8f0;">
            Amount</td>
        <td
            style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center; background-color:#bdd8f0;">
            Rate</td>
        <td
            style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center; background-color:#bdd8f0;">
            Amount</td>
    </tr>

    <tr>
        <td width="5%"
            style="border-left-color: rgb(207, 2, 2);border-top-color: black; border-bottom-color: black; border-right-color: black; font-size: 7px; text-align:center;">
            1</td>
        <td width="9%"
            style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">
            {{ $invoice->title ?? '' }} </td>
        <td width="6%"> </td>
        <td width="5%"> </td>
        <td width="5%"> </td>
        <td width="7%"> </td>
        <td width="7%"
            style="border-right-color: black;border-top-color: black; border-left-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">
            {{ $invoice->gst_prices->sub_total ?? '' }}</td>
        <td width="8%"
            style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">
            {{ $data->discount ?? 0 }}</td>
        <td width="7%"
            style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">
            {{ $invoice->gst_prices->sub_total ?? '' }}</td>
        <td width="5.5%"
            style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">
            {{ $invoice->gst_prices->cgst ?? '' }}</td>
        <td width="5.5%"
            style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">
            {{ $invoice->gst_prices->cgst_total ?? '' }}</td>
        <td width="5.5%"
            style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">
            {{ $invoice->gst_prices->sgst ?? '' }}</td>
        <td width="5.5%"
            style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">
            {{ $invoice->gst_prices->sgst_total ?? '' }}
        </td>
        <td width="5.5%"
            style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">
            {{ $invoice->gst_prices->igst ?? '' }}
        </td>
        <td width="5.5%"
            style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">
            {{ $invoice->gst_prices->igst_total ?? '' }}
        </td>
        <td width="8%"
            style="border-right-color: rgb(207, 2, 2);border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">
            {{ $invoice->gst_prices->total ?? '' }}</td>
    </tr>


    <tr>
        <td width="25%"
            style="border-left-color: rgb(207, 2, 2);border-top-color: black; border-bottom-color: black; border-right-color: black; font-size: 9px; text-align:center;">
            <b>Grand Total</b>
        </td>
        <td width="5%"
            style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">
        </td>
        <td width="7%"
            style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">
        </td>
        <td width="7%"
            style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">
            {{ $invoice->gst_prices->sub_total ?? '' }}
        </td>
        <td width="8%"
            style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">
            0</td>
        <td width="7%"
            style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">
            {{ $invoice->gst_prices->sub_total ?? '' }}</td>
        <td width="5.5%"
            style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">
            {{ $invoice->gst_prices->cgst ?? '' }}</td>
        <td width="5.5%"
            style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">
            {{ $invoice->gst_prices->cgst_total ?? '' }}</td>
        <td width="5.5%"
            style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">
            {{ $invoice->gst_prices->sgst ?? '' }}</td>
        <td width="5.5%"
            style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">
            {{ $invoice->gst_prices->sgst_total ?? '' }}
        </td>
        <td width="5.5%"
            style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">
            {{ $invoice->gst_prices->igst ?? '' }}
        </td>
        <td width="5.5%"
            style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">
            {{ $invoice->gst_prices->igst_total ?? '' }}
        </td>
        <td width="8%"
            style="border-right-color: rgb(207, 2, 2);border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">
            {{ $invoice->gst_prices->total ?? '' }}</td>
    </tr>
    <tr class="table_row">
        <td width="100%"
            style="text-align:center; border-bottom-color:black; border-left-color: rgb(207, 2, 2);border-top-color: black;border-right-color: rgb(207, 2, 2);">
            <b>Total Invoice Amount (in words): {{ $in_words }}</b>
        </td>
    </tr>
    <tr class="table_row">
        <td width="33.33%" style="border-left-color: rgb(207, 2, 2);border-top-color: black;">Bank Details</td>
        <td width="33.33%" style="border-left-color: black;border-top-color: black;border-right-color: black;">Order
            id : {{ $orderFormatedId ?? '-' }}</td>
        <td width="33.33%" style="border-right-color: rgb(207, 2, 2);border-top-color: black;">State: Maharashtra</td>
    </tr>
    <tr class="table_row">
        <td width="33.33%" style="border-left-color: rgb(207, 2, 2);border-top-color: black;">Bank Name:
            {{ $invoice->bank_name ?? '-' }}</td>
        <td width="33.33%" style="border-left-color: black;border-top-color: black;border-right-color: black;"></td>
        <td width="33.33%" style="border-right-color: rgb(207, 2, 2);border-top-color: black;">Ceritified that the
            particular given above are true and correct</td>
    </tr>
    <tr class="table_row">
        <td width="33.33%"
            style="border-left-color: rgb(207, 2, 2);border-top-color: black; border-right-color: black;">Bank A/C No.:
            {{ $invoice->account_number ?? '-' }}</td>
        <td width="33.33%" style="border-left-color: border-right-color: black;"></td>
        <td width="33.33%"
            style="border-right-color: rgb(207, 2, 2);border-top-color: black; border-left-color: black; text-align:center">
            For Packarma</td>
    </tr>
    <tr class="table_row">
        <td width="33.33%"
            style="border-left-color: rgb(207, 2, 2);border-top-color: black; border-right-color: black;">Account Name
            : {{ $invoice->account_name ?? '-' }}</td>
        <td width="33.33%" style="border-left-color: border-right-color: black;"></td>
        <td width="33.33%" style="border-left-color:black; border-right-color: red;"></td>
    </tr>
    <tr class="table_row">
        <td width="33.33%"
            style="border-left-color: rgb(207, 2, 2);border-top-color: black; border-bottom-color: rgb(207, 2, 2);border-right-color: black;">
            Bank IFSC: {{ $invoice->ifsc_code ?? '-' }}</td>
        <td width="33.33%"
            style="border-left-color: border-right-color: black; border-bottom-color: rgb(207, 2, 2);text-align:center">
            <br><br><br><br>Common Seal
        </td>
        <td width="33.33%"
            style="border-right-color: rgb(207, 2, 2); border-left-color: black; border-bottom-color: rgb(207, 2, 2); text-align:center">
            <br><br><br><br>Authorized Signatory
        </td>
    </tr>
    <tr class="table_row">
        <td width="90%" style="border-top-color: rgb(207, 2, 2); font-size:9px;">This is a computer generated
            invoice does not require signature</td>
        <td width="10%" style="border-top-color: rgb(207, 2, 2); font-size:9px;">E. & O. E.</td>
    </tr>
</table>

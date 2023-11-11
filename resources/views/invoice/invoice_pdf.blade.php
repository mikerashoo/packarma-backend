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
            style="border-left-color: black; border-right-color: rgb(207, 2, 2); border-top-color:rgb(207, 2, 2);">
            <br>
            <h2>Packult Studio Pvt. Ltd.</h2>
            230, Udyog Bhavan, Sonawala Rd, Jay Prakash Nagar, <br>Goregaon, Mumbai, Maharashtra 400063
        </td>
    </tr>
    <tr class="table_row">
        <td width="35%" style="border-left-color: rgb(207, 2, 2); border-top-color:black; "><b>GSTIN</b>
            27AAMCP2500K1ZD</td>
        <td width="35%" style="border-left-color: black;border-top-color: black;border-right-color: black;"><b>CIN
                No. :</b> U74999MH2021PTC366605</td>
        <td width="30%" style="border-right-color: rgb(207, 2, 2);border-top-color: black;"><b>PAN No.: </b>
            AAMCP2500K</td>
    </tr>


    <tr>
        <td width="100%"
            style="text-align:center; border-bottom-color:black; border-left-color: rgb(207, 2, 2);border-top-color: black;border-right-color: rgb(207, 2, 2); background-color:#BAD530;">
            <h1>Tax Invoice</h1>
        </td>
    </tr>
    <tr class="table_row">
        <td width="50%"
            style="border-left-color: rgb(207, 2, 2);border-top-color: black; border-right-color: black;">Invoice No:
            {{ $financialYear }}/{{ $invoice->id }}</td>

        <td width="50%"
            style="border-right-color: rgb(207, 2, 2);border-top-color: black; border-left-color: black;">State:
            {{ $invoice->address->state->state_name }}</td>
    </tr>
    <tr class="table_row">
        <td width="50%"
            style="border-left-color: rgb(207, 2, 2);border-top-color: black; border-right-color: black;">Invoice Date:
            {{ $invoiceDate }}</td>

        <td width="50%"
            style="border-right-color: rgb(207, 2, 2);border-top-color: black; border-left-color: black; border-bottom-color: black;">
            State Code: IN-MH</td>
    </tr>

    <tr>
        <td width="100%"
            style="border-left-color: rgb(207, 2, 2);border-top-color: black; border-right-color: black; border-bottom-color: black; text-align:center; background-color:#BAD530;">
            <b>Bill to Party</b>
        </td>
    </tr>
    <tr class="table_row">
        <td width="100%"
            style="border-left-color: rgb(207, 2, 2);border-top-color: black; border-right-color: black;">Name:
            {{ $invoice->user->name ?? '' }} </td>
    </tr>
    <tr class="table_row">

        <td width="100%"
            style="border-left-color: rgb(207, 2, 2);border-top-color: black; border-right-color: black;">Address:
            {{ $invoice->address->billing_address ?? '' }} </td>
    </tr>
    <tr class="table_row">
        <td width="100%"
            style="border-left-color: rgb(207, 2, 2);border-top-color: black; border-right-color: black;">GSTIN/UIN:
            {{ $invoice->address->gstin ?? '' }} </td>
        {{-- <td width="100%" style="border-right-color: rgb(207, 2, 2);border-top-color: black;">GSTIN: NA</td> --}}
    </tr>
    <tr class="table_row">
        <td width="100%"
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
            style="border-left-color: rgb(207, 2, 2);border-top-color: black; border-bottom-color: black; border-right-color: black; font-size: 7px; text-align:center; background-color:#BAD530;">
            Sr.No</td>
        <td width="32%" rowspan="2"
            style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; vertical-align: middle; background-color:#BAD530;">
            Production Description</td>
        {{-- <td width="6%" rowspan="2">
        </td>
        <td width="5%" rowspan="2">
        </td>
        <td width="5%" rowspan="2">
        </td>
        <td width="7%" rowspan="2">
        </td> --}}
        <td width="7%" rowspan="2"
            style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center; background-color:#BAD530;">
            Amount</td>
        <td width="8%" rowspan="2"
            style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center; background-color:#BAD530;">
            Discount /Adj</td>
        <td width="7%" rowspan="2"
            style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center; background-color:#BAD530;">
            Taxable Value</td>
        <td width="11%" colspan="2"
            style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center; background-color:#BAD530;">
            CGST</td>
        <td width="11%" colspan="2"
            style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center; background-color:#BAD530;">
            SGST</td>
        <td width="11%" colspan="2"
            style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center; background-color:#BAD530;">
            IGST</td>
        <td width="8%" rowspan="2"
            style="border-right-color: rgb(207, 2, 2);border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center; background-color:#BAD530;">
            Total</td>
    </tr>
    <tr>
        <td
            style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center; background-color:#BAD530;">
            Rate</td>
        <td
            style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center; background-color:#BAD530;">
            Amount</td>
        <td
            style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center; background-color:#BAD530;">
            Rate</td>
        <td
            style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center; background-color:#BAD530;">
            Amount</td>
        <td
            style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center; background-color:#BAD530;">
            Rate</td>
        <td
            style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center; background-color:#BAD530;">
            Amount</td>
    </tr>

    <tr>
        <td width="5%"
            style="border-left-color: rgb(207, 2, 2);border-top-color: black; border-bottom-color: black; border-right-color: black; font-size: 7px; text-align:center;">
            1</td>
        <td width="32%"
            style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">
            {{ $invoice->title ?? '' }} </td>

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
        <td width="37%"
            style="border-left-color: rgb(207, 2, 2);border-top-color: black; border-bottom-color: black; border-right-color: black; font-size: 9px; text-align:center;">
            <b>Grand Total</b>
        </td>
        {{-- <td width="5%"
            style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">
        </td>
        <td width="7%"
            style="border-right-color: black;border-top-color: black; border-bottom-color: black; font-size: 7px; text-align:center;">
        </td> --}}
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
        <td width="33.33%"
            style="border-left-color: black;border-top-color: black; border-bottom-color:  black;border-right-color: black;">
            Transaction Id :
            {{ $transactionId }}</td>
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
    <ol style="padding-left: 0%; font-size: 10px">
        <li>
            This is a computer-generated invoice that does not require a signature.
        </li>
        <li>
            Packarma is owned by Packult Studio Private Limited.
        </li>
        <li>
            Declaration: It is hereby certified that the above information is true and correct and the GST charged in
            the above bill be paid to the Government exchequer as per the provisions of GST. It is further certified
            that the taxable service described above has been rendered by us and no other consideration is received
            towards the above service, directly or indirectly from the service receiver.

        </li>
    </ol>
</table>

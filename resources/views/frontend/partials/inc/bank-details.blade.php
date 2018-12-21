<?php $bank_accounts = json_decode(App\Setting::get_setting('bank_account')); ?>

@if($bank_accounts)
    <table width="100%" border="1" cellspacing="0" cellpadding="2"> 
    <tr>
        <th>Account Name</th>
        <th>Account Number</th>
        <th>Bank Name</th>
        <th>Bank Address</th>
        <th>IBAN</th>
        <th>BIC / Swift </th>
    </tr>
    @foreach($bank_accounts as $details)
        <tr>
            @foreach($details as $d_k => $d_v)
                @if( ! in_array($d_k, ['location_address']) )
                <td>{{ $d_v }}</td>
                @endif
            @endforeach
        </tr>
    @endforeach
    </table>
@endif

<tr class="table-ba-row">
    <td><i class="fa fa-sort"></i></td>
    <td><input type="text" name="bank_account[{{ $b }}][account_name]" value="{{ @$ba['account_name'] }}" class="form-control form-control-sm rounded-0 input-f"></td>
    <td><input type="text" name="bank_account[{{ $b }}][account_number]" value="{{ @$ba['account_number'] }}" class="form-control form-control-sm rounded-0 input-f"></td>
    <td><input type="text" name="bank_account[{{ $b }}][bank_name]" value="{{ @$ba['bank_name'] }}" class="form-control form-control-sm rounded-0 input-f"></td>
    <td><input type="text" name="bank_account[{{ $b }}][bank_address]" value="{{ @$ba['bank_address'] }}" class="form-control form-control-sm rounded-0 input-f"></td>
    <td><input type="text" name="bank_account[{{ $b }}][iban]" value="{{ @$ba['iban'] }}" class="form-control form-control-sm rounded-0 input-f"></td>
    <td><input type="text" name="bank_account[{{ $b }}][bic_swift]" value="{{ @$ba['bic_swift'] }}" class="form-control form-control-sm rounded-0 input-f"></td>
    <td><input type="text" name="bank_account[{{ $b }}][location_address]" value="{{ @$ba['location_address'] }}" class="form-control form-control-sm rounded-0 input-f"></td>
    <td class="align-middle text-center pl-2 h6">
        <a href="" class="btn-remove-ba" data-target=".table-ba"><i class="fa fa-times text-danger"></i></a>
    </td>
</tr>
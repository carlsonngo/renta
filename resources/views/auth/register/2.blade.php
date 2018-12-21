<div class="steps text-center">
    <ol>
        <li>
            <div>
                <h2>1</h2>
            </div>
        </li>
        <li>
            <div class="steps-divider"></div>
        </li>
        <li>
            <div>
                <h2 class="active">2</h2>
            </div>
        </li>
        <li>
            <div class="steps-divider"></div>
        </li>
        <li>
            <div>
                <h2>3</h2>
            </div>
        </li>
        <p>Account Details</p>
    </ol>
</div>

@include('notification')

<div class="account-details">
    <div class="row">
        <div class="col-lg">
            <h3 class="mb-0">Fullname</h3>
            <p>As it appears on your permanent ID</p>
            
            <div class="row">
                <div class="form-group col-md-6">
                    <input type="text" name="suffix" class="form-control" placeholder="Suffix" value="{{ Input::old('suffix', @$suffix) }}">
                </div>
                <div class="form-group col-md-6">
                    <input type="text" name="firstname" class="form-control" placeholder="First Name *" value="{{ Input::old('firstname', @$firstname) }}">
                    {!! $errors->first('firstname','<p class="text-danger my-2">:message</p>') !!}
                </div> 
            </div>

            <div class="row">
                <div class="form-group col-md-6">
                    <input type="text" name="middlename" class="form-control" placeholder="Middle Name" value="{{ Input::old('middlename', @$middlename) }}">
                </div>
                <div class="form-group col-md-6">
                    <input type="text" name="lastname" class="form-control" placeholder="Last Name *" value="{{ Input::old('lastname', @$lastname) }}">
                    {!! $errors->first('lastname','<p class="text-danger my-2">:message</p>') !!}
                </div> 
            </div>

            <div class="form-group">
                <input type="text" name="nickname" class="form-control" placeholder="Nickname" value="{{ Input::old('nickname', @$nickname) }}">
            </div> 
        </div>
        <div class="col-lg">
            <h3>Contact Details</h3>

            <div class="row">
                <div class="form-group col-md-6">
                    <input type="text" name="landline" class="form-control" placeholder="Landline *" value="{{ Input::old('landline', @$landline) }}">
                    {!! $errors->first('landline','<p class="text-danger my-2">:message</p>') !!}
                </div>
                <div class="form-group col-md-6">
                    <input type="text" name="mobile" class="form-control" placeholder="Mobile *" value="{{ Input::old('mobile', @$mobile) }}">
                    {!! $errors->first('mobile','<p class="text-danger my-2">:message</p>') !!}
                </div> 
            </div>

            <div class="form-group">
                <input type="text" name="email" class="form-control" placeholder="Email *" value="{{ Input::old('email', @$email) }}">
                {!! $errors->first('email','<p class="text-danger my-2">:message</p>') !!}
            </div> 

            <h3 class="mb-0 mt-4">Date of Birth</h3>
            <div class="form-group">
                <input type="text" name="birthday" class="form-control datepicker date-format" placeholder="Birthday *" value="{{ Input::old('birthday', @$birthday) }}">
                {!! $errors->first('birthday','<p class="text-danger my-2">:message</p>') !!}
            </div> 

        </div>
    </div>
    <div class="row">
        <div class="col-lg">
            <h3>Home Address</h3>

            <div class="row">
                <div class="form-group col-md-6">
                    <input type="text" name="home_floor" class="form-control" placeholder="Floor" value="{{ Input::old('home_floor', @$home_floor) }}">
                </div>
                <div class="form-group col-md-6">
                    <input type="text" name="home_unit_no" class="form-control" placeholder="Unit No. *" value="{{ Input::old('home_unit_no', @$home_unit_no) }}">
                    {!! $errors->first('home_unit_no','<p class="text-danger my-2">:message</p>') !!}
                </div> 
            </div>

            <div class="row">
                <div class="form-group col-md-6">
                    <input type="text" name="home_building_name" class="form-control" placeholder="Building Name" value="{{ Input::old('home_building_name', @$home_building_name) }}">
                </div>
                <div class="form-group col-md-6">
                    <input type="text" name="home_street_address_1" class="form-control" placeholder="Street Address 1 *" value="{{ Input::old('home_street_address_1', @$home_street_address_1) }}">
                    {!! $errors->first('home_street_address_1','<p class="text-danger my-2">:message</p>') !!}
                </div> 
            </div>

            <div class="row">
                <div class="form-group col-md-6">
                    <input type="text" name="home_street_address_2" class="form-control" placeholder="Street Address 2" value="{{ Input::old('home_street_address_2', @$home_street_address_2) }}">
                </div>
                <div class="form-group col-md-6">
                    {{ Form::select('home_country', countries(), Input::old('home_country', @$home_country), ['class' => 'form-control']) }}
                    {!! $errors->first('home_country','<p class="text-danger my-2">:message</p>') !!}
                </div> 
            </div>

            <div class="row">
                <div class="form-group col-md-6">
                    <input type="text" name="home_region" class="form-control" placeholder="Region *" value="{{ Input::old('home_region', @$home_region) }}">
                    {!! $errors->first('home_region','<p class="text-danger my-2">:message</p>') !!}
                </div>
                <div class="form-group col-md-6">
                    <input type="text" name="home_city" class="form-control" placeholder="City *" value="{{ Input::old('home_city', @$home_city) }}">
                    {!! $errors->first('home_city','<p class="text-danger my-2">:message</p>') !!}
                </div> 
            </div>

            <div class="row">
                <div class="form-group col-md-6">
                    <input type="text" name="home_barangay" class="form-control" placeholder="Barangay *" value="{{ Input::old('home_barangay', @$home_barangay) }}">
                    {!! $errors->first('home_barangay','<p class="text-danger my-2">:message</p>') !!}
                </div>
                <div class="form-group col-md-6">
                    <input type="text" name="home_zipcode" class="form-control" placeholder="Zip Code *" value="{{ Input::old('home_zipcode', @$home_zipcode) }}">
                    {!! $errors->first('home_zipcode','<p class="text-danger my-2">:message</p>') !!}
                </div> 
            </div>
        </div>
        <div class="col-lg">
            <div class="row align-items-center mb-3">
                <div class="col">
                    <h3 class="m-0">Delivery Address</h3>                    
                </div>
                <div class="col">
                    <div class="form-check">
                        <input type="hidden" name="same_as_home" value="0">
                        <input type="checkbox" name="same_as_home" class="form-check-input" id="same_as_home" value="1" {{ checked(1, $same_as_home = Input::old('same_as_home', @$same_as_home)) }}>
                        <label class="form-check-label" for="same_as_home">Same as Home Address</label>
                    </div>                    
                </div>
            </div>

            <div class="delivery-address" style="{{ $same_as_home ? 'display:none;' : '' }}">
                <div class="row">
                    <div class="form-group col-md-6">
                        <input type="text" name="delivery_floor" class="form-control" placeholder="Floor" value="{{ Input::old('delivery_floor', @$delivery_floor) }}">
                    </div>
                    <div class="form-group col-md-6">
                        <input type="text" name="delivery_unit_no" class="form-control" placeholder="Unit No. *" value="{{ Input::old('delivery_unit_no', @$delivery_unit_no) }}">
                        {!! $errors->first('delivery_unit_no','<p class="text-danger my-2">:message</p>') !!}
                    </div> 
                </div>

                <div class="row">
                    <div class="form-group col-md-6">
                        <input type="text" name="delivery_building_name" class="form-control" placeholder="Building Name" value="{{ Input::old('delivery_building_name', @$delivery_building_name) }}">
                    </div>
                    <div class="form-group col-md-6">
                        <input type="text" name="delivery_street_address_1" class="form-control" placeholder="Street Address 1 *" value="{{ Input::old('delivery_street_address_1', @$delivery_street_address_1) }}">
                        {!! $errors->first('delivery_street_address_1','<p class="text-danger my-2">:message</p>') !!}
                    </div> 
                </div>

                <div class="row">
                    <div class="form-group col-md-6">
                        <input type="text" name="delivery_street_address_2" class="form-control" placeholder="Street Address 2" value="{{ Input::old('delivery_street_address_2', @$delivery_street_address_2) }}">
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::select('delivery_country', countries(), Input::old('delivery_country', @$delivery_country), ['class' => 'form-control']) }}
                        {!! $errors->first('delivery_country','<p class="text-danger my-2">:message</p>') !!}
                    </div> 
                </div>

                <div class="row">
                    <div class="form-group col-md-6">
                        <input type="text" name="delivery_region" class="form-control" placeholder="Region *" value="{{ Input::old('delivery_region', @$delivery_region) }}">
                        {!! $errors->first('delivery_region','<p class="text-danger my-2">:message</p>') !!}
                    </div>
                    <div class="form-group col-md-6">
                        <input type="text" name="delivery_city" class="form-control" placeholder="City *" value="{{ Input::old('delivery_city', @$delivery_city) }}">
                        {!! $errors->first('delivery_city','<p class="text-danger my-2">:message</p>') !!}
                    </div> 
                </div>

                <div class="row">
                    <div class="form-group col-md-6">
                        <input type="text" name="delivery_barangay" class="form-control" placeholder="Barangay *" value="{{ Input::old('delivery_barangay', @$delivery_barangay) }}">
                        {!! $errors->first('delivery_barangay','<p class="text-danger my-2">:message</p>') !!}
                    </div>
                    <div class="form-group col-md-6">
                        <input type="text" name="delivery_zipcode" class="form-control" placeholder="Zip Code *" value="{{ Input::old('delivery_zipcode', @$delivery_zipcode) }}">
                        {!! $errors->first('delivery_zipcode','<p class="text-danger my-2">:message</p>') !!}
                    </div> 
                </div>
                
            </div>

        </div>
    </div>

    @if( session('step-1')['membership'] == 'premium' )

    <h3 class="mb-0 mt-4">Additional Requirements</h3>

    <div class="row">
        <div class="col-lg">
            <div class="form-group">
                <label for="government_id">Government ID</label>
                <input type="file" name="government_id" class="form-control" id="government_id" accept="image/*">
            </div>

            @if( @$government_id['data'] )
            <div class="img-container border rounded">
                <img src="{{ $government_id['data'] }}" class="img-fluid">                
            </div>
            @endif
        </div>
        <div class="col-lg">
            <div class="form-group">
                <label for="bank_statement">Scanned bank statements or Payslips (Last 3 months)</label>
                <input type="file" name="bank_statement" class="form-control" id="bank_statement" accept="image/*">
            </div>
            @if( @$bank_statement['data'] )
            <div class="img-container border rounded">
                <img src="{{ $bank_statement['data'] }}" class="img-fluid">
            </div>
            @endif
        </div>
    </div>
    @endif

    <div class="row my-5 align-items-center">
        <div class="col">
            <a href="?step=1" class="btn text-left btn-back">
                Back to step 1
                <div class="small text-muted mt-2">Choose Your Membership</div>
            </a>
        </div>
        <div class="col text-right">
            <button type="submit" class="btn btn-brown py-4 px-5 rounded-0">Save and Continue</button>                
        </div>
    </div>

    <h6>Mandatory Input Fields <b class="text-danger">*</b></h6>
</div>

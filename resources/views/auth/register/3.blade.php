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
                <h2>2</h2>
            </div>
        </li>
        <li>
            <div class="steps-divider"></div>
        </li>
        <li>
            <div>
                <h2 class="active">3</h2>
            </div>
        </li>
        <p>Your profile</p>
        <p>(We meant to get to know you better)</p>
    </ol>
</div>

@include('notification')

<div class="account-details">

    <h3 class="mb-4">Let's get personal</h3>

    <div class="row">
        <div class="col-lg">                    

            <div class="form-group">
                <div class="custom-control custom-radio custom-control-inline"">
                  <input type="radio" id="male" name="gender" class="custom-control-input" value="male" {{ checked('male', Input::old('gender', @$gender)) }}>
                  <label class="custom-control-label" for="male">Male</label>
                </div>

                <div class="custom-control custom-radio custom-control-inline"">
                  <input type="radio" id="female" name="gender" class="custom-control-input" value="female" {{ checked('female', Input::old('gender', @$gender)) }}>
                  <label class="custom-control-label" for="female">Female *</label>
                </div>    
                {!! $errors->first('gender','<p class="text-danger my-2">:message</p>') !!}            
            </div>

            <div class="form-group">
                <input type="text" name="industry" class="form-control" placeholder="Industry" value="{{ Input::old('industry', @$industry) }}">
            </div> 
            <div class="form-group">
                <input type="text" name="profession" class="form-control" placeholder="Profession" value="{{ Input::old('profession', @$profession) }}">
            </div>                        
            <div class="form-group">
                <input type="text" name="how_did_you_hear_from_us" class="form-control" placeholder="How did you here from us? *" value="{{ Input::old('how_did_you_hear_from_us', @$how_did_you_hear_from_us) }}">
                {!! $errors->first('how_did_you_hear_from_us','<p class="text-danger my-2">:message</p>') !!}
            </div>
        </div>
        <div class="col-lg">
            <div class="form-group">
                <input type="text" name="blood_type" class="form-control" placeholder="Body type" value="{{ Input::old('blood_type', @$blood_type) }}">
            </div>
            <div class="form-group">
                <input type="text" name="clothing_style" class="form-control" placeholder="Clothing Style" value="{{ Input::old('clothing_style', @$clothing_style) }}">
            </div>
            <div class="form-group">
                <input type="text" name="preferred_style" class="form-control" placeholder="Preferred Style" value="{{ Input::old('preferred_style', @$preferred_style) }}">
            </div>
            <div class="form-group">
                <input type="text" name="color_scheme" class="form-control" placeholder="Color Scheme" value="{{ Input::old('color_scheme', @$color_scheme) }}">
            </div>
        </div>
    </div>



    <div class="row my-5 align-items-center">
        <div class="col">
            <a href="?step=2" class="btn text-left btn-back">
                Back to step 2
                <div class="small text-muted mt-2">Account Details</div>
            </a>
        </div>
        <div class="col text-right">
            <button type="submit" class="btn btn-brown py-4 px-5 rounded-0">Submit</button>                
        </div>
    </div>
    
    <h6>Mandatory Input Fields <b class="text-danger">*</b></h6>
</div>

<div class="steps text-center">
    <ol>
        <li>
            <div>
                <h2 class="active">1</h2>
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
                <h2>3</h2>
            </div>
        </li>
        <p>Choose Your Membership</p>
    </ol>
</div>

<input type="hidden" name="membership" value="1">
<div class="row membership-plans">
   
    @foreach($memberships as $membership)
    <?php $postmeta = get_meta( $membership->postMetas()->get() ); ?>
    <div class="col-lg">
        <div class="standard-membership">
            <div class="title">
                <h3>{{ $membership->post_title }}</h3>
                <p>{{ $postmeta->sub_title }}</p>
                <button type="submit" class="btn-choose btn btn-brown rounded-0 py-3 px-4" data-id="{{ $membership->post_name }}">Choose</button>
            </div>
            <div class="info">
                {!! $membership->post_content !!}
            </div>
        </div>
    </div>
    @endforeach


</div>


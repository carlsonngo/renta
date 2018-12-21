@if( $my_review )
    <div class="float-right">
        <a href="" class="edit-review">Edit</a> | 
        <a href="" class="text-danger delete-review" data-url="{{ route('backend.shop.review') }}">Delete</a>            
    </div>      
    <div class="mb-3">   
    <span class="rates-o">
        <span>{{ stars_review(5) }}</span>
        <span class="text-warning">{{ stars_review($my_review->post_title) }}</span>
    </span>      
    <span class="text-muted">by</span> {{ $my_review->user->fullname }}</div>
    <div class="mb-1 text-justify">{{ $my_review->post_content }}</div>
    <div class="text-muted small">{{ time_ago($my_review->created_at) }}</div>            
@endif


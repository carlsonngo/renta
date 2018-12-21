<div class="mb-4" id="note-{{ $note->id }}">
    <div class="note-content">
    	@if( $note->post_author == Auth::User()->id )
        <a href="#" data-url="{{ route('backend.general.note', [$note->id, true]) }}" data-id="{{ $note->id }}" class="delete-note text-danger float-right">
            <i class="fa fa-times"></i>
        </a>
        @endif
        {{ $note->post_content }}</div>
    <p class="px-3 pt-2">{!! $note->post_title !!}</p>    
</div>
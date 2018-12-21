	<div class="o-loader animated">
		<div class="row align-items-center h-100">
			<div class="col-12 m-auto w-25 text-center animated bounceIn">
				<img src="{{ asset('assets/img/loaders/5.gif' ) }}?<?php date('ymdhis'); ?>" class="img-thumbnail rounded-circle animated bounce delay-1s" width="90">			
			</div>
		</div>
	</div>

	<div class="modal fade confirm-modal" tabindex="-1" role="dialog" aria-hidden="true">
	  <div class="modal-dialog modal-md" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title"></h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	      </div>
	      <div class="modal-footer">
	        <a href="#" class="btn btn-danger btn-confirm">{{ trans('backend.confirm') }}</a>
	      </div>
	    </div>
	  </div>
	</div>

    <a href="javascript:" id="return-to-top"><i class="fas fa-chevron-up"></i></a>
  
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script type="text/javascript" src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/popper.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('plugins/inputmask/jquery.inputmask.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/select2/select2.min.js') }}"></script>  
	<script type="text/javascript" src="{{ asset('plugins/tinymce/js/tinymce/tinymce.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('plugins/tags/jquery.caret.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('plugins/tags/jquery.tag-editor.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/timepicker/mmnt.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/backend.js') }}?v={{ filemtime(public_path('js/backend.js')) }}"></script>

	@yield('plugin_script')

	@yield('script')
	<script type="text/javascript">
		$('.tags-group').tagEditor();   
	</script>

  </body>
</html>

<div class="modal fade" id="media-modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog mw-100 mx-3">
    <div class="modal-content">

      <!-- Modal body -->
      <div class="modal-body">
        <iframe src="" class="w-100" frameborder="0"></iframe>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">{{ trans('backend.close') }}</button>
      </div>

    </div>
  </div>
</div>

<style>
  .media-thumb {
      position: relative;
  }
  .delete-media:hover {
      background: #ff0000;
      color: #fff;        
  }
  .delete-media {
      position: absolute;
      top: -12px;
      right: -12px;
      border: 2px solid #ff0000;
      border-radius: 50px;
      padding: 5px 10px;
      color: #ff0000;
      background-color: #fff;
  }
  #media-modal iframe { height: 76vh; }    
  body.modal-open #media-modal { overflow-y: hidden; }
</style>

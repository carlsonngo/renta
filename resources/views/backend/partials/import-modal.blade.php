<div class="modal fade" id="import-data">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h5 class="modal-title">Import Data</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="#" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="file" name="file" class="form-control file-import"> 
                    {{ csrf_field() }}
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-block btn-import" disabled>Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
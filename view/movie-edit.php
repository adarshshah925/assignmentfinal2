<?php wp_enqueue_media();?>
<div class="container">
  <div class="row">
    <div class="alert alert-info">
      <h4> Edit Movie To Add Page</h4>
    </div>
    <div class="panel panel-primary">
      <div class="panel-heading">Edit Movie</div>
      <div class="panel-body">
       <form class="form-horizontal" action="javascript:void(0)" id="frmEditMovie">
            <input type="hidden" name="movie_id" value="<?php echo isset($_GET['edit'])?intval($_GET['edit']):0;?>">
            <div class="form-group">
              <label class="control-label col-sm-2" for="movie_name">Movie Name</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="movie_name" placeholder="Enter Movie Name" required name="movie_name">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-2" for="director">Director</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="director_name" placeholder="Enter Director Name" required name="director_name">
              </div>
            </div>
             <div class="form-group">
              <label class="control-label col-sm-2" for="category">Category</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="director_name" placeholder="category" required name="category">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-2" for="description">Description</label>
              <div class="col-sm-10">
                <textarea name="description" required id="description" placeholder="Movie Description" class="form-control"></textarea>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-2" for="movie_name">Upload Book Image</label>
              <div class="col-sm-10">
               <input type="button" class="btn btn-info" id="btn-upload" value="Upload Image">
               <span id="show-image"></span>
               <input type="hidden" name="image_name" id="image_name">
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-offset-2 col-sm-10">
              <button type="submit" name="update" class="btn btn-default">Update</button>
              </div>
            </div>
      </form>
      </div>
    </div>
  </div>
</div>
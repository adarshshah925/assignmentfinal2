<?php
 global $wpdb;
 $table_name=$wpdb->prefix.'my_movie_list';
 $result=$wpdb->get_results("SELECT * FROM $table_name");
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
   <div class="container">
   	<div class="row">
	<div class="pannel pannel-primary">
		<table id="my-movie" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Id</th>
                <th>Movie Name</th>
                <th>Director</th>
                <th>Category</th>
                <th>Image</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody >
				<?php $i=1; 
				foreach($result as $row){
					  $movie_id=$row->id;
                      $movie_name=$row->movie_name;
                      $director=$row->director;
                      $category=$row->category;
                      $movie_image=$row->movie_image;
                      $created_at=$row->created_at;
					?>
				<tr>
					<td><?php echo $i++; ?></td>
					<td><?php echo $movie_name; ?></td>
					<td><?php echo $director; ?></td>
					<td><?php echo $category; ?></td>
					<td><img src="<?php echo $movie_image;?>" style="height: 80px; width: 80px;"></td>
					<td><?php echo $created_at; ?></td>	
					<td>
						<a class="btn btn-info" href="admin.php?page=movie-edit&edit=<?php echo $movie_id;?>">Edit</a>
						<a  class="btn btn-danger btnmoviedelete" href="javascript:void(0)" data-id="<?php echo $movie_id;?>">Delete</a>
					</td>	
				</tr>
				<?php } ?>
			</tbody>
    </table>
</div>
</div>
</body>
</html>
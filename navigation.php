<!-- navbar -->
<div class="navbar navbar-default navbar-static-top" role="navigation">
	<div class="container">
		 
		<div class="navbar-header">
			<!-- to enable navigation dropdown when viewed in mobile device -->
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			</button>
			
			<!-- Change "Your Site" to your site name -->
			<a class="navbar-brand" href="<?php echo $home_url; ?>read_alertes.php">Your Site</a>
		</div>
		 
		<div class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				
				<!-- highlight if $page_title has 'Products' word. -->
				<li <?php echo strpos($page_title, "Alerte")!==false || isset($app_name) 
							? "class='active dropdown'" : "class='dropdown'"; ?>>
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
						Alertes <span class="caret"></span>
					</a>
					<ul class="dropdown-menu" role="menu">
					
						<!-- 
							highlight if $page_title has 'Products' word AND 
							category name wasn't set, because it is 'all products' page. 
						-->
						<li <?php echo strpos($page_title, 'Alerte')!==false && !isset($app_name) ? "class='active'" : ""; ?>>
							<a href="<?php echo $home_url; ?>read_alertes.php">All alertes</a>
						</li>
						
						<?php
						// read all product categories
						$stmt=$category->read();
						$num = $stmt->rowCount();

						if($num>0){
							while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
								
								// highlight if the currenct $category_name is the same as the current category name in the loop
								if(isset($app_name) && $app_name==$row['name']){
									echo "<li class='active'><a href='{$home_url}category.php?id={$row['id']}'>{$row['name']}</a></li>";
								}
								
								// no highlight
								else{
									echo "<li><a href='{$home_url}category.php?id={$row['id']}'>{$row['name']}</a></li>";
								}								
							}
						}
						?>
					</ul>
				</li>
				<li <?php echo $page_title=="Read Categories" || $page_title=="Category Search Results" ? "class='active'" : ""; ?>>
					<a href='<?php echo $home_url; ?>read_categories.php'>Categories</a>
				</li>
			</ul>
			
			
		</div><!--/.nav-collapse -->
		 
	</div>
</div>
<!-- /navbar -->

<!--a revoir-->
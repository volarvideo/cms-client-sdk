<?php
include('config.inc.php');
include('template/header.php');

$v = new Volar(VOLAR_API_KEY, VOLAR_SECRET_KEY, VOLAR_BASE_URL);

//call this to get the site slug.
if(!$account_info = $v->sites())
{
	die($v->getError());
}

$page = isset($_REQUEST['page']) && is_numeric($_REQUEST['page']) ? $_REQUEST['page'] : 1;

//note - this is assuming api user only has access to one site account.  if it has access to multiple, you will want to change this to be able to select which site you want.
$site_slug = $account_info['sites'][0]['slug'];

if(!$broadcasts = $v->broadcasts(array('site' => $site_slug, 'list' => 'upcoming', 'sort_by' => 'date', 'sort_dir' => 'asc', 'page' => $page)))
{
	echo $v->getError();
}
else
{
	if($broadcasts['num_pages'] > 0)
	{
		?>
		<div class="broadcast-pages">
			<ul>
				<li><span>pages:</span></li>
				<?php for($i = 1; $i <= $broadcasts['num_pages']; $i++): ?>
					<li><a href="?page=<?php echo $i; ?>"<?php echo $i == $page ? ' class="broadcast-current-page"' : ''; ?>><?php echo $i; ?></a></li>
				<?php endfor; ?>
			</ul>
			<div style="clear:both;"></div>
		</div>
		<?php
	}
	if($broadcasts['item_count'] > 0)
	{
		?>
		<div class="broadcast-list">
			<?php
			foreach($broadcasts['broadcasts'] as $broadcast)
			{
				//convert date from UTC time
				$date = new DateTime($broadcast['date'], new DateTimezone('UTC'));
				$date->setTimezone(new DateTimezone(TIMEZONE));	//TIMEZONE is set in config.inc.php.
				?>
				<div class="broadcast">
					<div class="broadcast-image">
						<a href="watch.php?id=<?php echo $broadcast['id']; ?>">
						<?php if($broadcast['medium_image']): ?>
							<img src="<?php echo $broadcast['medium_image']; ?>" />
						<?php endif; ?>
						</a>
					</div>
					<div class="broadcast-info">
						<h2 class="broadcast-title">
							<a href="watch.php?id=<?php echo $broadcast['id']; ?>"><?php echo $broadcast['title']; ?></a>
						</h2>
						<em class="broadcast-date">
							<a href="watch.php?id=<?php echo $broadcast['id']; ?>">Scheduled for <?php echo $date->format('F jS, Y @ g:i a'); ?></a>
						</em>
						<p class="broadcast-description">
							<a href="watch.php?id=<?php echo $broadcast['id']; ?>">
							<?php
							$description = strip_tags($broadcast['description']);
							if(strlen($description) > 128)
							{
								$description = trim(substr($description, 0, 128)).'...';
							}
							echo $description;
							?>
							</a>
						</p>
					</div>
				</div>
				<?php
			}
			?>
			<div style="clear:both;"></div>
		</div>
		<?php
	}
	else
	{
		?>
		<p>Sorry - there are currently no upcoming broadcasts available</p>
		<?php
	}
}

include('template/footer.php');
?>
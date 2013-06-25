<?php
include('config.inc.php');

if(!isset($_REQUEST['id']) || !is_numeric($_REQUEST['id']))
{
	die("Invalid id given");
}


include('template/header.php');

$v = new Volar(VOLAR_API_KEY, VOLAR_SECRET_KEY, VOLAR_BASE_URL);

//call this to get the site slug.
if(!$account_info = $v->sites())
{
	die($v->getError());
}


//note - this is assuming api user only has access to one site account.  if it has access to multiple, you will want to change this to be able to select which site you want.
$site_slug = $account_info['sites'][0]['slug'];

if(!$broadcasts = $v->broadcasts(array('site' => $site_slug, 'id' => $_REQUEST['id'], 'embed_width' => 700)))
{
	echo $v->getError();
}
else
{
	if($broadcasts['item_count'] > 0)
	{
		$broadcast = $broadcasts['broadcasts'][0];
		//convert date from UTC time
		$date = new DateTime($broadcast['date'], new DateTimezone('UTC'));
		$date->setTimezone(new DateTimezone(TIMEZONE));	//TIMEZONE is set in config.inc.php.

		?>
		<div id="broadcast">
			<h1 class="broadcast-title"><?php echo $broadcast['title']; ?></h1>
			<em class="broadcast-date">
				<?php
				$backlink = '';
				switch($broadcast['status'])
				{
					case 'Upcoming':
						echo 'Scheduled for '.$date->format('F jS, Y @ g:i a');
						$backlink = 'upcoming_broadcasts.php';
					break;
					case 'Streaming':
						echo 'Now Playing';
						$backlink = 'now_playing.php';
					break;
					case 'Archived':
						echo 'Originally aired on '.$date->format('F jS, Y @ g:i a');
						$backlink = 'archived_broadcasts.php';
					break;
				}
				?>
			</em>
			<?php
			
			echo $broadcast['embed_code'];
			echo $broadcast['description'];
			?>
			<div class="broadcast-backlink">
				<a href="<?php echo $backlink; ?>">&laquo; return</a>
			</div>
		</div>
		<?php
	}
	else
	{
		echo "Error: no broadcast found with that id";
	}
}
include('template/footer.php');
?>
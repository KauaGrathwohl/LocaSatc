<?php
// $section ou $action se forem formadas de uma URL inválida (e inexistente) terão o valor "cake_error" e "error404"
$section = !$has_http_error ? strip_tags(strtolower($this->request->controller)) : "cake_error";
$action = !$has_http_error ? strip_tags(strtolower($this->request->action)) : "error{$this->response->statusCode()}";

$is_not_speedtest = !isset($_SERVER['HTTP_USER_AGENT']) || stripos($_SERVER['HTTP_USER_AGENT'], 'Speed Insights') === false || stripos($_SERVER['HTTP_USER_AGENT'], 'Lighthouse') === false;

// cache control...
header("Content-Type: text/html; charset=UTF-8"); // Charset
header("X-UA-Compatible: IE=Edge"); // Edge optimization
header("X-Frame-Options: DENY"); // anti-Clickjacking

if(empty($page_modified))
{
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Data no passado
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // sempre modificada
	header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	header("Pragma: no-cache"); // HTTP/1.0
}
else
{
	$one_day = 86400;
	$file_expires = gmdate("D, d M Y H:i:s", time() + $one_day) . " GMT";
	header("Cache-Control: max-age={$one_day}, pre-check={$one_day}", true);
	header("Expires: " . $file_expires, true);

	$file_last_modified = gmdate("D, d M Y H:i:s", strtotime($page_modified)) . " GMT";
	if(!empty($file_last_modified))
	{
		header("ETag: " . md5($this->here . "_" . $file_last_modified), true);
		header("Last-Modified: " . $file_last_modified, true);
	}
}
?><!DOCTYPE html>
<html lang="pt-br">
	<head>
		<?php
			echo "<meta charset=\"" . Configure::read("App.encoding") . "\" />" . chr(13);
			echo '<meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" />' . chr(13);

			echo $this->xHtml->tag('title', htmlentities(strip_tags($this->fetch('title')), ENT_QUOTES)) . chr(13);

			echo '<meta http-equiv="x-dns-prefetch-control" content="on">
<link href="https://ajax.googleapis.com" rel="preconnect" />
<link href="https://www.google-analytics.com" rel="preconnect" />
<link href="https://fonts.googleapis.com" rel="preconnect" />
<link crossorigin href="https://fonts.gstatic.com" rel="preconnect" />' . chr(13);

			echo $this->xHtml->meta('description', (empty($description_for_layout) ? $conf["description"] : $description_for_layout)) . chr(13);
			echo $this->xHtml->meta('keywords', $conf["keywords"]) . chr(13);
			echo $this->xHtml->meta(array('name' => 'author', 'content' => 'Burn web.studio')) . chr(13);

			if(!empty($canonical_url))
			{
				if(strpos($canonical_url, "http") === false)
				{
					$canonical_url = Router::url($canonical_url, true);
				}

				echo '<link rel="canonical" href="' . $canonical_url . '" />' . chr(13);
			}

			if($section === "cake_error" || $section === "search")
			{
				echo $this->xHtml->meta(array('name' => 'robots', 'content' => 'noindex, nofollow')) . chr(13);
			}
			else
			{
				echo $this->xHtml->meta(array('name' => 'robots', 'content' => 'index, follow')) . chr(13);
				echo $this->xHtml->meta(array('name' => 'revisit-after', 'content' => '1')) . chr(13);
			}

			/* OG Tags Facebook */
			echo "<meta property='og:type' content='website' />" . chr(13);
			echo "<meta property='og:site_name' content='" . $conf['name'] . "' />" . chr(13);
			echo "<meta property='og:title' content='" . htmlentities(strip_tags($this->fetch('title')), ENT_QUOTES) . "' />" . chr(13);
			echo "<meta property='og:locale' content='pt_BR' />" . chr(13);
			echo "<meta property='og:description' content='" . (empty($description_for_layout) ? $conf["description"] : htmlentities($description_for_layout, ENT_QUOTES)) . "' />" . chr(13);
			echo "<meta property='og:url' content='" . Router::url($this->request->url == "/" ? $this->request->url : "/" . $this->request->url, true) . "' />" . chr(13);

			if(!empty($thumb_page))
			{
				$thumb_options = array(
					'width' => 1200,
					'height' => 630,
					'aoe' => 1,
					'zc' => 1,
					'q' => 96,
					'only_image_name' => true
				);
				if(isset($tag))
				{
					$thumb_options['tag'] = $tag;
				}

				$thumb_resized = $this->Image->src($thumb_page, $thumb_options);

				echo "<meta name='image' content='" . Router::url($thumb_resized, true) . "' />" . chr(13);
				echo "<meta property='og:image' content='" . Router::url($thumb_resized, true) . "' />" . chr(13);
				echo '<meta property="og:image:width" content="1200" /><meta property="og:image:height" content="630" />' . chr(13);
				echo "<meta property='og:image:alt' content='" . $conf['name'] . "' />" . chr(13);
				echo '<meta name="twitter:card" content="summary_large_image" />' . chr(13);
				echo "<meta name='twitter:image' content='" . Router::url($thumb_resized, true) . "' />" . chr(13);
				echo "<meta name='twitter:image:alt' content='" . $conf['name'] . "' />" . chr(13);
				echo '<link rel="image_src" href="' . Router::url($thumb_resized, true) . '" />' . chr(13);
			}
			else
			{
				echo "<meta name='image' content='" . Router::url("/facebook_lg.png", true) . "' />" . chr(13);
				echo "<meta property='og:image' content='" . Router::url("/facebook_lg.png", true) . "' />" . chr(13);
				echo '<meta property="og:image:width" content="1200" /><meta property="og:image:height" content="630" />' . chr(13);
				echo "<meta property='og:image:alt' content='Logo " . $conf['name'] . "' />" . chr(13);
				echo '<meta name="twitter:card" content="summary_large_image" />' . chr(13);
				echo "<meta name='twitter:image' content='" . Router::url("/facebook_lg.png", true) . "' />" . chr(13);
				echo "<meta name='twitter:image:alt' content='Logo " . $conf['name'] . "' />" . chr(13);
				echo '<link rel="image_src" href="' . Router::url("/facebook_lg.png", true) . '" />' . chr(13);
			}

			echo $this->xHtml->favicon();

			$default_js = array("default");

			$js = array();
			if(file_exists(JS . $section . ".js")) $js[] = $section;
			if(file_exists(JS . $section . DS . $action . ".js")) $js[] = $section . "/" . $action;
			if(isset($custom_js) && is_array($custom_js)) $js = array_merge($js, $custom_js);

			$css = array(
				'https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,600;0,700;0,800;0,900;1,400;1,600;1,700;1,800;1,900&display=swap',
				'default'
			);
			if(file_exists(CSS . $section . ".css")) $css[] = $section;
			if(file_exists(CSS . $section . DS . $action . ".css")) $css[] = $section . "/" . $action;
			echo $this->xHtml->css($css);

			echo $this->xHtml->script($default_js, array('defer' => true));
		?>
	</head>
	<body id="<?php echo $section; ?>" class="<?php echo $action; ?>" data-uri="<?php echo Router::url("/"); ?>" data-webroot="<?php echo $this->request->webroot; ?>" data-here="<?php echo !$has_http_error ? $this->here : ""; ?>">
		<div id="accessJumperMenu" class="hidden">
			<a href="#content" title="Atalho: Tecla de acesso+1" accesskey="1" tabindex="1">Ir para o Conteúdo da página</a>
			<a href="#header-nav" title="Atalho: Tecla de acesso+2" accesskey="2" tabindex="2">Ir para o Menu da página</a>
		</div>
		<div id="activity" class="disabled"><span class="sr-only">Carregando Dados...</span></div>
		<?php
			echo $this->Flash->render();
			echo $this->Flash->render('auth');
		?>
		<div id="page">
			<?php echo $this->element('layout/header', array(), array('cache' => array("config" => "temp"))); ?>

			<main id="content">
				<?php echo $this->fetch("content"); ?>
			</main>

			<?php echo $this->element("layout/footer", array(), array('cache' => array("config" => "long"))); ?>

			<div class="fixed inset-x-0 bottom-5 bg-gray rounded-lg px-4 z-50 container shadow-cookies hidden">
			  <div class="relative flex flex-col lg:flex-row items-center justify-between px-4 py-5 rounded-lg">
			    <p class="text-sm font-medium text-center mb-5 lg:text-left lg:mb-0 lg:mr-14">
			      Utilizamos cookies e outras tecnologias para aprimorar sua experiência de navegação de acordo com nossa <a class="underline text-primary hover:text-warning" href="<?php echo $this->Html->url("/privacidade-e-termos"); ?>"> Política de Privacidade</a>.
			    </p>
			    <button aria-label="Close" class="text-white bg-primary py-2 px-6 rounded hover:bg-dark-gray" id="btn-cookies">
			      PROSSEGUIR
			    </button>
			  </div>
			</div>
		</div>

		<?php if (!empty($js)) echo $this->xHtml->script($js); ?>
	</body>
</html>

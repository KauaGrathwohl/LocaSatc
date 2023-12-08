<div class="container my-6 xl:my-9">
	<div class="<?php echo classnames(array("content-column", "news-view")); ?>">
		<div class="mb-4 xl:mb-6">
			<h2><?php echo $noticia['Noticia']['name']; ?></h2>
		</div>
		<?php
		if(!empty($noticia['NoticiaFoto'][0]['img'])):
			$title = !empty($noticia['NoticiaFoto'][0]['name']) ? trim($noticia['NoticiaFoto'][0]['name']) : "";
			$tag = strpos($noticia['NoticiaFoto'][0]['img'], "/foto_") !== false ? substr($noticia['Noticia']['name'], 0, 90) : null;
		?>
		<figure class="block mb-4 lg:mb-5 w-full">
			<a class="block border-2 border-transparent w-full" href="<?php echo $this->webroot . $noticia['NoticiaFoto'][0]['img']; ?>" title="<?php echo $title; ?>" data-fancybox="gallery">
				<picture>
					<source srcset="<?php echo $this->Image->src($noticia['NoticiaFoto'][0]['img'], array('width' => 425, 'height' => 248, 'zc' => 1, 'q' => 98, 'tag' => $tag)); ?>" media="(max-width: 425px)" />
					<img class="block w-full" src="<?php echo $this->Image->src($noticia['NoticiaFoto'][0]['img'], array('width' => 728, 'q' => 98, 'tag' => $tag)); ?>" alt="<?php echo $title; ?>" />
				</picture>
			</a>
			<?php if(!empty($noticia['NoticiaFoto'][0]['name'])): ?>
			<figcaption class="px-1.5 py-1 italic text-right text-xs lg:text-sm"><?php echo $noticia['NoticiaFoto'][0]['name']; ?></figcaption>
			<?php endif; ?>
		</figure>
		<?php
			unset($noticia['NoticiaFoto'][0]);
		endif; ?>

		<div class="body-text mb-6">
			<?php
				$texto = trim($noticia['Noticia']['texto']);

				$twitterEmbed = strpos($texto, "twitter-tweet") !== false;
				$instagramEmbed = strpos($texto, "instagram-media") !== false;

				$texto = preg_replace("/<p>&nbsp;<\/p>$/i", "", $texto);
				echo trim($texto);
			?>
		</div>

		<?php if(!empty($noticia['NoticiaFoto'])): ?>
		<div class="flex flex-wrap justify-center md:justify-start mb-6">
			<?php
			foreach($noticia['NoticiaFoto'] as $foto):
				if(!empty($foto['img'])):
			?>
			<div class="w-1/2 md:w-1/3 lg:w-1/4 text-center p-1 md:p-2">
				<a class="inline-block border-2 border-transparent hover:border-primary" href="<?php echo $this->webroot . $foto['img']; ?>" title="<?php echo $foto['name']; ?>" data-fancybox="gallery" data-turbolinks="false">
					<img src="<?php echo $this->Image->src($foto['img'], array('width' => 190, 'height' => 142, 'zc' => 1, 'q' => 98)); ?>" alt="<?php echo $foto['name']; ?>" />
				</a>
			</div>
			<?php endif;
			endforeach; ?>
		</div>
		<?php endif; ?>

		<?php if(!empty($noticia['NoticiaTag'])): ?>
		<div class="mb-6">
			<p class="text-sm lg:text-base cursor-default">
				<strong>Tags:</strong>
				<?php
					$tagList = array();
					foreach($noticia['NoticiaTag']as $tagCounter => $tag)
					{
						$uri = $this->xHtml->url("/noticias/index/tag:{$tag['name']}");

						$tagList[] = '<a class="inline-block leading-tight bg-gray px-1.5 py-1 mb-1.5 ml-1.5 rounded-md hover:text-white hover:bg-primary" href="' . $uri . '" title="Filtrar conteúdo com a tag: ' . $tag['name'] . '">'. trim($tag['name']) . '</a>';
					}

					echo implode(" ", $tagList);
				?>
			</p>
		</div>
		<?php endif; ?>
	</div>
</div>

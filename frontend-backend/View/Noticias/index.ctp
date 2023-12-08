<div class="container container-ws my-6 xl:my-9">
	<div class="content-column">
	  <h2 class="content-title mb-8">
			<strong>Notícias</strong>
	  </h2>
		<?php
		if(empty($noticias))
		{
			echo "<p style='font-size: 16px; line-height: 25px; margin: 12px 0; text-align: center;'>Nenhum informação disponível nesta categoria ou filtro.</p>";
		}
		else
		{ ?>
		<div class="destaques-container">
			<?php foreach($noticias as $i => $data): ?>
				<?php
				$thumb = null;
				if(!empty($data['NoticiaFoto']['img']))
				{
					$thumb = $this->Image->src($data['NoticiaFoto']['img'], array(
						'width' => 240,
						'height' => 206,
						'zc' => 1,
						'q' => 96
					));
				}
				$baseClasses = array(
					"destaque-conteudo",
					"has-thumb" => !empty($thumb),
				);
				if(!empty($klass))
				{
					$baseClasses = array_merge($baseClasses, $klass);
				}
				?>
				<div class="<?php echo classnames($baseClasses); ?>">
					<a href="<?php echo $this->Html->url('/noticia/' . $data['Noticia']['slug']); ?>" title="Veja mais: <?php echo $data['Noticia']['name']; ?>">
						<?php if(!empty($thumb)): ?>
						<div class="destaque--thumb">
							<img class="thumb--img" src="<?php echo $thumb; ?>" alt="<?php echo $data['Noticia']['name']; ?>" />
						</div>
						<?php endif; ?>
						<div class="destaque-content">
							<h4 class="destaque--title"><?php echo $data['Noticia']['name']; ?></h4>
						</div>
					</a>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
			echo $this->element('layout/paging');
		} ?>
	</div>
</div>

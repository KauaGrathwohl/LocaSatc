<div class="page-header">
	<div class="pull-right btn-group">
		<a href="<?php echo $this->xHtml->url(array('action' => 'index')); ?>" class="btn btn-info">Listar Arquivo</a>
	</div>
	<h2><?php echo !$add ? "Editando" : "Adicionando"; ?> Notícia</h2>
</div>

<?php
$form_uri = array('action' => 'add');
if(!$add)
{
	$form_uri['action'] = 'edit';
}

echo $this->xForm->create('Noticia', array('url' => $form_uri, 'type' => 'file', 'class' => 'form-noticia')); ?>
	<fieldset>
		<?php
			if(!$add) echo $this->xForm->input('id');
			echo $this->xForm->input('data', array('label' => 'Data de Publicação', 'class' => 'input-small', 'dateFormat' => 'DMY', 'timeFormat' => '24', 'interval' => 5, 'separator' => ' / ', 'minYear' => date('Y')-2, 'maxYear' => date('Y')+1));
			echo $this->xForm->input('name', array('label' => 'Título', 'class' => 'col-md-8'));
			echo $this->xForm->input('texto', array('type' => 'textarea', 'label' => 'Corpo da Publicação', 'class' => 'editor'));
			echo $this->xForm->input('tags', array('type' => 'text', 'label' => 'Tags', 'value' => isset($tags) ? $tags : array()));
			echo $this->xForm->input('ativo', array('label' => 'Publicar Notícia no Site?'));
		?>

		<h3 class="col-md-12">Adicionar Fotos</h3>
		<div class="well col-md-12" style="padding: 12px; margin-top: 11px;">
			<?php echo $this->xForm->input('NoticiaFoto.img', array('type' => 'file', 'name' => 'img[]', 'label' => 'Arquivo da Imagem', 'multiple' => 'multiple', 'accept' => 'image/*')); ?>
		</div>

		<table id="tblImages" class="table table-bordered table-striped table-hover" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<th style="width: 120px;">Imagem</th>
					<th>Legenda (opcional)</th>
					<th style="width: 60px;">Padrão</th>
					<th style="width: 60px;">Ativo</th>
					<th class="actions">Ações</th>
				</tr>
			</thead>
			<tbody>
			<?php
			if(!empty($data['NoticiaFoto'])):
				foreach ($data['NoticiaFoto'] as $i => $img): ?>
				<tr data-id="<?php echo $img['id']; ?>" data-noticia_id="<?php echo $img['noticia_id']; ?>" data-loaded="true">
					<td><img src="<?php echo $this->Image->src($img['img'], array('width' => 120, 'height' => 120, 'zc' => 1, 'q' => 90)); ?>" alt="<?php echo $img['name']; ?>" /></td>
					<td><?php
						echo $this->Form->input("NoticiaFoto.{$i}.id");
						echo $this->Form->input("NoticiaFoto.{$i}.name", array('label' => false, 'class' => 'name-field col-md-12', 'placeholder' => 'Legenda desta Foto (opcional)'));
					?></td>
					<td class="toggle-actions toggle-default" style="text-align: center;"><a href="<?php echo $this->xHtml->url(array('action' => 'image_update', 'default', $img['id'])); ?>" class="btn btn-xs" data-field="default" data-state="<?php echo $img['default']; ?>"><?php echo $actives[$img['default']]; ?></a></td>
					<td class="toggle-actions toggle-active" style="text-align: center;"><a href="<?php echo $this->xHtml->url(array('action' => 'image_update', 'ativo', $img['id'])); ?>" class="btn btn-xs" data-field="ativo" data-state="<?php echo $img['ativo']; ?>"><?php echo $actives[$img['ativo']]; ?></a></td>
					<td class="actions"><?php echo $this->xHtml->link('Deletar', array('action' => 'image_update', 'delete', $img['id'])); ?></td>
				</tr>
			<?php
				endforeach;
			endif;
			?>
			</tbody>
		</table>

		<div class="form-actions col-md-12 text-center">
			<?php echo $this->xForm->submit('Salvar Notícia', array('class' => 'btn btn-lg btn-primary', 'id' => 'btnSave')); ?>
		</div>
	</fieldset>
<?php echo $this->xForm->end();?>

<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/css/selectize.default.min.css" />
<script src="//cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/js/standalone/selectize.min.js"></script>
<script type="text/javascript">
<!--
function PhotoManager()
{
	var self = this;
	var tblImages = $("#tblImages");

	var trImageTmpl = [
		"<tr class='tr-preview' data-index='{{index}}' data-filename='{{name}}'>",
			"<td><img src='{{src}}' alt='{{name}}' class='preview-image' /></td>",
			"<td><div class=\"input text form-group col-md-12\">",
  			"<input name=\"data[NoticiaFotoTemp][{{index}}][name]\" class=\"name-field form-control\" placeholder=\"Legenda desta Foto (opcional)\" maxlength=\"255\" type=\"text\" />",
			"</div></td>",
			"<td class='toggle-actions text-center' colspan='2' style='font-style: italic; font-size: 12px;'>Não enviada</td>",
			"<td class='actions'><a title=\"javascript:void(0);\" rel=\"tooltip\" data-original-title=\"Remover Imagem da Lista de Envio\" class=\"btn-warning btn btn-xs\"><span class=\"glyphicon glyphicon-remove\"></span> Remover</a></td>",
		"</tr>"
	].join("\n");

	this.getTableImages = function()
	{
		return tblImages;
	};

	this.refreshColors = function()
	{
		tblImages.find("td.toggle-actions").each(function() {
			if((a = $("a.btn", this)).data("state") == "Y") a.html("Sim").addClass("btn-primary").removeClass("btn-default");
			else a.html("Não").addClass("btn-default").removeClass("btn-primary");
		});

		if(tblImages.find("tbody tr").length < 1) {
			tblImages.hide();
		} else {
			tblImages.show();
		}
	};

	this.addPreviewImage = function(index, file)
	{
		var self = this;
		var reader = new FileReader();
		reader.onload = function(event) {
			var data = { index: index, src: event.target.result, name: file.name };
			var $tr = $(Mustache.render(trImageTmpl, data));

			tblImages.find("tbody").prepend($tr);

			self.refreshColors();
		};
		reader.readAsDataURL(file);
	};

	this.appendPreviewImages = function(files, startIndex)
	{
		if(!startIndex) startIndex = 0;

		for(var j=startIndex; j<files.length; j++) {
			this.addPreviewImage(j, files[j]);
		}
	};

	// events
	tblImages.on("click", "tr:not(.tr-preview) > td.toggle-actions > a.btn", function(event) {
		event.preventDefault();

		var a = $(this).addClass("disabled"),
			tr = a.parents("tr"),
			newState = (a.data("state") === "Y" ? "N" : "Y"),
			dataPost = {};
			dataPost["data[NoticiaFoto]["+a.data("field")+"]"] = newState;
			dataPost["data[NoticiaFoto][noticia_id]"] = tr.data("noticia_id");

		if(a.data("field") === "default")
		{
			tblImages.find("td.toggle-default > a").not(this).data("state", "N");
		}

		$.ajax({
			url: a.attr("href"),
			type: "post",
			dataType: "json",
			data: dataPost,
			success: function(data) {
				a.data("state", newState).removeClass("disabled");
				self.refreshColors();
			}
		});
		return false;
	});

	tblImages.on("click", "tr:not(.tr-preview) > td.actions > a.btn-danger", function(event) {
		event.preventDefault();

		var a = $(this);
		if(confirm("Você confirma a exclusão deste registro?"))
		{
			a.addClass("disabled");

			$.getJSON(a.attr("href"), function(data) {
				a.removeClass("disabled").parents("tr").remove();
			});
		}
		return false;
	});

	tblImages.on("blur", "tr:not(.tr-preview) > td input.name-field", function(event) {
		var input = $(this), tr = input.parents("tr"), dataPost = {};
		dataPost["data[NoticiaFoto][name]"] = input.val();

		input.attr("readonly", true).addClass("disabled");

		$.ajax({
			url: $.url() + "admin/noticias/image_update/name/" + tr.data("id"),
			type: "post",
			dataType: "json",
			data: dataPost,
			success: function(data) {
				input.removeAttr("readonly").removeClass("disabled");
			}
		});
	});

	// init
	this.refreshColors();
};

function PhotoUploadManager(pm)
{
	var photoManager = pm;
	var fileImages = [];

	this.getFileImages = function()
	{
		return fileImages;
	};

	// events
	photoManager.getTableImages().on("click", "tr.tr-preview > td.actions > a.btn-warning", function(event) {
		event.preventDefault();

		var a = $(this), tr = a.parents("tr");
		fileImages.splice(+tr.data("index"), 1);

		tr.remove();

		return false;
	});

	$("#NoticiaFotoImg").on('change', function(event) {
		var tmp = [], startIndex = fileImages.length;

    // transfer dropped content to temporary array
    if (event.dataTransfer) {
      tmp = event.dataTransfer.files;
    } else if (event.target) {
      tmp = event.target.files;
    }

    // Copy the file items into the array
    for(var i = 0; i < tmp.length; i++) {
      fileImages.push(tmp.item(i));
    }

    photoManager.appendPreviewImages(fileImages, startIndex);

    $(this).val("");
	});
};

function FormManager(pum)
{
	var self = this;
	var $form = $("#content .form-noticia");
	var $submit = $form.find(".submit :input");

	this.formProgress = function(event) {
		var p = Math.min(Math.max(Math.ceil((event.loaded / event.total) * 100), 0), 100);
		$(".turbolinks-progress-bar").css("width", p + "%");
	};

	this.sendForm = function(form) {
		$submit.attr("disabled", true);

		var formData = new FormData(form);

		var files = pum.getFileImages();
		if(files.length > 0)
		{
			formData.delete("img[]"); // removendo campo original...

			/* ADICIONANDO FOTOS MANUAIS */
			files.map(function(img) {
				formData.append("img[]", img);
			});
		}

		$.ajax({
			type: "post",
			dataType: "json",
			cache: false,
			url: $form.attr("action") + ".json",
			data: formData,
			contentType: false,
			processData: false,
			xhr: function() { // Custom XMLHttpRequest
				var myXhr = $.ajaxSettings.xhr();
				if(!!myXhr.upload) { // Avalia se tem suporte a propriedade upload
					myXhr.upload.addEventListener('progress', self.formProgress, false);
				}

				return myXhr;
			}
		}).then(function(data) {
			if(!!data.error) {
				var msg = ["Foram encontrados erros em seus dados:"];
				for(var k in data.validationErrors) {
					msg.push("- " + data.validationErrors[k].join(" / "));
				}

				alert(msg.join("\n"));
				$submit.removeAttr("disabled");
			} else {
				window.location.href = data.redirect_url || $(document.body).data("uri");
			}
		}, function(jqXHR, textStatus) {
			alert("Não foi possível enviar seu conteúdo neste momento. Por favor tente novamente mais tarde.");

			$submit.removeAttr("disabled");
		});
	};

	// events
	$form.on('submit', function(event) {
		event.preventDefault();

		CKupdate();

		var _form = this;
		requestAnimationFrame(function() {
			self.sendForm(_form);
		});

		return false;
	});
};

window.photoManager = new PhotoManager();
window.photoUploadManager = new PhotoUploadManager(window.photoManager);
window.formManager = new FormManager(window.photoUploadManager);

var $tags = $('#NoticiaTags').selectize({
	delimiter: ',',
	persist: false,
	options: <?php echo json_encode($tagsOptions); ?>,
	create: function(input) {
		return {
			value: input,
			text: input
		}
	}
});

window.$selectizeTags = $tags[0].selectize;
//-->
</script>

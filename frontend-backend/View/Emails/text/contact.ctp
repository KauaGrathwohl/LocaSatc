<?php echo $subject; ?>


<?php echo $site; ?>


Enviado em:<?php echo $data; ?> às <?php echo $hr; ?>


<?php foreach($dados as $key => $value){
	echo "\n".$key."\n---\n".$value."\n";
} ?>


IP: <?php echo $IP; ?>
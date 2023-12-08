<table width="520" cellpadding="5" cellspacing="5">
	<tbody>
		<tr>
			<td colspan="3" valign="top" align="left"><strong><?php echo $subject; ?></strong></td>
		</tr>
		<?php if(!empty($site)): ?>
		<tr>
			<td colspan="3" valign="top" align="left"><a target="_blank" href="<?php echo $this->Util->url($site); ?>"><?php echo $site; ?></a></td>
		</tr>
		<?php endif; ?>
		<tr>
			<td colspan="3" valign="top" align="left"><strong>Enviado em:</strong> <?php echo $data; ?> às <?php echo $hr; ?></td>
		</tr>
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>
		<?php foreach($dados as $key => $value){ ?>
		<tr>
			<td width="130" valign="top" align="right"><strong><?php echo $key; ?></strong></td>
			<td width="5">&nbsp;</td>
			<td><?php echo $value; ?></td>
		</tr>
		<?php } ?>
		<tr>
			<td width="130" valign="top" align="right"><strong>IP</strong></td>
			<td width="5">&nbsp;</td>
			<td><?php echo $IP; ?></td>
		</tr>
	</tbody>
</table>
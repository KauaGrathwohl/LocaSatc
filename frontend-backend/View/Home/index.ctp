<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Tabela de Endereços</h1>
    <table class="w-full bg-gray-800 rounded-lg">
        <thead>
          <tr>
            <th class="py-2 px-4 text-left">UF</th>
            <th class="py-2 px-4 text-left">Município</th>
            <th class="py-2 px-4 text-left">Endereço</th>
            <th class="py-2 px-4 text-left">Bairro</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($enderecos as $endereco) { ?>
           <tr>
              <td class="py-2 px-4 border-b border-gray-600"><?php echo $endereco['Endereco']['uf']; ?></td>
              <td class="py-2 px-4 border-b border-gray-600"><?php echo $endereco['Endereco']['municipio']; ?></td>
              <td class="py-2 px-4 border-b border-gray-600"><?php echo $endereco['Endereco']['endereco']; ?></td>
              <td class="py-2 px-4 border-b border-gray-600"><?php echo $endereco['Endereco']['bairro']; ?></td>
          </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php echo $this->element('layout/paging');?>
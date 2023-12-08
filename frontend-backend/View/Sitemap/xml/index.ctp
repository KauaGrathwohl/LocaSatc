<?php
header("Content-Type: text/xml; charset=UTF-8"); // Charset
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Data no passado
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // sempre modificada
header("Cache-Control: max-age=0, no-cache, no-store, must-revalidate"); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
?>
<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    <?php foreach ($items as $k => $item): ?>
    <url>
        <loc><?php echo $item['loc']; ?></loc>
        <changefreq><?php echo $item['changefreq']; ?></changefreq>
        <priority><?php echo isset($item['priority']) ? $item['priority'] : "0.8"; ?></priority>
    </url>
    <?php endforeach; ?>

</urlset>

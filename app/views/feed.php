<?php echo '<?xml version="1.0" encoding="UTF-8"?>';?>
<rss xmlns:atom="http://www.w3.org/2005/Atom" version="2.0">
    <channel>
        <title><?=$site['title']?></title>
        <description><?=$site['description']?></description>
        <link><?=$site['url']?></link>
        <atom:link href="<?=$site['url']?>/feed.xml" rel="self" type="application/rss+xml" />
        <pubDate><?=date('Y-m-d H:m:s')?></pubDate>
        <lastBuildDate><?=date('Y-m-d H:m:s')?></lastBuildDate>
        <generator>Gitblog v1.0</generator>
        <?php foreach($blogList as $blog):?>
        <item>
            <title><?=$blog['title']?></title>
            <description>
            <?=htmlspecialchars($blog['content'])?>
            </description>
            <pubDate><?=$blog['ctime']?></pubDate>
            <link><?=$site['url']?><?=$blog['siteURL']?></link>
            <guid isPermaLink="true"><?=$site['url']?><?=$blog['siteURL']?></guid>
            <?php if(count($blog['category']) > 0):?>
                <?php foreach($blog['category'] as $category):?>
                   <category><?=$category['name']?></category>
                <?php endforeach;?>
            <?php endif;?>
        </item>
        <?php endforeach;?>
    </channel>
</rss>
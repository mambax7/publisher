<?xml version="1.0" encoding="<{$channel_charset}>"?>
<rss version="2.0">
    <channel>
        <title><{$channel_title}></title>
        <link><{$channel_link}></link>
        <description><{$channel_desc}></description>
        <lastBuildDate><{$channel_lastbuild}></lastBuildDate>
        <docs>https://backend.userland.com/rss/</docs>
        <generator><{$channel_generator}></generator>
        <category><{$channelCategory}></category>
        <managingEditor><{$channel_editor}></managingEditor>
        <webMaster><{$channel_webmaster}></webMaster>
        <language><{$channel_language}></language>
        <{if $image_url|default:'' != ''}>
            <image>
                <title><{$channel_title}></title>
                <url><{$image_url}></url>
                <link><{$channel_link}></link>
                <width><{$image_width}></width>
                <height><{$image_height}></height>
            </image>
        <{/if}>
        <{foreach item=item from=$items|default:null}>
            <item>
                <title><{$item.title}></title>
                <link><{$item.link}></link>
                <description><{$item.description}></description>
                <pubDate><{$item.pubdate}></pubDate>
                <guid><{$item.guid}></guid>
            </item>
        <{/foreach}>
    </channel>
</rss>

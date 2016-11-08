<?php
if (! defined('IS_CMS')) {
    die();
}

/**
 *
 * @author David Ringsdorf <git@drdf.de>
 * @copyright (c) 2016, David Ringsdorf
 * @license The MIT License (MIT)
 */
?>
<h1><?= $t->getLanguageValue('info.description.description_header'); ?></h1>
<p><?= $t->getLanguageValue('info.description.description_text'); ?></p>
<h2><?= $t->getLanguageValue('info.description.example_header'); ?></h2>
<pre>
  <code>
    {head|
      &lt;!-- head-Plugin --&gt;
      &lt;style type="text/css"&gt; h1 ^{background-color: #f00;^} &lt;/style&gt;
      &lt;script type="text/javascript"&gt; document.write("Hello World!") &lt;/script&gt;
    }
  </code>
</pre>
<h1><?= $t->getLanguageValue('info.description.licence_header'); ?></h1>
<p><?= $p['licence.text'] ?></p>
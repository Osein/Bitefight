<?php

include 'item_table_dom.php';

// Item tables are located at;
// http://board.us.bitefight.gameforge.com/board263-bitefight-community/board32-guides-faqs/board426-goods-and-hideout/34015-merchant-s-goods-new-items

$htmlstring = <<<EOT
                   <tr>
					<td><img class="itemPic" src="http://s15.us.bitefight.gameforge.com/img/items/1/152.jpg"></td>
					<td>Skagerak </td>
					<td>1030</td>
					<td>-</td>
					<td><table>
						<tbody><tr><td class="pricecell">196524344<img src="http://s15.us.bitefight.gameforge.com/img/symbols/res2.gif"></td></tr>
						<tr><td class="pricecell">0<img src="http://s15.us.bitefight.gameforge.com/img/symbols/res3.gif"></td></tr>
					   </tbody></table></td>
					<td><table><tbody><tr><td>Defence</td><td>+231</td></tr><tr><td>Charisma</td><td>+154</td></tr></tbody></table></td>
				  </tr><tr>
					<td><img class="itemPic" src="http://s15.us.bitefight.gameforge.com/img/items/1/153.jpg"></td>
					<td>Vigor </td>
					<td>1130</td>
					<td>-</td>
					<td><table>
						<tbody><tr><td class="pricecell">217611426<img src="http://s15.us.bitefight.gameforge.com/img/symbols/res2.gif"></td></tr>
						<tr><td class="pricecell">0<img src="http://s15.us.bitefight.gameforge.com/img/symbols/res3.gif"></td></tr>
					   </tbody></table></td>
					<td><table><tbody><tr><td>Strength</td><td>+166</td></tr><tr><td>Endurance</td><td>+249</td></tr></tbody></table></td>
				  </tr><tr>
					<td><img class="itemPic" src="http://s15.us.bitefight.gameforge.com/img/items/1/154.jpg"></td>
					<td>Aiedail </td>
					<td>1230</td>
					<td>-</td>
					<td><table>
						<tbody><tr><td class="pricecell">238886182<img src="http://s15.us.bitefight.gameforge.com/img/symbols/res2.gif"></td></tr>
						<tr><td class="pricecell">0<img src="http://s15.us.bitefight.gameforge.com/img/symbols/res3.gif"></td></tr>
					   </tbody></table></td>
					<td><table><tbody><tr><td>Hit Chance (on opponent)</td><td>-14</td></tr><tr><td>Bonus hit chance (on opponent)</td><td>-52</td></tr></tbody></table></td>
				  </tr>
EOT;


$html = str_get_html($htmlstring);

if(!$html) {
    echo 6;
    die;
}

foreach($html->childNodes() as $tr) {
    /**
     * @var simple_html_dom $tr
     * @var simple_html_dom $itemImg
     * @var simple_html_dom $nameTd
     * @var simple_html_dom $sternTd
     * @var simple_html_dom $priceTd
     * @var simple_html_dom $attributeTd
     */

    $imgTd = $tr->childNodes(0);
    $itemImg = $imgTd->childNodes(0);
    $nameTd = $tr->childNodes(1);
    $levelTd = $tr->childNodes(2);
    $sternTd = $tr->childNodes(3);
    $priceTd = $tr->childNodes(4);
    $attributeTd = $tr->childNodes(5);

    $itemImgLink = $itemImg->src;
    $itemName = trim($nameTd->plaintext);
    $itemLevel = trim($levelTd->plaintext);
    $itemStern = count($sternTd->childNodes());
    $itemPriceGold = $priceTd->find('.pricecell')[0]->plaintext;
    $itemPriceStone = $priceTd->find('.pricecell')[1]->plaintext;

    var_dump($priceTd->find('.pricecell')[1]->plaintext);
}


















































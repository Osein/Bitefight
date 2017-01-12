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

set_time_limit(600);

$html = str_get_html($htmlstring);

if(!$html) {
    echo 6;
    die;
}

class Item
{
    public $img;
    public $stern;
    public $model;
    public $name;
    public $level;
    public $gcost;
    public $slcost;
    public $scost;
    public $str;
    public $def;
    public $dex;
    public $end;
    public $cha;
    public $hpbonus;
    public $regen;
    public $sbschc;
    public $sbscdmg;
    public $sbsctlnt;
    public $sbnshc;
    public $sbnsdmg;
    public $sbnstlnt;
    public $ebschc;
    public $ebscdmg;
    public $ebsctlnt;
    public $ebnshc;
    public $ebnsdmg;
    public $ebnstlnt;
    public $apup;
    public $cooldown;
    public $duration;
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

    $item = new Item();

    $imgTd = $tr->childNodes(0);
    $itemImg = $imgTd->childNodes(0);
    $nameTd = $tr->childNodes(1);
    $levelTd = $tr->childNodes(2);
    $sternTd = $tr->childNodes(3);
    $priceTd = $tr->childNodes(4);
    $attributeTd = $tr->childNodes(5);
    $attributesTbody = $attributeTd->childNodes(0)->childNodes(0);

    $item->img = $itemImg->src;
    $item->name = trim($nameTd->plaintext);
    $item->level = (int)trim($levelTd->plaintext);
    $item->stern = (int)count($sternTd->childNodes());
    $item->gcost = (int)$priceTd->find('.pricecell')[0]->plaintext;
    $item->scost = (int)$priceTd->find('.pricecell')[1]->plaintext;
    $item->slcost = (int)floor($item->gcost / 4);
    $item->model = 1;

    foreach($attributesTbody->childNodes() as $attrTr) {
        /**
         * @var simple_html_dom $attrTr
         * @var simple_html_dom $attrName
         * @var simple_html_dom $attrValue
         */

        $attrName = trim($attrTr->childNodes(0)->plaintext);
        $attrValue = (int)trim($attrTr->childNodes(1)->plaintext);

        switch(strtolower($attrName)) {
            case 'strength':
                $item->str = $attrValue;
                break;
            case 'defence':
                $item->def = $attrValue;
                break;
            case 'dexterity':
                $item->dex = $attrValue;
                break;
            case 'endurance':
                $item->end = $attrValue;
                break;
            case 'charisma':
                $item->cha = $attrValue;
                break;
            case 'basic damage':
                $item->sbscdmg = $attrValue;
                break;
            case 'hit chance':
                $item->sbschc = $attrValue;
                break;
            case 'basic talent':
                $item->sbsctlnt = $attrValue;
                break;
            case 'bonus damage':
                $item->sbnsdmg = $attrValue;
                break;
            case 'bonus hit chance':
                $item->sbnshc = $attrValue;
                break;
            case 'bonus talent':
                $item->sbnstlnt = $attrValue;
                break;
            case 'basic damage on opponent':
                $item->ebscdmg = $attrValue;
                break;
            case 'hit chance on opponent':
                $item->ebschc = $attrValue;
                break;
            case 'basic talent on opponent':
                $item->ebsctlnt = $attrValue;
                break;
            case 'bonus damage on opponent':
                $item->ebnsdmg = $attrValue;
                break;
            case 'bonus hit chance on opponent':
                $item->ebnshc = $attrValue;
                break;
            case 'bonus talent on opponent':
                $item->ebnstlnt = $attrValue;
                break;
            case 'basic damage (on opponent)':
                $item->ebscdmg = $attrValue;
                break;
            case 'hit chance (on opponent)':
                $item->ebschc = $attrValue;
                break;
            case 'basic talent (on opponent)':
                $item->ebsctlnt = $attrValue;
                break;
            case 'bonus damage (on opponent)':
                $item->ebnsdmg = $attrValue;
                break;
            case 'bonus hit chance (on opponent)':
                $item->ebnshc = $attrValue;
                break;
            case 'bonus talent (on opponent)':
                $item->ebnstlnt = $attrValue;
                break;
            case 'regeneration':
                $item->regen = $attrValue;
                break;
            case 'health':
                $item->hpbonus = $attrValue;
                break;
        }
    }

    var_dump($item);
}
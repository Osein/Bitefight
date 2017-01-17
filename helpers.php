<?php
/**
 * Created by PhpStorm.
 * User: osein
 * Date: 09/01/17
 * Time: 15:14
 */

function getAssetLink($assetLink) {
    /**
     * @var \Phalcon\Config $config
     */
    global $config;

    return $config->get('cdn') . $assetLink;
}

function headLink($file) {
    return '<link rel="stylesheet" type="text/css" href="'.getAssetLink('css/'.$file).'">'.PHP_EOL;
}

function headJs($file) {
    return '<script type="text/javascript" src="'.getAssetLink('js/'.$file).'"></script>'.PHP_EOL;
}

function getUrl($link) {
    /**
     * @var \Phalcon\Config $config
     */
    global $config;

    return $config->get('baseUrl') . $link;
}

function getHuntNameFromId($huntId) {
    if($huntId == 1) {
        return 'Farm';
    } elseif($huntId == 2) {
        return 'Village';
    } elseif($huntId == 2) {
        return 'Small Town';
    } elseif($huntId == 2) {
        return 'City';
    } else {
        return 'Metropolis';
    }
}

function getSkillCost($skillLevel) {
    return floor(pow($skillLevel - 4, 2.4));
}

function e($str)
{
    return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}

function prettyNumber($number)
{
    return number_format($number, 0, ',', '.');
}

function getLevel($exp)
{
    return floor( sqrt( $exp / 5 ) ) + 1;
}

function getExpNeeded($level)
{
    return ((pow( $level, 2 ) * 5) + (5 * floor($level / 5)));
}

function getPreviousExpNeeded($level)
{
    return getExpNeeded($level - 1);
}

function getItemModelFromModelNo($modelNo)
{
    $modelArray = array('weapons', 'potions', 'helmets', 'armour', 'jewellery', 'gloves', 'boots', 'shields');

    return $modelArray[$modelNo-1];
}

function profilePrintUserItem($i)
{
    ?>
    <tr>
        <td class='<?php if($i->equipped) echo 'active'; else echo 'inactive'; ?> itemslot' style="text-align:center;">
            <div style="position:relative;width:300px;">
                <img src="<?php echo getAssetLink('img/items/'.$i->model.'/'.$i->id.'.jpg') ?>" <?php if($i->scost > 0) echo 'style="border: 1px solid #6f86a9;"'; ?> alt="<?php echo $i->name ?>">

                <!-- Unterscheidung ob Waffe oder nicht, da unterschiedliches Bildformat -->
                <div style="position: absolute; right: 20px; top: 15px; z-index: 9999;">
                    <?php if ($i->stern >= 1): ?>
                        <img src="<?php echo getAssetLink('img/symbols/stern.png'); ?>" style="border: 0px none;">
                    <?php endif ?>
                    <?php if ($i->stern == 2): ?>
                        <img src="<?php echo getAssetLink('img/symbols/stern.png'); ?>" style="border: 0px none;">
                    <?php endif ?>
                </div>
            </div>
        </td>

        <?php
        if($i->duration > 0)
        {
            $durationString = '';
            $dur = $i->duration;
            if($dur/3600 < 10) $durationString .= '0' . $dur/3600 . ':'; else $durationString .= $dur/3600 . ':';
            $dur = $dur %3600;
            if($dur/60 < 10) $durationString .= '0' . $dur/60 . ':'; else $durationString .= $dur/60 . ':';
            $dur = $dur%60;
            if($dur < 10) $durationString .= '0' . $dur; else $durationString .= $dur;
        }

        if($i->cooldown > 0) {
            $cooldownString = '';
            $cd = $i->cooldown;
            if($cd/3600 < 10) $cooldownString .= '0' . $cd/3600 . ':'; else $cooldownString .= $cd/3600 . ':';
            $cd = $cd %3600;
            if($cd/60 < 10) $cooldownString .= '0' . $cd/60 . ':'; else $cooldownString .= $cd/60 . ':';
            $cd = $cd%60;
            if($cd < 10) $cooldownString .= '0' . $cd; else $cooldownString .= $cd;
        }
        ?>

        <td class='<?php if($i->equipped) echo 'active'; else echo 'tdn'; ?>'>
            <strong><?php echo $i->name; ?> </strong><br>
            (Your inventory: <?php if($i->volume) echo $i->volume; else echo 0; ?> item(s))<br><br>
            Resale value: <?php echo number_format($i->slcost, 0, ",", "."); ?><img src="<?php echo getAssetLink('img/symbols/res2.gif'); ?>" alt="Gold" align="absmiddle" border="0"><br><br>
            <?php if($i->str > 0): ?>        Strenght: +<?php echo $i->str; ?><br> <?php endif; ?>
            <?php if($i->def > 0): ?>         Defence: +<?php echo $i->def; ?><br> <?php endif; ?>
            <?php if($i->dex > 0): ?>         Dexterity: +<?php echo $i->dex; ?><br> <?php endif; ?>
            <?php if($i->end > 0): ?>         Endurance: +<?php echo $i->end; ?><br> <?php endif; ?>
            <?php if($i->cha > 0): ?>         Charisma: +<?php echo $i->cha; ?><br> <?php endif; ?>
            <?php if($i->str < 0): ?>         Strenght: <?php echo $i->str; ?><br> <?php endif; ?>
            <?php if($i->def < 0): ?>         Defence: <?php echo $i->def; ?><br> <?php endif; ?>
            <?php if($i->dex < 0): ?>         Dexterity: <?php echo $i->dex; ?><br> <?php endif; ?>
            <?php if($i->end < 0): ?>         Endurance: <?php echo $i->end; ?><br> <?php endif; ?>
            <?php if($i->cha < 0): ?>         Charisma: <?php echo $i->cha; ?><br> <?php endif; ?>
            <?php if($i->hpbonus > 0): ?>     Vitality: +<?php echo number_format($i->hpbonus, 0, ",", "."); ?><br> <?php endif; ?>
            <?php if($i->regen > 0): ?>       Regeneration: +<?php echo number_format($i->regen, 0, ",", "."); ?><br> <?php endif; ?>
            <?php if($i->hpbonus < 0): ?>     Vitality: <?php echo number_format($i->hpbonus, 0, ",", "."); ?><br> <?php endif; ?>
            <?php if($i->regen < 0): ?>       Regeneration: <?php echo number_format($i->regen, 0, ",", "."); ?><br> <?php endif; ?>
            <?php if($i->sbschc > 0): ?>      Basic hit chance: +<?php echo $i->sbschc; ?><br> <?php endif; ?>
            <?php if($i->sbscdmg > 0): ?>     Basic damage: +<?php echo $i->sbscdmg; ?><br> <?php endif; ?>
            <?php if($i->sbsctlnt > 0): ?>    Basic talent: +<?php echo $i->sbsctlnt; ?><br> <?php endif; ?>
            <?php if($i->sbnshc > 0): ?>      Bonus hit chance: +<?php echo $i->sbnshc; ?><br> <?php endif; ?>
            <?php if($i->sbnsdmg > 0): ?>     Bonus damage: +<?php echo $i->sbnsdmg; ?><br>   <?php endif; ?>
            <?php if($i->sbnstlnt > 0): ?>    Bonus talent: +<?php echo $i->sbnstlnt; ?><br> <?php endif; ?>
            <?php if($i->sbschc < 0): ?>      Basic hit chance: <?php echo $i->sbschc; ?><br> <?php endif; ?>
            <?php if($i->sbscdmg < 0): ?>     Basic damage: <?php echo $i->sbscdmg; ?><br> <?php endif; ?>
            <?php if($i->sbsctlnt < 0): ?>    Basic talent: <?php echo $i->sbsctlnt; ?><br> <?php endif; ?>
            <?php if($i->sbnshc < 0): ?>      Bonus hit chance: <?php echo $i->sbnshc; ?><br> <?php endif; ?>
            <?php if($i->sbnsdmg < 0): ?>     Bonus damage: <?php echo $i->sbnsdmg; ?><br>   <?php endif; ?>
            <?php if($i->sbnstlnt < 0): ?>    Bonus talent: <?php echo $i->sbnstlnt; ?><br> <?php endif; ?>
            <?php if($i->ebschc < 0): ?>      Basic hit chance (on opponent): <?php echo $i->ebschc; ?><br> <?php endif; ?>
            <?php if($i->ebscdmg < 0): ?>     Basic damage (on opponent): <?php echo $i->ebscdmg; ?><br> <?php endif; ?>
            <?php if($i->ebsctlnt < 0): ?>    Basic talent (on opponent): <?php echo $i->ebsctlnt; ?><br> <?php endif; ?>
            <?php if($i->ebnshc < 0): ?>      Bonus hit chance (on opponent): <?php echo $i->ebnshc; ?><br> <?php endif; ?>
            <?php if($i->ebnsdmg < 0): ?>     Bonus damage (on opponent): <?php echo $i->ebnsdmg; ?><br> <?php endif; ?>
            <?php if($i->ebnstlnt < 0): ?>    Bonus talent (on opponent): <?php echo $i->ebnstlnt; ?><br> <?php endif; ?>
            <?php if($i->apup > 0): ?>    Energy: <?php echo $i->apup; ?><br> <?php endif; ?>
            <?php if($i->id == 156): ?>       Man hunt: gold and booty x2<br> <?php endif; ?>
            <?php if($i->duration > 0): ?>    Duration of effect: <?php echo $durationString; ?><br> <?php endif; ?>
            <?php if(!$i->duration > 0): if($i->cooldown > 0): ?>    Cooldown time: <?php echo $cooldownString; ?><br> <?php endif; endif; ?>
            <br>
            Requirement: level <?php echo number_format($i->level, 0, ",", "."); ?><br>

            <?php if(!$i->equipped && $i->expire <= time()): ?>
            <br><div class="btn-left left"><div class="btn-right">
                        <form method="post" action="<?php echo getUrl('profile/item/equip'); ?>">
                            <input type="hidden" name="__token" value="">
                            <input type="hidden" name="item_id" value="<?php echo $i->id; ?>">
                            <button class="btn">Use this item</button>
                        </form>
                    </div></div><br/>
            <?php elseif($i->cooldown > 0 && $i->expire > time()): ?>
                Cooldown time <span id="item_cooldown2_<?php echo $i->id; ?>" ></span><br/>
                <script type="text/javascript">
                    $(function () {
                        $("#item_cooldown2_<?php echo $i->id; ?>").countdown({
                            until: +<?php echo $i->expire - time(); ?>,
                            compact: true,
                            compactLabels: ['y', 'm', 'w', 'd'],
                            description: '',onExpiry: redirectUser
                        });
                    });
                    function redirectUser() {
                        setTimeout('window.location = "<?php echo url('profile/index') ?>"',3000);
                    }
                </script>
            <?php endif; ?>
        </td>
    </tr>
    <?php
}
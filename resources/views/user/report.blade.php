@extends('index')

@section('content')
    <div class="btn-left left">
        <div class="btn-right"><a href="<?php echo $backlink; ?>" class="btn">back</a></div>
    </div>
    <!--<div class="btn-left left">
    <div class="btn-right"><a href="<?php /*echo getUrl('report/fightreport/'.$reportId.'/conversion') */?>" class="btn">Battle report conversion</a></div>
</div>-->
    <br class="clearfloat">
    <div id="reportResult">
        <!-- box HEADER START -->
        <div class="wrap-top-left clearfix">
            <div class="wrap-top-right clearfix">
                <div class="wrap-top-middle clearfix"></div>
            </div>
        </div>
        <!-- box HEADER END -->
        <!-- box CONTENT START -->
        <div class="wrap-left clearfix">
            <div class="wrap-content wrap-right clearfix">
                <h2>Battle Report <?php echo date('d.m.Y H:i', $attack_date); ?></h2>
                <h3>Winner: <?php if($report['attacker']['total_damage'] > $report['defender']['total_damage']) echo e($attacker['name']); else echo e($defender['name']); ?></h3>
                <p><strong>End of fight:</strong> The <?php if($report['attacker']['total_damage'] > $report['defender']['total_damage']) echo 'attackers'; else echo 'defenders'; ?> have caused more damage (<?php if($report['attacker']['total_damage'] > $report['defender']['total_damage']): echo round($report['attacker']['total_damage'], 2); else: echo round($report['defender']['total_damage'], 2); endif; ?> : <?php if($report['attacker']['total_damage'] > $report['defender']['total_damage']): echo round($report['defender']['total_damage'], 2); else: echo round($report['attacker']['total_damage'], 2); endif; ?>)!</p>
                <p><strong>Health (after battle):</strong> <?php if($report['attacker']['id'] == $user->id) echo prettyNumber($report['attacker']['hp_end']); else echo prettyNumber($report['defender']['hp_end']); ?></p>
                <?php if($report['earned_gold'] > 0 || $report['earned_bonus_gold'] > 0): ?>
                <p class="gold"><?php if($report['attacker']['total_damage'] > $report['defender']['total_damage']) echo e($attacker['name']); else echo e($defender['name']); ?> captured: <?php if($report['earned_gold'] > 0): ?><?php echo prettyNumber($report['earned_gold']); ?> <img src="https://s202-en.bitefight.gameforge.com:443/img/symbols/res2.gif" title="Gold" align="absmiddle" border="0"> Gold<?php endif; ?><?php if($report['earned_bonus_gold'] > 0): ?>&nbsp;+&nbsp;<?php echo prettyNumber($report['earned_bonus_gold']); ?> <img src="https://s202-en.bitefight.gameforge.com:443/img/symbols/res2.gif" title="Gold bonus" align="absmiddle" border="0"> Gold bonus<?php endif; ?></p>
            <?php endif; ?>
            <!-- CONTENT END -->
            </div>
        </div>
        <!-- box CONTENT END -->
        <!-- box FOOTER START -->
        <div class="wrap-bottom-left">
            <div class="wrap-bottom-right">
                <div class="wrap-bottom-middle"></div>
            </div>
        </div>
        <!-- box CONTENT END -->
    </div>
    <br>
    <h1 id="fighter_details_header" style="cursor:pointer;">Report details</h1>
    <div id="fighter_details" style="">
        <div id="reportResult">
            <div class="wrap-top-left clearfix">
                <div class="wrap-top-right clearfix">
                    <div class="wrap-top-middle clearfix"></div>
                </div>
            </div>
            <div class="wrap-left clearfix">
                <div class="wrap-content wrap-right clearfix">
                    <h2>Report details</h2>
                    <div class="table-wrap clearfix">
                        <div id="fighter_details_attacker" class="fighter_details_view" style="width:355px;z-index:10000;float:left;">
                            <table cellpadding="2" cellspacing="2" border="0" width="100%">
                                <tbody>
                                <tr>
                                    <td class="tdh" align="center" colspan="2">
                                        <h3><a href="<?php echo getUrl('profile/player/'.$attacker->id); ?>"><?php echo e($attacker->name); ?><?php if($attacker->clan_id > 0): ?></a>&nbsp;<a href="<?php echo getUrl('clan/'.$attacker->clan_id); ?>">[<?php echo e($attacker->tag); ?>]</a><?php endif; ?></h3>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="logo" align="center" valign="middle" colspan="2">
                                        <?php if($attacker->show_picture): ?>
                                        <img src="<?php echo getAssetLink('img/logo/'.$attacker->race.'/'.$attacker->gender.'/'.$attacker->image_type.'.jpg') ?>" border="0" width="168" alt="playerlogo">
                                        <?php else: ?>
                                        <img src="<?php echo getAssetLink('img/symbols/race'.$attacker->race.'.gif'); ?>" border="0" width="168" alt="playerlogo">
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="rbg">Level:</td>
                                    <td class="rbg"><?php echo prettyNumber($report['attacker']['level']); ?></td>
                                </tr>
                                <tr>
                                    <td class="rbg">Battle value:</td>
                                    <td class="rbg" colspan="2"><?php echo prettyNumber($report['attacker']['battle_value']); ?></td>
                                </tr>
                                <tr>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                </tr>
                                </tbody>
                            </table>
                            <table cellpadding="2" cellspacing="2" border="0" width="100%">
                                <tbody>
                                <tr>
                                    <td class="rbg">Strength:</td>
                                    <td class="rbg">
                                        <div class="tooltip" title="<div>
                                        <div class='tooltipHead'>Strength: +<?php echo prettyNumber($report['attacker']['str'] + $report['attacker']['str_extra']) ?></div>
                                        <div class='tooltipHeadInfo'><p>* Bonus Damage (self)</p></div>
                                        <div class='tooltipContent'>
                                            <table>
                                                <tr>
                                                    <td>Basic value:</td>
                                                    <td align='right'>+<?php echo prettyNumber($report['attacker']['str']); ?></td>
                                                </tr>
                                                <?php foreach($report['attacker']['str_extra_tooltip'] as $tooltip): ?>
                                                <tr>
                                                    <td><?php echo $tooltip['name']; ?>:</td>
                                                    <td align='right'><?php echo plusSignedNumberString($tooltip['val']); ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                                </table>
                                            </div></div>" style="text-align:left;">
                                            <img src="<?php echo getAssetLink('img/b1.gif') ?>" alt=""><img src="<?php echo getAssetLink('img/b2.gif'); ?>" alt="" height="12" width="<?php echo $report['attacker']['str'] / $report['attacker']['max_stat'] * 130; ?>"><?php if($report['attacker']['str_extra'] > 0): ?><img src="<?php echo getAssetLink('img/b6.gif'); ?>" alt="" height="12" width="<?php echo $report['attacker']['str_extra'] / $report['attacker']['max_stat'] * 130; ?>"><img src="<?php echo getAssetLink('img/b7.gif'); ?>" alt=""><?php else: ?><img src="<?php echo getAssetLink('img/b3.gif'); ?>" alt=""><?php endif; ?>
                                            <span class="fontsmall">(<?php echo prettyNumber($report['attacker']['str'] + $report['attacker']['str_extra']); ?>)</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="rbg">Defence:</td>
                                    <td class="rbg">
                                        <div class="tooltip" title="<div>
                                        <div class='tooltipHead'>Defence: +<?php echo prettyNumber($report['attacker']['def'] + $report['attacker']['def_extra']) ?></div>
                                        <div class='tooltipHeadInfo'><p>* Bonus hit chance (on opponents)</p><p>* Maximum health</p></div>
                                        <div class='tooltipContent'>
                                            <table>
                                                <tr>
                                                    <td>Basic value:</td>
                                                    <td align='right'>+<?php echo prettyNumber($report['attacker']['def']); ?></td>
                                                </tr>
                                                <?php foreach($report['attacker']['def_extra_tooltip'] as $tooltip): ?>
                                                <tr>
                                                    <td><?php echo $tooltip['name']; ?>:</td>
                                                    <td align='right'><?php echo plusSignedNumberString($tooltip['val']); ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                                </table>
                                            </div></div>" style="text-align:left;">
                                            <img src="<?php echo getAssetLink('img/b1.gif') ?>" alt=""><img src="<?php echo getAssetLink('img/b2.gif'); ?>" alt="" height="12" width="<?php echo $report['attacker']['def'] / $report['attacker']['max_stat'] * 130; ?>"><?php if($report['attacker']['def_extra'] > 0): ?><img src="<?php echo getAssetLink('img/b6.gif'); ?>" alt="" height="12" width="<?php echo $report['attacker']['def_extra'] / $report['attacker']['max_stat'] * 130; ?>"><img src="<?php echo getAssetLink('img/b7.gif'); ?>" alt=""><?php else: ?><img src="<?php echo getAssetLink('img/b3.gif'); ?>" alt=""><?php endif; ?>
                                            <span class="fontsmall">(<?php echo prettyNumber($report['attacker']['def'] + $report['attacker']['def_extra']); ?>)</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="rbg">Dexterity:</td>
                                    <td class="rbg">
                                        <div class="tooltip" title="<div>
                                        <div class='tooltipHead'>Dexterity: +<?php echo prettyNumber($report['attacker']['dex'] + $report['attacker']['dex_extra']) ?></div>
                                        <div class='tooltipHeadInfo'><p>* Bonus hit chance (self)</p></div>
                                        <div class='tooltipContent'>
                                            <table>
                                                <tr>
                                                    <td>Basic value:</td>
                                                    <td align='right'>+<?php echo prettyNumber($report['attacker']['dex']); ?></td>
                                                </tr>
                                                <?php foreach($report['attacker']['dex_extra_tooltip'] as $tooltip): ?>
                                                <tr>
                                                    <td><?php echo $tooltip['name']; ?>:</td>
                                                    <td align='right'><?php echo plusSignedNumberString($tooltip['val']); ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                                </table>
                                            </div></div>" style="text-align:left;">
                                            <img src="<?php echo getAssetLink('img/b1.gif') ?>" alt=""><img src="<?php echo getAssetLink('img/b2.gif'); ?>" alt="" height="12" width="<?php echo $report['attacker']['dex'] / $report['attacker']['max_stat'] * 130; ?>"><?php if($report['attacker']['dex_extra'] > 0): ?><img src="<?php echo getAssetLink('img/b6.gif'); ?>" alt="" height="12" width="<?php echo $report['attacker']['dex_extra'] / $report['attacker']['max_stat'] * 130; ?>"><img src="<?php echo getAssetLink('img/b7.gif'); ?>" alt=""><?php else: ?><img src="<?php echo getAssetLink('img/b3.gif'); ?>" alt=""><?php endif; ?>
                                            <span class="fontsmall">(<?php echo prettyNumber($report['attacker']['dex'] + $report['attacker']['dex_extra']); ?>)</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="rbg">Endurance:</td>
                                    <td class="rbg">
                                        <div class="tooltip" title="<div>
                                        <div class='tooltipHead'>Endurance: +<?php echo prettyNumber($report['attacker']['end'] + $report['attacker']['end_extra']) ?></div>
                                        <div class='tooltipHeadInfo'><p>* Bonus damage (on opponents)</p><p>* Regeneration</p></div>
                                        <div class='tooltipContent'>
                                            <table>
                                                <tr>
                                                    <td>Basic value:</td>
                                                    <td align='right'>+<?php echo prettyNumber($report['attacker']['end']); ?></td>
                                                </tr>
                                                <?php foreach($report['attacker']['end_extra_tooltip'] as $tooltip): ?>
                                                <tr>
                                                    <td><?php echo $tooltip['name']; ?>:</td>
                                                    <td align='right'><?php echo plusSignedNumberString($tooltip['val']); ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                                </table>
                                            </div></div>" style="text-align:left;">
                                            <img src="<?php echo getAssetLink('img/b1.gif') ?>" alt=""><img src="<?php echo getAssetLink('img/b2.gif'); ?>" alt="" height="12" width="<?php echo $report['attacker']['end'] / $report['attacker']['max_stat'] * 130; ?>"><?php if($report['attacker']['end_extra'] > 0): ?><img src="<?php echo getAssetLink('img/b6.gif'); ?>" alt="" height="12" width="<?php echo $report['attacker']['end_extra'] / $report['attacker']['max_stat'] * 130; ?>"><img src="<?php echo getAssetLink('img/b7.gif'); ?>" alt=""><?php else: ?><img src="<?php echo getAssetLink('img/b3.gif'); ?>" alt=""><?php endif; ?>
                                            <span class="fontsmall">(<?php echo prettyNumber($report['attacker']['end'] + $report['attacker']['end_extra']); ?>)</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="rbg">Charisma:</td>
                                    <td class="rbg">
                                        <div class="tooltip" title="<div>
                                        <div class='tooltipHead'>Charisma: +<?php echo prettyNumber($report['attacker']['cha'] + $report['attacker']['cha_extra']) ?></div>
                                        <div class='tooltipHeadInfo'><p>* Bonus talents (self)</p><p>* Bonus talents (on opponents)</p></div>
                                        <div class='tooltipContent'>
                                            <table>
                                                <tr>
                                                    <td>Basic value:</td>
                                                    <td align='right'>+<?php echo prettyNumber($report['attacker']['cha']); ?></td>
                                                </tr>
                                                <?php foreach($report['attacker']['cha_extra_tooltip'] as $tooltip): ?>
                                                <tr>
                                                    <td><?php echo $tooltip['name']; ?>:</td>
                                                    <td align='right'><?php echo plusSignedNumberString($tooltip['val']); ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                                </table>
                                            </div></div>" style="text-align:left;">
                                            <img src="<?php echo getAssetLink('img/b1.gif') ?>" alt=""><img src="<?php echo getAssetLink('img/b2.gif'); ?>" alt="" height="12" width="<?php echo $report['attacker']['cha'] / $report['attacker']['max_stat'] * 130; ?>"><?php if($report['attacker']['cha_extra'] > 0): ?><img src="<?php echo getAssetLink('img/b6.gif'); ?>" alt="" height="12" width="<?php echo $report['attacker']['cha_extra'] / $report['attacker']['max_stat'] * 130; ?>"><img src="<?php echo getAssetLink('img/b7.gif'); ?>" alt=""><?php else: ?><img src="<?php echo getAssetLink('img/b3.gif'); ?>" alt=""><?php endif; ?>
                                            <span class="fontsmall">(<?php echo prettyNumber($report['attacker']['cha'] + $report['attacker']['cha_extra']); ?>)</span>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <table cellpadding="2" cellspacing="2" border="0" width="100%">
                                <tbody><tr>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                </tr>
                                <tr>
                                    <td class="rbg">Health (at the beginning):</td>
                                    <td class="rbg"><?php echo prettyNumber($report['attacker']['hp_start']); ?></td>
                                </tr>
                                <tr>
                                    <td class="rbg">Health (after battle):</td>
                                    <td class="rbg"><?php echo prettyNumber($report['attacker']['hp_end']); ?></td>
                                </tr>
                                </tbody>
                            </table>
                            <?php if(isset($report['attacker']['items']['weapon']) > 0 || isset($report['attacker']['items']['helmet']) > 0 || isset($report['attacker']['items']['armour']) > 0 || isset($report['attacker']['items']['jewellery']) > 0 || isset($report['attacker']['items']['boot']) > 0 || isset($report['attacker']['items']['shield']) > 0 || isset($report['attacker']['items']['glove']) > 0): ?>
                            <h3>Items</h3>
                            <table class="items" cellpadding="2" cellspacing="2" border="0" width="100%">
                                <tbody>
                                <?php if(isset($report['attacker']['items']['weapon'])): ?>
                                <tr>
                                    <td>Weapon</td>
                                    <td>
                                        <span class="tooltip" title='<div style="width:300px;">
                                                <img src="<?php echo getAssetLink('img/items/1/'.$report['attacker']['items']['weapon']['id'].'.jpg') ?>" alt="<?php echo $report['attacker']['items']['weapon']['name']; ?>">

                                                <!-- Unterscheidung ob Waffe oder nicht, da unterschiedliches Bildformat -->
                                                <div style="position:absolute;right:20px;top:15px;z-index:9999">
                                                    <?php for($y = 0; $y < $report['attacker']['items']['weapon']['stern']; $y++): ?>
                                                <img src="<?php echo getAssetLink('img/symbols/stern.png'); ?>" style="border: 0 none;">
                                                    <?php endfor; ?>
                                                </div>
                                            </div>' style="text-align:left;"><?php echo $report['attacker']['items']['weapon']['name']; ?></span>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                <?php if(isset($report['attacker']['items']['helmet'])): ?>
                                <tr>
                                    <td>Helmet</td>
                                    <td>
                                        <span class="tooltip" title='<div style="width:300px;">
                                                <img src="<?php echo getAssetLink('img/items/3/'.$report['attacker']['items']['helmet']['id'].'.jpg') ?>" alt="<?php echo $report['attacker']['items']['helmet']['name']; ?>">

                                                <!-- Unterscheidung ob Waffe oder nicht, da unterschiedliches Bildformat -->
                                                <div style="position:absolute;right:20px;top:15px;z-index:9999">
                                                    <?php for($y = 0; $y < $report['attacker']['items']['helmet']['stern']; $y++): ?>
                                                <img src="<?php echo getAssetLink('img/symbols/stern.png'); ?>" style="border: 0 none;">
                                                    <?php endfor; ?>
                                                </div>
                                            </div>' style="text-align:left;"><?php echo $report['attacker']['items']['helmet']['name']; ?></span>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                <?php if(isset($report['attacker']['items']['armour'])): ?>
                                <tr>
                                    <td>Armour</td>
                                    <td>
                                        <span class="tooltip" title='<div style="width:300px;">
                                                <img src="<?php echo getAssetLink('img/items/4/'.$report['attacker']['items']['armour']['id'].'.jpg') ?>" alt="<?php echo $report['attacker']['items']['armour']['name']; ?>">

                                                <!-- Unterscheidung ob Waffe oder nicht, da unterschiedliches Bildformat -->
                                                <div style="position:absolute;right:20px;top:15px;z-index:9999">
                                                    <?php for($y = 0; $y < $report['attacker']['items']['armour']['stern']; $y++): ?>
                                                <img src="<?php echo getAssetLink('img/symbols/stern.png'); ?>" style="border: 0 none;">
                                                    <?php endfor; ?>
                                                </div>
                                            </div>' style="text-align:left;"><?php echo $report['attacker']['items']['armour']['name']; ?></span>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                <?php if(isset($report['attacker']['items']['jewellery'])): ?>
                                <tr>
                                    <td>Jewellery</td>
                                    <td>
                                        <span class="tooltip" title='<div style="width:300px;">
                                                <img src="<?php echo getAssetLink('img/items/5/'.$report['attacker']['items']['jewellery']['id'].'.jpg') ?>" alt="<?php echo $report['attacker']['items']['jewellery']['name']; ?>">

                                                <!-- Unterscheidung ob Waffe oder nicht, da unterschiedliches Bildformat -->
                                                <div style="position:absolute;right:20px;top:15px;z-index:9999">
                                                    <?php for($y = 0; $y < $report['attacker']['items']['jewellery']['stern']; $y++): ?>
                                                <img src="<?php echo getAssetLink('img/symbols/stern.png'); ?>" style="border: 0 none;">
                                                    <?php endfor; ?>
                                                </div>
                                            </div>' style="text-align:left;"><?php echo $report['attacker']['items']['jewellery']['name']; ?></span>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                <?php if(isset($report['attacker']['items']['glove'])): ?>
                                <tr>
                                    <td>Gloves</td>
                                    <td>
                                        <span class="tooltip" title='<div style="width:300px;">
                                                <img src="<?php echo getAssetLink('img/items/6/'.$report['attacker']['items']['glove']['id'].'.jpg') ?>" alt="<?php echo $report['attacker']['items']['glove']['name']; ?>">

                                                <!-- Unterscheidung ob Waffe oder nicht, da unterschiedliches Bildformat -->
                                                <div style="position:absolute;right:20px;top:15px;z-index:9999">
                                                    <?php for($y = 0; $y < $report['attacker']['items']['glove']['stern']; $y++): ?>
                                                <img src="<?php echo getAssetLink('img/symbols/stern.png'); ?>" style="border: 0 none;">
                                                    <?php endfor; ?>
                                                </div>
                                            </div>' style="text-align:left;"><?php echo $report['attacker']['items']['glove']['name']; ?></span>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                <?php if(isset($report['attacker']['items']['boot'])): ?>
                                <tr>
                                    <td>Boots</td>
                                    <td>
                                        <span class="tooltip" title='<div style="width:300px;">
                                                <img src="<?php echo getAssetLink('img/items/7/'.$report['attacker']['items']['boot']['id'].'.jpg') ?>" alt="<?php echo $report['attacker']['items']['boot']['name']; ?>">

                                                <!-- Unterscheidung ob Waffe oder nicht, da unterschiedliches Bildformat -->
                                                <div style="position:absolute;right:20px;top:15px;z-index:9999">
                                                    <?php for($y = 0; $y < $report['attacker']['items']['boot']['stern']; $y++): ?>
                                                <img src="<?php echo getAssetLink('img/symbols/stern.png'); ?>" style="border: 0 none;">
                                                    <?php endfor; ?>
                                                </div>
                                            </div>' style="text-align:left;"><?php echo $report['attacker']['items']['boot']['name']; ?></span>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                <?php if(isset($report['attacker']['items']['shield'])): ?>
                                <tr>
                                    <td>Shield</td>
                                    <td>
                                        <span class="tooltip" title='<div style="width:300px;">
                                                <img src="<?php echo getAssetLink('img/items/8/'.$report['attacker']['items']['shield']['id'].'.jpg') ?>" alt="<?php echo $report['attacker']['items']['shield']['name']; ?>">

                                                <!-- Unterscheidung ob Waffe oder nicht, da unterschiedliches Bildformat -->
                                                <div style="position:absolute;right:20px;top:15px;z-index:9999">
                                                    <?php for($y = 0; $y < $report['attacker']['items']['shield']['stern']; $y++): ?>
                                                <img src="<?php echo getAssetLink('img/symbols/stern.png'); ?>" style="border: 0 none;">
                                                    <?php endfor; ?>
                                                </div>
                                            </div>' style="text-align:left;"><?php echo $report['attacker']['items']['shield']['name']; ?></span>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                            <?php endif; ?>
                        </div>
                        <div id="fighter_details_defender" class="fighter_details_view" style="width:355px;z-index:10000;float:left;">
                            <table cellpadding="2" cellspacing="2" border="0" width="100%">
                                <tbody>
                                <tr>
                                    <td class="tdh" align="center" colspan="2">
                                        <h3><a href="<?php echo getUrl('profile/player/'.$defender->id); ?>"><?php echo e($defender->name); ?><?php if($defender->clan_id > 0): ?></a>&nbsp;<a href="<?php echo getUrl('clan/'.$defender->clan_id); ?>">[<?php echo e($defender->tag); ?>]</a><?php endif; ?></h3>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="logo" align="center" valign="middle" colspan="2">
                                        <?php if($defender->show_picture): ?>
                                        <img src="<?php echo getAssetLink('img/logo/'.$defender->race.'/'.$defender->gender.'/'.$defender->image_type.'.jpg') ?>" border="0" width="168" alt="playerlogo">
                                        <?php else: ?>
                                        <img src="<?php echo getAssetLink('img/symbols/race'.$defender->race.'.gif'); ?>" border="0" width="168" alt="playerlogo">
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="rbg">Level:</td>
                                    <td class="rbg"><?php echo prettyNumber($report['defender']['level']); ?></td>
                                </tr>
                                <tr>
                                    <td class="rbg">Battle value:</td>
                                    <td class="rbg" colspan="2"><?php echo prettyNumber($report['defender']['battle_value']); ?></td>
                                </tr>
                                <tr>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                </tr>
                                </tbody>
                            </table>
                            <table cellpadding="2" cellspacing="2" border="0" width="100%">
                                <tbody>
                                <tr>
                                    <td class="rbg">Strength:</td>
                                    <td class="rbg">
                                        <div class="tooltip" title="<div>
                                        <div class='tooltipHead'>Strength: +<?php echo prettyNumber($report['defender']['str'] + $report['defender']['str_extra']) ?></div>
                                        <div class='tooltipHeadInfo'><p>* Bonus Damage (self)</p></div>
                                        <div class='tooltipContent'>
                                            <table>
                                                <tr>
                                                    <td>Basic value:</td>
                                                    <td align='right'>+<?php echo prettyNumber($report['defender']['str']); ?></td>
                                                </tr>
                                                <?php foreach($report['defender']['str_extra_tooltip'] as $tooltip): ?>
                                                <tr>
                                                    <td><?php echo $tooltip['name']; ?>:</td>
                                                    <td align='right'><?php echo plusSignedNumberString($tooltip['val']); ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                                </table>
                                            </div></div>" style="text-align:left;">
                                            <img src="<?php echo getAssetLink('img/b1.gif') ?>" alt=""><img src="<?php echo getAssetLink('img/b2.gif'); ?>" alt="" height="12" width="<?php echo $report['defender']['str'] / $report['defender']['max_stat'] * 130; ?>"><?php if($report['defender']['str_extra'] > 0): ?><img src="<?php echo getAssetLink('img/b6.gif'); ?>" alt="" height="12" width="<?php echo $report['defender']['str_extra'] / $report['defender']['max_stat'] * 130; ?>"><img src="<?php echo getAssetLink('img/b7.gif'); ?>" alt=""><?php else: ?><img src="<?php echo getAssetLink('img/b3.gif'); ?>" alt=""><?php endif; ?>
                                            <span class="fontsmall">(<?php echo prettyNumber($report['defender']['str'] + $report['defender']['str_extra']); ?>)</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="rbg">Defence:</td>
                                    <td class="rbg">
                                        <div class="tooltip" title="<div>
                                        <div class='tooltipHead'>Defence: +<?php echo prettyNumber($report['defender']['def'] + $report['defender']['def_extra']) ?></div>
                                        <div class='tooltipHeadInfo'><p>* Bonus hit chance (on opponents)</p><p>* Maximum health</p></div>
                                        <div class='tooltipContent'>
                                            <table>
                                                <tr>
                                                    <td>Basic value:</td>
                                                    <td align='right'>+<?php echo prettyNumber($report['defender']['def']); ?></td>
                                                </tr>
                                                <?php foreach($report['defender']['def_extra_tooltip'] as $tooltip): ?>
                                                <tr>
                                                    <td><?php echo $tooltip['name']; ?>:</td>
                                                    <td align='right'><?php echo plusSignedNumberString($tooltip['val']); ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                                </table>
                                            </div></div>" style="text-align:left;">
                                            <img src="<?php echo getAssetLink('img/b1.gif') ?>" alt=""><img src="<?php echo getAssetLink('img/b2.gif'); ?>" alt="" height="12" width="<?php echo $report['defender']['def'] / $report['defender']['max_stat'] * 130; ?>"><?php if($report['defender']['def_extra'] > 0): ?><img src="<?php echo getAssetLink('img/b6.gif'); ?>" alt="" height="12" width="<?php echo $report['defender']['def_extra'] / $report['defender']['max_stat'] * 130; ?>"><img src="<?php echo getAssetLink('img/b7.gif'); ?>" alt=""><?php else: ?><img src="<?php echo getAssetLink('img/b3.gif'); ?>" alt=""><?php endif; ?>
                                            <span class="fontsmall">(<?php echo prettyNumber($report['defender']['def'] + $report['defender']['def_extra']); ?>)</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="rbg">Dexterity:</td>
                                    <td class="rbg">
                                        <div class="tooltip" title="<div>
                                        <div class='tooltipHead'>Dexterity: +<?php echo prettyNumber($report['defender']['dex'] + $report['defender']['dex_extra']) ?></div>
                                        <div class='tooltipHeadInfo'><p>* Bonus hit chance (self)</p></div>
                                        <div class='tooltipContent'>
                                            <table>
                                                <tr>
                                                    <td>Basic value:</td>
                                                    <td align='right'>+<?php echo prettyNumber($report['defender']['dex']); ?></td>
                                                </tr>
                                                <?php foreach($report['defender']['dex_extra_tooltip'] as $tooltip): ?>
                                                <tr>
                                                    <td><?php echo $tooltip['name']; ?>:</td>
                                                    <td align='right'><?php echo plusSignedNumberString($tooltip['val']); ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                                </table>
                                            </div></div>" style="text-align:left;">
                                            <img src="<?php echo getAssetLink('img/b1.gif') ?>" alt=""><img src="<?php echo getAssetLink('img/b2.gif'); ?>" alt="" height="12" width="<?php echo $report['defender']['dex'] / $report['defender']['max_stat'] * 130; ?>"><?php if($report['defender']['dex_extra'] > 0): ?><img src="<?php echo getAssetLink('img/b6.gif'); ?>" alt="" height="12" width="<?php echo $report['defender']['dex_extra'] / $report['defender']['max_stat'] * 130; ?>"><img src="<?php echo getAssetLink('img/b7.gif'); ?>" alt=""><?php else: ?><img src="<?php echo getAssetLink('img/b3.gif'); ?>" alt=""><?php endif; ?>
                                            <span class="fontsmall">(<?php echo prettyNumber($report['defender']['dex'] + $report['defender']['dex_extra']); ?>)</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="rbg">Endurance:</td>
                                    <td class="rbg">
                                        <div class="tooltip" title="<div>
                                        <div class='tooltipHead'>Endurance: +<?php echo prettyNumber($report['defender']['end'] + $report['defender']['end_extra']) ?></div>
                                        <div class='tooltipHeadInfo'><p>* Bonus damage (on opponents)</p><p>* Regeneration</p></div>
                                        <div class='tooltipContent'>
                                            <table>
                                                <tr>
                                                    <td>Basic value:</td>
                                                    <td align='right'>+<?php echo prettyNumber($report['defender']['end']); ?></td>
                                                </tr>
                                                <?php foreach($report['defender']['end_extra_tooltip'] as $tooltip): ?>
                                                <tr>
                                                    <td><?php echo $tooltip['name']; ?>:</td>
                                                    <td align='right'><?php echo plusSignedNumberString($tooltip['val']); ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                                </table>
                                            </div></div>" style="text-align:left;">
                                            <img src="<?php echo getAssetLink('img/b1.gif') ?>" alt=""><img src="<?php echo getAssetLink('img/b2.gif'); ?>" alt="" height="12" width="<?php echo $report['defender']['end'] / $report['defender']['max_stat'] * 130; ?>"><?php if($report['defender']['end_extra'] > 0): ?><img src="<?php echo getAssetLink('img/b6.gif'); ?>" alt="" height="12" width="<?php echo $report['defender']['end_extra'] / $report['defender']['max_stat'] * 130; ?>"><img src="<?php echo getAssetLink('img/b7.gif'); ?>" alt=""><?php else: ?><img src="<?php echo getAssetLink('img/b3.gif'); ?>" alt=""><?php endif; ?>
                                            <span class="fontsmall">(<?php echo prettyNumber($report['defender']['end'] + $report['defender']['end_extra']); ?>)</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="rbg">Charisma:</td>
                                    <td class="rbg">
                                        <div class="tooltip" title="<div>
                                        <div class='tooltipHead'>Charisma: +<?php echo prettyNumber($report['defender']['cha'] + $report['defender']['cha_extra']) ?></div>
                                        <div class='tooltipHeadInfo'><p>* Bonus talents (self)</p><p>* Bonus talents (on opponents)</p></div>
                                        <div class='tooltipContent'>
                                            <table>
                                                <tr>
                                                    <td>Basic value:</td>
                                                    <td align='right'>+<?php echo prettyNumber($report['defender']['cha']); ?></td>
                                                </tr>
                                                <?php foreach($report['defender']['cha_extra_tooltip'] as $tooltip): ?>
                                                <tr>
                                                    <td><?php echo $tooltip['name']; ?>:</td>
                                                    <td align='right'><?php echo plusSignedNumberString($tooltip['val']); ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                                </table>
                                            </div></div>" style="text-align:left;">
                                            <img src="<?php echo getAssetLink('img/b1.gif') ?>" alt=""><img src="<?php echo getAssetLink('img/b2.gif'); ?>" alt="" height="12" width="<?php echo $report['defender']['cha'] / $report['defender']['max_stat'] * 130; ?>"><?php if($report['defender']['cha_extra'] > 0): ?><img src="<?php echo getAssetLink('img/b6.gif'); ?>" alt="" height="12" width="<?php echo $report['defender']['cha_extra'] / $report['defender']['max_stat'] * 130; ?>"><img src="<?php echo getAssetLink('img/b7.gif'); ?>" alt=""><?php else: ?><img src="<?php echo getAssetLink('img/b3.gif'); ?>" alt=""><?php endif; ?>
                                            <span class="fontsmall">(<?php echo prettyNumber($report['defender']['cha'] + $report['defender']['cha_extra']); ?>)</span>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <table cellpadding="2" cellspacing="2" border="0" width="100%">
                                <tbody><tr>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                </tr>
                                <tr>
                                    <td class="rbg">Health (at the beginning):</td>
                                    <td class="rbg"><?php echo prettyNumber($report['defender']['hp_start']); ?></td>
                                </tr>
                                <tr>
                                    <td class="rbg">Health (after battle):</td>
                                    <td class="rbg"><?php echo prettyNumber($report['defender']['hp_end']); ?></td>
                                </tr>
                                </tbody>
                            </table>
                            <?php if(isset($report['defender']['items']['weapon']) > 0 || isset($report['defender']['items']['helmet']) > 0 || isset($report['defender']['items']['armour']) > 0 || isset($report['defender']['items']['jewellery']) > 0 || isset($report['defender']['items']['boot']) > 0 || isset($report['defender']['items']['glove']) > 0 || isset($report['defender']['items']['shield']) > 0): ?>
                            <h3>Items</h3>
                            <table class="items" cellpadding="2" cellspacing="2" border="0" width="100%">
                                <tbody>
                                <?php if(isset($report['defender']['items']['weapon'])): ?>
                                <tr>
                                    <td>Weapon</td>
                                    <td>
                                        <span class="tooltip" title='<div style="width:300px;">
                                                <img src="<?php echo getAssetLink('img/items/1/'.$report['defender']['items']['weapon']['id'].'.jpg') ?>" alt="<?php echo $report['defender']['items']['weapon']['name']; ?>">

                                                <!-- Unterscheidung ob Waffe oder nicht, da unterschiedliches Bildformat -->
                                                <div style="position:absolute;right:20px;top:15px;z-index:9999">
                                                    <?php for($y = 0; $y < $report['defender']['items']['weapon']['stern']; $y++): ?>
                                                <img src="<?php echo getAssetLink('img/symbols/stern.png'); ?>" style="border: 0 none;">
                                                    <?php endfor; ?>
                                                </div>
                                            </div>' style="text-align:left;"><?php echo $report['defender']['items']['weapon']['name']; ?></span>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                <?php if(isset($report['defender']['items']['helmet'])): ?>
                                <tr>
                                    <td>Helmet</td>
                                    <td>
                                        <span class="tooltip" title='<div style="width:300px;">
                                                <img src="<?php echo getAssetLink('img/items/3/'.$report['defender']['items']['helmet']['id'].'.jpg') ?>" alt="<?php echo $report['defender']['items']['helmet']['name']; ?>">

                                                <!-- Unterscheidung ob Waffe oder nicht, da unterschiedliches Bildformat -->
                                                <div style="position:absolute;right:20px;top:15px;z-index:9999">
                                                    <?php for($y = 0; $y < $report['defender']['items']['helmet']['stern']; $y++): ?>
                                                <img src="<?php echo getAssetLink('img/symbols/stern.png'); ?>" style="border: 0 none;">
                                                    <?php endfor; ?>
                                                </div>
                                            </div>' style="text-align:left;"><?php echo $report['defender']['items']['helmet']['name']; ?></span>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                <?php if(isset($report['defender']['items']['armour'])): ?>
                                <tr>
                                    <td>Armour</td>
                                    <td>
                                        <span class="tooltip" title='<div style="width:300px;">
                                                <img src="<?php echo getAssetLink('img/items/4/'.$report['defender']['items']['armour']['id'].'.jpg') ?>" alt="<?php echo $report['defender']['items']['armour']['name']; ?>">

                                                <!-- Unterscheidung ob Waffe oder nicht, da unterschiedliches Bildformat -->
                                                <div style="position:absolute;right:20px;top:15px;z-index:9999">
                                                    <?php for($y = 0; $y < $report['defender']['items']['armour']['stern']; $y++): ?>
                                                <img src="<?php echo getAssetLink('img/symbols/stern.png'); ?>" style="border: 0 none;">
                                                    <?php endfor; ?>
                                                </div>
                                            </div>' style="text-align:left;"><?php echo $report['defender']['items']['armour']['name']; ?></span>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                <?php if(isset($report['defender']['items']['jewellery'])): ?>
                                <tr>
                                    <td>Jewellery</td>
                                    <td>
                                        <span class="tooltip" title='<div style="width:300px;">
                                                <img src="<?php echo getAssetLink('img/items/5/'.$report['defender']['items']['jewellery']['id'].'.jpg') ?>" alt="<?php echo $report['defender']['items']['jewellery']['name']; ?>">

                                                <!-- Unterscheidung ob Waffe oder nicht, da unterschiedliches Bildformat -->
                                                <div style="position:absolute;right:20px;top:15px;z-index:9999">
                                                    <?php for($y = 0; $y < $report['defender']['items']['jewellery']['stern']; $y++): ?>
                                                <img src="<?php echo getAssetLink('img/symbols/stern.png'); ?>" style="border: 0 none;">
                                                    <?php endfor; ?>
                                                </div>
                                            </div>' style="text-align:left;"><?php echo $report['defender']['items']['jewellery']['name']; ?></span>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                <?php if(isset($report['defender']['items']['glove'])): ?>
                                <tr>
                                    <td>Gloves</td>
                                    <td>
                                        <span class="tooltip" title='<div style="width:300px;">
                                                <img src="<?php echo getAssetLink('img/items/6/'.$report['defender']['items']['glove']['id'].'.jpg') ?>" alt="<?php echo $report['defender']['items']['glove']['name']; ?>">

                                                <!-- Unterscheidung ob Waffe oder nicht, da unterschiedliches Bildformat -->
                                                <div style="position:absolute;right:20px;top:15px;z-index:9999">
                                                    <?php for($y = 0; $y < $report['defender']['items']['glove']['stern']; $y++): ?>
                                                <img src="<?php echo getAssetLink('img/symbols/stern.png'); ?>" style="border: 0 none;">
                                                    <?php endfor; ?>
                                                </div>
                                            </div>' style="text-align:left;"><?php echo $report['defender']['items']['glove']['name']; ?></span>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                <?php if(isset($report['defender']['items']['boot'])): ?>
                                <tr>
                                    <td>Boots</td>
                                    <td>
                                        <span class="tooltip" title='<div style="width:300px;">
                                                <img src="<?php echo getAssetLink('img/items/7/'.$report['defender']['items']['boot']['id'].'.jpg') ?>" alt="<?php echo $report['defender']['items']['boot']['name']; ?>">

                                                <!-- Unterscheidung ob Waffe oder nicht, da unterschiedliches Bildformat -->
                                                <div style="position:absolute;right:20px;top:15px;z-index:9999">
                                                    <?php for($y = 0; $y < $report['defender']['items']['boot']['stern']; $y++): ?>
                                                <img src="<?php echo getAssetLink('img/symbols/stern.png'); ?>" style="border: 0 none;">
                                                    <?php endfor; ?>
                                                </div>
                                            </div>' style="text-align:left;"><?php echo $report['defender']['items']['boot']['name']; ?></span>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                <?php if(isset($report['defender']['items']['shield'])): ?>
                                <tr>
                                    <td>Shield</td>
                                    <td>
                                        <span class="tooltip" title='<div style="width:300px;">
                                                <img src="<?php echo getAssetLink('img/items/8/'.$report['defender']['items']['shield']['id'].'.jpg') ?>" alt="<?php echo $report['defender']['items']['shield']['name']; ?>">

                                                <!-- Unterscheidung ob Waffe oder nicht, da unterschiedliches Bildformat -->
                                                <div style="position:absolute;right:20px;top:15px;z-index:9999">
                                                    <?php for($y = 0; $y < $report['defender']['items']['shield']['stern']; $y++): ?>
                                                <img src="<?php echo getAssetLink('img/symbols/stern.png'); ?>" style="border: 0 none;">
                                                    <?php endfor; ?>
                                                </div>
                                            </div>' style="text-align:left;"><?php echo $report['defender']['items']['shield']['name']; ?></span>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                            <?php endif; ?>
                            <?php if($report['defender']['wall'] > 0 || $report['defender']['land'] > 0): ?>
                            <h3>Active effects</h3>
                            <table class="items" cellpadding="2" cellspacing="2" border="0" width="100%">
                                <tbody>
                                <?php if($report['defender']['wall'] > 0): ?>
                                <tr>
                                    <td>
                                        <span class="tooltip" title='<img alt="Wall" src="<?php echo getAssetLink('img/hideout/1/palace2.jpg'); ?>">' style="text-align:left;">Wall</span>
                                    </td>
                                    <td>Bonus damage (on opponent): <?php echo getWallEffect($report['defender']['wall']); ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if($report['defender']['land'] > 0): ?>
                                <tr>
                                    <td>
                                        <span class="tooltip" title='<img alt="Landscape" src="<?php echo getAssetLink('img/hideout/1/palace4.jpg'); ?>">' style="text-align:left;">Landscape</span>
                                    </td>
                                    <td>Bonus Talent: <?php echo getLandEffect($report['defender']['land']); ?></td>
                                </tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="wrap-bottom-left">
                <div class="wrap-bottom-right">
                    <div class="wrap-bottom-middle"></div>
                </div>
            </div>
        </div>
    </div>
    <div style="clear:both"></div>
    <h1 id="fightround_details_header" style="cursor:pointer;">Detailed fight rounds</h1>
    <div id="fightround_details" style="">
        <div id="reportResult">
            <div class="wrap-top-left clearfix">
                <div class="wrap-top-right clearfix">
                    <div class="wrap-top-middle clearfix"></div>
                </div>
            </div>
            <div class="wrap-left clearfix">
                <div class="wrap-content wrap-right clearfix">
                <span id="fightround_details_contents">
                    <h2 style="text-align:left;padding-left:10px;">
                        <span style="float: right; cursor: pointer;">
                            <img id="show_all_round_details" src="<?php echo getAssetLink('img/symbols/iconplus.png'); ?>" alt="" border="0">
                        </span>
                    </h2>
                    <?php for($i = 1; $i <= count($report['rounds']); $i++): ?>
                    <?php $r = $report['rounds'][$i]; ?>
                    <h2 style="text-align:left;padding-left:10px;">
                            <span style="float: right; cursor: pointer;"><img class="show_round_details" data-index="<?php echo $i; ?>" src="<?php echo getAssetLink('img/symbols/iconplus.png'); ?>" alt="" border="0"></span>
                            Round 1 - <?php echo e($attacker->name); ?>: <?php echo prettyNumber($r['attacker_total_damage']); ?> Damage, <?php echo e($defender->name); ?>: <?php echo prettyNumber($r['defender_total_damage']); ?> Damage
                        </h2>
                        <div id="round_details<?php echo $i; ?>" class="table-wrap clearfix" style="display:none;">
                            <div style="width: 350px; z-index: 10000; float: left;">
                                <table cellpadding="2" cellspacing="2" border="0" width="100%">
                                <tbody>
                                <tr>
                                    <td colspan="2">hit: <?php echo prettyNumber($r['attacker_total_damage']); ?> Damage&nbsp;
                                        <span class="tooltip" title="
                                            <div class='tooltipContent'>
                                                <table>
                                                <?php foreach($r['attacker_tooltip_damage'] as $tt): ?>
                                                <tr><td><?php echo $tt['name']; ?>:</td><td align='right'><?php echo plusSignedNumberString($tt['val']); ?></td></tr>
                                                <?php endforeach; ?>
                                                </table>
                                            </div>
                                        " style="text-align:left;">(<?php echo prettyNumber($r['attacker_damage']); ?> per hit)</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="tooltip" title="
                                            <div class='tooltipContent'>
                                                <table>
                                                <?php foreach($r['attacker_tooltip_hc'] as $tt): ?>
                                                <tr><td><?php echo $tt['name']; ?>:</td><td align='right'><?php echo plusSignedNumberString($tt['val']); ?></td></tr>
                                                <?php endforeach; ?>
                                                </table>
                                            </div>
                                        " style="text-align:left;">Hit Chance: <?php echo plusSignedNumberString($r['attacker_hc']); ?>%</div>
                                    </td>
                                    <td><?php echo $r['attacker_hit_count']; ?> hits (of <?php echo $r['attacker_attack_count']; ?>)</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="tooltip" title="
                                            <div class='tooltipContent'>
                                                <table>
                                                <?php foreach($r['attacker_tooltip_talent'] as $tt): ?>
                                                <tr><td><?php echo $tt['name']; ?>:</td><td align='right'><?php echo plusSignedNumberString($tt['val']); ?></td></tr>
                                                <?php endforeach; ?>
                                                </table>
                                            </div>
                                        " style="text-align:left;">Chance: <?php echo plusSignedNumberString($r['attacker_tc']); ?>%</div>
                                    </td>
                                    <td>
                                        <?php if(isset($r['attacker_talent'])): ?>
                                        <div class="tooltip" title='<?php $this->partial('partials/report_talent_tooltip_content', ['talent_obj' => $r['attacker_talent']]) ?>' style="text-align:left;"><?php echo \Bitefight\Library\Translate::_tn($r['attacker_talent']['id']); ?></div>
                                        <?php else: ?>
                                        <div style="text-align: left;">not activated</div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                </tbody>
                                </table>
                            </div>
                            <div style="width: 350px; z-index: 10000; float: left;">
                                <table cellpadding="2" cellspacing="2" border="0" width="100%">
                                <tbody>
                                <tr>
                                    <td colspan="2">hit: <?php echo prettyNumber($r['defender_total_damage']); ?> Damage&nbsp;
                                        <span class="tooltip" title="
                                            <div class='tooltipContent'>
                                                <table>
                                                <?php foreach($r['defender_tooltip_damage'] as $tt): ?>
                                                <tr><td><?php echo $tt['name']; ?>:</td><td align='right'><?php echo plusSignedNumberString($tt['val']); ?></td></tr>
                                                <?php endforeach; ?>
                                                </table>
                                            </div>
                                        " style="text-align:left;">(<?php echo prettyNumber($r['defender_damage']); ?> per hit)</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="tooltip" title="
                                            <div class='tooltipContent'>
                                                <table>
                                                <?php foreach($r['defender_tooltip_hc'] as $tt): ?>
                                                <tr><td><?php echo $tt['name']; ?>:</td><td align='right'><?php echo plusSignedNumberString($tt['val']); ?></td></tr>
                                                <?php endforeach; ?>
                                                </table>
                                            </div>
                                        " style="text-align:left;">Hit Chance: <?php echo plusSignedNumberString($r['defender_hc']); ?>%</div>
                                    </td>
                                    <td><?php echo $r['defender_hit_count']; ?> hits (of <?php echo $r['defender_attack_count']; ?>)</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="tooltip" title="
                                            <div class='tooltipContent'>
                                                <table>
                                                <?php foreach($r['defender_tooltip_talent'] as $tt): ?>
                                                <tr><td><?php echo $tt['name']; ?>:</td><td align='right'><?php echo plusSignedNumberString($tt['val']); ?></td></tr>
                                                <?php endforeach; ?>
                                                </table>
                                            </div>
                                        " style="text-align:left;">Chance: <?php echo plusSignedNumberString($r['defender_tc']); ?>%</div>
                                    </td>
                                    <td>
                                        <?php if(isset($r['defender_talent'])): ?>
                                        <div class="tooltip" title='<?php $this->partial('partials/report_talent_tooltip_content', ['talent_obj' => $r['defender_talent']]) ?>' style="text-align:left;"><?php echo \Bitefight\Library\Translate::_tn($r['defender_talent']['id']); ?></div>
                                        <?php else: ?>
                                        <div style="text-align: left;">not activated</div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endfor; ?>
                </span>
                </div>
            </div>
            <div class="wrap-bottom-left">
                <div class="wrap-bottom-right">
                    <div class="wrap-bottom-middle"></div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(function ()
        {
            $("#show_all_round_details").click(function() {
                if($("*[id^=round_details]:visible").length > 0) {
                    $("*[id^=round_details]:visible").hide();
                } else {
                    $("*[id^=round_details]").show();
                }
            });

            $(".show_round_details").click(function() {
                $("#round_details"+$(this).data('index')).toggle();
            });
        });
    </script>

@endsection
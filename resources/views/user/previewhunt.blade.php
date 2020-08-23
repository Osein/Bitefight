@extends('index')

@section('content')
    <h1>lust for hunting <?php echo $user->name; ?></h1>
    <p style="text-align:justify">Your search has been successful. Now you can attack your discovered victim!</p>
    <div id="robberyProfilPic">
        <!-- box HEADER START -->
        <div class="wrap-top-left">
            <div class="wrap-top-right">
                <div class="wrap-top-middle"></div>
            </div>
        </div>
        <!-- box HEADER END -->
        <!-- box CONTENT START -->
        <div class="wrap-left">
            <div class="wrap-content wrap-right">
                <?php if($puser->show_picture): ?>
                <img src="<?php echo getAssetLink('img/logo/'.$puser->race.'/'.$puser->gender.'/'.$puser->image_type.'.jpg') ?>" width="170">
                <?php else: ?>
                <img src="<?php echo getAssetLink('img/symbols/race'.$puser->race.'.gif') ?>">
                <?php endif; ?>
                <?php if($puser->clan_id > 0): ?>
                <a href="<?php echo getUrl('clan/view/'.$puser->clan_id); ?>"><img src="<?php echo getAssetLink('img/clan/'.$puser->logo_bg.'-'.$puser->logo_sym.'.png'); ?>" border="0"></a>
                <?php endif; ?>
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
    <div id="robberyProfil">
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
                <!-- CONTENT START -->
                <h2><img src="<?php echo getAssetLink('img/symbols/race'.$puser->race.'small.gif'); ?>" alt=""><?php echo getRaceString($puser->race); ?> <span><?php echo e($puser->name); ?></span></h2>
                <div class="table-wrap">
                    <table cellpadding="2" cellspacing="2" border="0" width="100%">
                        <tbody>
                        <tr>
                            <td class="tdn">Entire Booty:</td>
                            <td class="tdn"><?php echo \Bitefight\Library\Translate::_('entire_booty_count', ['count' => prettyNumber($puser->s_booty)]); ?></td>
                        </tr>
                        <?php if($puser->clan_id > 0): ?>
                        <tr>
                            <td><?php echo \Bitefight\Library\Translate::_('clan'); ?>:</td>
                            <td><a href="<?php echo getUrl('clan/view/'.$puser->clan_id); ?>"><?php echo e($puser->clan_name); ?> [<?php echo e($puser->clan_tag); ?>]</a></td>
                        </tr>
                        <tr>
                            <td><?php echo \Bitefight\Library\Translate::_('rank'); ?>:</td>
                            <td><?php echo e($puser->rank_name); ?><?php if($puser->war_minister) { echo ' ('.\Bitefight\Library\Translate::_('war_minister').')'; } ?></td>
                        </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <h2><img alt="" src="<?php echo getAssetLink('img/symbols/race'.$puser->race.'small.gif'); ?>">Character description</h2>
                <p style="overflow:hidden; width:100%; text-align:center;">
                    <?php echo empty($puser->descriptionHtml) ? '-- none available --' : $puser->descriptionHtml; ?>
                </p>
                <h2><img src="<?php echo getAssetLink('img/symbols/race'.$puser->race.'small.gif'); ?>" alt=""><?php echo \Bitefight\Library\Translate::_('characteristics_of_user', ['user' => e($puser->name)]); ?></h2>
                <div class="table-wrap">
                    <table width="100%">
                        <tbody><tr>
                            <td class="tdn">Level:</td>
                            <td class="tdn"><?php echo prettyNumber(getLevel($puser->exp)); ?></td>
                        </tr>
                        <tr>
                            <td class="tdn">Battle value:</td>
                            <td class="tdn"><?php echo prettyNumber($puser->battle_value); ?></td>
                        </tr>
                        <tr>
                            <td class="tdn">Strength:</td>
                            <td class="tdn" colspan="2">
                                <img src="<?php echo getAssetLink('img/b1.gif'); ?>" alt=""><img src="<?php echo getAssetLink('img/b2.gif'); ?>" alt="" height="12" width="<?php echo $str_red_long; ?>"><img src="<?php echo getAssetLink('img/b3.gif'); ?>" alt="">
                                <span class="fontsmall">(<?php echo prettyNumber($puser->str); ?>)</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="tdn">Defence:</td>
                            <td class="tdn" colspan="2">
                                <img src="<?php echo getAssetLink('img/b1.gif'); ?>" alt=""><img src="<?php echo getAssetLink('img/b2.gif'); ?>" alt="" height="12" width="<?php echo $def_red_long; ?>"><img src="<?php echo getAssetLink('img/b3.gif'); ?>" alt="">
                                <span class="fontsmall">(<?php echo prettyNumber($puser->def); ?>)</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="tdn">Dexterity:</td>
                            <td class="tdn" colspan="2">
                                <img src="<?php echo getAssetLink('img/b1.gif'); ?>" alt=""><img src="<?php echo getAssetLink('img/b2.gif'); ?>" alt="" height="12" width="<?php echo $dex_red_long; ?>"><img src="<?php echo getAssetLink('img/b3.gif'); ?>" alt="">
                                <span class="fontsmall">(<?php echo prettyNumber($puser->dex); ?>)</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="tdn">Endurance:</td>
                            <td class="tdn" colspan="2">
                                <img src="<?php echo getAssetLink('img/b1.gif'); ?>" alt=""><img src="<?php echo getAssetLink('img/b2.gif'); ?>" alt="" height="12" width="<?php echo $end_red_long; ?>"><img src="<?php echo getAssetLink('img/b3.gif'); ?>" alt="">
                                <span class="fontsmall">(<?php echo prettyNumber($puser->end); ?>)</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="tdn">Charisma:</td>
                            <td class="tdn" colspan="2">
                                <img src="<?php echo getAssetLink('img/b1.gif'); ?>" alt=""><img src="<?php echo getAssetLink('img/b2.gif'); ?>" alt="" height="12" width="<?php echo $cha_red_long; ?>"><img src="<?php echo getAssetLink('img/b3.gif'); ?>" alt="">
                                <span class="fontsmall">(<?php echo prettyNumber($puser->cha); ?>)</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="tdn">Experience:</td>
                            <td class="tdn">
                                <img src="<?php echo getAssetLink('img/b1.gif'); ?>"><img src="<?php echo getAssetLink('img/b2.gif'); ?>" height="12" width="<?php echo $exp_red_long; ?>"><img src="<?php echo getAssetLink('img/b4.gif'); ?>" height="12" width="<?php echo 200 - $exp_red_long; ?>"><img src="<?php echo getAssetLink('img/b5.gif'); ?>"><span class="fontsmall"> (<?php echo prettyNumber($puser->exp); ?> / <?php echo prettyNumber(getExpNeeded(getLevel($puser->exp))); ?>)</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" align="center" class="no-bg">
                                <table>
                                    <tbody><tr>
                                        <td class="no-bg">
                                            <?php if($user->hp_now > 0 && $user->ap_now > 0): ?>
                                            <form action="<?php echo getUrl('hunt/attack/'.$puser->id); ?>" method="GET">
                                                <div class="btn-left center">
                                                    <div class="btn-right">
                                                        <button type="submit" class="btn">Attack <span class="cost">-1<img src="<?php echo getAssetLink('img/symbols/ap.gif'); ?>"></span></button>
                                                    </div>
                                                </div>
                                            </form>
                                            <?php else: ?>
                                            <button class="btn">Attack <span class="cost">-1<img src="<?php echo getAssetLink('img/symbols/ap.gif'); ?>"></span></button>
                                            <?php endif; ?>
                                        </td>
                                        <?php if(isset($search_again)): ?>
                                        <td class="no-bg">
                                            <form action="<?php echo getUrl('hunt/race/search'); ?>" method="POST">
                                                <input type="hidden" name="<?php echo $this->security->getTokenKey() ?>" value="<?php echo $this->security->getToken() ?>"/>
                                                <input type="hidden" name="enemy_type" value="<?php echo $enemy_type; ?>">
                                                <div class="btn-left center">
                                                    <div class="btn-right">
                                                        <input type="submit" name="optionsearch" class="btn" value="search again">
                                                    </div>
                                                </div>
                                            </form>
                                        </td>
                                        <?php endif; ?>
                                    </tr>
                                    </tbody></table>
                            </td>
                        </tr>
                        </tbody></table>
                </div>
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
@endsection
# Bitefight Clone Script

Bitefight clone version for study purposes. I am going to build a private server for this and planning to use phalcon framework. If you have any better idea open issue, leave comment etc etc I am not familiar with github discussions lol

Also if you are efficient on calculating formulas you can help me with grotte and tavern missions.

Current item and talent table is below.

Slcost means sellcost
"e","s" Enemy, self
"bsc","bns" Basic, bonus

```sql
CREATE TABLE `item` (
  `id` int(10) unsigned NOT NULL,
  `stern` smallint(6) NOT NULL,
  `model` smallint(6) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `level` mediumint(9) NOT NULL,
  `gcost` int(11) NOT NULL,
  `slcost` int(11) NOT NULL,
  `scost` int(11) NOT NULL,
  `str` mediumint(9) NOT NULL,
  `def` mediumint(9) NOT NULL,
  `dex` mediumint(9) NOT NULL,
  `end` mediumint(9) NOT NULL,
  `cha` mediumint(9) NOT NULL,
  `hpbonus` int(11) NOT NULL,
  `regen` mediumint(9) NOT NULL,
  `sbschc` smallint(6) NOT NULL,
  `sbscdmg` smallint(6) NOT NULL,
  `sbsctlnt` smallint(6) NOT NULL,
  `sbnshc` smallint(6) NOT NULL,
  `sbnsdmg` smallint(6) NOT NULL,
  `sbnstlnt` smallint(6) NOT NULL,
  `ebschc` smallint(6) NOT NULL,
  `ebscdmg` smallint(6) NOT NULL,
  `ebsctlnt` smallint(6) NOT NULL,
  `ebnshc` smallint(6) NOT NULL,
  `ebnsdmg` smallint(6) NOT NULL,
  `ebnstlnt` smallint(6) NOT NULL,
  `apup` smallint(6) NOT NULL,
  `cooldown` mediumint(9) NOT NULL,
  `duration` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=781 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
```

```sql
CREATE TABLE `talent` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  `level` mediumint(9) NOT NULL,
  `pair` int(11) NOT NULL DEFAULT '0',
  `duration` int(11) NOT NULL DEFAULT '0',
  `attack` smallint(6) NOT NULL DEFAULT '0',
  `eattack` smallint(6) NOT NULL DEFAULT '0',
  `str` mediumint(9) NOT NULL DEFAULT '0',
  `def` mediumint(9) NOT NULL DEFAULT '0',
  `dex` mediumint(9) NOT NULL DEFAULT '0',
  `end` mediumint(9) NOT NULL DEFAULT '0',
  `cha` mediumint(9) NOT NULL DEFAULT '0',
  `estr` mediumint(9) NOT NULL DEFAULT '0',
  `edef` mediumint(9) NOT NULL DEFAULT '0',
  `edex` mediumint(9) NOT NULL DEFAULT '0',
  `eend` mediumint(9) NOT NULL DEFAULT '0',
  `echa` mediumint(9) NOT NULL DEFAULT '0',
  `hpbonus` int(11) NOT NULL DEFAULT '0',
  `regen` mediumint(9) NOT NULL DEFAULT '0',
  `sbschc` smallint(6) NOT NULL DEFAULT '0',
  `sbscdmg` smallint(6) NOT NULL DEFAULT '0',
  `sbsctlnt` smallint(6) NOT NULL DEFAULT '0',
  `sbnshc` smallint(6) NOT NULL DEFAULT '0',
  `sbnsdmg` smallint(6) NOT NULL DEFAULT '0',
  `sbnstlnt` smallint(6) NOT NULL DEFAULT '0',
  `ebschc` smallint(6) NOT NULL DEFAULT '0',
  `ebscdmg` smallint(6) NOT NULL DEFAULT '0',
  `ebsctlnt` smallint(6) NOT NULL DEFAULT '0',
  `ebnshc` smallint(6) NOT NULL DEFAULT '0',
  `ebnsdmg` smallint(6) NOT NULL DEFAULT '0',
  `ebnstlnt` smallint(6) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
```

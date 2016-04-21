-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Jeu 21 Avril 2016 à 02:22
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `test_tournament`
--

-- --------------------------------------------------------

--
-- Structure de la table `tournament_coach`
--

CREATE TABLE IF NOT EXISTS `tournament_coach` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `team_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `file` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `id_race` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `email` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fan_factor` tinyint(3) unsigned DEFAULT '1',
  `reroll` tinyint(3) unsigned DEFAULT '0',
  `apothecary` tinyint(3) DEFAULT '0',
  `assistant_coach` tinyint(3) DEFAULT '0',
  `cheerleader` tinyint(3) DEFAULT '0',
  `wizard` tinyint(3) DEFAULT '0',
  `points` tinyint(3) unsigned DEFAULT '0',
  `opponents_points` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `net_td` tinyint(3) DEFAULT '0',
  `casualties` tinyint(3) DEFAULT '0',
  `edition` tinyint(4) NOT NULL DEFAULT '1',
  `naf_number` smallint(6) NOT NULL DEFAULT '0',
  `id_coach_team` smallint(6) DEFAULT NULL,
  `ready` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0 AUTO_INCREMENT=13 ;

--
-- Contenu de la table `tournament_coach`
--

INSERT INTO `tournament_coach` (`id`, `team_name`, `name`, `file`, `id_race`, `email`, `fan_factor`, `reroll`, `apothecary`, `assistant_coach`, `cheerleader`, `wizard`, `points`, `opponents_points`, `net_td`, `casualties`, `edition`, `naf_number`, `id_coach_team`, `ready`) VALUES
(1, 'Team A', 'Coach A', '0', 1, 'coacha@test.org', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 101, 1, 0),
(2, 'Team B', 'Coach B', '0', 2, 'coachb@test.org', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 102, 1, 0),
(3, 'Team C', 'Coach C', '0', 3, 'coachc@test.org', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 103, 1, 0),
(4, 'Team D', 'Coach D', '0', 4, 'coachd@test.org', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 104, 2, 0),
(5, 'Team E', 'Coach E', '0', 5, 'coache@test.org', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 105, 2, 0),
(6, 'Team F', 'Coach F', '0', 6, 'coachf@test.org', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 106, 2, 0),
(7, 'Team G', 'Coach G', '0', 7, 'coachg@test.org', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 107, 3, 0),
(8, 'Team H', 'Coach H', '0', 8, 'coachh@test.org', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 108, 3, 0),
(9, 'Team I', 'Coach I', '0', 9, 'coachi@test.org', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 109, 3, 0),
(10, 'Team J', 'Coach J', '0', 10, 'coachj@test.org', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 110, 4, 0),
(11, 'Team K', 'Coach K', '0', 11, 'coachk@test.org', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 111, 4, 0),
(12, 'Team L', 'Coach L', '0', 12, 'coachl@test.org', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 112, 4, 0);

-- --------------------------------------------------------

--
-- Structure de la table `tournament_coach_team`
--

CREATE TABLE IF NOT EXISTS `tournament_coach_team` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `tournament_coach_team`
--

INSERT INTO `tournament_coach_team` (`id`, `name`) VALUES
(1, 'Triplette A'),
(2, 'Triplette B'),
(3, 'Triplette C'),
(4, 'Triplette D');

-- --------------------------------------------------------

--
-- Structure de la table `tournament_edition`
--

CREATE TABLE IF NOT EXISTS `tournament_edition` (
  `id` int(11) NOT NULL,
  `day_1` date NOT NULL,
  `day_2` date NOT NULL,
  `round_number` int(11) NOT NULL,
  `current_round` int(11) NOT NULL,
  `use_finale` tinyint(1) NOT NULL DEFAULT '1',
  `full_triplette` tinyint(4) NOT NULL DEFAULT '1',
  `ranking_strategy` varchar(65) COLLATE utf8_unicode_ci NOT NULL,
  `first_day_round` int(11) NOT NULL DEFAULT '3',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `tournament_edition`
--

INSERT INTO `tournament_edition` (`id`, `day_1`, `day_2`, `round_number`, `current_round`, `use_finale`, `full_triplette`, `ranking_strategy`, `first_day_round`) VALUES
(1, '2015-06-14', '2015-06-15', 5, 0, 1, 1, 'Rdvbb13', 3);

-- --------------------------------------------------------

--
-- Structure de la table `tournament_match`
--

CREATE TABLE IF NOT EXISTS `tournament_match` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `id_coach_1` smallint(6) unsigned DEFAULT '0',
  `id_coach_2` smallint(6) unsigned DEFAULT '0',
  `td_1` tinyint(3) unsigned DEFAULT '0',
  `td_2` tinyint(3) unsigned DEFAULT '0',
  `round` tinyint(3) DEFAULT '0',
  `points_1` tinyint(3) DEFAULT '0',
  `points_2` tinyint(3) DEFAULT '0',
  `casualties_1` tinyint(3) unsigned DEFAULT '0',
  `casualties_2` tinyint(3) unsigned DEFAULT '0',
  `completions_1` smallint(6) DEFAULT NULL,
  `completions_2` smallint(6) DEFAULT NULL,
  `fouls_1` smallint(6) DEFAULT NULL,
  `fouls_2` smallint(6) DEFAULT NULL,
  `special_1` varchar(33) COLLATE utf8_bin DEFAULT NULL,
  `special_2` varchar(33) COLLATE utf8_bin DEFAULT NULL,
  `finale` enum('true','false') CHARACTER SET latin1 NOT NULL DEFAULT 'false',
  `edition` tinyint(4) NOT NULL DEFAULT '2',
  `status` enum('programme','resume','detail') CHARACTER SET latin1 NOT NULL DEFAULT 'programme',
  `table_number` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin PACK_KEYS=0 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `tournament_precoach`
--

CREATE TABLE IF NOT EXISTS `tournament_precoach` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(65) COLLATE utf8_unicode_ci NOT NULL,
  `id_race` int(11) NOT NULL,
  `email` varchar(129) COLLATE utf8_unicode_ci NOT NULL,
  `edition` int(11) NOT NULL,
  `naf_number` int(11) NOT NULL,
  `lang` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `team_name` varchar(129) COLLATE utf8_unicode_ci NOT NULL,
  `id_coach_team` int(11) NOT NULL,
  `contact` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `tournament_race`
--

CREATE TABLE IF NOT EXISTS `tournament_race` (
  `edition` tinyint(4) NOT NULL DEFAULT '1',
  `id_race` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `nom_fr` varchar(33) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nom_en` varchar(33) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nom_en_2` varchar(33) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nom_fr_2` varchar(33) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reroll` tinyint(3) unsigned DEFAULT '5',
  PRIMARY KEY (`id_race`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=25 ;

--
-- Contenu de la table `tournament_race`
--

INSERT INTO `tournament_race` (`edition`, `id_race`, `nom_fr`, `nom_en`, `nom_en_2`, `nom_fr_2`, `reroll`) VALUES
(1, 1, 'Orc', 'Orc', 'Orc', 'd''orcs', 6),
(1, 2, 'Skaven', 'Skaven', 'Skaven', 'de skavens', 6),
(1, 3, 'Elfe noir', 'Dark elf', 'Dark Elves', 'd''elfes noirs', 5),
(1, 4, 'Humain', 'Human', 'Humans', 'd''hommes', 5),
(1, 5, 'Nain', 'Dwarf', 'Dwarves', 'de nains', 4),
(1, 6, 'Haut elfe', 'High elf', 'High Elves', 'd''hauts elfes', 5),
(1, 7, 'Gobelin', 'Goblin', 'Goblins', 'de gobelins', 6),
(1, 8, 'Halfling', 'Halfling', 'Halflings', 'de halflings', 6),
(1, 9, 'Elfe sylvain', 'Wood elf', 'Wood elves', 'd''elfes sylvains', 5),
(1, 10, 'Chaos', 'Chaos', 'Chaos', 'du Chaos', 7),
(1, 11, 'Nain du Chaos', 'Chaos dwarf', 'Chaos dwarves', 'de nains du Chaos', 7),
(1, 12, 'Mort-vivant', 'Undead', 'Undead', 'de mort-vivants', 7),
(1, 13, 'Nordique', 'Norse', 'Norse', 'de nordiques', 6),
(1, 14, 'Amazone', 'Amazon', 'Amazons', 'd''amazones', 4),
(1, 15, 'Homme-lézard', 'Lizardmen', 'Lizardmen', 'd''hommes lézards', 6),
(2, 16, 'Khemri', 'Khemri', 'Khemri', 'de Khemri', 7),
(2, 17, 'Nécromantique', 'Necromantic', 'Necromantic', 'nécromantique', 7),
(2, 18, 'Elfe pro', 'Elf', 'Elves', 'd''elfes pro', 5),
(2, 19, 'Nurgle', 'Nurgle', 'Nurgle''s Rotters', 'des pourris de Nurgle', 7),
(3, 20, 'Ogre', 'Ogre', 'Ogres', 'd''ogres', 7),
(3, 21, 'Vampire', 'Vampire', 'Vampires', 'de vampires', 7),
(7, 22, 'Bas fonds', 'Underworld', 'Underworld', 'des bas fonds', 7),
(7, 23, 'Pacte chaotique', 'Chaos Pact', 'Chaos Pact', 'chaotique', 7),
(7, 24, 'Slann', 'Slann', 'Slann', 'de slanns', 5);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

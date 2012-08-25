-- phpMyAdmin SQL Dump
-- version 3.5.2.1
-- http://www.phpmyadmin.net
--
-- Machine: localhost
-- Genereertijd: 25 aug 2012 om 22:57
-- Serverversie: 5.0.51a-community
-- PHP-versie: 5.2.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Databank: `nopaintjus_portfo`
--

-- --------------------------------------------------------

--
-- Tablestructure fo table `rsa`
--

CREATE TABLE IF NOT EXISTS `rsa` (
  `id` int(11) NOT NULL auto_increment,
  `uniqueUrl` varchar(255) NOT NULL,
  `senderName` varchar(255) NOT NULL,
  `senderEmail` varchar(255) NOT NULL,
  `recipientName` varchar(255) NOT NULL,
  `recipientEmail` varchar(255) NOT NULL,
  `message` varchar(255) NOT NULL,
  `modulo` int(11) NOT NULL,
  `publicKey` int(11) NOT NULL,
  `privateKey` int(11) NOT NULL,
  `createDate` datetime NOT NULL,
  `burnType` tinyint(4) NOT NULL,
  `burnDate` datetime NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;
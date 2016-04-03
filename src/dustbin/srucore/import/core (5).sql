-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Lun 19 Mai 2014 à 16:45
-- Version du serveur: 5.5.35
-- Version de PHP: 5.4.4-14+deb7u8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `core`
--

-- --------------------------------------------------------

--
-- Structure de la table `Brand`
--

CREATE TABLE IF NOT EXISTS `Brand` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `picture` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_DD93D65C16DB4F89` (`picture`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `Category`
--

CREATE TABLE IF NOT EXISTS `Category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `child_category` int(11) DEFAULT NULL,
  `picture` int(11) DEFAULT NULL,
  `parent_category` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_FF3A7B978C4B6465` (`child_category`),
  KEY `IDX_FF3A7B9716DB4F89` (`picture`),
  KEY `IDX_FF3A7B979981B510` (`parent_category`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Contenu de la table `Category`
--

INSERT INTO `Category` (`id`, `child_category`, `picture`, `parent_category`, `name`) VALUES
(1, NULL, NULL, NULL, 'Homme'),
(2, NULL, NULL, NULL, 'Femme');

-- --------------------------------------------------------

--
-- Structure de la table `Choice`
--

CREATE TABLE IF NOT EXISTS `Choice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C6075FA4D34A04AD` (`product`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `Feature`
--

CREATE TABLE IF NOT EXISTS `Feature` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `fos_group`
--

CREATE TABLE IF NOT EXISTS `fos_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_4B019DDB5E237E06` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `order_details`
--

CREATE TABLE IF NOT EXISTS `order_details` (
  `choice` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY (`choice`,`order`),
  KEY `IDX_845CA2C1C1AB5A92` (`choice`),
  KEY `IDX_845CA2C1F5299398` (`order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Order_user`
--

CREATE TABLE IF NOT EXISTS `Order_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) DEFAULT NULL,
  `user_info_invoicing` int(11) DEFAULT NULL,
  `order_info` int(11) DEFAULT NULL,
  `user_info_delivery` int(11) DEFAULT NULL,
  `creation_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_8F3FEF8E8D93D649` (`user`),
  KEY `IDX_8F3FEF8EB158A48E` (`user_info_invoicing`),
  KEY `IDX_8F3FEF8E86780B40` (`order_info`),
  KEY `IDX_8F3FEF8ECE3A3A2A` (`user_info_delivery`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `Order_user_info`
--

CREATE TABLE IF NOT EXISTS `Order_user_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `state` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `Picture`
--

CREATE TABLE IF NOT EXISTS `Picture` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `extension` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=15 ;

--
-- Contenu de la table `Picture`
--

INSERT INTO `Picture` (`id`, `url`, `path`, `extension`, `name`) VALUES
(10, 'http://interfacelift.com/wallpaper/previews/03552_colorsofthesunset@2x.jpg', '/var/www/symfony/app/../web/bundles/srucore/images/', 'jpg', 'vacances'),
(13, 'http://4.bp.blogspot.com/-0_5BSeTKYZA/URxULn29glI/AAAAAAAAfoI/HwXhWmwH_2o/s1600/HD+Wallpaper+Download+(8).jpg', '/var/www/symfony/app/../web/bundles/srucore/images/', 'jpg', 'Planête');

-- --------------------------------------------------------

--
-- Structure de la table `Product`
--

CREATE TABLE IF NOT EXISTS `Product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `brand` int(11) DEFAULT NULL,
  `tva` int(11) DEFAULT NULL,
  `provider` int(11) DEFAULT NULL,
  `ref` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ean` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `short_description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `weight` double DEFAULT NULL,
  `price_ht` double NOT NULL,
  `price_ttc` double NOT NULL,
  `price_provider` double NOT NULL,
  `eco_participation` double DEFAULT NULL,
  `keywords` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `stock` int(11) NOT NULL,
  `creation_date` date NOT NULL,
  `delete_date` date DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL,
  `margin` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_1CF73D311C52F958` (`brand`),
  KEY `IDX_1CF73D31EF699620` (`tva`),
  KEY `IDX_1CF73D3192C4739C` (`provider`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Contenu de la table `Product`
--

INSERT INTO `Product` (`id`, `brand`, `tva`, `provider`, `ref`, `ean`, `name`, `short_description`, `description`, `weight`, `price_ht`, `price_ttc`, `price_provider`, `eco_participation`, `keywords`, `stock`, `creation_date`, `delete_date`, `enabled`, `margin`) VALUES
(1, NULL, NULL, NULL, '544546545', 2147483647, 'Pull', 'Pull pas mal', 'Pull pas mal', 5000, 0, 1, 0, 0, 'pull homme ete', 20, '2014-05-19', NULL, 1, 0),
(2, NULL, NULL, NULL, '5454545', 2147483647, 'TShirt', 'TShirt', 'TShirt', 5000, 0, 1, 0, 0, 'TShirt', 20, '2014-05-19', NULL, 1, 0);

-- --------------------------------------------------------

--
-- Structure de la table `product_category`
--

CREATE TABLE IF NOT EXISTS `product_category` (
  `product` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  PRIMARY KEY (`product`,`category`),
  KEY `IDX_CDFC7356D34A04AD` (`product`),
  KEY `IDX_CDFC735664C19C1` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `product_category`
--

INSERT INTO `product_category` (`product`, `category`) VALUES
(1, 1),
(1, 2);

-- --------------------------------------------------------

--
-- Structure de la table `product_feature`
--

CREATE TABLE IF NOT EXISTS `product_feature` (
  `product` int(11) NOT NULL,
  `feature` int(11) NOT NULL,
  PRIMARY KEY (`product`,`feature`),
  KEY `IDX_CE0E6ED6D34A04AD` (`product`),
  KEY `IDX_CE0E6ED61FD77566` (`feature`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `product_picture`
--

CREATE TABLE IF NOT EXISTS `product_picture` (
  `product` int(11) NOT NULL,
  `picture` int(11) NOT NULL,
  PRIMARY KEY (`product`,`picture`),
  KEY `IDX_C7025439D34A04AD` (`product`),
  KEY `IDX_C702543916DB4F89` (`picture`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `product_picture`
--

INSERT INTO `product_picture` (`product`, `picture`) VALUES
(1, 13);

-- --------------------------------------------------------

--
-- Structure de la table `Promotion`
--

CREATE TABLE IF NOT EXISTS `Promotion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order` int(11) DEFAULT NULL,
  `category` int(11) DEFAULT NULL,
  `product` int(11) DEFAULT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `begin_at_date` date DEFAULT NULL,
  `expire_at_date` date NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `min` double DEFAULT NULL,
  `reduction` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_43ECFF72F5299398` (`order`),
  KEY `IDX_43ECFF7264C19C1` (`category`),
  KEY `IDX_43ECFF72D34A04AD` (`product`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `Provider`
--

CREATE TABLE IF NOT EXISTS `Provider` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `picture` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `zip_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `street` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `siret` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `memo` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `IDX_6BB211CA16DB4F89` (`picture`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `Provider_info`
--

CREATE TABLE IF NOT EXISTS `Provider_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `provider` int(11) DEFAULT NULL,
  `label` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `surname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` int(11) DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9D7F19DE92C4739C` (`provider`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `provider_transport`
--

CREATE TABLE IF NOT EXISTS `provider_transport` (
  `provider` int(11) NOT NULL,
  `transport` int(11) NOT NULL,
  PRIMARY KEY (`provider`,`transport`),
  KEY `IDX_BF35399C92C4739C` (`provider`),
  KEY `IDX_BF35399C66AB212E` (`transport`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Shipment_zone`
--

CREATE TABLE IF NOT EXISTS `Shipment_zone` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `Transport`
--

CREATE TABLE IF NOT EXISTS `Transport` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `min_delay` int(11) DEFAULT NULL,
  `max_delay` int(11) DEFAULT NULL,
  `url_tracking` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `Tva`
--

CREATE TABLE IF NOT EXISTS `Tva` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `taux` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `locked` tinyint(1) NOT NULL,
  `expired` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  `confirmation_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `credentials_expired` tinyint(1) NOT NULL,
  `credentials_expire_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D64992FC23A8` (`username_canonical`),
  UNIQUE KEY `UNIQ_8D93D649A0D96FBF` (`email_canonical`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`id`, `username`, `username_canonical`, `email`, `email_canonical`, `enabled`, `salt`, `password`, `last_login`, `locked`, `expired`, `expires_at`, `confirmation_token`, `password_requested_at`, `roles`, `credentials_expired`, `credentials_expire_at`) VALUES
(1, 'admin', 'admin', 'admin@admin.com', 'admin@admin.com', 1, 'hwkhpp534d4c4k80g044c00o0sc4ogs', 'jEIkwjlDdgo1k6PiHRd/5mA1/Wt5RmwE9A8sxkvRdjJh3LERWvQp+mDiYZpEp+ykfwrEAf2zti5Sw1Wlb9q8rA==', '2014-05-19 13:39:47', 0, 0, NULL, NULL, NULL, 'a:1:{i:0;s:16:"ROLE_SUPER_ADMIN";}', 0, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `UserInfo`
--

CREATE TABLE IF NOT EXISTS `UserInfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) DEFAULT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `street` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `zip_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `default_info` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_34B0844E8D93D649` (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `user_promotion`
--

CREATE TABLE IF NOT EXISTS `user_promotion` (
  `user` int(11) NOT NULL,
  `promotion` int(11) NOT NULL,
  PRIMARY KEY (`user`,`promotion`),
  KEY `IDX_C1FDF0358D93D649` (`user`),
  KEY `IDX_C1FDF035C11D7DD1` (`promotion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `Brand`
--
ALTER TABLE `Brand`
  ADD CONSTRAINT `FK_DD93D65C16DB4F89` FOREIGN KEY (`picture`) REFERENCES `Picture` (`id`);

--
-- Contraintes pour la table `Category`
--
ALTER TABLE `Category`
  ADD CONSTRAINT `FK_FF3A7B979981B510` FOREIGN KEY (`parent_category`) REFERENCES `Category` (`id`),
  ADD CONSTRAINT `FK_FF3A7B9716DB4F89` FOREIGN KEY (`picture`) REFERENCES `Picture` (`id`),
  ADD CONSTRAINT `FK_FF3A7B978C4B6465` FOREIGN KEY (`child_category`) REFERENCES `Category` (`id`);

--
-- Contraintes pour la table `Choice`
--
ALTER TABLE `Choice`
  ADD CONSTRAINT `FK_C6075FA4D34A04AD` FOREIGN KEY (`product`) REFERENCES `Product` (`id`);

--
-- Contraintes pour la table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `FK_845CA2C1F5299398` FOREIGN KEY (`order`) REFERENCES `Order_user` (`id`),
  ADD CONSTRAINT `FK_845CA2C1C1AB5A92` FOREIGN KEY (`choice`) REFERENCES `Choice` (`id`);

--
-- Contraintes pour la table `Order_user`
--
ALTER TABLE `Order_user`
  ADD CONSTRAINT `FK_8F3FEF8ECE3A3A2A` FOREIGN KEY (`user_info_delivery`) REFERENCES `UserInfo` (`id`),
  ADD CONSTRAINT `FK_8F3FEF8E86780B40` FOREIGN KEY (`order_info`) REFERENCES `Order_user_info` (`id`),
  ADD CONSTRAINT `FK_8F3FEF8E8D93D649` FOREIGN KEY (`user`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_8F3FEF8EB158A48E` FOREIGN KEY (`user_info_invoicing`) REFERENCES `UserInfo` (`id`);

--
-- Contraintes pour la table `Product`
--
ALTER TABLE `Product`
  ADD CONSTRAINT `FK_1CF73D3192C4739C` FOREIGN KEY (`provider`) REFERENCES `Provider` (`id`),
  ADD CONSTRAINT `FK_1CF73D311C52F958` FOREIGN KEY (`brand`) REFERENCES `Brand` (`id`),
  ADD CONSTRAINT `FK_1CF73D31EF699620` FOREIGN KEY (`tva`) REFERENCES `Tva` (`id`);

--
-- Contraintes pour la table `product_category`
--
ALTER TABLE `product_category`
  ADD CONSTRAINT `FK_CDFC735664C19C1` FOREIGN KEY (`category`) REFERENCES `Category` (`id`),
  ADD CONSTRAINT `FK_CDFC7356D34A04AD` FOREIGN KEY (`product`) REFERENCES `Product` (`id`);

--
-- Contraintes pour la table `product_feature`
--
ALTER TABLE `product_feature`
  ADD CONSTRAINT `FK_CE0E6ED61FD77566` FOREIGN KEY (`feature`) REFERENCES `Feature` (`id`),
  ADD CONSTRAINT `FK_CE0E6ED6D34A04AD` FOREIGN KEY (`product`) REFERENCES `Product` (`id`);

--
-- Contraintes pour la table `product_picture`
--
ALTER TABLE `product_picture`
  ADD CONSTRAINT `FK_C702543916DB4F89` FOREIGN KEY (`picture`) REFERENCES `Picture` (`id`),
  ADD CONSTRAINT `FK_C7025439D34A04AD` FOREIGN KEY (`product`) REFERENCES `Product` (`id`);

--
-- Contraintes pour la table `Promotion`
--
ALTER TABLE `Promotion`
  ADD CONSTRAINT `FK_43ECFF72D34A04AD` FOREIGN KEY (`product`) REFERENCES `Product` (`id`),
  ADD CONSTRAINT `FK_43ECFF7264C19C1` FOREIGN KEY (`category`) REFERENCES `Category` (`id`),
  ADD CONSTRAINT `FK_43ECFF72F5299398` FOREIGN KEY (`order`) REFERENCES `Order_user` (`id`);

--
-- Contraintes pour la table `Provider`
--
ALTER TABLE `Provider`
  ADD CONSTRAINT `FK_6BB211CA16DB4F89` FOREIGN KEY (`picture`) REFERENCES `Picture` (`id`);

--
-- Contraintes pour la table `Provider_info`
--
ALTER TABLE `Provider_info`
  ADD CONSTRAINT `FK_9D7F19DE92C4739C` FOREIGN KEY (`provider`) REFERENCES `Provider` (`id`);

--
-- Contraintes pour la table `provider_transport`
--
ALTER TABLE `provider_transport`
  ADD CONSTRAINT `FK_BF35399C66AB212E` FOREIGN KEY (`transport`) REFERENCES `Transport` (`id`),
  ADD CONSTRAINT `FK_BF35399C92C4739C` FOREIGN KEY (`provider`) REFERENCES `Provider` (`id`);

--
-- Contraintes pour la table `UserInfo`
--
ALTER TABLE `UserInfo`
  ADD CONSTRAINT `FK_34B0844E8D93D649` FOREIGN KEY (`user`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `user_promotion`
--
ALTER TABLE `user_promotion`
  ADD CONSTRAINT `FK_C1FDF035C11D7DD1` FOREIGN KEY (`promotion`) REFERENCES `Promotion` (`id`),
  ADD CONSTRAINT `FK_C1FDF0358D93D649` FOREIGN KEY (`user`) REFERENCES `user` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

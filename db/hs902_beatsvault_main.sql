-- phpMyAdmin SQL Dump
-- version 4.9.11
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 19, 2023 at 01:16 PM
-- Server version: 5.7.42
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hs902_beatsvault_main`
--

-- --------------------------------------------------------

--
-- Table structure for table `daws_list`
--

CREATE TABLE `daws_list` (
  `id` int(11) NOT NULL,
  `daw_name` varchar(100) NOT NULL,
  `logo_url` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `daws_list`
--

INSERT INTO `daws_list` (`id`, `daw_name`, `logo_url`) VALUES
(1, 'Ableton Live', '/css/misc/img/daw-logos/ableton-live-logo.png'),
(2, 'Bitwig Studio', '/css/misc/img/daw-logos/bitwig-studio-logo.png'),
(3, 'Cakewalk', '/css/misc/img/daw-logos/cakewalk-logo.png'),
(4, 'FL Studio', '/css/misc/img/daw-logos/fl-studio-logo.png'),
(5, 'GarageBand', '/css/misc/img/daw-logos/garageband-logo.png'),
(6, 'Logic Pro', '/css/misc/img/daw-logos/logic-pro-logo.png'),
(7, 'Pro Tools', '/css/misc/img/daw-logos/pro-tools-logo.png'),
(8, 'Reaper', '/css/misc/img/daw-logos/reaper-logo.png'),
(9, 'Reason', '/css/misc/img/daw-logos/reason-logo.png'),
(10, 'Steinberg Cubase', '/css/misc/img/daw-logos/cubase-logo.png'),
(11, 'Studio One', '/css/misc/img/daw-logos/studio-one-logo.png');

-- --------------------------------------------------------

--
-- Table structure for table `genres_list`
--

CREATE TABLE `genres_list` (
  `id` int(11) NOT NULL,
  `genre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `genres_list`
--

INSERT INTO `genres_list` (`id`, `genre`) VALUES
(1, 'Rock'),
(2, 'Pop'),
(3, 'Hip Hop'),
(4, 'R&B'),
(5, 'Electronic'),
(6, 'Electronic dance'),
(7, 'Alternative rock'),
(8, 'Indie rock'),
(9, 'Pop rock'),
(10, 'Punk rock'),
(11, 'Country'),
(12, 'Progressive rock'),
(13, 'Jazz'),
(14, 'Lowercase'),
(15, 'Pop-punk'),
(16, 'Acoustic'),
(17, 'New wave'),
(18, 'Latin'),
(19, 'Drill'),
(20, 'Emo rap'),
(21, 'Hyperpop'),
(22, 'Shoegaze'),
(23, 'Indie pop'),
(24, 'Skweee'),
(25, 'Contemporary R&B'),
(26, 'Gospel music'),
(27, 'Regional Mexican'),
(28, 'Techno'),
(29, 'Independent music'),
(30, 'Neurofunk'),
(31, 'Straight edge'),
(32, 'Freakbeat'),
(33, 'Aggrotech'),
(34, 'brostep'),
(35, 'Freak folk'),
(36, 'Beatdown hardcore'),
(37, 'Psychobilly'),
(38, 'Unblack metal'),
(39, 'Crust punk'),
(40, 'Martial industrial'),
(41, 'Contemporary Christian music'),
(42, 'Pop rap'),
(43, 'Screamo'),
(44, 'Fingerstyle guitar'),
(45, 'Trap'),
(46, 'Electro house'),
(47, 'Zydeco'),
(48, 'Fado'),
(49, 'Zouk'),
(50, 'Soukous'),
(51, 'Sega');

-- --------------------------------------------------------

--
-- Table structure for table `instruments_list`
--

CREATE TABLE `instruments_list` (
  `id` int(11) NOT NULL,
  `instrument` varchar(500) NOT NULL,
  `logo_url` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `instruments_list`
--

INSERT INTO `instruments_list` (`id`, `instrument`, `logo_url`) VALUES
(1, 'Acoustic Guitar', '/css/misc/img/instrument-logos/acoustic-g-logo.jpeg'),
(2, 'Electric Guitar', '/css/misc/img/instrument-logos/electric-g-logo.jpeg'),
(3, 'Bass Guitar', '/css/misc/img/instrument-logos/bass-g-logo.jpeg'),
(4, 'Piano', '/css/misc/img/instrument-logos/piano-logo.jpeg'),
(5, 'Synthesizer', '/css/misc/img/instrument-logos/synth-logo.jpg'),
(6, 'Violin', '/css/misc/img/instrument-logos/violin-logo.jpeg'),
(7, 'Cello', '/css/misc/img/instrument-logos/cello-logo.jpeg'),
(8, 'Flute', '/css/misc/img/instrument-logos/flute-logo.jpeg'),
(9, 'Drum Kit', '/css/misc/img/instrument-logos/drum-kit-logo.jpeg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `daws_list`
--
ALTER TABLE `daws_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `genres_list`
--
ALTER TABLE `genres_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `instruments_list`
--
ALTER TABLE `instruments_list`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `daws_list`
--
ALTER TABLE `daws_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `genres_list`
--
ALTER TABLE `genres_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `instruments_list`
--
ALTER TABLE `instruments_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

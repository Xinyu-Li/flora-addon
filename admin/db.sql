-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 24, 2022 at 12:00 PM
-- Server version: 10.3.34-MariaDB-0ubuntu0.20.04.1
-- PHP Version: 7.4.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: flora
--
CREATE DATABASE IF NOT EXISTS flora DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE flora;

-- --------------------------------------------------------

--
-- Table structure for table scaffolds
--

CREATE TABLE scaffolds (
  id varchar(3) NOT NULL,
  title varchar(64) NOT NULL,
  content1 varchar(512) NOT NULL,
  content2 varchar(512) NOT NULL,
  language char(4) NOT NULL,
  option1_text varchar(128) NOT NULL,
  option2_text varchar(128) NOT NULL,
  option3_text varchar(128) NOT NULL,
  option4_text varchar(128) NOT NULL,
  option1_short varchar(128) NOT NULL,
  option2_short varchar(128) NOT NULL,
  option3_short varchar(128) NOT NULL,
  option4_short varchar(128) NOT NULL,
  option1_enabled int(11) NOT NULL,
  option2_enabled int(11) NOT NULL,
  option3_enabled int(11) NOT NULL,
  option4_enabled int(11) NOT NULL,
  option1_link varchar(128) NOT NULL,
  option2_link varchar(128) NOT NULL,
  option3_link varchar(128) NOT NULL,
  option4_link varchar(128) NOT NULL,
  type varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table scaffolds
--

INSERT INTO scaffolds (id, title, content1, content2, language, option1_text, option2_text, option3_text, option4_text, option1_short, option2_short, option3_short, option4_short, option1_enabled, option2_enabled, option3_enabled, option4_enabled, option1_link, option2_link, option3_link, option4_link, type) VALUES
('1', 'Understand the task', 'Es ist wichtig zu verstehen, worum es bei dieser Aufgabe geht.', 'Basierend auf deinem bisherigen Lernverhalten, empfehlen wir dir folgende Schritte: ', 'de', 'Überprüfe die Lernziele und Anweisungen.', 'Überprüfe die Aufsatz-Rubrik', 'Verwende das Menü, um dir einen Überblick zu verschaffen, und überfliege die Texte', 'Verarbeite Informationen durch Notizen', 'Allgemeine Anweisungen', 'Rubrik', 'Navigationsmenü', 'Notizen', 1, 1, 1, 1, 'u:https://floralearn.org/lms/mod/lesson/view.php?id=1&pageid=1', 'u:https://floralearn.org/lms/mod/lesson/view.php?id=1&pageid=2', '', '', 'scaffold'),
('1', 'Understand the task', 'It is important to understand what the task is about.', 'Which are the most helpful steps for you to understand the task? \r\n\r\n(Please select from the recommended options below)', 'en', 'Check the learning goals and instructions', 'Check the essay rubric', 'Use menu to get an overview and skim text', 'Process information by taking notes', 'Instructions page', 'Rubric page', 'Navigation panel', 'Annotation tool', 1, 1, 1, 1, 'u:https://floralearn.org/lms/mod/lesson/view.php?id=1&pageid=1', 'u:https://floralearn.org/lms/mod/lesson/view.php?id=1&pageid=2', '', '', 'scaffold'),
('1', 'Understand the task', 'It is important to understand what the task is about.', 'Which are the most helpful steps for you to understand the task? \r\n\r\n(Please select from the recommended options below)', 'nl', 'Check the learning goals and instructions', 'Check the essay rubric', 'Use menu to get an overview and skim text', 'Process information by taking notes', 'Instructions page', 'Rubric page', 'Navigation panel', 'Annotation tool', 1, 1, 1, 1, 'u:https://floralearn.org/lms/mod/lesson/view.php?id=1&pageid=1', 'u:https://floralearn.org/lms/mod/lesson/view.php?id=1&pageid=2', '', '', 'scaffold'),
('2', 'Start reading', 'Es ist wichtig, die Informationen über die Themen zu lesen.', 'Welche Schritte sind für dich am hilfreichsten, um die Aufgabe zu verstehen?', 'de', 'Wähle aus, was du lesen möchtest.', 'Suche nach spezifischen Informationen\r\n', 'Notiere und strukturiere wichtige Informationen\r\n', 'Überprüfe die übrige Zeit', 'Navigationsmenü', 'Such-Tool', 'Notizen', 'Timer', 1, 1, 1, 1, '', '', '', 'a:.letime', 'scaffold'),
('2', 'Start reading', 'It is important to read information about the topics.', 'Which are the most helpful steps for you to understand the text so as to do the task? (Please select from the recommended options below)', 'en', 'Select what to read', 'Search for (specific) information', 'Note down important information', 'Check the time left', 'Navigation panel', 'Search tool', 'Annotation tool', 'Timer tool', 1, 1, 1, 1, '', '', '', 'a:.letime', 'scaffold'),
('2', 'Start reading', 'It is important to read information about the topics.', 'Which are the most helpful steps for you to understand the text so as to do the task? (Please select from the recommended options below)', 'nl', 'Select what to read', 'Search for (specific) information', 'Note down important information', 'Check the time left', 'Navigation panel', 'Search tool', 'Annotation tool', 'Timer tool', 1, 1, 1, 1, '', '', '', 'a:.letime', 'scaffold'),
('3', 'Monitor reading', 'Es ist wichtig, die aufgabenrelevanten Informationen zu lesen und dein Lernen zu überprüfen.', 'Welche Schritte sind für dich am hilfreichsten, um dein Lesen zu überprüfen?', 'de', 'Überprüfe das bisher Gelernte mit Hilfe der Notizen', 'Überprüfe die Aufsatz-Rubrik', 'Überprüfe im Aufsatz, was du als nächstes liest', 'Überprüfe die Lernziele und Anweisungen', 'Notizen', 'Rubrik', 'Aufsatz', 'Allgemeine Anweisungen', 1, 1, 1, 1, '', 'u:https://floralearn.org/lms/mod/lesson/view.php?id=1&pageid=2', 'a:.leessay', 'u:https://floralearn.org/lms/mod/lesson/view.php?id=1&pageid=1', 'scaffold'),
('3', 'Monitor reading', 'It is important to read relevant information and review your reading', 'Which are the most helpful steps for you to review your reading? (Please select from the recommended options below)', 'en', 'Review annotations to check learning so far', 'Check the essay rubric', 'Check essay to determine what to read next', 'Review the learning goals and instructions', 'Go to Annotation tool', 'Go to Rubric Page', 'Go to Essay', 'Go to Instruction', 1, 1, 1, 1, '', 'u:https://floralearn.org/lms/mod/lesson/view.php?id=1&pageid=2', 'a:.leessay', 'u:https://floralearn.org/lms/mod/lesson/view.php?id=1&pageid=1', 'scaffold'),
('3', 'Monitor reading', 'It is important to read relevant information and review your reading', 'Which are the most helpful steps for you to review your reading? (Please select from the recommended options below)', 'nl', 'Review annotations to check learning so far', 'Check the essay rubric', 'Check essay to determine what to read next', 'Review the learning goals and instructions', 'Go to Annotation tool', 'Go to Rubric Page', 'Go to Essay', 'Go to Instruction', 1, 1, 1, 1, '', 'u:https://floralearn.org/lms/mod/lesson/view.php?id=1&pageid=2', 'a:.leessay', 'u:https://floralearn.org/lms/mod/lesson/view.php?id=1&pageid=1', 'scaffold'),
('4', 'Start essay', 'Es ist wichtig, einen guten Aufsatz zu schreiben.', 'Welche Schritte sind für dich am hilfreichsten, um an deinem Aufsatz zu arbeiten? ', 'de', 'Entwirf den Aufsatz, indem du das Gelernte in Stichpunkte überträgst', 'Überprüfe die Aufsatz-Rubrik', 'Überprüfe die übrige Zeit ', 'Schreibe mit Hilfe der Notizen den Aufsatz', 'Aufsatz', 'Rubrik', 'Timer', 'Notizen', 1, 1, 1, 1, 'a:.leessay', 'u:https://floralearn.org/lms/mod/lesson/view.php?id=1&pageid=2', 'a:.letime', '', 'scaffold'),
('4', 'Start essay', 'It is important to write a good essay.', 'Which are the most helpful steps for you to work on your essay? (Please select from the recommended options below)', 'en', 'Draft essay by transferring learning to main points', 'Review the essay rubric', 'Check the remaining time', 'Write the essay with help from notes', 'Essay tool', 'Rubric page', 'Timer', 'Annotation tool', 1, 1, 1, 1, 'a:.leessay', 'u:https://floralearn.org/lms/mod/lesson/view.php?id=1&pageid=2', 'a:.letime', '', 'scaffold'),
('4', 'Start essay', 'It is important to write a good essay.', 'Which are the most helpful steps for you to work on your essay? (Please select from the recommended options below)', 'nl', 'Draft essay by transferring learning to main points', 'Review the essay rubric', 'Check the remaining time', 'Write the essay with help from notes', 'Essay tool', 'Rubric page', 'Timer', 'Annotation too', 1, 1, 1, 1, 'a:.leessay', 'u:https://floralearn.org/lms/mod/lesson/view.php?id=1&pageid=2', 'a:.letime', '', 'scaffold'),
('5', 'Monitor essay', 'Es ist wichtig, relevante Informationen zu notieren und dein Geschriebenes zu überprüfen.', 'Welche Schritte sind für dich am hilfreichsten, um dein Geschriebenes zu überprüfen? ', 'de', 'Überprüfe die Aufsatz-Rubrik', 'Überprüfe die übrige Zeit ', 'Schreibe mit Hilfe der Notizen den Aufsatz', 'Kontrolliere die Lernziele und Anweisungen', 'Rubrik', 'Timer', 'Aufsatz', 'Allgemeine Anweisungen', 1, 1, 1, 1, 'u:https://floralearn.org/lms/mod/lesson/view.php?id=1&pageid=2', 'a:.letime', 'a:.leessay', 'u:https://floralearn.org/lms/mod/lesson/view.php?id=1&pageid=1', 'scaffold'),
('5', 'Monitor essay', 'It is important to write relevant information and check your writing.', 'Which are the most helpful steps for you to check your writing? (Please select from the recommended options below)', 'en', 'Review the essay rubric', 'Check the timer to manage your time', 'Edit your essay', 'Check the learning goals and instructions', 'Rubrics page', 'Timer tool', 'Essay tool', 'Instructions page', 1, 1, 1, 1, 'u:https://floralearn.org/lms/mod/lesson/view.php?id=1&pageid=2', 'a:.letime', 'a:.leessay', 'u:https://floralearn.org/lms/mod/lesson/view.php?id=1&pageid=1', 'scaffold'),
('5', 'Monitor essay', 'It is important to write relevant information and check your writing.', 'Which are the most helpful steps for you to check your writing? (Please select from the recommended options below)', 'nl', 'Review the essay rubric', 'Check the timer to manage your time', 'Edit your essay', 'Check the learning goals and instructions', 'Rubrics page', 'Timer tool', 'Essay tool', 'Instructions page', 1, 1, 1, 1, 'u:https://floralearn.org/lms/mod/lesson/view.php?id=1&pageid=2', 'a:.letime', 'a:.leessay', 'u:https://floralearn.org/lms/mod/lesson/view.php?id=1&pageid=1', 'scaffold');

-- --------------------------------------------------------

--
-- Table structure for table scaffolds_cn_improved
--

CREATE TABLE scaffolds_cn_improved (
  id varchar(3) NOT NULL,
  title varchar(128) CHARACTER SET utf16 NOT NULL,
  content1 varchar(512) NOT NULL,
  content2 varchar(512) NOT NULL,
  language char(5) NOT NULL,
  option1_text varchar(128) NOT NULL,
  option2_text varchar(128) NOT NULL,
  option3_text varchar(128) NOT NULL,
  option4_text varchar(128) NOT NULL,
  option1_short varchar(128) NOT NULL,
  option2_short varchar(128) NOT NULL,
  option3_short varchar(128) NOT NULL,
  option4_short varchar(128) NOT NULL,
  option1_enabled int(11) NOT NULL,
  option2_enabled int(11) NOT NULL,
  option3_enabled int(11) NOT NULL,
  option4_enabled int(11) NOT NULL,
  option1_link varchar(128) NOT NULL,
  option2_link varchar(128) NOT NULL,
  option3_link varchar(128) NOT NULL,
  option4_link varchar(128) NOT NULL,
  type varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table scaffolds_cn_improved
--

INSERT INTO scaffolds_cn_improved (id, title, content1, content2, language, option1_text, option2_text, option3_text, option4_text, option1_short, option2_short, option3_short, option4_short, option1_enabled, option2_enabled, option3_enabled, option4_enabled, option1_link, option2_link, option3_link, option4_link, type) VALUES
('1', '这是一个测试句子来测试能否正常显示', 'It is important to understand what the task is about.', 'Which are the most helpful steps for you to understand the task? \r\n\r\n(Please select from the recommended options below)', 'zh_cn', 'Use menu to get an overview and skim text', 'Check the essay rubric', 'Check the learning goals and instructions', 'Process information by taking notes', 'Navigation panel', 'Rubric', 'Instructions page', 'Annotation tool', 1, 1, 1, 0, '', 'u:https://www.floraproject.org/moodle/mod/lesson/view.php?id=11&pageid=2', 'u:https://www.floraproject.org/moodle/mod/lesson/view.php?id=11&pageid=1', '', 'scaffold'),
('2', '却慲琠牥慤楮朠', 'It is important to read information about the topics.', 'Which are the most helpful steps for you to understand the text so as to do the task? (Please select from the recommended options below)', 'zh_cn', 'Note down important information', 'Select what to read', 'Check the time left', 'Check the time left', 'Annotation tool', 'Navigation page', 'Timer', 'Timer tool', 1, 1, 1, 0, '', '', 'a:.letime', '', 'scaffold'),
('3', 'M潮楴潲⁲敡摩湧', 'It is important to read relevant information and review your reading', 'Which are the most helpful steps for you to review your reading? (Please select from the recommended options below)', 'zh_cn', 'Review annotations to check learning so far', 'Review the learning goals and instructions', 'Check essay to determine what to read next', 'Review the learning goals and instructions', 'Annotation tool', 'Instructions page', 'Essay', 'Go to Instruction', 1, 1, 1, 0, '', 'u:https://www.floraproject.org/moodle/mod/lesson/view.php?id=11&pageid=1', 'a:.leessay', '', 'scaffold'),
('4', 'S瑡牴⁥獳慹', 'It is important to write a good essay.', 'Which are the most helpful steps for you to work on your essay? (Please select from the recommended options below)', 'zh_cn', 'Check the remaining time', 'Check the essay rubric', 'Draft essay by transferring learning to main points', 'Write the essay with help from notes', 'Timer', 'Rubric', 'Essay', 'Annotation tool', 1, 1, 1, 0, 'a:.letime', 'u:https://www.floraproject.org/moodle/mod/lesson/view.php?id=11&pageid=2', 'a:.leessay', '', 'scaffold'),
('5', 'M潮楴潲⁥獳慹', 'It is important to write relevant information and check your writing.', 'Which are the most helpful steps for you to check your writing? (Please select from the recommended options below)', 'zh_cn', 'Check the essay rubric', 'Edit your essay', 'Check the learning goals and instructions', 'Check the learning goals and instructions', 'Rubric page', 'Essay', 'Instructions page', 'Algemene instructie', 1, 1, 1, 0, 'u:https://www.floraproject.org/moodle/mod/lesson/view.php?id=11&pageid=2', 'a:.leessay', 'u:https://www.floraproject.org/moodle/mod/lesson/view.php?id=11&pageid=1', '', 'scaffold');

-- --------------------------------------------------------

--
-- Table structure for table scaffolds_improved
--

CREATE TABLE scaffolds_improved (
  id varchar(3) NOT NULL,
  title varchar(128) NOT NULL,
  content1 varchar(512) NOT NULL,
  content2 varchar(512) NOT NULL,
  language char(5) NOT NULL,
  option1_text varchar(128) NOT NULL,
  option2_text varchar(128) NOT NULL,
  option3_text varchar(128) NOT NULL,
  option4_text varchar(128) NOT NULL,
  option1_short varchar(128) NOT NULL,
  option2_short varchar(128) NOT NULL,
  option3_short varchar(128) NOT NULL,
  option4_short varchar(128) NOT NULL,
  option1_enabled int(11) NOT NULL,
  option2_enabled int(11) NOT NULL,
  option3_enabled int(11) NOT NULL,
  option4_enabled int(11) NOT NULL,
  option1_link varchar(128) NOT NULL,
  option2_link varchar(128) NOT NULL,
  option3_link varchar(128) NOT NULL,
  option4_link varchar(128) NOT NULL,
  type varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table scaffolds_improved
--

INSERT INTO scaffolds_improved (id, title, content1, content2, language, option1_text, option2_text, option3_text, option4_text, option1_short, option2_short, option3_short, option4_short, option1_enabled, option2_enabled, option3_enabled, option4_enabled, option1_link, option2_link, option3_link, option4_link, type) VALUES
('1', 'Sample Prompt Title 1', 'Sample Main Message # 1', 'Sample Secondary Message 1', 'eg', 'Option 1 Text', 'Option 2 Text', 'Option 3 Text', 'Option 4 Text', 'Tool 1', 'Tool 2', 'Tool 3', 'Tool 4', 1, 1, 1, 1, '', '', '', '', 'scaffold'),
('1', '\0Understand the task', 'It is important to understand what the task is about.', 'Which are the most helpful steps for you to understand the task? \r\n\r\n(Please select from the recommended options below)', 'en', 'Use menu to get an overview and skim text', 'Check the essay rubric', 'Check the learning goals and instructions', 'Process information by taking notes', 'Navigation panel', 'Rubric', 'Instructions page', 'Annotation tool', 1, 1, 1, 0, '', 'u:https://www.floraproject.org/moodle/mod/lesson/view.php?id=11&pageid=2', 'u:https://www.floraproject.org/moodle/mod/lesson/view.php?id=11&pageid=1', '', 'scaffold'),
('1', '\0Begrijp de taak', 'Om goed te presteren is het belangrijk om te begrijpen waar deze taak over gaat.', 'Welke stappen zijn het meest behulpzaam voor jou om de taak te begrijpen?', 'nl', 'Scan het menu en de teksten om een overzicht te krijgen', 'Controleer de rubric', 'Controleer de algemene instructie', 'Process information by taking notes', 'Menu', 'Rubric', 'Algemene instructie', 'Annotation tool', 1, 1, 1, 0, '', 'u:https://www.floraproject.org/moodle/mod/lesson/view.php?id=11&pageid=2', 'u:https://www.floraproject.org/moodle/mod/lesson/view.php?id=11&pageid=1', '', 'scaffold'),
('2', 'Sample Prompt Title 2', 'Sample Main Message # 2', 'Sample Secondary Message 2', 'eg', 'Option 1 Text', 'Option 2 Text', 'Option 3 Text', 'Option 4 Text', 'Tool 1', 'Tool 2', 'Tool 3', 'Tool 4', 1, 1, 1, 1, '', '', '', '', 'scaffold'),
('2', 'Start reading ', 'It is important to read information about the topics.', 'Which are the most helpful steps for you to understand the text so as to do the task? (Please select from the recommended options below)', 'en', 'Note down important information', 'Select what to read', 'Check the time left', 'Check the time left', 'Annotation tool', 'Navigation page', 'Timer', 'Timer tool', 1, 1, 1, 0, '', '', 'a:.letime', '', 'scaffold'),
('2', '\0Start lezen', 'Het is belangrijk om informatie over de onderwerpen te lezen.', 'Welke stappen zijn het meest behulpzaam voor jou om de teksten te begrijpen in relatie tot de taak?', 'nl', 'Noteer en organiseer belangrijke informatie', 'Selecteer wat je gaat lezen', 'Controleer de resterende tijd', 'Check the time left', 'Notities', 'Menu', 'Timer', 'Timer tool', 1, 1, 1, 0, '', '', 'a:.letime', '', 'scaffold'),
('3', 'Sample Prompt Title 3', 'Sample Main Message # 3', 'Sample Secondary Message 3', 'eg', 'Option 1 Text', 'Option 2 Text', 'Option 3 Text', 'Option 4 Text', 'Tool 1', 'Tool 2', 'Tool 3', 'Tool 4', 1, 1, 1, 1, '', '', '', '', 'scaffold'),
('3', '\0Monitor reading', 'It is important to read relevant information and review your reading', 'Which are the most helpful steps for you to review your reading? (Please select from the recommended options below)', 'en', 'Review annotations to check learning so far', 'Review the learning goals and instructions', 'Check essay to determine what to read next', 'Review the learning goals and instructions', 'Annotation tool', 'Instructions page', 'Essay', 'Go to Instruction', 1, 1, 1, 0, '', 'u:https://www.floraproject.org/moodle/mod/lesson/view.php?id=11&pageid=1', 'a:.leessay', '', 'scaffold'),
('3', 'Controleer lezen', 'Om goed te presteren is het belangrijk om relevante informatie te lezen en na te denken over wat je leest.', 'Welke stappen zijn het meest behulpzaam voor jou om jouw lezen te controleren?', 'nl', 'Herlees notities om wat je geleerd hebt te controleren', 'Herlees de leerdoelen en instructies', 'Stel het essay op door wat je geleerd hebt te vertalen naar kernpunten', 'Review the learning goals and instructions', 'Notities', 'Algemene instructie', 'Essay', 'Go to Instruction', 1, 1, 1, 0, '', 'u:https://www.floraproject.org/moodle/mod/lesson/view.php?id=11&pageid=1', 'a:.leessay', '', 'scaffold'),
('4', 'Sample  Prompt Title 4', 'Sample Main Message # 4', 'Sample Secondary Message 4', 'eg', 'Option 1 Text', 'Option 2 Text', 'Option 3 Text', 'Option 4 Text', 'Tool 1', 'Tool 2', 'Tool 3', 'Tool 4', 1, 1, 1, 1, '', '', '', '', 'scaffold'),
('4', '\0Start essay', 'It is important to write a good essay.', 'Which are the most helpful steps for you to work on your essay? (Please select from the recommended options below)', 'en', 'Check the remaining time', 'Check the essay rubric', 'Draft essay by transferring learning to main points', 'Write the essay with help from notes', 'Timer', 'Rubric', 'Essay', 'Annotation tool', 1, 1, 1, 0, 'a:.letime', 'u:https://www.floraproject.org/moodle/mod/lesson/view.php?id=11&pageid=2', 'a:.leessay', '', 'scaffold'),
('4', '\0Start essay', 'Het is belangrijk om een goed essay te schrijven.', 'Welke stappen zijn het meest behulpzaam voor jou om aan jouw essay te werken?', 'nl', 'Controleer de resterende tijd', 'Herlees de essay-rubric', 'Stel het essay op door wat je geleerd hebt te vertalen naar kernpunten', 'Write the essay with help from notes', 'Timer', 'Rubric', 'Essay', 'Annotation too', 1, 1, 1, 0, 'a:.letime', 'u:https://www.floraproject.org/moodle/mod/lesson/view.php?id=11&pageid=2', 'a:.leessay', '', 'scaffold'),
('5', 'Sample Prompt Title 5', 'Sample Main Message # 5', 'Sample Secondary Message 5', 'eg', 'Option 1 Text', 'Option 2 Text', 'Option 3 Text', 'Option 4 Text', 'Tool 1', 'Tool 2', 'Tool 3', 'Tool 4', 1, 1, 1, 1, '', '', '', '', 'scaffold'),
('5', '\0Monitor essay', 'It is important to write relevant information and check your writing.', 'Which are the most helpful steps for you to check your writing? (Please select from the recommended options below)', 'en', 'Check the essay rubric', 'Edit your essay', 'Check the learning goals and instructions', 'Check the learning goals and instructions', 'Rubric page', 'Essay', 'Instructions page', 'Algemene instructie', 1, 1, 1, 0, 'u:https://www.floraproject.org/moodle/mod/lesson/view.php?id=11&pageid=2', 'a:.leessay', 'u:https://www.floraproject.org/moodle/mod/lesson/view.php?id=11&pageid=1', '', 'scaffold'),
('5', 'Controleer essay', 'Het is belangrijk om relevante informatie op te schrijven en na te denken over wat je opschrijft.', 'Welke stappen zijn het meest behulpzaam voor jou om jouw tekst te controleren?', 'nl', 'Herlees de essay-rubric', 'Pas jouw essay aan', 'Controleer de leerdoelen en instructies', 'Check the learning goals and instructions', 'Rubric', 'Essay', 'Algemene instructie', 'Algemene instructie', 1, 1, 1, 0, 'u:https://www.floraproject.org/moodle/mod/lesson/view.php?id=11&pageid=2', 'a:.leessay', 'u:https://www.floraproject.org/moodle/mod/lesson/view.php?id=11&pageid=1', '', 'scaffold');

-- --------------------------------------------------------

--
-- Table structure for table user_log
--

CREATE TABLE user_log (
  id int(11) NOT NULL,
  source varchar(100) NOT NULL,
  userid varchar(256) NOT NULL,
  uid varchar(256) NOT NULL,
  session_start varchar(96) DEFAULT NULL,
  block varchar(8) NOT NULL,
  block_desc varchar(128) NOT NULL,
  time_lapsed bigint(20) NOT NULL,
  clock varchar(512) NOT NULL,
  timestamp varchar(256) NOT NULL,
  url varchar(256) NOT NULL,
  action varchar(256) NOT NULL,
  sub_action varchar(256) NOT NULL,
  value longtext NOT NULL,
  logServer varchar(256) NOT NULL,
  server_time timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  log_obj varchar(5000) NOT NULL,
  granular_time timestamp(6) NOT NULL DEFAULT current_timestamp(6),
  essay_snapshot longtext DEFAULT '\'""\'',
  action_label varchar(128) NOT NULL,
  action_delta bigint(20) NOT NULL DEFAULT -99,
  process_label varchar(128) NOT NULL,
  process_starttime bigint(20) NOT NULL DEFAULT -99,
  process_endtime bigint(20) NOT NULL DEFAULT -99
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table scaffolds
--
ALTER TABLE scaffolds
  ADD PRIMARY KEY (id,language);

--
-- Indexes for table scaffolds_cn_improved
--
ALTER TABLE scaffolds_cn_improved
  ADD PRIMARY KEY (id,language);

--
-- Indexes for table scaffolds_improved
--
ALTER TABLE scaffolds_improved
  ADD PRIMARY KEY (id,language);

--
-- Indexes for table user_log
--
ALTER TABLE user_log
  ADD PRIMARY KEY (id),
  ADD KEY uid (uid);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table user_log
--
ALTER TABLE user_log
  MODIFY id int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

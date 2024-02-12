/*
 Navicat Premium Data Transfer

 Source Server         : Localhost
 Source Server Type    : MySQL
 Source Server Version : 100428 (10.4.28-MariaDB)
 Source Host           : localhost:3306
 Source Schema         : employer_kubu_id

 Target Server Type    : MySQL
 Target Server Version : 100428 (10.4.28-MariaDB)
 File Encoding         : 65001

 Date: 12/02/2024 16:43:24
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for arif_activity
-- ----------------------------
DROP TABLE IF EXISTS `arif_activity`;
CREATE TABLE `arif_activity`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `post_id` int NULL DEFAULT NULL,
  `grup_id` int NULL DEFAULT NULL,
  `loker_id` int NULL DEFAULT NULL,
  `feature` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `create_at` datetime NOT NULL DEFAULT current_timestamp,
  `time` datetime NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_id`(`user_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9687 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_banner
-- ----------------------------
DROP TABLE IF EXISTS `arif_banner`;
CREATE TABLE `arif_banner`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `link` varchar(300) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `img_banner` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `create_at` datetime NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_block_user
-- ----------------------------
DROP TABLE IF EXISTS `arif_block_user`;
CREATE TABLE `arif_block_user`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `blocked_id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 105 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_broadcas
-- ----------------------------
DROP TABLE IF EXISTS `arif_broadcas`;
CREATE TABLE `arif_broadcas`  (
  `broadcas_id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `target` int NOT NULL,
  `id_grup` int NULL DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`broadcas_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 28 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_bulan
-- ----------------------------
DROP TABLE IF EXISTS `arif_bulan`;
CREATE TABLE `arif_bulan`  (
  `id_bulan` int NOT NULL AUTO_INCREMENT,
  `name` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_year` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_bulan`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_chat
-- ----------------------------
DROP TABLE IF EXISTS `arif_chat`;
CREATE TABLE `arif_chat`  (
  `id_chat` int NOT NULL AUTO_INCREMENT,
  `id_thread` int NOT NULL,
  `id_user` int NOT NULL,
  `chat_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `chat_foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `chat_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `date_time` datetime NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id_chat`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 138 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_chat_thread
-- ----------------------------
DROP TABLE IF EXISTS `arif_chat_thread`;
CREATE TABLE `arif_chat_thread`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_thread` int NOT NULL,
  `id_sender` int NOT NULL,
  `id_receiver` int NOT NULL,
  `isread` int NOT NULL DEFAULT 0,
  `update_at` datetime NOT NULL DEFAULT current_timestamp ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 117 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_city
-- ----------------------------
DROP TABLE IF EXISTS `arif_city`;
CREATE TABLE `arif_city`  (
  `kode` varchar(13) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `nama` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `ibukota` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `lat` double NULL DEFAULT NULL COMMENT 'latitude in degrees',
  `lng` double NULL DEFAULT NULL COMMENT 'longitude in degrees',
  `province` int NOT NULL DEFAULT 0,
  UNIQUE INDEX `kode`(`kode`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_comment
-- ----------------------------
DROP TABLE IF EXISTS `arif_comment`;
CREATE TABLE `arif_comment`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `post_id` int NOT NULL,
  `comment_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `file_comment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `comen_url` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `comment_time` datetime NOT NULL DEFAULT current_timestamp,
  `update_at` datetime NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 706 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_comment_replies
-- ----------------------------
DROP TABLE IF EXISTS `arif_comment_replies`;
CREATE TABLE `arif_comment_replies`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `comment_id` int NOT NULL,
  `user_id` int NOT NULL,
  `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp,
  `update_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 101 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_company
-- ----------------------------
DROP TABLE IF EXISTS `arif_company`;
CREATE TABLE `arif_company`  (
  `company_id` int NOT NULL AUTO_INCREMENT,
  `employer_foto` char(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'mitra-default.png',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `cover` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `category` int NOT NULL,
  `total_employe` int NOT NULL DEFAULT 0,
  `pic_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `sector` int NOT NULL DEFAULT 0,
  `employer_username` char(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `employer_password` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `employer_approvement` enum('approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'Null = belum diterima atau ditolak, perlu approvement dari administrator',
  `employer_approvementReason` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'Keterangan tentang approvement. Misal ketika ditolak akan diberi penjelasan kenapa di tolak',
  `employer_approvementBy` int NULL DEFAULT NULL COMMENT 'ID Administrator',
  `employer_approvementAt` datetime NULL DEFAULT NULL,
  `employer_createdAt` datetime NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`company_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4740 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_contag
-- ----------------------------
DROP TABLE IF EXISTS `arif_contag`;
CREATE TABLE `arif_contag`  (
  `con_id` int NOT NULL AUTO_INCREMENT,
  `post_id` int NOT NULL,
  `hastag_id` int NOT NULL,
  PRIMARY KEY (`con_id`) USING BTREE,
  INDEX `hastag_id`(`hastag_id` ASC) USING BTREE,
  INDEX `post_id`(`post_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 303 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_education
-- ----------------------------
DROP TABLE IF EXISTS `arif_education`;
CREATE TABLE `arif_education`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `school_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `level` int NOT NULL DEFAULT 0,
  `faculty` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `end_date` year NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2307 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_experience
-- ----------------------------
DROP TABLE IF EXISTS `arif_experience`;
CREATE TABLE `arif_experience`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `sector` int NOT NULL DEFAULT 0,
  `experience_company` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `experience_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `experience_start_date` year NULL DEFAULT NULL,
  `experience_end_date` year NULL DEFAULT NULL,
  `experience_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `isNow` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_id`(`user_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2396 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_favorite_loker
-- ----------------------------
DROP TABLE IF EXISTS `arif_favorite_loker`;
CREATE TABLE `arif_favorite_loker`  (
  `favorite_id` int NOT NULL AUTO_INCREMENT,
  `loker_id` int NOT NULL,
  `user_id` int NOT NULL,
  `create_at` datetime NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`favorite_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1817 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_fitur
-- ----------------------------
DROP TABLE IF EXISTS `arif_fitur`;
CREATE TABLE `arif_fitur`  (
  `id_fitur` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_fitur`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_followers
-- ----------------------------
DROP TABLE IF EXISTS `arif_followers`;
CREATE TABLE `arif_followers`  (
  `follow_id` int NOT NULL AUTO_INCREMENT,
  `following_id` int NOT NULL DEFAULT 0,
  `follower_id` int NOT NULL DEFAULT 0,
  `is_notify` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`follow_id`) USING BTREE,
  INDEX `following_id`(`following_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1151 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_group
-- ----------------------------
DROP TABLE IF EXISTS `arif_group`;
CREATE TABLE `arif_group`  (
  `group_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `group_username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `group_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `group_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `group_cover` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `group_about` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `group_category` int NOT NULL DEFAULT 0,
  `group_privacy` int NOT NULL DEFAULT 1 COMMENT '0=publik,1=private',
  `group_status` int NOT NULL DEFAULT 1,
  `top_list` int NOT NULL DEFAULT 0 COMMENT '0=Reguler, 1=Top List',
  `create_at` datetime NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`group_id`) USING BTREE,
  INDEX `user_id`(`user_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 72 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_group_category
-- ----------------------------
DROP TABLE IF EXISTS `arif_group_category`;
CREATE TABLE `arif_group_category`  (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `active` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`category_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_group_invet
-- ----------------------------
DROP TABLE IF EXISTS `arif_group_invet`;
CREATE TABLE `arif_group_invet`  (
  `invet_id` int NOT NULL AUTO_INCREMENT,
  `group_id` int NOT NULL DEFAULT 0,
  `user_id` int NOT NULL DEFAULT 0,
  `user_id_inveter` int NOT NULL DEFAULT 0,
  `invet_status` int NOT NULL DEFAULT 1 COMMENT '0=waiting, 1= accept,2=reject',
  PRIMARY KEY (`invet_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 62 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_group_member
-- ----------------------------
DROP TABLE IF EXISTS `arif_group_member`;
CREATE TABLE `arif_group_member`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `id_group` int NOT NULL,
  `is_admin` int NULL DEFAULT NULL,
  `id_moderator` int NULL DEFAULT NULL,
  `datetime` datetime NOT NULL DEFAULT current_timestamp,
  `akses` int NOT NULL DEFAULT 0 COMMENT '0=member,1=Admin,2=moderator',
  `member_status` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 473 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_group_member_status
-- ----------------------------
DROP TABLE IF EXISTS `arif_group_member_status`;
CREATE TABLE `arif_group_member_status`  (
  `id_status` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_group_poling
-- ----------------------------
DROP TABLE IF EXISTS `arif_group_poling`;
CREATE TABLE `arif_group_poling`  (
  `poling_id` int NOT NULL AUTO_INCREMENT,
  `poling_text` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `poling_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `poling_enum` int NOT NULL DEFAULT 0,
  `post_id` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`poling_id`) USING BTREE,
  INDEX `post_id`(`post_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 33 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_group_request_delet
-- ----------------------------
DROP TABLE IF EXISTS `arif_group_request_delet`;
CREATE TABLE `arif_group_request_delet`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `grup_id` int NOT NULL,
  `user_id` int NOT NULL,
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` int NOT NULL DEFAULT 1,
  `create_at` datetime NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_group_request_join
-- ----------------------------
DROP TABLE IF EXISTS `arif_group_request_join`;
CREATE TABLE `arif_group_request_join`  (
  `id_request` int NOT NULL AUTO_INCREMENT,
  `grup_id` int NOT NULL,
  `user_id` int NOT NULL,
  `status` int NOT NULL,
  `request_time` datetime NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id_request`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 53 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_group_status
-- ----------------------------
DROP TABLE IF EXISTS `arif_group_status`;
CREATE TABLE `arif_group_status`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_group_status_join
-- ----------------------------
DROP TABLE IF EXISTS `arif_group_status_join`;
CREATE TABLE `arif_group_status_join`  (
  `id_status` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_hastag
-- ----------------------------
DROP TABLE IF EXISTS `arif_hastag`;
CREATE TABLE `arif_hastag`  (
  `hastag_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `totals_use` int NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`hastag_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 146 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_hide
-- ----------------------------
DROP TABLE IF EXISTS `arif_hide`;
CREATE TABLE `arif_hide`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `post_id` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 121 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_image_edited
-- ----------------------------
DROP TABLE IF EXISTS `arif_image_edited`;
CREATE TABLE `arif_image_edited`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `post_id` int NOT NULL,
  `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `post_id`(`post_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_image_file
-- ----------------------------
DROP TABLE IF EXISTS `arif_image_file`;
CREATE TABLE `arif_image_file`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `post_id` int NOT NULL,
  `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `post_id`(`post_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1135 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_job_type
-- ----------------------------
DROP TABLE IF EXISTS `arif_job_type`;
CREATE TABLE `arif_job_type`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `job_type_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_loker
-- ----------------------------
DROP TABLE IF EXISTS `arif_loker`;
CREATE TABLE `arif_loker`  (
  `loker_id` int NOT NULL AUTO_INCREMENT,
  `company_id` int NOT NULL,
  `job_desc` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `job_position` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `cualified` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `provinice` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `city` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `start_salary` int NULL DEFAULT NULL,
  `end_salary` int NULL DEFAULT NULL,
  `job_type` int NULL DEFAULT NULL COMMENT '0=Full time,1=Part-Time,2=freelance\r\n',
  `level` int NOT NULL DEFAULT 1 COMMENT '1=Pemula.2=Menengah,3=Mahir',
  `benefit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `start_open` date NULL DEFAULT NULL,
  `end_open` date NULL DEFAULT NULL,
  `create_at` datetime NOT NULL DEFAULT current_timestamp,
  `status` int NOT NULL DEFAULT 3,
  PRIMARY KEY (`loker_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4784 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_loker_history_applay
-- ----------------------------
DROP TABLE IF EXISTS `arif_loker_history_applay`;
CREATE TABLE `arif_loker_history_applay`  (
  `history_id` int NOT NULL AUTO_INCREMENT,
  `loker_id` int NOT NULL,
  `user_id` int NOT NULL,
  `status` int NOT NULL DEFAULT 1 COMMENT '1=send,2=reject,3=archive',
  `create_at` datetime NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`history_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2853 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_loker_status
-- ----------------------------
DROP TABLE IF EXISTS `arif_loker_status`;
CREATE TABLE `arif_loker_status`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_menu
-- ----------------------------
DROP TABLE IF EXISTS `arif_menu`;
CREATE TABLE `arif_menu`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `path_url` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_menu_config
-- ----------------------------
DROP TABLE IF EXISTS `arif_menu_config`;
CREATE TABLE `arif_menu_config`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_menu` int NOT NULL,
  `id_user` int NOT NULL,
  `create_at` datetime NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 110 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_menu_report
-- ----------------------------
DROP TABLE IF EXISTS `arif_menu_report`;
CREATE TABLE `arif_menu_report`  (
  `num` int NOT NULL AUTO_INCREMENT,
  `id_menu` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `type` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`num`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_most_accessed_page
-- ----------------------------
DROP TABLE IF EXISTS `arif_most_accessed_page`;
CREATE TABLE `arif_most_accessed_page`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `page` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 97034 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_mytim
-- ----------------------------
DROP TABLE IF EXISTS `arif_mytim`;
CREATE TABLE `arif_mytim`  (
  `id_project` int NOT NULL AUTO_INCREMENT,
  `id_owner` int NOT NULL,
  `kategori` int NOT NULL DEFAULT 0,
  `mytim_nama_proyek` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `mytim_jenis_proyek` int NOT NULL COMMENT '0=publik,1=private',
  `mytim_tgl_mulai` date NOT NULL,
  `mytim_tgl_selesai` date NOT NULL,
  `mytim_lokasi` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `mytim_deskrispi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `mytim_cover` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `status` int NOT NULL DEFAULT 1,
  `mytim_create_at` datetime NOT NULL DEFAULT current_timestamp,
  `mytim_update_at` datetime NOT NULL DEFAULT current_timestamp ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_project`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 196 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_mytim_dikusi_proyek
-- ----------------------------
DROP TABLE IF EXISTS `arif_mytim_dikusi_proyek`;
CREATE TABLE `arif_mytim_dikusi_proyek`  (
  `id_forum` int NOT NULL AUTO_INCREMENT,
  `id_proyek` int NOT NULL,
  `id_user` int NOT NULL,
  `komentar_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `komentar_foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `komentar_link` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `komentar_dokumen` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `create_at` datetime NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id_forum`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 15 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_mytim_invet
-- ----------------------------
DROP TABLE IF EXISTS `arif_mytim_invet`;
CREATE TABLE `arif_mytim_invet`  (
  `id_invet` int NOT NULL AUTO_INCREMENT,
  `id_user` int NOT NULL,
  `id_proyek` int NOT NULL,
  `waktu` datetime NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id_invet`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 22 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_mytim_kategori
-- ----------------------------
DROP TABLE IF EXISTS `arif_mytim_kategori`;
CREATE TABLE `arif_mytim_kategori`  (
  `id_kategori` int NOT NULL AUTO_INCREMENT,
  `nam_kategori` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `is_active` int NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_kategori`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_mytim_member
-- ----------------------------
DROP TABLE IF EXISTS `arif_mytim_member`;
CREATE TABLE `arif_mytim_member`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_proyek` int NOT NULL,
  `id_user` int NOT NULL,
  `is_owner` int NULL DEFAULT NULL,
  `akses` int NOT NULL DEFAULT 0,
  `join_date` datetime NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 255 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_mytim_member_task
-- ----------------------------
DROP TABLE IF EXISTS `arif_mytim_member_task`;
CREATE TABLE `arif_mytim_member_task`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_task` int NOT NULL,
  `id_user` int NOT NULL,
  `cerate_at` datetime NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_mytim_rating
-- ----------------------------
DROP TABLE IF EXISTS `arif_mytim_rating`;
CREATE TABLE `arif_mytim_rating`  (
  `id_rating` int NOT NULL AUTO_INCREMENT,
  `id_proyek` int NOT NULL,
  `id_user` int NOT NULL,
  `id_kategori` int NOT NULL,
  `nilai` int NOT NULL,
  PRIMARY KEY (`id_rating`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 41 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_mytim_rating_kategori
-- ----------------------------
DROP TABLE IF EXISTS `arif_mytim_rating_kategori`;
CREATE TABLE `arif_mytim_rating_kategori`  (
  `id_kategori` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_kategori`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_mytim_request_delet
-- ----------------------------
DROP TABLE IF EXISTS `arif_mytim_request_delet`;
CREATE TABLE `arif_mytim_request_delet`  (
  `id_request` int NOT NULL AUTO_INCREMENT,
  `id_proyek` int NOT NULL,
  `id_user` int NOT NULL,
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `waktu` datetime NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id_request`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_mytim_request_join
-- ----------------------------
DROP TABLE IF EXISTS `arif_mytim_request_join`;
CREATE TABLE `arif_mytim_request_join`  (
  `id_request` int NOT NULL AUTO_INCREMENT,
  `id_user` int NOT NULL,
  `id_proyek` int NOT NULL,
  `request_time` datetime NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id_request`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1392 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_mytim_status
-- ----------------------------
DROP TABLE IF EXISTS `arif_mytim_status`;
CREATE TABLE `arif_mytim_status`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_mytim_task
-- ----------------------------
DROP TABLE IF EXISTS `arif_mytim_task`;
CREATE TABLE `arif_mytim_task`  (
  `id_task` int NOT NULL AUTO_INCREMENT,
  `id_proyek` int NOT NULL,
  `id_kategori` int NOT NULL DEFAULT 1,
  `nama_task` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi_task` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `taskUrl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `start_task` date NULL DEFAULT NULL,
  `end_task` date NULL DEFAULT NULL,
  `status_task` int NOT NULL DEFAULT 0,
  `cereat_at` datetime NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id_task`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 61 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_mytim_task_diskusi
-- ----------------------------
DROP TABLE IF EXISTS `arif_mytim_task_diskusi`;
CREATE TABLE `arif_mytim_task_diskusi`  (
  `id_diskusi` int NOT NULL AUTO_INCREMENT,
  `id_task` int NOT NULL,
  `id_user` int NOT NULL,
  `komentar` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `foto` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `link` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `waktu_komen` datetime NOT NULL DEFAULT current_timestamp,
  `status_diskusi_task` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_diskusi`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 47 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_nonaktif
-- ----------------------------
DROP TABLE IF EXISTS `arif_nonaktif`;
CREATE TABLE `arif_nonaktif`  (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `start_date` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `end_date` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id_user`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7895 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_noti_kategori
-- ----------------------------
DROP TABLE IF EXISTS `arif_noti_kategori`;
CREATE TABLE `arif_noti_kategori`  (
  `id_kategori` int NOT NULL AUTO_INCREMENT,
  `fitur` int NULL DEFAULT NULL,
  `nama` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_kategori`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 23 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_notif
-- ----------------------------
DROP TABLE IF EXISTS `arif_notif`;
CREATE TABLE `arif_notif`  (
  `notif_id` int NOT NULL AUTO_INCREMENT,
  `id_user_sender` int NULL DEFAULT NULL,
  `id_user_receiver` int NULL DEFAULT NULL,
  `conten` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `conten_admin` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `target` int NULL DEFAULT NULL,
  `kategori` int NULL DEFAULT NULL,
  `destination` int NULL DEFAULT NULL,
  `is_read` int NOT NULL DEFAULT 0,
  `is_read_admin` int NOT NULL DEFAULT 0,
  `create_at` datetime NOT NULL DEFAULT current_timestamp,
  `id_post` int NULL DEFAULT NULL,
  `id_grup` int NULL DEFAULT NULL,
  `id_proyek` int NULL DEFAULT NULL,
  `id_task` int NULL DEFAULT NULL,
  `is_admin` int NOT NULL DEFAULT 0,
  `id_tiket` int NULL DEFAULT NULL,
  `total_send` int NOT NULL,
  `total_read` int NOT NULL,
  PRIMARY KEY (`notif_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3650 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_otp
-- ----------------------------
DROP TABLE IF EXISTS `arif_otp`;
CREATE TABLE `arif_otp`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `otp_code` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp,
  `phone_number` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 17297 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_otp_email
-- ----------------------------
DROP TABLE IF EXISTS `arif_otp_email`;
CREATE TABLE `arif_otp_email`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `otp_code` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp,
  `email` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 172 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_poling_enum
-- ----------------------------
DROP TABLE IF EXISTS `arif_poling_enum`;
CREATE TABLE `arif_poling_enum`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `poling_id` int NOT NULL DEFAULT 0,
  `user_id` int NOT NULL DEFAULT 0,
  `post_id` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 28 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_post
-- ----------------------------
DROP TABLE IF EXISTS `arif_post`;
CREATE TABLE `arif_post`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `post_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `post_image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `post_privacy` int NOT NULL DEFAULT 2 COMMENT '1=teman,0=publik',
  `post_type` int NOT NULL DEFAULT 1 COMMENT '1=social,2=grup',
  `grup_id` int NULL DEFAULT 0,
  `share_id` int NULL DEFAULT NULL,
  `is_poling` int NOT NULL DEFAULT 0,
  `is_comment` int NOT NULL DEFAULT 1,
  `poling_time` datetime NULL DEFAULT NULL,
  `poling_selected` int NULL DEFAULT NULL,
  `is_edit` int NOT NULL DEFAULT 0,
  `is_delet` int NOT NULL DEFAULT 1,
  `post_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `post_category` set('lowongan kerja','politik','buruh') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp,
  `update_at` datetime NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id` ASC) USING BTREE,
  INDEX `user_id`(`user_id` ASC) USING BTREE,
  INDEX `grup_id`(`grup_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2014 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_post_edited
-- ----------------------------
DROP TABLE IF EXISTS `arif_post_edited`;
CREATE TABLE `arif_post_edited`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `post_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `post_image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `post_privacy` int NOT NULL DEFAULT 2 COMMENT '1=teman,0=publik',
  `post_type` int NOT NULL DEFAULT 1 COMMENT '1=social,2=grup',
  `grup_id` int NULL DEFAULT 0,
  `share_id` int NULL DEFAULT NULL,
  `is_poling` int NOT NULL DEFAULT 0,
  `is_comment` int NOT NULL DEFAULT 1,
  `poling_time` timestamp NULL DEFAULT NULL,
  `is_edit` int NOT NULL DEFAULT 0,
  `is_delet` int NOT NULL DEFAULT 0,
  `post_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp,
  `update_at` datetime NULL DEFAULT current_timestamp ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id` ASC) USING BTREE,
  INDEX `user_id`(`user_id` ASC) USING BTREE,
  INDEX `grup_id`(`grup_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_post_params_filter
-- ----------------------------
DROP TABLE IF EXISTS `arif_post_params_filter`;
CREATE TABLE `arif_post_params_filter`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `category` set('lowongan kerja','politik','buruh') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `filter` mediumtext CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_post_reaction
-- ----------------------------
DROP TABLE IF EXISTS `arif_post_reaction`;
CREATE TABLE `arif_post_reaction`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `post_id` int NOT NULL DEFAULT 0,
  `comment_id` int NOT NULL DEFAULT 0,
  `replay_id` int NOT NULL DEFAULT 0,
  `reaction` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_id`(`user_id` ASC) USING BTREE,
  INDEX `post_id`(`post_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4321 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_post_report
-- ----------------------------
DROP TABLE IF EXISTS `arif_post_report`;
CREATE TABLE `arif_post_report`  (
  `id_report` int NOT NULL AUTO_INCREMENT,
  `category` int NOT NULL,
  `user_id` int NOT NULL,
  `post_id` int NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `create_at` datetime NULL DEFAULT current_timestamp,
  `type` int NOT NULL COMMENT '1=Social,2=Grup',
  PRIMARY KEY (`id_report`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 74 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_post_report_category
-- ----------------------------
DROP TABLE IF EXISTS `arif_post_report_category`;
CREATE TABLE `arif_post_report_category`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_post_saved
-- ----------------------------
DROP TABLE IF EXISTS `arif_post_saved`;
CREATE TABLE `arif_post_saved`  (
  `id_save` int NOT NULL AUTO_INCREMENT,
  `post_id` int NOT NULL,
  `user_id` int NOT NULL,
  `create_at` datetime NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id_save`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 83 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_post_share
-- ----------------------------
DROP TABLE IF EXISTS `arif_post_share`;
CREATE TABLE `arif_post_share`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `share_id` int NOT NULL,
  `post_id` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_post_status
-- ----------------------------
DROP TABLE IF EXISTS `arif_post_status`;
CREATE TABLE `arif_post_status`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_province
-- ----------------------------
DROP TABLE IF EXISTS `arif_province`;
CREATE TABLE `arif_province`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `kode` varchar(13) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `nama` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `ibukota` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `lat` double NULL DEFAULT NULL COMMENT 'latitude in degrees',
  `lng` double NULL DEFAULT NULL COMMENT 'longitude in degrees',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `kode`(`kode`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 35 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_reject
-- ----------------------------
DROP TABLE IF EXISTS `arif_reject`;
CREATE TABLE `arif_reject`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `feature` int NOT NULL,
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `referenc_id` int NOT NULL,
  `create_at` datetime NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 21 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_report_comment
-- ----------------------------
DROP TABLE IF EXISTS `arif_report_comment`;
CREATE TABLE `arif_report_comment`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `reporter` int NOT NULL,
  `owner` int NOT NULL,
  `comment_id` int NOT NULL,
  `category` int NOT NULL,
  `comment` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `reason` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `isreplay` int NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_sektor
-- ----------------------------
DROP TABLE IF EXISTS `arif_sektor`;
CREATE TABLE `arif_sektor`  (
  `sektor_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`sektor_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 27 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_settings_web
-- ----------------------------
DROP TABLE IF EXISTS `arif_settings_web`;
CREATE TABLE `arif_settings_web`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `judul` varchar(355) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `urutan` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_skill
-- ----------------------------
DROP TABLE IF EXISTS `arif_skill`;
CREATE TABLE `arif_skill`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `skill_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `skill_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_id`(`user_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2090 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_tiket
-- ----------------------------
DROP TABLE IF EXISTS `arif_tiket`;
CREATE TABLE `arif_tiket`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_kategori` int NOT NULL,
  `isi_laporan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` int NOT NULL DEFAULT 1,
  `create_at` datetime NOT NULL DEFAULT current_timestamp,
  `update_at` datetime NOT NULL DEFAULT current_timestamp ON UPDATE CURRENT_TIMESTAMP,
  `id_user` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 40 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_tiket_diskusi
-- ----------------------------
DROP TABLE IF EXISTS `arif_tiket_diskusi`;
CREATE TABLE `arif_tiket_diskusi`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_tiket` int NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_user` int NOT NULL,
  `create_at` datetime NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_tiket_kategori
-- ----------------------------
DROP TABLE IF EXISTS `arif_tiket_kategori`;
CREATE TABLE `arif_tiket_kategori`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_tiket_status
-- ----------------------------
DROP TABLE IF EXISTS `arif_tiket_status`;
CREATE TABLE `arif_tiket_status`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_users
-- ----------------------------
DROP TABLE IF EXISTS `arif_users`;
CREATE TABLE `arif_users`  (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `foto_profile` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `full_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `brith_date` date NULL DEFAULT NULL,
  `gender` int NULL DEFAULT NULL,
  `age` int NULL DEFAULT NULL,
  `religion` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `menikah` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `city` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `province` int NULL DEFAULT NULL,
  `school` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `experience` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `phone` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `country` int NOT NULL DEFAULT 1,
  `password` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `work_status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `is_status` int NOT NULL DEFAULT 1 COMMENT '0=Belum verifikasi, 1= Aktif, 2= Block, 3= tidak aktif',
  `is_verified` int NOT NULL DEFAULT 0,
  `privacy` int NULL DEFAULT 0,
  `fcm_token` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `login_with` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `access` int NULL DEFAULT 2,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_user`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8487 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_users_activation
-- ----------------------------
DROP TABLE IF EXISTS `arif_users_activation`;
CREATE TABLE `arif_users_activation`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `activation_token` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8086 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_users_level
-- ----------------------------
DROP TABLE IF EXISTS `arif_users_level`;
CREATE TABLE `arif_users_level`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_users_report
-- ----------------------------
DROP TABLE IF EXISTS `arif_users_report`;
CREATE TABLE `arif_users_report`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_user` int NOT NULL,
  `id_user_report` int NOT NULL,
  `reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `date_time` datetime NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_users_status
-- ----------------------------
DROP TABLE IF EXISTS `arif_users_status`;
CREATE TABLE `arif_users_status`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for arif_year
-- ----------------------------
DROP TABLE IF EXISTS `arif_year`;
CREATE TABLE `arif_year`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` year NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for auth_tokens
-- ----------------------------
DROP TABLE IF EXISTS `auth_tokens`;
CREATE TABLE `auth_tokens`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `expiry_date` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8418 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for employer_administrator
-- ----------------------------
DROP TABLE IF EXISTS `employer_administrator`;
CREATE TABLE `employer_administrator`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `foto` char(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'admin-default.png',
  `nama` char(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `alamat` varchar(1000) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `email` char(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `telepon` char(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `username` char(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `createdBy` int NOT NULL COMMENT 'id superadmin, karena hanya superadmin yang berhak memanage administrator',
  `createdAt` datetime NOT NULL DEFAULT current_timestamp,
  `updatedBy` int NULL DEFAULT NULL COMMENT 'id superadmin, karena hanya superadmin yang berhak memanage administrator',
  `updatedAt` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for employer_administrator_log
-- ----------------------------
DROP TABLE IF EXISTS `employer_administrator_log`;
CREATE TABLE `employer_administrator_log`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` char(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `module` char(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'Module mana yang ditrigger',
  `idModule` int NOT NULL COMMENT 'ID Data mana yang ditrigger',
  `keterangan` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `createdBy` int NOT NULL COMMENT 'ID Administrator',
  `createdAt` datetime NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 58 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for employer_loker
-- ----------------------------
DROP TABLE IF EXISTS `employer_loker`;
CREATE TABLE `employer_loker`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `judul` char(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'HTML Content',
  `kategori` int NOT NULL COMMENT 'ID Kategori',
  `jenis` int NOT NULL COMMENT 'ID Jenis',
  `namaPIC` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `emailPIC` char(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `batasAwalPendaftaran` datetime NOT NULL,
  `batasAkhirPendaftaran` datetime NOT NULL,
  `kualifikasi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'HTML Content',
  `provinsi` int NOT NULL COMMENT 'ID Provinsi',
  `kota` char(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'ID Kota',
  `keterangan` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `gajiMinimum` bigint NOT NULL,
  `gajiMaximum` bigint NOT NULL,
  `benefit` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'HTML Content',
  `paket` int NOT NULL COMMENT 'ID Paket yang digunakan',
  `createdBy` int NOT NULL COMMENT 'ID Mitra',
  `createdAt` datetime NOT NULL DEFAULT current_timestamp,
  `expiredAt` datetime NOT NULL COMMENT 'Tanggal Kadaluarsa Loker (tanggal loker dibuat + sisa hari paket berlaku)',
  `visibility` enum('visible','invisible') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'Override visibility dari perbandingan (expiredAt >= current date) jika != null',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for employer_mitra_log
-- ----------------------------
DROP TABLE IF EXISTS `employer_mitra_log`;
CREATE TABLE `employer_mitra_log`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` char(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `module` char(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'Module mana yang ditrigger',
  `idModule` int NOT NULL COMMENT 'ID Data mana yang ditrigger',
  `keterangan` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `createdBy` int NOT NULL COMMENT 'ID Mitra',
  `createdAt` datetime NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 27 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for employer_paket
-- ----------------------------
DROP TABLE IF EXISTS `employer_paket`;
CREATE TABLE `employer_paket`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` char(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama` char(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `durasi` tinyint NOT NULL COMMENT 'Satuan Hari',
  `harga` bigint NOT NULL,
  `keterangan` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `createdBy` int NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp,
  `updatedBy` int NULL DEFAULT NULL,
  `updatedAt` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for employer_transaksi
-- ----------------------------
DROP TABLE IF EXISTS `employer_transaksi`;
CREATE TABLE `employer_transaksi`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `nomor` char(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `mitra` int NOT NULL COMMENT 'ID Mitra',
  `paket` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'ID Paket',
  `buktiBayar` char(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `berlakuMulai` datetime NOT NULL,
  `berlakuSampai` datetime NOT NULL,
  `harga` bigint NOT NULL,
  `ppn` bigint NOT NULL,
  `approvement` enum('approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'Null = belum diterima atau ditolak, perlu approvement dari administrator',
  `approvementReason` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'Keterangan tentang approvement. Misal ketika ditolak akan diberi penjelasan kenapa di tolak',
  `approvementBy` int NULL DEFAULT NULL COMMENT 'ID Administrator',
  `approvementAt` datetime NULL DEFAULT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp,
  `stackedBy` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'Jika tertimpa dengan pembelian paket yang baru akan berisi dengan ID Transaksi (transaksi baru yang diapprove)',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tbl_referral_code
-- ----------------------------
DROP TABLE IF EXISTS `tbl_referral_code`;
CREATE TABLE `tbl_referral_code`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `referral_code` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tbl_utm
-- ----------------------------
DROP TABLE IF EXISTS `tbl_utm`;
CREATE TABLE `tbl_utm`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL COMMENT 'pemilik referral code/utm_content',
  `utm_content` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `utm_campaign` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `utm_source` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `manufacturer` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `device` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 23 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- View structure for kandidat
-- ----------------------------
DROP VIEW IF EXISTS `kandidat`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `kandidat` AS SELECT
	arif_users.id_user AS id, 
	arif_users.foto_profile AS foto, 
	arif_users.full_name AS nama, 
	arif_users.brith_date as tanggalLahir, 
	arif_users.gender as jenisKelamin, 
	arif_users.religion as agama, 
	arif_users.email, 
	arif_users.phone as telepon, 
	arif_users.address as alamat, 
	arif_users.country as negara, 
	arif_users.province as provinsi, 
	arif_users.city as kota, 
	arif_users.school as sekolah, 
	arif_users.experience as pengalaman, 
	arif_users.username, 
	arif_users.`password`, 
	arif_users.menikah as statusPernikahan, 
	arif_users.work_status as statusPekerjaan, 
	arif_users.fcm_token as fcmToken, 
	arif_users.create_at as tanggalTerdaftar
FROM
	arif_users ;

-- ----------------------------
-- View structure for loker_apply
-- ----------------------------
DROP VIEW IF EXISTS `loker_apply`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `loker_apply` AS SELECT
	arif_loker_history_applay.history_id as id, 
	arif_loker_history_applay.loker_id as loker, 
	arif_loker_history_applay.user_id as kandidat, 
	arif_loker_history_applay.`status`, 
	arif_loker_history_applay.create_at as createdAt
FROM
	arif_loker_history_applay ;

-- ----------------------------
-- View structure for mitra
-- ----------------------------
DROP VIEW IF EXISTS `mitra`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `mitra` AS SELECT
	arif_company.company_id AS id, 
	arif_company.employer_foto as foto,
	arif_company.`name` AS nama, 
	arif_company.cover AS cover, 
	arif_company.address AS alamat, 
	arif_company.phone AS telepon, 
	arif_company.category AS category, 
	arif_company.total_employe AS total_employe, 
	arif_company.pic_name AS pic_name, 
	arif_company.email AS email, 
	arif_company.sector AS sector, 
	arif_company.employer_username AS username, 
	arif_company.employer_password AS `password`, 
	arif_company.employer_approvement AS approvement, 
	arif_company.employer_approvementReason AS approvementReason, 
	arif_company.employer_approvementBy AS approvementBy, 
	arif_company.employer_approvementAt AS approvementAt, 
	arif_company.employer_createdAt AS createdAt
FROM
	arif_company ;

SET FOREIGN_KEY_CHECKS = 1;

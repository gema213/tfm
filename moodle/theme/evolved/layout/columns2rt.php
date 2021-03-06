<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

// Get the HTML for the settings bits.
$html = theme_clean_get_html_for_settings($OUTPUT, $PAGE);

$left = (!right_to_left());
echo $OUTPUT->doctype() ?>
<html <?php echo $OUTPUT->htmlattributes(); ?>>
<head>
    <title><?php echo $OUTPUT->page_title(); ?></title>
    <link rel="shortcut icon" href="<?php echo $OUTPUT->favicon(); ?>" />
    <?php echo $OUTPUT->standard_head_html() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <!-- Google web fonts -->
    <?php require_once(dirname(__FILE__).'/includes/fonts.php'); ?>
<link href="//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.css" rel="stylesheet" type='text/css' />
</head>

<body <?php echo $OUTPUT->body_attributes('two-column'); ?>>
<?php echo $OUTPUT->standard_top_of_body_html() ?>

<header role="banner" class="navbar navbar-fixed-top<?php echo $html->navbarclass ?>">
    <nav role="navigation" class="navbar-inner">
        <div class="container-fluid">
            <a class="brand" href="<?php echo $CFG->wwwroot;?>"><?php echo
                format_string($SITE->shortname, true, array('context' => context_course::instance(SITEID)));
                ?></a>
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <?php echo $OUTPUT->user_menu(); ?>
            <div class="nav-collapse collapse">
                <?php echo $OUTPUT->custom_menu(); ?>
                <ul class="nav pull-right">
                    <li><?php echo $OUTPUT->page_heading_menu(); ?></li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<div id="page" class="container-fluid">

    <header id="page-header" class="clearfix">
        <div id="page-navbar" class="clearfix">
            <div class="breadcrumb-nav"><?php echo $OUTPUT->navbar(); ?></div>
            <nav class="breadcrumb-button"></nav>
        </div>
    </header>

    <div id="page-content" class="row-fluid">

    <?php if (!$left) { ?>
    <section id="region-main" class="span9 pull-left">
    <?php } else { ?>

    <section id="region-main" class="span9">
    <div class="course-title">
         <div id="editbutton">
      <?php echo $OUTPUT->page_heading_button(); ?>
      </div>
    <?php echo $html->heading; ?>
    </div>
        <div id="course-header">
            <?php echo $OUTPUT->course_header(); ?>
        </div>
    <?php } ?>

        <?php
        echo $OUTPUT->course_content_header();
        echo $OUTPUT->main_content();
        echo $OUTPUT->course_content_footer();
        ?>
    </section>

        <?php echo $OUTPUT->blocks('side-pre', 'span3'); ?>
        <?php echo $OUTPUT->blocks('side-post', 'span3 pull-right'); ?>
    </div>

     <footer id="page-footer">
        <!-- Start Marketing Spots -->
    <?php 
        if($PAGE->theme->settings->togglemarketing==1) {
            require_once(dirname(__FILE__).'/includes/marketingspots.php');
        } else if($PAGE->theme->settings->togglemarketing==2 && !isloggedin()) {
            require_once(dirname(__FILE__).'/includes/marketingspots.php');
        } else if($PAGE->theme->settings->togglemarketing==3 && isloggedin()) {
            require_once(dirname(__FILE__).'/includes/marketingspots.php');
        } 
    ?>
    <!-- End Marketing Spots -->
        <div id="course-footer"><?php echo $OUTPUT->course_footer(); ?></div>
        <p class="helplink"><?php echo $OUTPUT->page_doc_link(); ?></p>
        <?php
        echo $html->footnote;
        echo $OUTPUT->login_info();
        echo $OUTPUT->home_link();
        echo $OUTPUT->standard_footer_html();
        ?>

    </footer>

 <?php echo $OUTPUT->standard_end_of_body_html() ?>

</div>
</body>
</html>
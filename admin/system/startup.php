<?php

// OGMA Startup script
// 

// Register the Bootstrap and Ogma CSS files
//


$styles = array(
    "bootstrap" => "../3rdparty/bootstrap3/css/bootstrap.min.css",
    "font-awesome" => "../3rdparty/font-awesome/css/font-awesome.min.css",
    "bootstrap-datepicker" => "../3rdparty/bootstrap-datepicker/css/bootstrap-datetimepicker.css",
    "bootstrap-toggle" => "../3rdparty/bootstrap-toggle/css/bootstrap-toggle.min.css",
    "codemirror" => "../3rdparty/codemirror/lib/codemirror.css",
    "codemirrortheme" => "../3rdparty/codemirror/theme/default.css"
);

$scripts = array(
    "jquery" => "../3rdparty/jquery/jquery.min.js",
    "bootstrap" => "../3rdparty/bootstrap3/js/bootstrap.min.js",
    "bootstrap-datepicker" => "../3rdparty/bootstrap-datepicker/js/bootstrap-datetimepicker.js",
    "bootstrap-toggle" => "../3rdparty/bootstrap-toggle/js/bootstrap-toggle.min.js",
    "codemirror" => "../3rdparty/codemirror/lib/codemirror-compressed.js"
);

if (Core::$site['cdn']) {
    $styles['bootstrap']    = "//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css";
    $scripts['bootstrap']   = "//netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js";
    $scripts['jquery']      = "//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js";
    $styles['font-awesome'] = "//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css";
}

Stylesheet::add($styles['bootstrap'], "backend", 1);

if (file_exists('../3rdparty/bootswatch/bootswatch.css')) {
    Stylesheet::add('../3rdparty/bootswatch/bootswatch.css', "backend", 1);
}
Stylesheet::add($styles['font-awesome'], "backend", 1);
Stylesheet::add("template/css/ogma.css", "backend", 2);
Stylesheet::add($styles['bootstrap-datepicker'], "backend", 3);
Stylesheet::add($styles['bootstrap-toggle'], "backend", 5);
Stylesheet::add("../3rdparty/bootstrap-tags/bootstrap-tagsinput.css", "backend", 6);
Stylesheet::add("../3rdparty/bootstrap-icon-picker/css/icon-picker.css", "backend", 7);
Stylesheet::add($styles['codemirror'], "backend", 7);
Stylesheet::add($styles['codemirrortheme'], "backend", 8);
Stylesheet::add("../3rdparty/dropzone/css/dropzone.css", "backend", 9);
Stylesheet::add("../3rdparty/bootstrap-multiselect/bootstrap-multiselect.css", "backend", 10);

Actions::addAction('admin-header', 'Stylesheet::show', 1, array(
    'backend'
));

Scripts::add($scripts['jquery'], "backend", 1);
Scripts::add($scripts['bootstrap'], "backend", 2);
Scripts::add($scripts['bootstrap-datepicker'], "backend", 3);
Scripts::add("template/js/jquery.nestable.js", "backend", 4);
Scripts::add("template/js/fm.js", "backend", 5);
Scripts::add("../3rdparty/dropzone/dropzone.js", "backend", 5);
Scripts::add("../3rdparty/bootstrap-tags/bootstrap-tagsinput.min.js", "backend", 6);
Scripts::add("../3rdparty/bootstrap-lightbox/ekko-lightbox.js", "backend", 7);
Scripts::add("../3rdparty/bootstrap-icon-picker/js/iconPicker.js", "backend", 8);
Scripts::add("template/js/jquery.spinedit.js", "backend", 9);
Scripts::add($scripts['bootstrap-toggle'], "backend", 10);

Scripts::add("template/js/jquery.validation.js", "backend", 11);
Scripts::add("template/js/jquery.ddslick.js", "backend", 12);
Scripts::add("template/js/jquery.sortable.js", "backend", 13);

Scripts::add($scripts['codemirror'], "backend", 14);

Scripts::add("template/js/rangy.inputs.js", "backend", 15);

Scripts::add("template/js/jconfirm.jquery.js", "backend", 16);
Scripts::add("../3rdparty/bootstrap-multiselect/bootstrap-multiselect.js", "backend", 17);

Scripts::add("template/js/ogma.js", "backend", 18);
Scripts::add("template/js/ogmaeditor.js", "backend", 19);

Actions::addAction('admin-header', 'Scripts::show', 1, array(
    'backend'
));

Actions::addAction('index-header', 'Stylesheet::show', 1, array(
    'frontend'
));
Actions::addAction('index-header', 'Scripts::show', 1, array(
    'frontend'
));
Actions::addAction('index-footer', 'Scripts::show', 1, array(
    'frontend',
    true
));
// load shortcodes and apply filter to 'content'
Core::autoloadShortcodes();

Filters::addFilter('content', 'Shortcodes::doShortcode');

?>
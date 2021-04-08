/**
 * @license Copyright (c) 2003-2019, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */
CKEDITOR.dtd['blockquote']['div'] = 1;
CKEDITOR.editorConfig = function( config ) {
    config.extraPlugins = 'videoembed';
    config.allowedContent = true;
    config.toolbar_Common =
        [
            { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
            { name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent',
                    '-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ] },
            { name: 'links', items : [ 'Link','Unlink' ] },
            { name: 'insert', items : ['Image', 'Table','HorizontalRule','Smiley','SpecialChar','VideoEmbed' ] },
            '/',
            { name: 'styles', items : [ 'Styles','Format','Font','FontSize','my_styles' ] },
            { name: 'colors', items : [ 'TextColor'] },
        ];
    config.stylesSet = 'my_styles';
};

CKEDITOR.stylesSet.add( 'my_styles', [
    // Block-level styles.
    { name: 'Forum blockquote', element: 'blockquote', attributes: { 'class': 'blockquote'} },
    { name: 'Citation', element: 'div', attributes: { 'class': 'citation'} },
    { name: 'citationLink', element: 'div', attributes: { 'class': 'citation_link'} },
    { name: 'Quote Content', element: 'div', attributes: { 'class': 'quoted_content'} }
]);
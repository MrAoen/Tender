/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

//promios
CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	config.language = 'ru';
	config.format_tags = 'p;h1;h2;h3;h4';
	config.contentsCss = '/css/editor.min.css';
	config.font_names = 'Tahoma;Arial;Verdana;Trebuchet MS;Helvetica;Georgia';
	config.justifyClasses = ['left','center','right','justify'];
	config.pasteFromWordIgnoreFontFace = true;
	config.pasteFromWordRemoveStyle = true;
	config.toolbar_Full =
	[
		['Cut','Copy','Paste','PasteText','PasteFromWord'],
		['Print','SpellChecker','Scayt'],
		['Undo','Redo'],
		['Find','Replace'],
		['SelectAll','RemoveFormat'],
		['Image','Flash','Table','SpecialChar'],
		['Link','Unlink','Anchor'],
		['Bold','Italic','Strike','Subscript','Superscript'],
		['JustifyLeft','JustifyCenter','JustifyRight','BulletedList'],
		['Format','TextColor'],
		//['Form','TextField','Textarea','ImageButton'],
		['Source','Preview','Maximize']
	];
};

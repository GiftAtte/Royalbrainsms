/**
 * @license Copyright (c) 2014-2020, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
 */

import CKFinderUploadAdapter from '@ckeditor/ckeditor5-adapter-ckfinder/uploadadapter.js';
import Essentials from '@ckeditor/ckeditor5-essentials/essentials.js';
import ExportToPDF from '@ckeditor/ckeditor5-export-pdf/exportpdf.js';

import Image from '@ckeditor/ckeditor5-image/image.js';
import ImageCaption from '@ckeditor/ckeditor5-image/imagecaption.js';
import ImageResize from '@ckeditor/ckeditor5-image/imageresize.js';
import ImageStyle from '@ckeditor/ckeditor5-image/imagestyle.js';
import ImageToolbar from '@ckeditor/ckeditor5-image/imagetoolbar.js';
import ImageUpload from '@ckeditor/ckeditor5-image/imageupload.js';

import MediaEmbed from '@ckeditor/ckeditor5-media-embed/mediaembed.js';
import MediaEmbedToolbar from '@ckeditor/ckeditor5-media-embed/mediaembedtoolbar.js';


import SpecialCharacters from '@ckeditor/ckeditor5-special-characters/src/specialcharacters.js';
import SpecialCharactersCurrency from '@ckeditor/ckeditor5-special-characters/specialcharacterscurrency.js';
import SpecialCharactersLatin from '@ckeditor/ckeditor5-special-characters/specialcharacterslatin.js';
import SpecialCharactersMathematical from '@ckeditor/ckeditor5-special-characters/specialcharactersmathematical.js';
import SpecialCharactersText from '@ckeditor/ckeditor5-special-characters/specialcharacterstext.js';


import Subscript from '@ckeditor/ckeditor5-basic-styles/subscript.js';
import Superscript from '@ckeditor/ckeditor5-basic-styles/superscript.js';

class Editor extends DecoupledDocumentEditor {}

// Plugins to include in the build.
Editor.builtinPlugins = [
	Alignment,
	Autoformat,
	BlockQuote,
	Bold,
	CKFinder,
	CKFinderUploadAdapter,
	Essentials,
	ExportToPDF,
	FontColor,
	FontFamily,
	FontSize,
	Heading,
	Highlight,
	HorizontalLine,
	Image,
	ImageCaption,
	ImageResize,
	ImageStyle,
	ImageToolbar,
	ImageUpload,
	Indent,
	IndentBlock,
	Italic,
	Link,
	List,
	MathType,
	MediaEmbed,
	MediaEmbedToolbar,
	Mention,
	Paragraph,
	PasteFromOffice,
	SpecialCharacters,
	SpecialCharactersCurrency,
	SpecialCharactersLatin,
	SpecialCharactersMathematical,
	SpecialCharactersText,
	StandardEditingMode,
	Strikethrough,
	Subscript,
	Superscript,
	Table,
	TableCellProperties,
	TableProperties,
	TableToolbar,
	TextTransformation,
	Title,
	TodoList,
	Underline
];

export default Editor;

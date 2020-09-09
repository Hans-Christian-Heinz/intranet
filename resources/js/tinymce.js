import tinymce from 'tinymce/tinymce';
//importing an icon as well as a theme is necessary
import 'tinymce/icons/default';
import 'tinymce/themes/silver';
import 'tinymce/plugins/lists';
import 'tinymce/plugins/advlist';
import 'tinymce/plugins/code';
import 'tinymce/plugins/table';

//initialize tinymce
tinymce.init({
    selector: 'textarea.section-content',
    skin_url: '/js/skins/ui/oxide',
    plugins: 'code, table, lists, advlist',
    toolbar: 'undo redo styleselect bold italic alignleft aligncenter alignright alignjustify bullist numlist outdent indent code table',
    table_header_type: 'cells'
});

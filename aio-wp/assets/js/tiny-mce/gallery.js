tinymce.PluginManager.add('mce_gallery', function (editor) {
    editor.addButton('gallery', {
        title: 'Gallery',
        icon: 'gallery',
        onclick: function () {
            openMediaModal(editor);
        }
    });

    function openMediaModal(editor) {
        let frame = wp.media({
            multiple: 'add'
        });

        frame.on('select', function () {
            var selection = frame.state().get('selection');
            insertGallery(editor, selection);
        });

        frame.open();
    }

    function insertGallery(editor, selection) {
        let shortcode = '[gallery ids="' + selection.pluck('id').join(',') + '"]';
        editor.execCommand('mceInsertContent', false, shortcode);
    }
});
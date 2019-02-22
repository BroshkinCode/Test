CSV_LOADER = {
    form: null,
    trigger: null,
    file: null,
    loader: null,
    warning: null,
    init: function (form) {
        this.form = form;
        this.trigger = $(this.form).find('.js-file-select-trigger');
        this.file = $(this.form).find('.js-file-select');
        this.loader = $(this.form).find('.js-upload-loader');
        this.warning = $('#CSV_WARN');

        $(this.trigger).click(this.startSelection);
        $(this.file).change(this.fileSelected);
    },
    startSelection: function(event) {
        $(CSV_LOADER.file).trigger('click');
    },
    fileSelected: function(event) {
        let fsize = event.target.files[0].size / (1024 * 2);
        if (fsize > 1) {
            $(CSV_LOADER.file).val('');
            CSV_LOADER.showWarning('File should be 1mb or less!');
            return;
        }
        if (event.target.files[0].name.split('.').pop() !== 'csv') {
            $(CSV_LOADER.file).val('');
            CSV_LOADER.showWarning('File should be csv');
            return;
        }
        $(CSV_LOADER.loader).show();
        $(CSV_LOADER.trigger).addClass('disabled');
        $(CSV_LOADER.form).submit();
    },
    showWarning: function (text) {
        $(this.warning).find('.js-text').html(text);
        $(this.warning).modal('show');
    }
}

$(document).ready((event) => {
    CSV_LOADER.init($('.js-upload-form'));
});
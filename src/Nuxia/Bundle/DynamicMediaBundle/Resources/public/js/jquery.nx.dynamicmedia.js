jQuery.fn.nx = {};

var $AjaxInterface = $('body');

function setRte(textarea, url) {
    var dynamic_media = document.createElement('div');
    $dynamic_media = $(dynamic_media);
    $dynamic_media.insertAfter(textarea);
    $dynamic_media.extend(true, dynamicMedia).init({ 'url': url });
    $dynamic_media.setWidth(textarea.outerWidth());
    CKEDITOR.replace(textarea.attr('id'), { width: (textarea.outerWidth() - 2) });
}

var dynamicMedia = {
    opts: {},
    init: function (opts, callback) {
        var $this = this;
        $.extend(this.opts, opts);
        $this.attr('id', 'dynamic_media');
        $this.load($this.opts.url, function () {
            $this.click();
            $this.paginate();
            $this.search();
            $this.reset();
            $this.liveSelect();
            $this.copy();
        });
        $this.click();
        if (callback != null) callback();
    },
    setWidth: function (width) {
        $(this).width(width);
    },
    click: function () {
        var $this = this;
        $AjaxInterface.on('click', '#dynamic_media .media_list li', function (e) {
            $('#dynamic_media .medias li.active').removeClass('active');
            $this.addClass('active');
            $this.toggleLink($(this).children('a').attr('href'));
        });
    },
    paginate: function () {
        var $this = this;
        $AjaxInterface.on('click', '#dynamic_media .nx-dynmedia-pagination a', function (e) {
            e.preventDefault();
            $this.toggleLink();
            $('#dynamic_media .medias').load($(this).attr('href'));
        });
    },
    search: function () {
        var $this = this;
        var $searchInput = $('#dynamic_media .media_filters input[type="text"]');
        var searchLoad = function () {
            $('#dynamic_media .medias').load($this.opts.url + '?' + $searchInput.attr('name') + '=' + encodeURIComponent($searchInput.val()));
        };
        $AjaxInterface.on('click', '#dynamic_media .media_filters a.submit', function (e) {
            e.preventDefault();
            $this.toggleLink();
            searchLoad();
        });
        $AjaxInterface.on('keypress', $searchInput, function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                $this.toggleLink();
                searchLoad();
            }
        });
    },
    reset: function () {
        var $this = this;
        $AjaxInterface.on('click', '#dynamic_media .media_filters a.reset', function (e) {
            e.preventDefault();
            $this.toggleLink();
            $('#dynamic_media .media_filters input[type="text"]').val('');
            $('#dynamic_media .media_filters a.submit').click();
        });
    },
    liveSelect: function () {
        var $this = this;
        $AjaxInterface.on('click', '#dynamic_media .media_link input[type="text"].link', function (e) {
            $this.selectLink();
        });
    },
    selectLink: function () {
        $('#dynamic_media .media_link input[type="text"].link').focus().select();
    },
    copy: function () {
        var $this = this;
        $AjaxInterface.on('click', '#dynamic_media .media_link a.copy', function (e) {
            e.preventDefault();
            $this.selectLink();
        });
    },
    toggleLink: function (html) {
        if (html != null) {
            $('#dynamic_media .media_link input[type="text"].link').val(html);
        } else {
            $('#dynamic_media .media_link input[type="text"].link').val('');
        }
    }
};

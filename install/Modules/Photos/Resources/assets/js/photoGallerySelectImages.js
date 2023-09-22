"use strict";

(function( $ ) {
    window.photoGalleryTarget = 'photo';
    const modal = '#select-image-galley-modal';
    const imageContainer = '#image-gallery';
    const formUpload = '#photo-gallery-upload-form';
    var images = [], dataImages = [], maxSize = 2000000;

    const uid = function(){
        return Date.now().toString(36) + Math.random().toString(36).substr(2);
    }

    $.extend({
        getUrlVars: function(url){
            var vars = [], hash;
            var hashes = url.slice(url.indexOf('?') + 1).split('&');
            for(var i = 0; i < hashes.length; i++)
            {
            hash = hashes[i].split('=');
            vars.push(hash[0]);
            vars[hash[0]] = hash[1];
            }
            return vars;
        },
        getUrlVar: function(url, name){
            return $.getUrlVars(url)[name];
        }
    });

    const Api = function(s) {
        this.setImages = function(images) {
            $(s).find('ul').html('');
            for (let index = 0; index < images.length; index++) {
                const img = images[index];
                var html = `<li class="list-group-item" data-id="${img.id}">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <div class="gallery-photo-preview">
                                            <img class="rounded" src="${img.preview_url}" alt="${img.name}" data-dz-thumbnail="" data-src="${img.original_url}">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <h6 class="text-sm mb-0 image-name" data-name="${img.name}">${img.name}</h6>
                                    </div>
                                    <div class="col-auto">
                                        <a href="javascript:void(0)" class="dropdown-item btn-remove-selected-gallery-image"><i class="fas fa-trash-alt"></i>
                                        </a>
                                    </div>
                                </div>
                            </li>`;
                $(s).find('ul').append(html);
            }
        };

        this.images = function() {
            return $(s).find('ul li').map(function() {
                return {
                    id: this.dataset.id,
                    name: $(this).find('.image-name').attr('data-name'),
                    preview_url: $(this).find('img').attr('src'),
                    original_url: $(this).find('img').data('src')
                };
            }).get();
        };

        return this;
    };

    const SGallery = function() {

        this.api = function() {
            return new Api(this);
        }

        this.each(function() {
            if ($(this).hasClass('image-gallery')) {
                return new Api(this);
            }

            this.classList.add('image-gallery');
            if (this.hasAttribute('data-image-gallery-multiple')) {
                this.setAttribute('data-image-gallery-multiple', true);
            } else {
                this.setAttribute('data-image-gallery-multiple', false);
            }

            this.setAttribute('data-image-gallery-open', false);
            const button = $('<button type="button" class="btn-open-gallery">'+app.trans('Open photos')+'</button>');

            $(this).prepend(button);
            if (!$(this).find('.list-group-item').length) {
                $(this).append($('<ul class="list-group"></ul>'));
            }
            const e = this;

            button.on('click', function() {
                if ($(modal).length) {
                    $(modal).modal('show');
                    $(this).closest('.image-gallery').attr('data-image-gallery-open', true);
                    window.photoGalleryTarget = 'photo';
                }
            });
        });

        return this;

    };

    const loadImages = function(array) {
        $(imageContainer).html('');
        if (!array.length) {
            $(imageContainer).append(`<div class="col-12 text-center">${app.trans('No photos')}</div>`);
        }
        for (let index = 0; index < array.length; index++) {
            const data = array[index];
            const html = `<div class="col-3 col-sm-3 d-flex align-items-center justify-content-center">
                            <label class="imagecheck mb-4">
                                <input name="photoImageGallerySelectItem" type="checkbox" value="${data.id}" class="imagecheck-input">
                                <figure class="imagecheck-figure">
                                    <img src="${data.generated_preview_url || data.preview_url}" alt="${data.name}" class="imagecheck-image" data-src="${data.original_url}">
                                </figure>
                            </label>
                        </div>`;
            $(imageContainer).append(html);
        }
    };

    const setPagination = function(data) {
        const paginatorEl = $('#select-image-galley-modal .photos-pagination');
        if (paginatorEl.length) {
            paginatorEl.find('ul').html('');
            $.each(data.links, function (i, item) {
                const li = $('<li class="page-item"></li>');
                if (!item.url) {
                    li.addClass('disabled');
                }

                if (item.active) {
                    li.addClass('active');
                }

                const a = $('<a class="page-link" href="javascript:void(0)" tabindex="-1">'+item.label+'</a>');
                if (item.url && !item.active) {
                    a.data('href', item.url);
                }
                li.append(a);

                paginatorEl.find('ul').append(li);
            });
        }
    };

    const getImages = function(page = 1) {
        const folder = $(modal).find('[name="gallery-folder-select"]').val();
        const route = `/admin/photos/gallery-select-list?folder=${folder}&page=${page}`;

        $.ajax({
            type: 'GET',
            url: route,
            beforeSend: function () {
                $(modal).find('.galley-image-loader').show();
                const paginatorEl = $('#select-image-galley-modal .pagination').closest('nav');
                if (paginatorEl.length) {
                    paginatorEl.find('.page-item:first-child').addClass('disabled');
                    paginatorEl.find('.page-item:last-child').addClass('disabled');
                }
            },
            success: function (response, textStatus, xhr) {
                images = response.data;
                loadImages(images);
                setPagination(response);
                setTimeout(function() {
                    $(modal).find('.galley-image-loader').hide();
                }, 1000);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $(modal).find('.galley-image-loader').hide();
                var response = XMLHttpRequest;
            }
        });
    };

    const getFolders = function() {
        $.ajax({
            type: 'GET',
            url: '/admin/photos/folders',
            success: function (response, textStatus, xhr) {
                if (response.length) {
                    const $folderSelect = $(modal).find('[name="gallery-folder-select"]');
                    $.each(response, function (i, item) {
                        var option = $('<option>', {
                            value: item.id,
                            text : item.name
                        });
                        $folderSelect.append(option);
                    });
                    $folderSelect.val('all').trigger('change');
                } else {
                    $(modal).find('.galley-image-loader').hide();
                    $(imageContainer).append(`<div class="col-12 text-center">${app.trans('No photos')}</div>`);
                }
            }
        });
    };

    if ($(modal).length) {
        getFolders();

        $(modal).on('hide.bs.modal', function (event) {
            $('[data-image-gallery-open="true"]').attr('data-image-gallery-open', false);
            $(modal).find('[name="photoImageGallerySelectItem"]:checked').map(function() {
                this.checked = false;
            });
        });

        $('#select-image-new-folder-dropdown').on('hidden.bs.dropdown', function () {
            app.formReset('#photo-new-folder-form');
        });

        $('.btn-image-gallery-select').on('click', function() {
            const selected = $(imageContainer).find('[name="photoImageGallerySelectItem"]:checked').map(function() {
                return parseInt(this.value);
            }).get();
            var selectedImages = images.filter(item => selected.includes(item.id));
            if (selectedImages.length) {
                var i = $('[data-image-gallery-open="true"]');
                if (i.length) {
                    i.find('ul').html('');
                    if (i.attr('data-image-gallery-multiple') === 'false') {
                        selectedImages = selectedImages.slice(0, 1);
                    }

                    for (let index = 0; index < selectedImages.length; index++) {
                        const img = selectedImages[index];
                        var html = `<li class="list-group-item" data-id="${img.id}">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <div class="gallery-photo-preview">
                                                    <img class="rounded" src="${img.preview_url}" alt="${img.name}" data-dz-thumbnail="" data-src="${img.original_url}">
                                                </div>
                                            </div>
                                            <div class="col overflow-hidden">
                                                <h6 class="text-sm mb-0 image-name" data-name="${img.name}">${img.name}</h6>
                                            </div>
                                            <div class="col-auto">
                                                <a href="javascript:void(0)" class="dropdown-item btn-remove-selected-gallery-image"><i class="fas fa-trash-alt"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </li>`;
                        i.find('ul').append(html);
                    }
                }

                $(modal).modal('hide');
            }
        });

        $(document).delegate('.btn-remove-selected-gallery-image', 'click', function() {
            $(this).closest('li.list-group-item').remove();
        });

        $(document).delegate('[name="photoImageGallerySelectItem"]', 'change', function() {
            const e = this;
            if (this.checked) {
                var i = $('[data-image-gallery-open="true"]');
                if (i.length) {
                    if (i.attr('data-image-gallery-multiple') === 'false') {
                        $('[name="photoImageGallerySelectItem"]:checked').map(function() {
                            if (this !== e) {
                                this.checked = false;
                            }
                        });
                    }
                } else {
                    $('[name="photoImageGallerySelectItem"]:checked').map(function() {
                        if (this !== e) {
                            this.checked = false;
                        }
                    });
                }
            }
        });

        $(document).delegate(modal + ' .page-link', 'click', function() {
            const route = $(this).data('href');
            if (route) {
                const page = $.getUrlVar(route, 'page');
                getImages(page);
            }
        });

        $(modal + ' [name="gallery-folder-select"]').on('change', function() {
            getImages();
            if (this.value !== 'all') {
                $('#select-image-upload-photos-dropdown .dropdown-toggle').attr('disabled', false).removeClass('disabled');
            } else {
                $('#select-image-upload-photos-dropdown .dropdown-toggle').attr('disabled', true).addClass('disabled');
                dataImages = [];
                $(formUpload).find('.photo-upload-images').html('');
            }
        });

        $(document).on('click', modal + ' .dropdown-menu', function (e) {
            e.stopPropagation();
        });

        $(modal + ' .btn-add-photo-gallery-upload').on('click', function() {
            $(this).closest('form').find('input').trigger('click');
        });

        $(formUpload+' [type="file"]').on('change', function(e) {
            const $form = $(this).closest('form');

            if (this.files.length) {
                for (let index = 0; index < this.files.length; index++) {
                    const file = this.files[index];

                    const d = {
                        id: uid(),
                        file: file
                    };

                    var reader = new FileReader();
                    reader.onload = function(event) {
                        var html = `<li class="list-group-item" data-id="${d.id}">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <div class="gallery-photo-preview">
                                                    <img class="rounded" src="${event.target.result}" alt="${d.file.name}">
                                                </div>
                                            </div>
                                            <div class="col-auto pl-0 ml-auto">
                                                <a href="javascript:void(0)" class="dropdown-item btn-remove-photo-upload-image">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </li>`;
                        $form.find('.photo-upload-images').append(html);
                        dataImages.push(d);
                    }

                    reader.readAsDataURL(d.file);
                }
            }

            $(this).val('');
        });

        $(document).delegate(formUpload+' .btn-remove-photo-upload-image', 'click', function() {
            const $li = $(this).closest('li');
            dataImages = dataImages.filter(item => item.id !== $li.data('id'));
            $li.remove();
        });

        $(formUpload).on('submit', function(e) {
            e.preventDefault();

            if (!$(formUpload).find('.photo-upload-images .list-group-item').length) {
                return;
            }

            if ($(formUpload).find('.photo-upload-images .list-group-item').length > 10) {
                app.formMessageHelper.showFormMessage(formUpload, app.trans('Upload must not greater than 10 photos.. Please remove some images to continue'), 413);
                $($self.config.modal).animate({scrollTop:0});
                return;
            }

            var $form = $(formUpload);
            var $button = $form.find(':submit');
            var $content = $button.html();

            var data = new FormData();
            var size = 0;

            data.append('folder', $(modal).find('[name="gallery-folder-select"]').val());
            $(formUpload).find('.photo-upload-images .list-group-item')
                .map(function(i, element) {
                    const id = $(element).data('id');
                    const file = dataImages.find(item => item.id === id);
                    size = size + file.file.size;
                    data.append(`photos[${i}]`, file.file);
                    if (file.file.size > maxSize) {
                        $(element).addClass('list-group-item-error');
                    }
                });

            $.ajax({
                type: 'POST',
                url: '/admin/photos/upload',
                data: data,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function () {
                    app.buttonLoader($button);
                    $form.find('.btn-add-photo-gallery-upload').attr('disabled', true).addClass('disabled');
                },
                success: function (response, textStatus, xhr) {
                    setTimeout(function() {
                        app.notify(response.message);
                        $form.parents('.btn-group').find('.dropdown-toggle').click();
                        $form.find('.photo-upload-images').html('');
                        app.backButtonContent($button, $content);
                        $form.find('.btn-add-photo-gallery-upload').attr('disabled', false).removeClass('disabled');
                        dataImages = [];
                        getImages();
                    }, 350);
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    var response = XMLHttpRequest;
                    // Check check if form validation errors
                    if (response.status === 413) {
                        app.formMessageHelper.showFormMessage(formUpload, response.statusText, response.status);
                    } else {
                        app.formErrors(formUpload, response.responseJSON, response.status);
                    }
                    // Reset button
                    app.backButtonContent($button, $content);
                    $form.find('.btn-add-photo-gallery-upload').attr('disabled', false).removeClass('disabled');
                }
            });
        });

    }

    $.fn.sgallery = SGallery;

    $.fn.sGallery = function ( opts ) {
		return $(this).sgallery( opts ).api();
	};

	$.each( SGallery, function ( prop, val ) {
		$.fn.sGallery[ prop ] = val;
	} );

    return $.fn.sgallery;

}( jQuery ));

$('[data-image-gallery]').sGallery();

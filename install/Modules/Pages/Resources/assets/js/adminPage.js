"use strict";

app.adminPage = {};

app.adminPage.sections = [];

app.adminPage.config = {
    sectionItemBtn: '.page-section-item-btn',
    sectionContainer: '#page-section-table',
    addSectionModal: '#add-page-section-modal',
    editSectionDetailsModal: '#admin-page-section-details-edit-modal',
    detailsModal: '.page-section-details-modal',
    removeSectionBtn: '.btn-remove-page-section',
    toggleSeoSetting: '.toggle-page-seo-settings',
    tagList: '.tag-list',
    sections: {
        hero: {
            default: {
                id: 0,
                name: 'Hero',
                template: 'hero',
                heading: 'Grow With Us',
                sub_heading: 'Making Project Faster',
                description: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias nesciunt, fugiat vitae doloribus repudiandae eos soluta unde voluptate architecto aperiam!',
                background_color: 'white',
                media_id: '',
                data: {}
            },
            modal: '#admin-page-hero-section-modal',
            form: '#admin-page-hero-section-form',
            addButton: '.btn-section-hero-add-button',
            removeButton: '.btn-remove-page-section-button-row',
            buttonList: '#section-hero-buttons',
            btnSectionDataSave: '.btn-save-page-hero-section-data',
            detailsModal: '#admin-page-hero-section-details-edit-modal',
            fillFormEdit: function(data) {
                $(this.buttonList).html('');
                $(this.form).find('[name="id"]').val(data.id);
                if (data.data.hasOwnProperty('buttons')) {
                    for (let index = 0; index < data.data.buttons.length; index++) {
                        const item = data.data.buttons[index];
                        $(this.buttonList).append(this.btnHtml(item));
                        $(`[data-tooltip="${item.id}"]`).tooltip({
                            container: '#admin-page-hero-section-modal'
                        });
                    }
                }
            },
            init: function() {
                this.initAddButton();
                this.initRemoveButton();
                this.saveSectionData();
            },
            initAddButton: function() {
                const self = this;
                $(self.addButton).on('click', function() {
                    const id = parseInt($(self.form).find('[name="id"]').val());
                    app.adminPage.sections = app.adminPage.sections.map(function(item) {
                        if (item.id === id) {
                            if (!item.data.hasOwnProperty('buttons')) {
                                item.data.buttons = [];
                            }
                            const button = {
                                id: app.adminPage.getId(),
                                label: '',
                                primary: true,
                                link: '',
                                new_tab: false
                            };
                            $(self.buttonList).append(self.btnHtml(button));
                        }
                        return item;
                    });
                });
            },
            initRemoveButton: function() {
                const self = this;
                $(document).delegate(self.removeButton, 'click', function() {
                    const row = $(this).closest('li');
                    const id = parseInt(row.data('id'));
                    const sectionId = parseInt($(self.form).find('[name="id"]').val());
                    app.adminPage.sections = app.adminPage.sections.map(function(item) {
                        if (item.id === sectionId) {
                            item.data.buttons = item.data.buttons.filter(btn => btn.id !== id);
                        }
                        return item;
                    });

                    row.remove();
                });
            },
            btnHtml: function(data) {
                return `<li class="data-list-item" data-id="${data.id}" id="button-list-${data.id}">
                            <table>
                                <tbody>
                                    <tr>
                                        <td colspan="2" class="pl-2">
                                            <a href="javascript:void(0)" role="button" class="btn btn-sm text-danger btn-remove-page-section-button-row" data-tooltip="${data.id}" title="Remove">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="lh-36px" style="width: 160px;">
                                            <span class="px-3">${app.trans('Label')}</span>
                                        </td>
                                        <td class="text-right">
                                            <input type="text" class="form-control" name="label" value="${data.label}" placeholder="${app.trans('Read more')}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="lh-36px" style="width: 160px;">
                                            <span class="px-3">${app.trans('Link')}</span>
                                        </td>
                                        <td class="text-right">
                                            <input type="text" class="form-control" name="link" value="${data.link}" placeholder="https://example.com">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="lh-34px" style="width: 160px;">
                                            <span class="px-3">${app.trans('Display as Primary')}</span>
                                        </td>
                                        <td>
                                            <div class="form-group custom-control custom-checkbox mx-0">
                                                <input type="checkbox" class="custom-control-input btn_primary" id="btn_primary_${data.id}" ${data.primary ? 'checked=""' : ''}>
                                                <label class="custom-control-label" for="btn_primary_${data.id}"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="lh-34px" style="width: 160px;">
                                            <span class="px-3">${app.trans('Open in new tab')}</span>
                                        </td>
                                        <td>
                                            <div class="form-group custom-control custom-checkbox mx-0">
                                                <input type="checkbox" class="custom-control-input btn_new_tab" id="btn_new_tab_${data.id}" ${data.new_tab ? 'checked=""' : ''}>
                                                <label class="custom-control-label" for="btn_new_tab_${data.id}"></label>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </li>`;
            },
            saveSectionData: function() {
                const self = this;
                $(self.btnSectionDataSave).on('click', function() {
                    const list = $(self.buttonList).find('li.data-list-item').map(function() {
                        return {
                            id: this.dataset.id,
                            label: $(this).find('[name="label"]').val(),
                            primary: $(this).find('.btn_primary:checked').length ? true: false,
                            link: $(this).find('[name="link"]').val(),
                            new_tab: $(this).find('.btn_new_tab:checked').length ? true: false
                        };
                    }).get();

                    const id = parseInt($(self.form).find('[name="id"]').val());
                    app.adminPage.sections = app.adminPage.sections.map(function(item) {
                        if (item.id === id) {
                            item.data.buttons = list;
                        }
                        return item;
                    });

                    $(self.modal).modal('hide');
                });
            }
        },
        about_us: {
            default: {
                id: 0,
                name: 'About us',
                template: 'about_us',
                heading: 'Know us better',
                sub_heading: 'Who we are',
                description: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias nesciunt, fugiat vitae doloribus repudiandae eos soluta unde voluptate architecto aperiam!',
                background_color: 'white',
                media_id: '',
                data: {
                    label: '',
                    link: '',
                    new_tab: 1,
                    primary: 0
                }
            },
            modal: '#admin-page-about-us-section-modal',
            form: '#admin-page-about-us-section-form',
            btnSave: '.btn-save-page-about-us-section-data',
            init: function() {
                this.saveSectionData();
            },
            fillFormEdit: function(json) {
                const data = json.data;
                $(this.form).find('[name="id"]').val(json.id);
                $(this.form).find('[name="label"]').val(data.label);
                $(this.form).find('[name="link"]').val(data.link);
                $(this.form).find('[name="new_tab"]').attr('checked', data.new_tab ? true : false);
                $(this.form).find('[name="primary"]').attr('checked', data.primary ? true : false);
            },
            saveSectionData: function() {
                const self = this;
                $(self.btnSave).on('click', function() {
                    const id = parseInt($(self.form).find('[name="id"]').val());
                    const data = $(self.form).serializeArray();
                    app.adminPage.sections = app.adminPage.sections.map(function(item) {
                        if (item.id === id) {
                            item.data.new_tab = 0;
                            item.data.primary = 0;
                            for (const [key, value] of Object.entries(item.data)) {
                                var i = data.find(it => it.name === key);
                                if (i) {
                                    item.data[key] = i.value;

                                    if (key === 'new_tab') {
                                        item.data[key] = 1;
                                    }

                                    if (key === 'primary') {
                                        item.data[key] = 1;
                                    }
                                }
                            }
                        }

                        return item;
                    });

                    $(self.modal).modal('hide');
                });
            }
        },
        statistics: {
            default: {
                id: 0,
                name: 'Statistics',
                template: 'statistics',
                heading: 'What our partners say about us',
                sub_heading: 'Our statistics',
                description: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias nesciunt, fugiat vitae doloribus repudiandae eos soluta unde voluptate architecto aperiam!',
                background_color: 'white',
                media_id: '',
                data: {}
            },
            modal: '#admin-page-statistics-section-modal',
            form: '#admin-page-statistics-section-form',
            btnSave: '.btn-save-page-statistics-section-data',
            dataItemList: '#section-statistics-data',
            btnAddDataItem: '.btn-section-statistics-add-data-item',
            btnRemoveDataItem: '.btn-remove-page-section-statistics-row',
            init: function() {
                this.initAddItem();
                this.initRemoveItem();
                this.saveSectionData();

                $(this.modal).on('shown.bs.modal', function (e) {
                    $(document).off('focusin.modal');
                });
            },
            fillFormEdit: function(data) {
                $(this.dataItemList).html('');
                $(this.form).find('[name="id"]').val(data.id);
                if (data.data.hasOwnProperty('items')) {
                    for (let index = 0; index < data.data.items.length; index++) {
                        const item = data.data.items[index];
                        $(this.dataItemList).append(this.itemHtml(item));

                        if(typeof $.fn.iconpicker !== 'undefined') {
                            $(`#icon-picker-statistics-${item.id}`).iconpicker().on('change', function(e) {
                                $(e.target).next().val(e.icon)
                            });
                        }
                    }
                }
            },
            initAddItem: function() {
                const self = this;
                $(self.btnAddDataItem).on('click', function() {
                    const id = parseInt($(self.form).find('[name="id"]').val());
                    app.adminPage.sections = app.adminPage.sections.map(function(item) {
                        if (item.id === id) {
                            if (!item.data.hasOwnProperty('items')) {
                                item.data.items = [];
                            }

                            const d = {
                                id: app.adminPage.getId(),
                                icon: 'fas fa-map-marker-alt',
                                title: '',
                                value: ''
                            };
                            $(self.dataItemList).append(self.itemHtml(d));

                            if(typeof $.fn.iconpicker !== 'undefined') {
                                $(`#icon-picker-statistics-${d.id}`).iconpicker().on('change', function(e) {
                                    $(e.target).next().val(e.icon)
                                });
                            }
                        }
                        return item;
                    });

                });
            },
            initRemoveItem: function() {
                const self = this;
                $(document).delegate(self.btnRemoveDataItem, 'click', function() {
                    const row = $(this).closest('li');
                    const id = parseInt(row.data('id'));
                    const sectionId = parseInt($(self.form).find('[name="id"]').val());
                    app.adminPage.sections = app.adminPage.sections.map(function(item) {
                        if (item.id === sectionId) {
                            item.data.items = item.data.items.filter(btn => btn.id !== id);
                        }
                        return item;
                    });

                    row.remove();
                });
            },
            saveSectionData: function() {
                const self = this;
                $(self.btnSave).on('click', function() {
                    const list = $(self.dataItemList).find('li.data-list-item').map(function() {
                        return {
                            id: this.dataset.id,
                            icon: $(this).find('[name="icon"]').val(),
                            title: $(this).find('[name="title"]').val(),
                            value: $(this).find('[name="value"]').val(),
                        };
                    }).get();

                    const id = parseInt($(self.form).find('[name="id"]').val());
                    app.adminPage.sections = app.adminPage.sections.map(function(item) {
                        if (item.id === id) {
                            item.data.items = list;
                        }
                        return item;
                    });

                    $(self.modal).modal('hide');
                });
            },
            itemHtml: function(data) {
                return `<li class="data-list-item" data-id="${data.id}">
                            <table>
                                <tbody>
                                    <tr>
                                        <td colspan="2" class="pl-2">
                                            <a href="javascript:void(0)" role="button" class="btn btn-sm text-danger btn-remove-page-section-statistics-row" data-toggle="tooltip" title="Remove">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="lh-36px" style="width: 70px;">
                                            <span class="px-3">${app.trans('Icon')}</span>
                                        </td>
                                        <td>
                                            <button class="btn btn-link" data-icon="${data.icon}" type="button" id="icon-picker-statistics-${data.id}"></button>
                                            <input type="text"
                                            name="icon"
                                            class="form-control display-none"
                                            value="${data.icon}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="lh-36px" style="width: 70px;">
                                            <span class="px-3">${app.trans('Title')}</span>
                                        </td>
                                        <td class="text-right">
                                            <input type="text" class="form-control" name="title" value="${data.title}" placeholder="${app.trans('Title')}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="lh-36px" style="width: 70px;">
                                            <span class="px-3">${app.trans('Value')}</span>
                                        </td>
                                        <td class="text-right">
                                            <input type="text" class="form-control" name="value" value="${data.value}" placeholder="0">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </li>`;
            }
        },
        recent_works: {
            default: {
                id: 0,
                name: 'Recent works',
                template: 'recent_works',
                heading: 'What we have accomplished',
                sub_heading: 'Recent works',
                description: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias nesciunt, fugiat vitae doloribus repudiandae eos soluta unde voluptate architecto aperiam!',
                background_color: 'white',
                media_id: '',
                data: {}
            },
            modal: '#admin-page-recent-works-section-modal',
            form: '#admin-page-recent-works-section-form',
            dataItemList: '#section-recent-works-data',
            btnSave: '.btn-save-page-recent-works-section-data',
            btnAddDataItem: '.btn-section-recent-works-add-data-item',
            btnRemoveDataItem: '.btn-remove-page-section-recent-works-row',
            init: function() {
                this.initAddItem();
                this.initRemoveItem();
                this.saveSectionData();
            },
            fillFormEdit: function(data) {
                $(this.dataItemList).html('');
                $(this.form).find('[name="id"]').val(data.id);
                if (data.data.hasOwnProperty('items')) {
                    for (let index = 0; index < data.data.items.length; index++) {
                        const item = data.data.items[index];
                        $(this.dataItemList).append(this.itemHtml(item));
                        $(`[data-image-gallery="${item.id}"]`).sGallery();
                        if (item.image) {
                            $(`[data-image-gallery="${item.id}"]`).sGallery().setImages([{
                                id: 0,
                                name: 'Preview',
                                preview_url: item.image,
                                original_url: item.image
                            }]);
                        }
                    }
                }
            },
            initAddItem: function() {
                const self = this;
                $(self.btnAddDataItem).on('click', function() {
                    const id = parseInt($(self.form).find('[name="id"]').val());
                    app.adminPage.sections = app.adminPage.sections.map(function(item) {
                        if (item.id === id) {
                            if (!item.data.hasOwnProperty('items')) {
                                item.data.items = [];
                            }

                            const d = {
                                id: app.adminPage.getId(),
                                image: '',
                                title: '',
                                description: '',
                                btn_label: '',
                                btn_link: ''
                            };
                            $(self.dataItemList).append(self.itemHtml(d));
                            $(`[data-image-gallery="${d.id}"]`).sGallery();
                        }
                        return item;
                    });
                });
            },
            initRemoveItem: function() {
                const self = this;
                $(document).delegate(self.btnRemoveDataItem, 'click', function() {
                    const row = $(this).closest('li');
                    const id = parseInt(row.data('id'));
                    const sectionId = parseInt($(self.form).find('[name="id"]').val());
                    app.adminPage.sections = app.adminPage.sections.map(function(item) {
                        if (item.id === sectionId) {
                            item.data.items = item.data.items.filter(btn => btn.id !== id);
                        }
                        return item;
                    });

                    row.remove();
                });
            },
            saveSectionData: function() {
                const self = this;
                $(self.btnSave).on('click', function() {
                    const list = $(self.dataItemList).find('li.data-list-item').map(function() {
                        var images = $(`[data-image-gallery="${this.dataset.id}"]`)
                            .sGallery()
                            .images();

                        return {
                            id: this.dataset.id,
                            image: images.length ? images[0].original_url : '',
                            title: $(this).find('[name="title"]').val(),
                            description: $(this).find('[name="description"]').val(),
                            btn_label: $(this).find('[name="btn_label"]').val(),
                            btn_link: $(this).find('[name="btn_link"]').val(),
                        };
                    }).get();

                    const id = parseInt($(self.form).find('[name="id"]').val());
                    app.adminPage.sections = app.adminPage.sections.map(function(item) {
                        if (item.id === id) {
                            item.data.items = list;
                        }
                        return item;
                    });

                    $(self.modal).modal('hide');
                });
            },
            itemHtml: function(data) {
                return `<li class="data-list-item" data-id="${data.id}">
                            <table>
                                <tbody>
                                    <tr>
                                        <td colspan="2" class="pl-2">
                                            <a href="javascript:void(0)" role="button" class="btn btn-sm text-danger btn-remove-page-section-recent-works-row" data-toggle="tooltip" title="Remove">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="p-3">
                                            <div data-image-gallery="${data.id}"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="lh-36px" style="width: 120px;">
                                            <span class="px-3">${app.trans('Title')}</span>
                                        </td>
                                        <td class="text-right">
                                            <input type="text" class="form-control" name="title" value="${data.title}" placeholder="${app.trans('Title')}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="lh-36px" style="width: 120px;">
                                            <span class="px-3">${app.trans('Description')}</span>
                                        </td>
                                        <td class="text-right">
                                            <input type="text" class="form-control" name="description" value="${data.description}" placeholder="${app.trans('Description')}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="lh-36px" style="width: 120px;">
                                            <span class="px-3">${app.trans('Button label')}</span>
                                        </td>
                                        <td class="text-right">
                                            <input type="text" class="form-control" name="btn_label" value="${data.btn_label}" placeholder="${app.trans('Read more')}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="lh-36px" style="width: 120px;">
                                            <span class="px-3">${app.trans('Button link')}</span>
                                        </td>
                                        <td class="text-right">
                                            <input type="text" class="form-control" name="btn_link" value="${data.btn_link}" placeholder="https://example.com">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </li>`;
            }
        },
        testimonial: {
            default: {
                id: 0,
                name: 'Testimonial',
                template: 'testimonial',
                heading: 'What our partners say about us',
                sub_heading: 'Testimonial ',
                description: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias nesciunt, fugiat vitae doloribus repudiandae eos soluta unde voluptate architecto aperiam!',
                background_color: 'white',
                media_id: '',
                data: {}
            },
            modal: '#admin-page-testimonial-section-modal',
            form: '#admin-page-testimonial-section-form',
            dataItemList: '#section-testimonial-data',
            btnSave: '.btn-save-page-testimonial-section-data',
            btnAddDataItem: '.btn-section-testimonial-add-data-item',
            btnRemoveDataItem: '.btn-remove-page-section-testimonial-row',
            init: function() {
                this.initAddItem();
                this.initRemoveItem();
                this.saveSectionData();
            },
            fillFormEdit: function(data) {
                $(this.dataItemList).html('');
                $(this.form).find('[name="id"]').val(data.id);
                if (data.data.hasOwnProperty('items')) {
                    for (let index = 0; index < data.data.items.length; index++) {
                        const item = data.data.items[index];
                        $(this.dataItemList).append(this.itemHtml(item));
                        $(`[data-image-gallery="${item.id}"]`).sGallery();
                        if (item.author_avatar) {
                            $(`[data-image-gallery="${item.id}"]`).sGallery().setImages([{
                                id: 0,
                                name: 'Preview',
                                preview_url: item.author_avatar,
                                original_url: item.author_avatar
                            }]);
                        }
                    }
                }
            },
            initAddItem: function() {
                const self = this;
                $(self.btnAddDataItem).on('click', function() {
                    const id = parseInt($(self.form).find('[name="id"]').val());
                    app.adminPage.sections = app.adminPage.sections.map(function(item) {
                        if (item.id === id) {
                            if (!item.data.hasOwnProperty('items')) {
                                item.data.items = [];
                            }

                            const d = {
                                id: app.adminPage.getId(),
                                message: '',
                                author_avatar: '',
                                author_name: '',
                                author_description: ''
                            };
                            $(self.dataItemList).append(self.itemHtml(d));
                            $(`[data-image-gallery="${d.id}"]`).sGallery();
                        }
                        return item;
                    });
                });
            },
            initRemoveItem: function() {
                const self = this;
                $(document).delegate(self.btnRemoveDataItem, 'click', function() {
                    const row = $(this).closest('li');
                    const id = parseInt(row.data('id'));
                    const sectionId = parseInt($(self.form).find('[name="id"]').val());
                    app.adminPage.sections = app.adminPage.sections.map(function(item) {
                        if (item.id === sectionId) {
                            item.data.items = item.data.items.filter(btn => btn.id !== id);
                        }
                        return item;
                    });

                    row.remove();
                });
            },
            saveSectionData: function() {
                const self = this;
                $(self.btnSave).on('click', function() {
                    const list = $(self.dataItemList).find('li.data-list-item').map(function() {
                        var images = $(`[data-image-gallery="${this.dataset.id}"]`)
                            .sGallery()
                            .images();

                        return {
                            id: this.dataset.id,
                            author_avatar: images.length ? images[0].original_url : '',
                            message: $(this).find('[name="message"]').val(),
                            author_name: $(this).find('[name="author_name"]').val(),
                            author_description: $(this).find('[name="author_description"]').val(),
                        };
                    }).get();

                    const id = parseInt($(self.form).find('[name="id"]').val());
                    app.adminPage.sections = app.adminPage.sections.map(function(item) {
                        if (item.id === id) {
                            item.data.items = list;
                        }
                        return item;
                    });

                    $(self.modal).modal('hide');
                });
            },
            itemHtml: function(data) {
                return `<li class="data-list-item" data-id="${data.id}">
                            <table>
                                <tbody>
                                    <tr>
                                        <td colspan="2" class="pl-2">
                                            <a href="javascript:void(0)" role="button" class="btn btn-sm text-danger btn-remove-page-section-testimonial-row" data-toggle="tooltip" title="Remove">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="px-3 pt-0 pb-3">
                                            <span class="lh-36px">${app.trans('Author avatar')}</span>
                                            <div data-image-gallery="${data.id}"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="lh-36px" style="width: 160px;">
                                            <span class="px-3">${app.trans('Author name')}</span>
                                        </td>
                                        <td class="text-right">
                                            <input type="text" class="form-control" name="author_name" value="${data.author_name}" placeholder="${app.trans('Author')}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="lh-36px" style="width: 160px;">
                                            <span class="px-3">${app.trans('Author description')}</span>
                                        </td>
                                        <td class="text-right">
                                            <input type="text" class="form-control" name="author_description" value="${data.author_description}" placeholder="${app.trans('Description')}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="lh-36px" style="width: 160px;">
                                            <span class="px-3">${app.trans('Message')}</span>
                                        </td>
                                        <td class="text-right">
                                            <textarea class="form-control" name="message" placeholder="${app.trans('Message')}">${data.message}</textarea>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </li>`;
            }
        },
        boxed: {
            default: {
                id: 0,
                name: 'Boxed content',
                template: 'boxed',
                heading: 'This is what you need',
                sub_heading: 'Our features',
                description: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias nesciunt, fugiat vitae doloribus repudiandae eos soluta unde voluptate architecto aperiam!',
                background_color: 'white',
                media_id: '',
                data: {}
            },
            modal: '#admin-page-boxed-section-modal',
            form: '#admin-page-boxed-section-form',
            dataItemList: '#section-boxed-data',
            btnSave: '.btn-save-page-boxed-section-data',
            btnAddDataItem: '.btn-section-boxed-add-data-item',
            btnRemoveDataItem: '.btn-remove-page-section-boxed-row',
            init: function() {
                this.initAddItem();
                this.initRemoveItem();
                this.saveSectionData();
                $(this.modal).on('shown.bs.modal', function (e) {
                    $(document).off('focusin.modal');
                });
            },
            fillFormEdit: function(data) {
                $(this.dataItemList).html('');
                $(this.form).find('[name="id"]').val(data.id);
                if (data.data.hasOwnProperty('items')) {
                    for (let index = 0; index < data.data.items.length; index++) {
                        const item = data.data.items[index];
                        $(this.dataItemList).append(this.itemHtml(item));

                        if(typeof $.fn.iconpicker !== 'undefined') {
                            $(`#icon-picker-boxed-${item.id}`).iconpicker().on('change', function(e) {
                                $(e.target).next().val(e.icon)
                            });
                        }
                    }
                }
            },
            initAddItem: function() {
                const self = this;
                $(self.btnAddDataItem).on('click', function() {
                    const id = parseInt($(self.form).find('[name="id"]').val());
                    app.adminPage.sections = app.adminPage.sections.map(function(item) {
                        if (item.id === id) {
                            if (!item.data.hasOwnProperty('items')) {
                                item.data.items = [];
                            }

                            const d = {
                                id: app.adminPage.getId(),
                                icon: 'fas fa-map-marker-alt',
                                title: '',
                                content: ''
                            };
                            $(self.dataItemList).append(self.itemHtml(d));

                            if(typeof $.fn.iconpicker !== 'undefined') {
                                $(`#icon-picker-boxed-${d.id}`).iconpicker().on('change', function(e) {
                                    $(e.target).next().val(e.icon)
                                });
                            }
                        }
                        return item;
                    });
                });
            },
            initRemoveItem: function() {
                const self = this;
                $(document).delegate(self.btnRemoveDataItem, 'click', function() {
                    const row = $(this).closest('li');
                    const id = parseInt(row.data('id'));
                    const sectionId = parseInt($(self.form).find('[name="id"]').val());
                    app.adminPage.sections = app.adminPage.sections.map(function(item) {
                        if (item.id === sectionId) {
                            item.data.items = item.data.items.filter(btn => btn.id !== id);
                        }
                        return item;
                    });

                    row.remove();
                });
            },
            saveSectionData: function() {
                const self = this;
                $(self.btnSave).on('click', function() {
                    const list = $(self.dataItemList).find('li.data-list-item').map(function() {
                        return {
                            id: this.dataset.id,
                            icon: $(this).find('[name="icon"]').val(),
                            title: $(this).find('[name="title"]').val(),
                            content: $(this).find('[name="content"]').val(),
                        };
                    }).get();

                    const id = parseInt($(self.form).find('[name="id"]').val());
                    app.adminPage.sections = app.adminPage.sections.map(function(item) {
                        if (item.id === id) {
                            item.data.items = list;
                        }
                        return item;
                    });

                    $(self.modal).modal('hide');
                });
            },
            itemHtml: function(data) {
                return `<li class="data-list-item" data-id="${data.id}">
                            <table>
                                <tbody>
                                    <tr>
                                        <td colspan="2" class="pl-2">
                                            <a href="javascript:void(0)" role="button" class="btn btn-sm text-danger btn-remove-page-section-boxed-row" data-toggle="tooltip" title="Remove">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="lh-36px" style="width: 85px;">
                                            <span class="px-3">${app.trans('Icon')}</span>
                                        </td>
                                        <td>
                                            <button class="btn btn-link" data-icon="${data.icon}" type="button" id="icon-picker-boxed-${data.id}"></button>
                                            <input type="text"
                                            name="icon"
                                            class="form-control display-none"
                                            value="${data.icon}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="lh-36px" style="width: 85px;">
                                            <span class="px-3">${app.trans('Title')}</span>
                                        </td>
                                        <td class="text-right">
                                            <input type="text" class="form-control" name="title" value="${data.title}" placeholder="${app.trans('Title')}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="lh-36px" style="width: 85px;">
                                            <span class="px-3">${app.trans('Content')}</span>
                                        </td>
                                        <td class="text-right">
                                            <textarea class="form-control" name="content" placeholder="${app.trans('Content')}">${data.content}</textarea>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </li>`;
            }
        },
        boxed_left_icon: {
            default: {
                id: 0,
                name: 'Boxed left icon',
                template: 'boxed_left_icon',
                heading: 'Follow the steps below to start',
                sub_heading: 'How to get started',
                description: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias nesciunt, fugiat vitae doloribus repudiandae eos soluta unde voluptate architecto aperiam!',
                background_color: 'white',
                media_id: '',
                data: {}
            },
            modal: '#admin-page-boxed-left-icon-section-modal',
            form: '#admin-page-boxed-left-icon-section-form',
            dataItemList: '#section-boxed-left-icon-data',
            btnSave: '.btn-save-page-boxed-left-icon-section-data',
            btnAddDataItem: '.btn-section-boxed-left-icon-add-data-item',
            btnRemoveDataItem: '.btn-remove-page-section-boxed-left-icon-row',
            init: function() {
                this.initAddItem();
                this.initRemoveItem();
                this.saveSectionData();

                $(this.modal).on('shown.bs.modal', function (e) {
                    $(document).off('focusin.modal');
                });
            },
            fillFormEdit: function(data) {
                $(this.dataItemList).html('');
                $(this.form).find('[name="id"]').val(data.id);
                if (data.data.hasOwnProperty('items')) {
                    for (let index = 0; index < data.data.items.length; index++) {
                        const item = data.data.items[index];
                        $(this.dataItemList).append(this.itemHtml(item));

                        if(typeof $.fn.iconpicker !== 'undefined') {
                            $(`#icon-picker-boxed-left-${item.id}`).iconpicker().on('change', function(e) {
                                $(e.target).next().val(e.icon)
                            });
                        }
                    }
                }
            },
            initAddItem: function() {
                const self = this;
                $(self.btnAddDataItem).on('click', function() {
                    const id = parseInt($(self.form).find('[name="id"]').val());
                    app.adminPage.sections = app.adminPage.sections.map(function(item) {
                        if (item.id === id) {
                            if (!item.data.hasOwnProperty('items')) {
                                item.data.items = [];
                            }

                            const d = {
                                id: app.adminPage.getId(),
                                icon: 'fas fa-map-marker-alt',
                                title: '',
                                content: ''
                            };
                            $(self.dataItemList).append(self.itemHtml(d));

                            if(typeof $.fn.iconpicker !== 'undefined') {
                                $(`#icon-picker-boxed-left-${d.id}`).iconpicker().on('change', function(e) {
                                    $(e.target).next().val(e.icon)
                                });
                            }
                        }
                        return item;
                    });
                });
            },
            initRemoveItem: function() {
                const self = this;
                $(document).delegate(self.btnRemoveDataItem, 'click', function() {
                    const row = $(this).closest('li');
                    const id = parseInt(row.data('id'));
                    const sectionId = parseInt($(self.form).find('[name="id"]').val());
                    app.adminPage.sections = app.adminPage.sections.map(function(item) {
                        if (item.id === sectionId) {
                            item.data.items = item.data.items.filter(btn => btn.id !== id);
                        }
                        return item;
                    });

                    row.remove();
                });
            },
            saveSectionData: function() {
                const self = this;
                $(self.btnSave).on('click', function() {
                    const list = $(self.dataItemList).find('li.data-list-item').map(function() {
                        return {
                            id: this.dataset.id,
                            icon: $(this).find('[name="icon"]').val(),
                            title: $(this).find('[name="title"]').val(),
                            content: $(this).find('[name="content"]').val(),
                        };
                    }).get();

                    const id = parseInt($(self.form).find('[name="id"]').val());
                    app.adminPage.sections = app.adminPage.sections.map(function(item) {
                        if (item.id === id) {
                            item.data.items = list;
                        }
                        return item;
                    });

                    $(self.modal).modal('hide');
                });
            },
            itemHtml: function(data) {
                return `<li class="data-list-item" data-id="${data.id}">
                            <table>
                                <tbody>
                                    <tr>
                                        <td colspan="2" class="pl-2">
                                            <a href="javascript:void(0)" role="button" class="btn btn-sm text-danger btn-remove-page-section-boxed-left-icon-row" data-toggle="tooltip" title="Remove">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="lh-36px" style="width: 85px;">
                                            <span class="px-3">${app.trans('Icon')}</span>
                                        </td>
                                        <td>
                                            <button class="btn btn-link" data-icon="${data.icon}" type="button" id="icon-picker-boxed-left-${data.id}"></button>
                                            <input type="text"
                                            name="icon"
                                            class="form-control display-none"
                                            value="${data.icon}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="lh-36px" style="width: 85px;">
                                            <span class="px-3">${app.trans('Title')}</span>
                                        </td>
                                        <td class="text-right">
                                            <input type="text" class="form-control" name="title" value="${data.title}" placeholder="${app.trans('Title')}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="lh-36px" style="width: 85px;">
                                            <span class="px-3">${app.trans('Content')}</span>
                                        </td>
                                        <td class="text-right">
                                            <textarea class="form-control" name="content" placeholder="${app.trans('Content')}">${data.content}</textarea>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </li>`;
            }
        },
        text_media: {
            default: {
                id: 0,
                name: 'Text media',
                template: 'text_media',
                heading: 'Our Mission & Vision',
                sub_heading: 'Our Commitment ',
                description: '',
                background_color: 'white',
                media_id: '',
                data: {
                    left: 1,
                    source_type: 'image',
                    media_source: '',
                    text_center: 0,
                    items: [],
                    checkmarks: []
                }
            },
            modal: '#admin-page-text-media-section-modal',
            form: '#admin-page-text-media-section-form',
            detailsModal: '#admin-page-text-media-section-details-edit-modal',
            dataItemList: '#section-text-media-data',
            checkmarkItemList: '#section-text-checkmarks',
            btnSave: '.btn-save-page-text-media-section-data',
            btnAddDataItem: '.btn-section-text-media-add-data-item',
            btnRemoveDataItem: '.btn-remove-page-section-text-media-row',
            btnAddCheckmarkItem: '.btn-section-text-media-add-checkmark-item',
            btnRemoveCheckmarkItem: '.btn-remove-page-section-text-media-checkmark-row',
            init: function() {
                this.initAddItem();
                this.initAddCheckmarkItem();
                this.initRemoveItem();
                this.initRemoveCheckmarkItem();
                this.saveSectionData();
                this.initMediaSourceChange();
            },
            fillFormEdit: function(data) {
                $(this.dataItemList).html('');
                $(this.form).find('[name="id"]').val(data.id);
                $(this.form).find('[name="text_center"]').prop("checked",data.data.text_center ? true : false);
                $(this.form).find('[name="left"][value="'+data.data.left+'"]').prop("checked",true);
                $(this.form).find('[name="source_type"]').val(data.data.source_type).trigger('change');
                if (data.data.source_type === 'image') {
                    $(self.form).find('.media-source-image').removeClass('d-none');
                    $(self.form).find('.media-source-embedded').addClass('d-none');
                    $('[data-image-gallery="text-media-image"]').sGallery();
                    if (data.data.media_source) {
                        $('[data-image-gallery="text-media-image"]').sGallery().setImages([{
                            id: 0,
                            name: 'Preview',
                            preview_url: data.data.media_source,
                            original_url: data.data.media_source
                        }]);
                    }
                } else {
                    $(self.form).find('.media-source-image').addClass('d-none');
                    $(self.form).find('.media-source-embedded').removeClass('d-none');
                    $(this.form).find('[name="media_embed_source"]').val(data.data.media_source);
                }

                if (data.data.hasOwnProperty('items')) {
                    for (let index = 0; index < data.data.items.length; index++) {
                        const item = data.data.items[index];
                        $(this.dataItemList).append(this.itemHtml(item));
                    }
                }

                if (data.data.hasOwnProperty('checkmarks')) {
                    for (let index = 0; index < data.data.checkmarks.length; index++) {
                        const item = data.data.checkmarks[index];
                        $(this.checkmarkItemList).append(this.checkmarkItemHtml(item));
                    }
                }
            },
            initAddItem: function() {
                const self = this;
                $(self.btnAddDataItem).on('click', function() {
                    const id = parseInt($(self.form).find('[name="id"]').val());
                    app.adminPage.sections = app.adminPage.sections.map(function(item) {
                        if (item.id === id) {
                            if (!item.data.hasOwnProperty('items')) {
                                item.data.items = [];
                            }

                            const d = {
                                id: app.adminPage.getId(),
                                content: ''
                            };
                            $(self.dataItemList).append(self.itemHtml(d));
                        }
                        return item;
                    });
                });
            },
            initAddCheckmarkItem: function() {
                const self = this;
                $(self.btnAddCheckmarkItem).on('click', function() {
                    const id = parseInt($(self.form).find('[name="id"]').val());
                    app.adminPage.sections = app.adminPage.sections.map(function(item) {
                        if (item.id === id) {
                            if (!item.data.hasOwnProperty('checkmarks')) {
                                item.data.checkmarks = [];
                            }

                            const d = {
                                id: app.adminPage.getId(),
                                content: ''
                            };
                            $(self.checkmarkItemList).append(self.checkmarkItemHtml(d));
                        }
                        return item;
                    });
                });
            },
            initRemoveItem: function() {
                const self = this;
                $(document).delegate(self.btnRemoveDataItem, 'click', function() {
                    const row = $(this).closest('li');
                    const id = parseInt(row.data('id'));
                    const sectionId = parseInt($(self.form).find('[name="id"]').val());
                    app.adminPage.sections = app.adminPage.sections.map(function(item) {
                        if (item.id === sectionId) {
                            item.data.items = item.data.items.filter(btn => btn.id !== id);
                        }
                        return item;
                    });

                    row.remove();
                });
            },
            initRemoveCheckmarkItem: function() {
                const self = this;
                $(document).delegate(self.btnRemoveCheckmarkItem, 'click', function() {
                    const row = $(this).closest('li');
                    const id = parseInt(row.data('id'));
                    const sectionId = parseInt($(self.form).find('[name="id"]').val());
                    app.adminPage.sections = app.adminPage.sections.map(function(item) {
                        if (item.id === sectionId) {
                            item.data.checkmarks = item.data.checkmarks.filter(btn => btn.id !== id);
                        }
                        return item;
                    });

                    row.remove();
                });
            },
            saveSectionData: function() {
                const self = this;
                $(self.btnSave).on('click', function() {
                    const list = $(self.dataItemList).find('li.data-list-item').map(function() {
                        return {
                            id: this.dataset.id,
                            content: $(this).find('[name="content"]').val()
                        };
                    }).get();

                    const checkmarks = $(self.checkmarkItemList).find('li.data-list-item').map(function() {
                        return {
                            id: this.dataset.id,
                            content: $(this).find('[name="content"]').val()
                        };
                    }).get();

                    const id = parseInt($(self.form).find('[name="id"]').val());
                    const left = $(self.form).find('[name="left"]:checked').val();
                    const sourceType = $(self.form).find('[name="source_type"]').val();
                    const textCenter = $(this.form).find('[name="text_center"]:checked').length ? 1 : 0;
                    if (sourceType === 'image') {
                        var images = $(`[data-image-gallery="text-media-image"]`)
                            .sGallery()
                            .images();

                        var source = images.length ? images[0].original_url : '';
                    } else {
                        var source = $(self.form).find('[name="media_embed_source"]').val();
                    }
                    app.adminPage.sections = app.adminPage.sections.map(function(item) {
                        if (item.id === id) {
                            item.data.left = left;
                            item.data.items = list;
                            item.data.checkmarks = checkmarks;
                            item.data.text_center = textCenter;
                            item.data.source_type = sourceType;
                            item.data.media_source = source;
                        }
                        return item;
                    });

                    $(self.modal).modal('hide');
                });
            },
            itemHtml: function(data) {
                return `<li class="data-list-item" data-id="${data.id}">
                            <table>
                                <tbody>
                                    <tr>
                                        <td class="pl-2">
                                            <a href="javascript:void(0)" role="button" class="btn btn-sm text-danger btn-remove-page-section-testimonial-row" data-toggle="tooltip" title="Remove">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <textarea class="form-control" style="min-height: 100px;" name="content" placeholder="${app.trans('Paragraph')}">${data.content}</textarea>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </li>`;
            },
            initMediaSourceChange: function() {
                const self = this;
                $(this.form).find('[name="source_type"]').on('change', function() {
                    if (this.value === 'image') {
                        $(self.form).find('.media-source-image').removeClass('d-none');
                        $(self.form).find('.media-source-embedded').addClass('d-none');
                    } else {
                        $(self.form).find('.media-source-image').addClass('d-none');
                        $(self.form).find('.media-source-embedded').removeClass('d-none');
                    }
                });
            },
            checkmarkItemHtml: function(data) {
                return `<li class="data-list-item" data-id="${data.id}">
                            <table>
                                <tbody>
                                    <tr>
                                        <td class="pl-2">
                                            <a href="javascript:void(0)" role="button" class="btn btn-sm text-danger btn-remove-page-section-text-media-checkmark-row" data-toggle="tooltip" title="Remove">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <textarea class="form-control" style="min-height: 100px;" name="content" placeholder="${app.trans('Sentence')}">${data.content}</textarea>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </li>`;
            }
        },
        team: {
            default: {
                id: 0,
                name: 'Team',
                template: 'team',
                heading: 'Our creative team that help our company',
                sub_heading: 'Meet our team',
                description: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias nesciunt, fugiat vitae doloribus repudiandae eos soluta unde voluptate architecto aperiam!',
                background_color: 'white',
                media_id: '',
                data: {}
            },
            modal: '#admin-page-team-section-modal',
            form: '#admin-page-team-section-form',
            dataItemList: '#section-team-data',
            btnSave: '.btn-save-page-team-section-data',
            btnAddDataItem: '.btn-section-team-add-data-item',
            btnRemoveDataItem: '.btn-remove-page-section-team-row',
            init: function() {
                this.initAddItem();
                this.initRemoveItem();
                this.saveSectionData();
            },
            fillFormEdit: function(data) {
                $(this.dataItemList).html('');
                $(this.form).find('[name="id"]').val(data.id);
                if (data.data.hasOwnProperty('items')) {
                    for (let index = 0; index < data.data.items.length; index++) {
                        const item = data.data.items[index];
                        $(this.dataItemList).append(this.itemHtml(item));
                        $(`[data-image-gallery="${item.id}"]`).sGallery();
                        if (item.avatar) {
                            $(`[data-image-gallery="${item.id}"]`).sGallery().setImages([{
                                id: 0,
                                name: 'Preview',
                                preview_url: item.avatar,
                                original_url: item.avatar
                            }]);
                        }
                    }
                }
            },
            initAddItem: function() {
                const self = this;
                $(self.btnAddDataItem).on('click', function() {
                    const id = parseInt($(self.form).find('[name="id"]').val());
                    app.adminPage.sections = app.adminPage.sections.map(function(item) {
                        if (item.id === id) {
                            if (!item.data.hasOwnProperty('items')) {
                                item.data.items = [];
                            }

                            const d = {
                                id: app.adminPage.getId(),
                                avatar: '',
                                name: '',
                                position: '',
                                description: '',
                                facebook: '',
                                twitter: '',
                                linkedin: '',
                                dribbble: ''
                            };
                            $(self.dataItemList).append(self.itemHtml(d));
                            $(`[data-image-gallery="${d.id}"]`).sGallery();
                        }
                        return item;
                    });
                });
            },
            initRemoveItem: function() {
                const self = this;
                $(document).delegate(self.btnRemoveDataItem, 'click', function() {
                    const row = $(this).closest('li');
                    const id = parseInt(row.data('id'));
                    const sectionId = parseInt($(self.form).find('[name="id"]').val());
                    app.adminPage.sections = app.adminPage.sections.map(function(item) {
                        if (item.id === sectionId) {
                            item.data.items = item.data.items.filter(btn => btn.id !== id);
                        }
                        return item;
                    });

                    row.remove();
                });
            },
            saveSectionData: function() {
                const self = this;
                $(self.btnSave).on('click', function() {
                    const list = $(self.dataItemList).find('li.data-list-item').map(function() {
                        var images = $(`[data-image-gallery="${this.dataset.id}"]`)
                            .sGallery()
                            .images();

                        return {
                            id: this.dataset.id,
                            avatar: images.length ? images[0].original_url : '',
                            name: $(this).find('[name="name"]').val(),
                            position: $(this).find('[name="position"]').val(),
                            description: $(this).find('[name="description"]').val(),
                            facebook: $(this).find('[name="facebook"]').val(),
                            twitter: $(this).find('[name="twitter"]').val(),
                            linkedin: $(this).find('[name="linkedin"]').val(),
                            dribbble: $(this).find('[name="dribbble"]').val()
                        };
                    }).get();

                    const id = parseInt($(self.form).find('[name="id"]').val());
                    app.adminPage.sections = app.adminPage.sections.map(function(item) {
                        if (item.id === id) {
                            item.data.items = list;
                        }
                        return item;
                    });

                    $(self.modal).modal('hide');
                });
            },
            itemHtml: function(data) {
                return `<li class="data-list-item" data-id="${data.id}">
                            <table>
                                <tbody>
                                    <tr>
                                        <td colspan="2" class="pl-2">
                                            <a href="javascript:void(0)" role="button" class="btn btn-sm text-danger btn-remove-page-section-testimonial-row" data-toggle="tooltip" title="Remove">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="px-3 pt-0 pb-3">
                                            <span class="lh-36px">${app.trans('Avatar')}</span>
                                            <div data-image-gallery="${data.id}"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="lh-36px" style="width: 120px;">
                                            <span class="px-3">${app.trans('Name')}</span>
                                        </td>
                                        <td lh-36px class="text-right">
                                            <input type="text" class="form-control" name="name" value="${data.name}" placeholder="${app.trans('Name')}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="lh-36px" style="width: 120px;">
                                            <span class="px-3">${app.trans('Position')}</span>
                                        </td>
                                        <td class="text-right">
                                            <input type="text" class="form-control" name="position" value="${data.position}" placeholder="${app.trans('Position')}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="lh-36px" style="width: 120px;">
                                            <span class="px-3">${app.trans('Description')}</span>
                                        </td>
                                        <td class="text-right">
                                            <textarea class="form-control" name="description" placeholder="${app.trans('Description')}">${data.description}</textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 120px;">
                                            <span class="px-3">
                                                <i class="fab fa-facebook" style="font-size: 16px;line-height: 34px;"></i>
                                            </span>
                                        </td>
                                        <td class="text-right">
                                            <input type="text" class="form-control" name="facebook" value="${data.facebook}" placeholder="${app.trans('Facebook link / Leave empty')}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 120px;">
                                            <span class="px-3">
                                                <i class="fab fa-twitter" style="font-size: 16px;line-height: 34px;"></i>
                                            </span>
                                        </td>
                                        <td class="text-right">
                                            <input type="text" class="form-control" name="twitter" value="${data.twitter}" placeholder="${app.trans('Twitter link / Leave empty')}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 120px;">
                                            <span class="px-3">
                                                <i class="fab fa-linkedin" style="font-size: 16px;line-height: 34px;"></i>
                                            </span>
                                        </td>
                                        <td class="text-right">
                                            <input type="text" class="form-control" name="linkedin" value="${data.linkedin}" placeholder="${app.trans('Linkedin link / Leave empty')}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 120px;">
                                            <span class="px-3">
                                                <i class="fab fa-dribbble" style="font-size: 16px;line-height: 34px;"></i>
                                            </span>
                                        </td>
                                        <td class="text-right">
                                            <input type="text" class="form-control" name="dribbble" value="${data.dribbble}" placeholder="${app.trans('Dribbble link / Leave empty')}">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </li>`;
            }
        },
        client: {
            default: {
                id: 0,
                name: 'Client',
                template: 'client',
                heading: 'See our happy clients',
                sub_heading: 'Our clients',
                description: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias nesciunt, fugiat vitae doloribus repudiandae eos soluta unde voluptate architecto aperiam!',
                background_color: 'white',
                media_id: '',
                data: {}
            },
            modal: '#admin-page-client-section-modal',
            form: '#admin-page-client-section-form',
            dataItemList: '#section-client-data',
            btnSave: '.btn-save-page-client-section-data',
            btnAddDataItem: '.btn-section-client-add-data-item',
            btnRemoveDataItem: '.btn-remove-page-section-client-row',
            init: function() {
                this.initAddItem();
                this.initRemoveItem();
                this.saveSectionData();
            },
            fillFormEdit: function(data) {
                $(this.dataItemList).html('');
                $(this.form).find('[name="id"]').val(data.id);
                if (data.data.hasOwnProperty('items')) {
                    for (let index = 0; index < data.data.items.length; index++) {
                        const item = data.data.items[index];
                        $(this.dataItemList).append(this.itemHtml(item));
                        $(`[data-image-gallery="${item.id}"]`).sGallery();
                        if (item.logo) {
                            $(`[data-image-gallery="${item.id}"]`).sGallery().setImages([{
                                id: 0,
                                name: 'Preview',
                                preview_url: item.logo,
                                original_url: item.logo
                            }]);
                        }
                    }
                }
            },
            initAddItem: function() {
                const self = this;
                $(self.btnAddDataItem).on('click', function() {
                    const id = parseInt($(self.form).find('[name="id"]').val());
                    app.adminPage.sections = app.adminPage.sections.map(function(item) {
                        if (item.id === id) {
                            if (!item.data.hasOwnProperty('items')) {
                                item.data.items = [];
                            }

                            const d = {
                                id: app.adminPage.getId(),
                                title: '',
                                logo: '',
                                url: ''
                            };
                            $(self.dataItemList).append(self.itemHtml(d));
                            $(`[data-image-gallery="${d.id}"]`).sGallery();
                        }
                        return item;
                    });
                });
            },
            initRemoveItem: function() {
                const self = this;
                $(document).delegate(self.btnRemoveDataItem, 'click', function() {
                    const row = $(this).closest('li');
                    const id = parseInt(row.data('id'));
                    const sectionId = parseInt($(self.form).find('[name="id"]').val());
                    app.adminPage.sections = app.adminPage.sections.map(function(item) {
                        if (item.id === sectionId) {
                            item.data.items = item.data.items.filter(btn => btn.id !== id);
                        }
                        return item;
                    });

                    row.remove();
                });
            },
            saveSectionData: function() {
                const self = this;
                $(self.btnSave).on('click', function() {
                    const list = $(self.dataItemList).find('li.data-list-item').map(function() {
                        var images = $(`[data-image-gallery="${this.dataset.id}"]`)
                            .sGallery()
                            .images();

                        return {
                            id: this.dataset.id,
                            title: $(this).find('[name="title"]').val(),
                            logo: images.length ? images[0].original_url : '',
                            url: $(this).find('[name="url"]').val()
                        };
                    }).get();

                    const id = parseInt($(self.form).find('[name="id"]').val());
                    app.adminPage.sections = app.adminPage.sections.map(function(item) {
                        if (item.id === id) {
                            item.data.items = list;
                        }
                        return item;
                    });

                    $(self.modal).modal('hide');
                });
            },
            itemHtml: function(data) {
                return `<li class="data-list-item" data-id="${data.id}">
                            <table>
                                <tbody>
                                    <tr>
                                        <td colspan="2" class="pl-2">
                                            <a href="javascript:void(0)" role="button" class="btn btn-sm text-danger btn-remove-page-section-testimonial-row" data-toggle="tooltip" title="Remove">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="px-3 pt-0 pb-3">
                                            <span class="lh-36px">${app.trans('Logo')}</span>
                                            <div data-image-gallery="${data.id}"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="lh-36px" style="width: 60px;">
                                            <span class="px-3">${app.trans('Title')}</span>
                                        </td>
                                        <td class="text-right">
                                            <input type="text" class="form-control" name="title" value="${data.title}" placeholder="${app.trans('Title')}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="lh-36px" style="width: 60px;">
                                            <span class="px-3">${app.trans('Url')}</span>
                                        </td>
                                        <td class="text-right">
                                            <input type="text" class="form-control" name="url" value="${data.url}" placeholder="https://example.com">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </li>`;
            }
        },
        pricing: {
            default: {
                id: 0,
                name: 'Pricing',
                template: 'pricing',
                heading: 'Select our affordable pricing',
                sub_heading: 'Our pricing',
                description: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias nesciunt, fugiat vitae doloribus repudiandae eos soluta unde voluptate architecto aperiam!',
                background_color: 'white',
                media_id: ''
            },
        },
        faq: {
            default: {
                id: 0,
                name: 'Faq',
                template: 'faq',
                heading: 'Learn more about our sevices',
                sub_heading: 'Faqs ',
                description: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias nesciunt, fugiat vitae doloribus repudiandae eos soluta unde voluptate architecto aperiam!',
                background_color: 'white',
                media_id: '',
                data: {}
            },
            modal: '#admin-page-faq-section-modal',
            form: '#admin-page-faq-section-form',
            dataItemList: '#section-faq-data',
            btnSave: '.btn-save-page-faq-section-data',
            btnAddDataItem: '.btn-section-faq-add-data-item',
            btnRemoveDataItem: '.btn-remove-page-section-faq-row',
            init: function() {
                this.initAddItem();
                this.initRemoveItem();
                this.saveSectionData();
            },
            fillFormEdit: function(data) {
                $(this.dataItemList).html('');
                $(this.form).find('[name="id"]').val(data.id);
                if (data.data.hasOwnProperty('items')) {
                    for (let index = 0; index < data.data.items.length; index++) {
                        const item = data.data.items[index];
                        $(this.dataItemList).append(this.itemHtml(item));
                    }
                }
            },
            initAddItem: function() {
                const self = this;
                $(self.btnAddDataItem).on('click', function() {
                    const id = parseInt($(self.form).find('[name="id"]').val());
                    app.adminPage.sections = app.adminPage.sections.map(function(item) {
                        if (item.id === id) {
                            if (!item.data.hasOwnProperty('items')) {
                                item.data.items = [];
                            }

                            const d = {
                                id: app.adminPage.getId(),
                                title: '',
                                content: ''
                            };
                            $(self.dataItemList).append(self.itemHtml(d));
                        }
                        return item;
                    });
                });
            },
            initRemoveItem: function() {
                const self = this;
                $(document).delegate(self.btnRemoveDataItem, 'click', function() {
                    const row = $(this).closest('li');
                    const id = parseInt(row.data('id'));
                    const sectionId = parseInt($(self.form).find('[name="id"]').val());
                    app.adminPage.sections = app.adminPage.sections.map(function(item) {
                        if (item.id === sectionId) {
                            item.data.items = item.data.items.filter(btn => btn.id !== id);
                        }
                        return item;
                    });

                    row.remove();
                });
            },
            saveSectionData: function() {
                const self = this;
                $(self.btnSave).on('click', function() {
                    const list = $(self.dataItemList).find('li.data-list-item').map(function() {
                        return {
                            id: this.dataset.id,
                            title: $(this).find('[name="title"]').val(),
                            content: $(this).find('[name="content"]').val()
                        };
                    }).get();

                    const id = parseInt($(self.form).find('[name="id"]').val());
                    app.adminPage.sections = app.adminPage.sections.map(function(item) {
                        if (item.id === id) {
                            item.data.items = list;
                        }
                        return item;
                    });

                    $(self.modal).modal('hide');
                });
            },
            itemHtml: function(data) {
                return `<li class="data-list-item" data-id="${data.id}">
                            <table>
                                <tbody>
                                    <tr>
                                        <td colspan="2" class="pl-2">
                                            <a href="javascript:void(0)" role="button" class="btn btn-sm text-danger btn-remove-page-section-testimonial-row" data-toggle="tooltip" title="Remove">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="lh-36px" style="width: 90px;">
                                            <span class="px-3">${app.trans('Title')}</span>
                                        </td>
                                        <td class="text-right">
                                            <input type="text" class="form-control" name="title" value="${data.title}" placeholder="${app.trans('Title')}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="lh-36px" style="width: 90px;">
                                            <span class="px-3">${app.trans('Content')}</span>
                                        </td>
                                        <td class="text-right">
                                            <textarea class="form-control" name="content" placeholder="${app.trans('Content')}">${data.content}</textarea>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </li>`;
            }
        },
        blog: {
            default: {
                id: 0,
                name: 'Blog',
                template: 'blog',
                heading: 'Blog',
                sub_heading: 'Latest blogs',
                description: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias nesciunt, fugiat vitae doloribus repudiandae eos soluta unde voluptate architecto aperiam!',
                background_color: 'white',
                media_id: '',
                data: {
                    count: 3,
                    sort: 'latest'
                }
            },
            modal: '#admin-page-blog-section-modal',
            form: '#admin-page-blog-section-form',
            btnSave: '.btn-save-page-blog-section-data',
            init: function() {
                this.saveSectionData();
            },
            fillFormEdit: function(data) {
                $(this.dataItemList).html('');
                $(this.form).find('[name="id"]').val(data.id);
                $(this.form).find('[name="count"]').val(data.data.count);
                $(this.form).find('[name="sort"]').val(data.data.sort);
            },
            saveSectionData: function() {
                const self = this;
                $(self.btnSave).on('click', function() {
                    const id = parseInt($(self.form).find('[name="id"]').val());
                    const data = $(self.form).serializeArray();
                    app.adminPage.sections = app.adminPage.sections.map(function(item) {
                        if (item.id === id) {
                            for (const [key, value] of Object.entries(item.data)) {
                                var i = data.find(it => it.name === key);
                                if (i) {
                                    item.data[key] = i.value;
                                }
                            }
                        }

                        return item;
                    });

                    $(self.modal).modal('hide');
                });
            }
        },
        cta: {
            default: {
                id: 0,
                name: 'CTA',
                template: 'cta',
                heading: 'Build web application faster than ever',
                sub_heading: '',
                description: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias nesciunt, fugiat vitae doloribus repudiandae eos soluta unde voluptate architecto aperiam!',
                background_color: 'white',
                media_id: '',
                data: {
                    label: 'Purchase now',
                    link: '',
                    new_tab: false
                }
            },
            modal: '#admin-page-cta-section-modal',
            form: '#admin-page-cta-section-form',
            btnSave: '.btn-save-page-cta-section-data',
            detailsModal: '#admin-page-cta-section-details-edit-modal',
            init: function() {
                this.saveSectionData();
            },
            fillFormEdit: function(json) {
                const data = json.data;
                $(this.form).find('[name="id"]').val(json.id);
                $(this.form).find('[name="label"]').val(data.label);
                $(this.form).find('[name="link"]').val(data.link);
                $(this.form).find('[name="new_tab"]').attr('checked', data.new_tab ? true : false);
            },
            saveSectionData: function() {
                const self = this;
                $(self.btnSave).on('click', function() {
                    const id = parseInt($(self.form).find('[name="id"]').val());
                    const data = $(self.form).serializeArray();
                    app.adminPage.sections = app.adminPage.sections.map(function(item) {
                        if (item.id === id) {
                            item.data.new_tab = 0;
                            for (const [key, value] of Object.entries(item.data)) {
                                var i = data.find(it => it.name === key);
                                if (i) {
                                    item.data[key] = i.value;

                                    if (key === 'new_tab') {
                                        item.data[key] = 1;
                                    }
                                }
                            }
                        }

                        return item;
                    });

                    $(self.modal).modal('hide');
                });
            }
        },
        news_letter: {
            default: {
                id: 0,
                name: 'News letter',
                template: 'news_letter',
                heading: 'Subscribe to our newsletter',
                sub_heading: '',
                description: '',
                background_color: 'white',
                media_id: ''
            },
        },
        contact_us: {
            default: {
                id: 0,
                name: 'Contact Us',
                template: 'contact_us',
                heading: 'Contact Us',
                sub_heading: 'Get in touch with us',
                description: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias nesciunt, fugiat vitae doloribus repudiandae eos soluta unde voluptate architecto aperiam!',
                background_color: 'white',
                media_id: ''
            },
        }
    }
};

app.adminPage.getSections = function() {
    const sections = $(this.config.sectionContainer)
        .find('tr.page-section-table-row')
        .map(function(i, tr) {
            const id = parseInt(tr.dataset.id);
            var section = app.adminPage.sections.find(item => item.id === id);
            if (section) {
                section.order = i + 1;
            }

            return section;
        })
        .get();

    return sections;
};

app.adminPage.sectionHtmlItem = function(row, hasData = true) {
    var html = `<tr role="row" class="page-section-table-row" data-id="${row.id}">
                <td class="page-section-table-row-drag-handle">
                    <span>
                        <i class="fas fa-arrows-alt drag-handle"></i>
                    </span>
                </td>
                <td class="page-section-table-row-details">
                    <div class="d-flex flex-column page-section-name">
                        <strong>${row.name} </strong>
                    </div>
                </td>
                <td class="page-section-table-row-remove-handle">
                    <div class="btn-group dropleft">
                        <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton-${row.id}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                        <div class="dropdown-menu dropleft">
                            <a class="dropdown-item has-icon btn-edit-page-section-details" href="javascript:void(0)">
                                ${app.trans('Edit section details')}
                            </a>`;
        if (hasData) {
            html += `<a class="dropdown-item has-icon btn-edit-page-section-data" href="javascript:void(0)">
                                ${app.trans('Edit section data')}
                            </a>`;
        }
        html += `<a class="dropdown-item has-icon btn-remove-page-section" href="javascript:void(0)">
                                ${app.trans('Remove section')}
                            </a>
                        </div>
                    </div>
                </td>
            </tr>`;
    return html;
};

app.adminPage.initContentTypeChange = function() {
    $('.admin-page-form [name="type"]').on('change', function() {
		var value = $(this).val();
		if(value == 'section') {
			$('.page-section-wrapper').show();
            $('.page-content-wrapper').hide();
		}

		if(value == 'wysiwyg') {
			$('.page-section-wrapper').hide();
            $('.page-content-wrapper').show();
		}
	});
};

app.adminPage.addSection = function() {
    const self = this;
    $(document).delegate(self.config.sectionItemBtn, 'click', function() {
        const section = $(this).data('template');
        const hasData = $(this).data('has-data');
        if (self.config.sections.hasOwnProperty(section)) {
            var data = self.config.sections[section];
            const json = {...{}, ...data.default};
            json.id = self.getId();
            self.sections.push(json);
            $(self.config.sectionContainer)
                .find('tbody')
                .append(self.sectionHtmlItem(json, hasData));
            $(self.config.addSectionModal).modal('hide');
        }
    });
};

app.adminPage.getId = function() {
    return (new Date()).getTime() + parseInt(Math.random().toString(8).slice(2));
};

app.adminPage.editSectionData = function() {
    const self = this;
    $(document).delegate('.btn-edit-page-section-data', 'click', function() {
        const id = $(this).closest('tr').data('id');
        const section = self.sections.find(item => item.id === id);
        if (section) {
            var modal = self.config.sections[section.template].modal;
            $(modal).modal('show');
            self.config.sections[section.template].fillFormEdit(section);
        }
    });
};

app.adminPage.editSectionDetails = function() {
    const self = this;
    $(document).delegate('.btn-edit-page-section-details', 'click', function() {
        const id = $(this).closest('tr').data('id');
        const section = self.sections.find(item => item.id === id);
        if (section) {
            var detailsModal = self.config.sections[section.template].detailsModal;
            const modal = detailsModal ? detailsModal : self.config.editSectionDetailsModal;
            $(modal).modal('show');
            $(modal).find('[name="id"]').val(section.id);
            $(modal).find('[name="heading"]').val(section.heading);
            $(modal).find('[name="sub_heading"]').val(section.sub_heading);
            $(modal).find('[name="description"]').val(section.description);
            if (section.background_color) {
                const colors = $(modal).find('[name="background_style"]').map(function() {
                    return this.value;
                }).get();
                console.log(colors)
                const color = colors.includes(section.background_color) ? section.background_color : colors[0];
                $(modal).find('[name="background_style"][value="'+color+'"]').prop('checked', true);
                $(modal).find('[name="background_color"]').val(color);
                $(modal).find('[data-image-gallery]').sGallery().setImages([]);
                $('.section-background-image-wrapper').hide();
            } else {
                $(modal).find('[name="background_style"][value="image"]').prop('checked', true);
                if (section.background_image) {
                    $(modal).find('[data-image-gallery]').sGallery().setImages([{
                        id: section.background_image.id,
                        name: section.background_image.name,
                        preview_url: section.background_image.preview_url,
                        original_url: section.background_image.original_url,
                    }]);
                }
                $('.section-background-image-wrapper').show();
                $(modal).find('[name="background_color"]').val('');
            }
        }
    });
};

app.adminPage.sectionDetailsFormBackgroundStyleChange = function() {
    const self = this;
    $(self.config.detailsModal+' [name="background_style"]').on('change', function() {
        if (this.value === 'image') {
            $('.section-background-image-wrapper').show();
            $(self.config.detailsModal).find('[name="background_color"]').val('');
        } else {
            $('.section-background-image-wrapper').hide();
            $(self.config.detailsModal).find('[name="background_color"]').val(this.value);
        }
    });
};

app.adminPage.saveSectionDetails = function() {
    const self = this;
    $('.btn-save-section-details').on('click', function() {
        const $form = $(this).closest('form');
        const $modal = $(this).closest(self.config.detailsModal);
        const id = parseInt($form
            .find('[name="id"]')
            .val());

        const data = $form.serializeArray();
        self.sections = self.sections.map(function(item) {
            if (item.id === id) {
                for (const [key, value] of Object.entries(item)) {
                    var i = data.find(it => it.name === key);
                    if (i) {
                        item[key] = key === 'id' ? value : i.value;
                    }
                }

                var i = data.find(ite => ite.name === 'background_style');
                if (i && i.value === 'image') {
                    const images = $form.find('[data-image-gallery]').sGallery().images();
                    if (images.length) {
                        item.media_id = images[0].id;
                        item.background_image = {
                            id: images[0].id,
                            name: images[0].name,
                            preview_url: images[0].preview_url,
                            original_url: images[0].original_url,
                        };
                    } else {
                        item.media_id = '';
                        item.background_image = '';
                    }
                } else {
                    item.media_id = '';
                    item.background_image = '';
                }
            }

            return item;
        });

        $modal.modal('hide');
    });
};

app.adminPage.removeSection = function() {
    const self = this;
    $(document).delegate(self.config.removeSectionBtn, 'click', function() {
        const row = $(this).closest('tr.page-section-table-row');
        const id = parseInt(row.data('id'));
        self.sections = self.sections.filter(item => item.id !== id);
        row.remove();
    });
};

app.adminPage.sortSections = function() {
    $( "#page-section-table tbody" ).sortable({
        delay: 150,
        handle: '.drag-handle',
        cursor: 'move',
        placeholder:'must-have-class',
        start: function(event, ui) {
            ui.item.map(function(i, tr) {
                $(tr).find('.page-section-table-row-remove-handle').addClass('d-none');
            });
        },
        stop: function(event, ui) {
            ui.item.map(function(i, tr) {
                $(tr).find('.page-section-table-row-remove-handle').removeClass('d-none');
            });
        }
    });
};

// initialize tinymce plugin
app.adminPage.tinyMCE = function() {
    tinymce.init({
        selector: 'textarea#page-content',
        plugins: ['preview', 'importcss', 'searchreplace', 'autolink', 'autosave', 'save', 'directionality', 'visualblocks', 'visualchars', 'fullscreen', 'photoGallery', 'image', 'link', 'media', 'codesample', 'table', 'charmap', 'pagebreak', 'nonbreaking', 'anchor', 'insertdatetime', 'advlist', 'lists', 'wordcount', 'help', 'charmap', 'quickbars', 'emoticons'],
        mobile: {
            plugins: ['preview', 'importcss', 'searchreplace', 'autolink', 'autosave', 'save', 'directionality', 'visualblocks', 'visualchars', 'fullscreen', 'image', 'link', 'media', 'codesample', 'table', 'charmap', 'pagebreak', 'nonbreaking', 'anchor', 'insertdatetime', 'advlist', 'lists', 'wordcount', 'help', 'charmap', 'quickbars', 'emoticons']
        },
        menubar: 'file edit view insert format tools table tc',
        toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist checklist | forecolor backcolor casechange formatpainter removeformat | insertPhoto image media link anchor codesample | a11ycheck ltr rtl',
        autosave_ask_before_unload: true,
        autosave_interval: '30s',
        autosave_prefix: '{path}{query}-{id}-',
        autosave_restore_when_empty: false,
        autosave_retention: '2m',
        image_advtab: true,
        importcss_append: true,
        height: 600,
        image_caption: true,
        quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
        toolbar_mode: 'sliding',
        spellchecker_ignore_list: ['Ephox', 'Moxiecode'],
        contextmenu: 'link image table',
        content_css: ["/plugins/tinymce/css/email-templates.css"],
        a11y_advanced_options: true,
        promotion: false,
        setup: function(editor) {
            editor.on('init', function(e) {
                $('.admin-page-actions').removeClass('d-none');
            });
        }
    });
};

app.adminPage.initTooltips = function() {
    // $(document).ready(function() {
    //     $('body').tooltip({
    //         selector: "[data-toggle=tooltip]",
    //         container: "body"
    //     });
    // });
};

app.adminPage.pageTitleInput = function() {
    if ($('.admin-page-form [name="page_title"]').length) {
        $('.admin-page-form .page_title_length').html($('.admin-page-form [name="page_title"]').val().length);
        $('.admin-page-form [name="page_title"]').on('input', function() {
            $('.admin-page-form .page_title_length').html(this.value.length);
        });
    }

    if ($('.admin-page-form [name="page_description"]').length) {
        $('.admin-page-form page_description_length').html($('.admin-page-form [name="page_description"]').val().length);
        $('.admin-page-form [name="page_description"]').on('input', function() {
            $('.admin-page-form .page_description_length').html(this.value.length);
        });
    }
};

app.adminPage.toggleSeoSettings = function() {
    $(this.config.toggleSeoSetting).on('click', function() {
        $(this).remove();
        $('.admin-page-form .blog-seo-edit').removeClass('display-none');
    });
};

app.adminPage.hasPageTitle = function() {
    if ($('.admin-page-form [name="name"]').length && $('.admin-page-form [name="name"]').val().trim()) {
        return true;
    }

    if ($('.admin-page-form [name="page_title"]').length && $('.admin-page-form [name="page_title"]').val().trim()) {
        return true;
    }

    return false;
};

app.adminPage.hasPageDescription = function() {
    if ($('.admin-page-form [name="description"]').length && $('.admin-page-form [name="description"]').val().trim()) {
        return true;
    }

    if ($('.admin-page-form [name="page_description"]').length && $('.admin-page-form [name="page_description"]').val().trim()) {
        return true;
    }

    return false;
};

app.adminPage.toggleSeoPreviewTitle = function() {
    if (this.hasPageTitle()) {
        $('.admin-page-form .blog-seo-preview-title-desc').addClass('d-none');
        $('.admin-page-form .blog-seo-preview-title-desc-value').removeClass('d-none');
    } else {
        $('.admin-page-form .blog-seo-preview-title-desc').removeClass('d-none');
        $('.admin-page-form .blog-seo-preview-title-desc-value').addClass('d-none');
    }
};

app.adminPage.seoSettingInpt = function() {
    const self = this;

    $('.admin-page-form [name="name"]').on('input', function() {
        if ($('.admin-page-form [name="page_title"]').length) {
            $('.admin-page-form [name="page_title"]').attr('placeholder', app.strMaxLength(this.value, 70));

            if (!$('.admin-page-form [name="page_title"]').val().trim()) {
                $('.admin-page-form .blog-seo-preview-title').html(app.strMaxLength(this.value, 70));
                self.toggleSeoPreviewTitle();
            }
        }

        if ($('.admin-page-form [name="slug"]').length) {
            if (!$('.admin-page-form [name="slug"]').val().trim()) {
                $('.admin-page-form [name="slug"]').attr('placeholder', app.slugify(app.strMaxLength(this.value, 70)));
                $('.admin-page-form .blog-seo-preview-url')
                    .html(window.location.origin + '/pages/'+app.slugify(app.strMaxLength(this.value, 70)));
                self.toggleSeoPreviewTitle();
            }
        }
    });

    $('.admin-page-form [name="description"]').on('input', function() {
        if ($('.admin-page-form [name="page_description"]').length) {
            $('.admin-page-form [name="page_description"]').attr('placeholder', app.strMaxLength(this.value.trim(), 320));

            if ($('.admin-page-form .blog-seo-edit').hasClass('display-none')) {
                $('.admin-page-form [name="page_description"]').val(app.strMaxLength(this.value, 320));
                $('.admin-page-form .blog-seo-preview-desc').html(app.strMaxLength(this.value, 320));
            }

            if (!$('.admin-page-form [name="page_description"]').val().trim()) {
                $('.admin-page-form .blog-seo-preview-desc').html(app.strMaxLength(this.value, 320));
            }

            self.toggleSeoPreviewTitle();
        }
    });

    $('.admin-page-form [name="page_title"]').on('input', function() {
        var val = this.value.trim();
        if (val) {
            $('.admin-page-form .blog-seo-preview-title').html(app.strMaxLength(val, 70));
        } else {
            val = $('.admin-page-form [name="name"]').length ? $('.admin-page-form [name="name"]').val().trim() : '';
            $('.admin-page-form .blog-seo-preview-title').html(app.strMaxLength(val, 70));
        }
        self.toggleSeoPreviewTitle();
    });

    $('.admin-page-form [name="page_description"]').on('input', function() {
        var val = this.value.trim();
        if (val) {
            $('.admin-page-form .blog-seo-preview-desc').html(app.strMaxLength(val, 320));
        } else {
            $('.admin-page-form .blog-seo-preview-desc').html(app.strMaxLength($('.admin-page-form [name="description"]').val().trim(), 320));
        }
        self.toggleSeoPreviewTitle();
    });

    $('.admin-page-form [name="page_description"]').on('focus', function() {
        if (!this.value.trim()) {
            var val = $('.admin-page-form [name="description"]').val().trim();
            $(this).val(val);
            $('.admin-page-form .blog-seo-preview-desc').html(app.strMaxLength(val, 320));

            self.toggleSeoPreviewTitle();
        }
    });

    $('.admin-page-form [name="slug"]').on('input propertychange', function() {
        this.value = this.value.replace(/\s+/g, '-');

        var val = this.value.trim();
        if (!val) {
            val = $('.admin-page-form [name="name"]').length ? $('.admin-page-form [name="name"]').val().trim() : '';
        }

        var text = val ? window.location.origin + '/pages/'+app.slugify(app.strMaxLength(val, 70)) : '';
        $('.admin-page-form .blog-seo-preview-url')
            .html(text);

        self.toggleSeoPreviewTitle();
    });
};

app.adminPage.darkModeOnChange = function() {
    const self = this;
    $('.admin-page-form [name="dark_mode"]').on('change', function() {
        var el = $('.page-section-details-modal [name="background_style"]');
        var dark = this.value === '1';
        el.map(function() {
            if (this.value === 'white') {
                $(this).next().html(dark ? 'Dark' : 'White');
            }

            if (this.value === 'gray') {
                $(this).next().html(dark ? 'Light' : 'Gray');
            }
        });
    });
};

// initialize functions of app.adminPage object
app.adminPage.init = function() {
    this.initContentTypeChange();
    this.addSection();
    this.editSectionDetails();
    this.editSectionData();
    this.sectionDetailsFormBackgroundStyleChange();
    this.saveSectionDetails();
    this.removeSection();
    this.initTooltips();
    this.pageTitleInput();
    this.seoSettingInpt();
    this.toggleSeoSettings();
    this.darkModeOnChange();

    if(typeof $.fn.sortable !== 'undefined') {
        this.sortSections();
    }

    this.config.sections.hero.init();
    this.config.sections.about_us.init();
    this.config.sections.statistics.init();
    this.config.sections.recent_works.init();
    this.config.sections.testimonial.init();
    this.config.sections.boxed.init();
    this.config.sections.boxed_left_icon.init();
    this.config.sections.team.init();
    this.config.sections.client.init();
    this.config.sections.faq.init();
    this.config.sections.blog.init();
    this.config.sections.cta.init();
    this.config.sections.text_media.init();

    if(typeof tinymce !== 'undefined' && $('#page-content').length) {
        app.adminPage.tinyMCE();
    }
};

// initialize app.adminPage object until the document is loaded
$(document).ready(app.adminPage.init());
